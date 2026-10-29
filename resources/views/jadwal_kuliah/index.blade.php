<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Kuliah</title>
    <style>
        @include('partials.style')
    </style>
</head>

<body>
    @include('partials.nav')

    <h1>Jadwal Kuliah</h1>

    <div class="page-actions">
        @can('jadwal-kuliah.create')
            <a href="{{ route('jadwal-kuliah.create') }}" class="btn">
                + Tambah Jadwal Kuliah
            </a>
        @endcan
    </div>

    @if (session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kelas</th>
                <th>Mata Kuliah</th>
                <th>Dosen</th>
                <th>Ruangan</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Lampiran</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($jadwalKuliah as $jk)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $jk->kelas->nama_kelas }}</td>
                    <td>{{ $jk->mataKuliah->nama_mata_kuliah }}</td>
                    <td>{{ $jk->dosen->nama }}</td>
                    <td>{{ $jk->ruangan->nama }} ({{ $jk->ruangan->kode_ruangan }})</td>
                    <td>{{ $jk->hari }}</td>
                    <td>{{ substr($jk->jam_mulai, 0, 5) }} - {{ substr($jk->jam_selesai, 0, 5) }}</td>
                    <td>
                        @forelse ($jk->lampiran as $lampiran)
                            <a href="{{ asset('storage/' . $lampiran->path) }}" download>{{ $lampiran->nama_asli }}</a><br>
                        @empty
                            <span>-</span>
                        @endforelse
                    </td>

                    <td>
                        <div class="action-buttons">
                            @can('jadwal-kuliah.edit')
                                <a href="{{ route('jadwal-kuliah.edit', $jk->id) }}" class="btn">
                                    Ubah
                                </a>
                            @endcan

                            @can('jadwal-kuliah.delete')
                                <form action="{{ route('jadwal-kuliah.destroy', $jk->id) }}" method="POST"
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
                    <td colspan="9">Belum ada jadwal kuliah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>