<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Dosen</title>
    <style>
        @include('partials.style')
        .dosen-thumbnail { width: 48px; height: 48px; object-fit: cover; border-radius: 50%; }
    </style>
</head>
<body>
    @include('partials.nav')

    <h1>Data Dosen</h1>

    <div class="page-actions">
        @can('dosen.create')
            <a href="{{ route('dosen.create') }}" class="btn">+ Tambah Dosen</a>
        @endcan
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>NIDN</th>
                <th>Nama</th>
                <th>Bidang Keahlian</th>
                <th>Email</th>
                <th>No. Telepon</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($dosen as $dsn)
                <tr>
                    <td>{{ $dosen->firstItem() + $loop->index }}</td>
                    <td>
                        @if ($dsn->foto_url)
                            <img class="dosen-thumbnail" src="{{ $dsn->foto_thumbnail_url }}" alt="Foto {{ $dsn->nama }}">
                        @else
                            <span>-</span>
                        @endif
                    </td>
                    <td>{{ $dsn->nidn }}</td>
                    <td>{{ $dsn->nama }}</td>
                    <td>{{ $dsn->bidang_keahlian }}</td>
                    <td>{{ $dsn->email }}</td>
                    <td>{{ $dsn->no_telepon }}</td>
                    <td>
                        <div class="action-buttons">
                            @can('dosen.edit')
                                <a href="{{ route('dosen.edit', $dsn->id) }}" class="btn">Ubah</a>
                            @endcan
                            @can('dosen.delete')
                                <form action="{{ route('dosen.destroy', $dsn->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit">Hapus</button>
                                </form>
                            @endcan
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8">Belum ada data dosen.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 20px;">
        {{ $dosen->links('pagination.custom') }}
    </div>
</body>
</html>
