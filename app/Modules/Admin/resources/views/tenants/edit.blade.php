<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Tenant</title>
</head>
<body>

    <h1>Edit Tenant</h1>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.tenants.update', $tenant) }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="canteen_id">Kantin</label>
            <select name="canteen_id" id="canteen_id" required>
                @foreach ($canteens as $canteen)
                    <option value="{{ $canteen->id }}"
                        {{ old('canteen_id', $tenant->canteen_id) == $canteen->id ? 'selected' : '' }}>
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
                value="{{ old('code', $tenant->code) }}"
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
                value="{{ old('slug', $tenant->slug) }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="status">Status</label>
            <select name="status" id="status" required>
                <option value="active"
                    {{ old('status', $tenant->status) === 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="inactive"
                    {{ old('status', $tenant->status) === 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>

        <br>

        <button type="submit">Update Tenant</button>

        <a href="{{ route('admin.tenants.index') }}">
            Kembali
        </a>
    </form>

</body>
</html>
