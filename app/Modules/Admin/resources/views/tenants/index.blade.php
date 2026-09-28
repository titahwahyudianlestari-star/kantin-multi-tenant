<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Tenant</title>
</head>
<body>

    <h1>Daftar Tenant</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="{{ route('admin.tenants.create') }}">
        Tambah Tenant
    </a>

    <br><br>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Slug</th>
                <th>Kantin</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($tenants as $tenant)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $tenant->code }}</td>
                    <td>{{ $tenant->slug }}</td>
                    <td>{{ $tenant->canteen->code ?? '-' }}</td>
                    <td>{{ $tenant->status }}</td>
                    <td>
                        <a href="{{ route('admin.tenants.edit', $tenant) }}">
                            Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada tenant.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
