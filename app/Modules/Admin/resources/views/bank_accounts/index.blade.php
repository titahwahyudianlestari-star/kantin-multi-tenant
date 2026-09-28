<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekening Bank Tenant</title>
</head>
<body>

    <h1>Rekening Bank Tenant</h1>

    <h2>Tenant: {{ $tenant->code }}</h2>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if ($errors->any())
        <div>
            <strong>Terjadi kesalahan:</strong>

            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <br>

    <a href="{{ route('admin.tenants.bank-accounts.create', $tenant) }}">
        Tambah Rekening Bank
    </a>

    <br><br>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>No</th>
                <th>Bank</th>
                <th>Nomor Rekening</th>
                <th>Nama Rekening</th>
                <th>Status</th>
                <th>Primary</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($bankAccounts as $bankAccount)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>
                        {{ $bankAccount->bank_name }}
                    </td>

                    <td>
                        ****{{ $bankAccount->account_number_last4 }}
                    </td>

                    <td>
                        {{ $bankAccount->account_name }}
                    </td>

                    <td>
                        {{ $bankAccount->status }}
                    </td>

                    <td>
                        {{ $bankAccount->is_primary ? 'Ya' : 'Tidak' }}
                    </td>

                    <td>
                        <a href="{{ route('admin.tenants.bank-accounts.edit', [$tenant, $bankAccount]) }}">
                            Edit
                        </a>

                        @if ($bankAccount->status !== 'active')
                            <form
                                action="{{ route('admin.tenants.bank-accounts.verify', [$tenant, $bankAccount]) }}"
                                method="POST"
                                style="display: inline;"
                            >
                                @csrf

                                <button type="submit">
                                    Verify
                                </button>
                            </form>
                        @else
                            <span>
                                Sudah aktif
                            </span>
                        @endif

                        @if ($bankAccount->status !== 'active')
                            <form
                                action="{{ route('admin.tenants.bank-accounts.reject', [$tenant, $bankAccount]) }}"
                                method="POST"
                                style="display: inline;"
                            >
                                @csrf

                                <button type="submit">
                                    Reject
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
                        Belum ada rekening bank.
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