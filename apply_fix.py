from pathlib import Path
import re
import shutil
from datetime import datetime

ROOT = Path.cwd()

VIEW_FILES = {
    "resources/views/mata_kuliah/index.blade.php": ("mata-kuliah", "$mk->id", "mata-kuliah"),
    "resources/views/dosen/index.blade.php": ("dosen", "$dsn->id", "dosen"),
    "resources/views/ruangan/index.blade.php": ("ruangan", "$r->id", "ruangan"),
    "resources/views/kelas/index.blade.php": ("kelas", "$k->id", "kelas"),
    "resources/views/jadwal_kuliah/index.blade.php": ("jadwal-kuliah", "$jk->id", "jadwal"),
}

def backup(path: Path):
    stamp = datetime.now().strftime("%Y%m%d-%H%M%S")
    backup_path = path.with_suffix(path.suffix + f".backup-{stamp}")
    shutil.copy2(path, backup_path)
    print(f"Backup: {backup_path}")

def patch_view(relative_path: str):
    path = ROOT / relative_path
    if not path.exists():
        raise FileNotFoundError(f"File tidak ditemukan: {relative_path}")

    content = path.read_text(encoding="utf-8")
    original = content

    # Form/link Tambah: hanya ganti guard admin menjadi permission tambah-data.
    content, count_add = re.subn(
        r"@role\('admin'\)([\s\S]*?)@endrole(?=\s*\|)",
        lambda m: "@can('tambah-data')" + m.group(1) + "@endcan",
        content,
        count=1
    )

    # Tombol Ubah dan form Hapus di kolom Action: pisahkan permission masing-masing.
    body_pos = content.find("<tbody>")
    if body_pos < 0:
        raise RuntimeError(f"Tag <tbody> tidak ditemukan di {relative_path}")

    prefix, body = content[:body_pos], content[body_pos:]
    start = body.find("@role('admin')")
    if start < 0:
        # Idempotent: jika sudah pernah diperbaiki, lewati.
        if "@can('ubah-data')" in body and "@can('hapus-data')" in body:
            print(f"Sudah menggunakan permission: {relative_path}")
            return
        raise RuntimeError(f"Pembungkus @role('admin') pada kolom Action tidak ditemukan: {relative_path}")

    end = body.find("@endrole", start)
    if end < 0:
        raise RuntimeError(f"@endrole tidak ditemukan di {relative_path}")

    inner = body[start + len("@role('admin')"):end]
    form_start = inner.find("<form")
    form_close = inner.find("</form>", form_start)
    if form_start < 0 or form_close < 0:
        raise RuntimeError(f"Form hapus tidak ditemukan di kolom Action: {relative_path}")
    form_end = form_close + len("</form>")

    edit_html = inner[:form_start].strip()
    delete_html = inner[form_start:form_end].strip()

    replacement = (
        "@can('ubah-data')\n"
        "                            " + edit_html + "\n"
        "                        @endcan\n"
        "                        @can('hapus-data')\n"
        "                            " + delete_html + "\n"
        "                        @endcan"
    )

    # Buang placeholder @else - @endrole lama.
    tail_start = end + len("@endrole")
    body = body[:start] + replacement + body[tail_start:]
    body = re.sub(r"\s*@else\s*-\s*", "\n", body, count=1)

    content = prefix + body

    if content != original:
        backup(path)
        path.write_text(content, encoding="utf-8")
        print(f"OK: {relative_path}")
    else:
        print(f"Tidak ada perubahan: {relative_path}")

def patch_routes():
    path = ROOT / "routes/web.php"
    if not path.exists():
        raise FileNotFoundError("File tidak ditemukan: routes/web.php")

    content = path.read_text(encoding="utf-8")
    original = content

    configs = [
        ("mahasiswa", "MahasiswaController", "menu-mahasiswa", "mahasiswa"),
        ("dosen", "DosenController", "menu-dosen", "dosen"),
        ("mata-kuliah", "MataKuliahController", "menu-mata-kuliah", "mata-kuliah"),
        ("ruangan", "RuanganController", "menu-ruangan", "ruangan"),
        ("kelas", "KelasController", "menu-kelas", "kelas"),
        ("jadwal-kuliah", "JadwalKuliahController", "menu-jadwal-kuliah", "jadwal-kuliah"),
    ]

    for uri, controller, menu_permission, resource_name in configs:
        # Cari satu grup menu resource berdasarkan URI dan ganti hanya isi grup tersebut.
        pattern = (
            r"    Route::middleware\('permission:" + re.escape(menu_permission) +
            r"'\)->group\(function \(\) \{[\s\S]*?^\s{4}\}\);\s*"
        )
        match = re.search(pattern, content, flags=re.MULTILINE)
        if not match:
            raise RuntimeError(f"Blok route untuk {uri} tidak ditemukan. Pastikan routes/web.php sesuai versi project.")

        params = "\n            ->parameters(['kelas' => 'kls'])" if uri == "kelas" else ""
        # Use resource's route names and parameter behavior, splitting CRUD by permission.
        block = (
            f"    Route::middleware('permission:{menu_permission}')->group(function () {{\n"
            f"        Route::resource('{uri}', {controller}::class)\n"
            f"            ->only(['create', 'store'])\n"
            f"            ->middleware('permission:tambah-data'){params};\n"
            f"        Route::resource('{uri}', {controller}::class)\n"
            f"            ->only(['edit', 'update'])\n"
            f"            ->middleware('permission:ubah-data'){params};\n"
            f"        Route::resource('{uri}', {controller}::class)\n"
            f"            ->only(['destroy'])\n"
            f"            ->middleware('permission:hapus-data'){params};\n"
            f"        Route::resource('{uri}', {controller}::class)\n"
            f"            ->only(['index', 'show']){params};\n"
            f"    }});\n\n"
        )
        content = content[:match.start()] + block + content[match.end():]

    if content != original:
        backup(path)
        path.write_text(content, encoding="utf-8")
        print("OK: routes/web.php")
    else:
        print("Tidak ada perubahan: routes/web.php")

def main():
    if not (ROOT / "artisan").exists() or not (ROOT / "routes/web.php").exists():
        raise SystemExit(
            "Jalankan script ini dari folder utama project Laravel, "
            "yaitu folder yang berisi file artisan."
        )

    print("Memperbaiki tombol CRUD berdasarkan permission...\n")
    for file in VIEW_FILES:
        patch_view(file)
    patch_routes()

    print("\nSelesai. Jalankan:")
    print("php artisan permission:cache-reset")
    print("php artisan optimize:clear")
    print("php artisan route:list")
    print("\nCatatan: role perlu memiliki permission tambah-data, ubah-data, hapus-data")
    print("dan permission menu yang sesuai untuk membuka setiap halaman.")

if __name__ == "__main__":
    main()
