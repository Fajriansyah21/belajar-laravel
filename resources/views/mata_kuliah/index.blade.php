<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Mata Kuliah</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 30px auto;
            padding: 0 20px;
        }

        h1 {
            margin-bottom: 20px;
        }

        a {
            color: #155eef;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .success {
            padding: 10px;
            background-color: #e8f7e8;
            color: #176b17;
            margin-bottom: 15px;
        }

        form {
            display: inline;
        }

        button {
            cursor: pointer;
        }
    </style>
</head>

<body>

    <h1>Data Mata Kuliah</h1>

    @if (session('success'))
        <p class="success">
            {{ session('success') }}
        </p>
    @endif

    <p>
        <a href="{{ route('mata-kuliah.create') }}">
            Tambah Mata Kuliah
        </a>
    </p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode MK</th>
                <th>Nama Mata Kuliah</th>
                <th>SKS</th>
                <th>Semester</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($mataKuliah as $mk)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $mk->kode_mk }}</td>
                    <td>{{ $mk->nama_mk }}</td>
                    <td>{{ $mk->sks }}</td>
                    <td>{{ $mk->semester }}</td>

                    <td>
                        <a href="{{ route('mata-kuliah.edit', $mk->id) }}">
                            Ubah
                        </a>

                        <form
                            action="{{ route('mata-kuliah.destroy', $mk->id) }}"
                            method="POST"
                            style="display: inline;"
                            onsubmit="return confirm('Yakin ingin menghapus data mata kuliah ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        Belum ada data mata kuliah.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>

</html>