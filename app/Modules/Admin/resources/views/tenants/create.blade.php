<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Tenant</title>
</head>
<body>

    <h1>Tambah Tenant</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tenants.store') }}" method="POST">
        @csrf

        <div>
            <label for="canteen_id">Kantin</label>
            <select name="canteen_id" id="canteen_id" required>
                <option value="">-- Pilih Kantin --</option>

                @foreach ($canteens as $canteen)
                    <option value="{{ $canteen->id }}"
                        {{ old('canteen_id') == $canteen->id ? 'selected' : '' }}>
                        {{ $canteen->code }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label for="code">Kode Tenant</label>
            <input
                type="text"
                name="code"
                id="code"
                value="{{ old('code') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="slug">Slug</label>
            <input
                type="text"
                name="slug"
                id="slug"
                value="{{ old('slug') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>
                    Active
                </option>
                <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>

        <br>

        <button type="submit">Simpan Tenant</button>

        <a href="{{ route('admin.tenants.index') }}">
            Kembali
        </a>
    </form>

</body>
</html>