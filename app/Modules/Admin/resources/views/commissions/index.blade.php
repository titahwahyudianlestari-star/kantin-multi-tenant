<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commission Tenant</title>
</head>
<body>

    <h1>Commission Tenant: {{ $tenant->code }}</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <a href="{{ route('admin.tenants.commissions.create', $tenant) }}">
        Tambah Commission
    </a>

    <br><br>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>No</th>
                <th>Commission Rate</th>
                <th>Berlaku Mulai</th>
                <th>Berlaku Sampai</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($commissions as $commission)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $commission->commission_rate }}%
                    </td>

                    <td>
                        {{ $commission->valid_from->format('d-m-Y') }}
                    </td>

                    <td>
                        {{ $commission->valid_to
                            ? $commission->valid_to->format('d-m-Y')
                            : 'Tidak ditentukan' }}
                    </td>

                    <td>
                        <a href="{{ route('admin.tenants.commissions.edit', [
                            $tenant,
                            $commission
                        ]) }}">
                            Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">
                        Belum ada commission.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <br>

    <a href="{{ route('admin.tenants.index') }}">
        Kembali ke Daftar Tenant
    </a>

</body>
</html>