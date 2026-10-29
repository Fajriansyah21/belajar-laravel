<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Mata Kuliah</title>
    <style>@include('partials.style')</style>
</head>
<body>
    @include('partials.nav')
    <h1>Data Mata Kuliah</h1>
    <div class="page-actions">
        @can('mata-kuliah.create')
            <a href="{{ route('mata-kuliah.create') }}" class="btn">+ Tambah Mata Kuliah</a>
        @endcan
    </div>
    @if (session('success'))<p class="success">{{ session('success') }}</p>@endif
    <table>
        <thead><tr><th>No</th><th>Kode</th><th>Nama Mata Kuliah</th><th>SKS</th><th>Semester</th><th>Modul</th><th>Action</th></tr></thead>
        <tbody>
        @forelse ($mataKuliah as $mk)
            <tr>
                <td>{{ $loop->iteration }}</td><td>{{ $mk->kode }}</td><td>{{ $mk->nama_mata_kuliah }}</td><td>{{ $mk->sks }}</td><td>{{ $mk->semester }}</td>
                <td>
                    @if ($mk->modul)
                        <a href="{{ asset('storage/' . $mk->modul) }}" download>Unduh Modul</a>
                    @else
                        <span>-</span>
                    @endif
                </td>
                <td><div class="action-buttons">
                    @can('mata-kuliah.edit')<a href="{{ route('mata-kuliah.edit', $mk->id) }}" class="btn">Ubah</a>@endcan
                    @can('mata-kuliah.delete')
                        <form action="{{ route('mata-kuliah.destroy', $mk->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data mata kuliah ini?')">
                            @csrf @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    @endcan
                </div></td>
            </tr>
        @empty
            <tr><td colspan="7">Belum ada data mata kuliah.</td></tr>
        @endforelse
        </tbody>
    </table>
</body>
</html>
