<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Data Dosen</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 30px auto; padding: 0 20px; }
        label { display: inline-block; margin-bottom: 5px; font-weight: bold; }
        input { width: 100%; box-sizing: border-box; padding: 9px; }
        button { padding: 9px 14px; cursor: pointer; }
        .errors { padding: 10px 10px 10px 30px; background-color: #ffcecc; color: #a40000; }
        .foto-preview { width: 120px; height: 120px; object-fit: cover; border-radius: 6px; display: block; margin: 8px 0; }
    </style>
</head>
<body>
    <h1>Ubah Data Dosen</h1>

    @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('dosen.update', $dosen->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <p>
            <label for="nidn">NIDN</label><br>
            <input type="text" id="nidn" name="nidn" value="{{ old('nidn', $dosen->nidn) }}" maxlength="20" required>
        </p>
        <p>
            <label for="nama">Nama</label><br>
            <input type="text" id="nama" name="nama" value="{{ old('nama', $dosen->nama) }}" maxlength="100" required>
        </p>
        <p>
            <label for="bidang_keahlian">Bidang Keahlian</label><br>
            <input type="text" id="bidang_keahlian" name="bidang_keahlian" value="{{ old('bidang_keahlian', $dosen->bidang_keahlian) }}" maxlength="100" required>
        </p>
        <p>
            <label for="email">Email</label><br>
            <input type="email" id="email" name="email" value="{{ old('email', $dosen->email) }}" maxlength="100">
        </p>
        <p>
            <label for="no_telepon">No. Telepon</label><br>
            <input type="text" id="no_telepon" name="no_telepon" value="{{ old('no_telepon', $dosen->no_telepon) }}" maxlength="20">
        </p>
        <p>
            <label for="foto">Foto Dosen (opsional, JPG/PNG/WEBP, maksimal 2 MB)</label><br>
            @if ($dosen->foto_url)
                <img class="foto-preview" src="{{ $dosen->foto_url }}" alt="Foto {{ $dosen->nama }}">
                <span>Foto saat ini</span>
            @else
                <span>Belum ada foto.</span>
            @endif
            <input type="file" id="foto" name="foto" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">
        </p>
        <button type="submit">Simpan Perubahan</button>
        <a href="{{ route('dosen.index') }}">Batal</a>
    </form>
</body>
</html>
