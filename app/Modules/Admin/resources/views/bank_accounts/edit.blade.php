<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Rekening Bank</title>
</head>
<body>

    <h1>Edit Rekening Bank</h1>

    <h2>Tenant: {{ $tenant->code }}</h2>

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

    <form
        action="{{ route('admin.tenants.bank-accounts.update', [$tenant, $bankAccount]) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        <div>
            <label for="bank_name">Nama Bank</label>
            <input
                type="text"
                name="bank_name"
                id="bank_name"
                value="{{ old('bank_name', $bankAccount->bank_name) }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="account_number">
                Nomor Rekening
            </label>

            <input
                type="text"
                name="account_number"
                id="account_number"
                placeholder="Kosongkan jika tidak ingin mengubah"
            >

            <small>
                Nomor rekening saat ini: ****{{ $bankAccount->account_number_last4 }}
            </small>
        </div>

        <br>

        <div>
            <label for="account_name">Nama Pemilik Rekening</label>
            <input
                type="text"
                name="account_name"
                id="account_name"
                value="{{ old('account_name', $bankAccount->account_name) }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="status">Status</label>

            <select name="status" id="status" required>
                <option value="active"
                    {{ old('status', $bankAccount->status) === 'active' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="inactive"
                    {{ old('status', $bankAccount->status) === 'inactive' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>
        </div>

        <br>

        <div>
            <label>
                <input
                    type="checkbox"
                    name="is_primary"
                    value="1"
                    {{ old('is_primary', $bankAccount->is_primary) ? 'checked' : '' }}
                >

                Jadikan rekening utama
            </label>
        </div>

        <br>

        <button type="submit">
            Update Rekening
        </button>

        <a href="{{ route('admin.tenants.bank-accounts.index', $tenant) }}">
            Batal
        </a>
    </form>

</body>
</html>