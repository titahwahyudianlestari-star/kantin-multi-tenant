<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Commission</title>
</head>
<body>

    <h1>Tambah Commission</h1>

    <p>Tenant: <strong>{{ $tenant->code }}</strong></p>

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.tenants.commissions.store', $tenant) }}"
        method="POST"
    >
        @csrf

        <div>
            <label for="commission_rate">
                Commission Rate (%)
            </label>

            <input
                type="number"
                name="commission_rate"
                id="commission_rate"
                value="{{ old('commission_rate') }}"
                min="0"
                max="100"
                step="0.01"
                required
            >
        </div>

        <br>

        <div>
            <label for="valid_from">
                Berlaku Mulai
            </label>

            <input
                type="date"
                name="valid_from"
                id="valid_from"
                value="{{ old('valid_from') }}"
                required
            >
        </div>

        <br>

        <div>
            <label for="valid_to">
                Berlaku Sampai
            </label>

            <input
                type="date"
                name="valid_to"
                id="valid_to"
                value="{{ old('valid_to') }}"
            >
        </div>

        <br>

        <button type="submit">
            Simpan Commission
        </button>

        <a href="{{ route('admin.tenants.commissions.index', $tenant) }}">
            Kembali
        </a>
    </form>

</body>
</html>