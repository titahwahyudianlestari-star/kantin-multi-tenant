<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meja & QR</title>
</head>
<body>

    <h1>Meja</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <h2>Tambah Meja</h2>

    <form method="POST" action="{{ route('admin.tables.store') }}">
        @csrf

        <div>
            <label for="code">Kode</label><br>
            <input
                type="text"
                id="code"
                name="code"
                value="{{ old('code') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="label">Label</label><br>
            <input
                type="text"
                id="label"
                name="label"
                value="{{ old('label') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="zone">Zona</label><br>
            <input
                type="text"
                id="zone"
                name="zone"
                value="{{ old('zone') }}"
            >
        </div>

        <br>

        <button type="submit">
            Tambah Meja
        </button>
    </form>

    <br><br>

    <h2>Daftar Meja</h2>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Label</th>
                <th>Zona</th>
                <th>Status QR</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($tables as $table)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $table->code }}</td>
                    <td>{{ $table->label }}</td>
                    <td>{{ $table->zone ?? '-' }}</td>

                    <td>
                        {{ $table->activeToken ? 'Aktif' : 'Belum ada' }}
                    </td>

                    <td>
                        <form
                            method="POST"
                            action="{{ route('admin.tables.rotate', $table) }}"
                        >
                            @csrf

                            <button type="submit">
                                {{ $table->activeToken ? 'Rotate QR' : 'Terbitkan QR' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        Belum ada meja.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>