<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Mata Kuliah</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 30px auto;
            padding: 0 20px;
        }

        label {
            display: inline-block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 9px;
            margin-bottom: 15px;
        }

        button {
            padding: 9px 14px;
            cursor: pointer;
        }

        .errors {
            padding: 10px 10px 10px 30px;
            background-color: #ffecec;
            color: #aa0000;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <h1>Edit Mata Kuliah</h1>

    @if ($errors->any())
        <div class="errors">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('mata-kuliah.update', $mataKuliah->id) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        <p>
            <label for="kode_mk">Kode Mata Kuliah</label><br>

            <input
                type="text"
                id="kode_mk"
                name="kode_mk"
                value="{{ old('kode_mk', $mataKuliah->kode_mk) }}"
                maxlength="20"
                required
            >
        </p>

        <p>
            <label for="nama_mk">Nama Mata Kuliah</label><br>

            <input
                type="text"
                id="nama_mk"
                name="nama_mk"
                value="{{ old('nama_mk', $mataKuliah->nama_mk) }}"
                maxlength="100"
                required
            >
        </p>

        <p>
            <label for="sks">SKS</label><br>

            <input
                type="number"
                id="sks"
                name="sks"
                value="{{ old('sks', $mataKuliah->sks) }}"
                min="1"
                max="6"
                required
            >
        </p>

        <p>
            <label for="semester">Semester</label><br>

            <input
                type="number"
                id="semester"
                name="semester"
                value="{{ old('semester', $mataKuliah->semester) }}"
                min="1"
                max="8"
                required
            >
        </p>

        <button type="submit">
            Simpan Perubahan
        </button>

        <a href="{{ route('mata-kuliah.index') }}">
            Batal
        </a>
    </form>

</body>

</html>