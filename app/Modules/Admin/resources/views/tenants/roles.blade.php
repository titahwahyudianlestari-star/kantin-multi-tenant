<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role Tenant</title>
</head>
<body>

    <h1>Role Tenant: {{ $tenant->code }}</h1>

    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    @if (session('error'))
        <p>{{ session('error') }}</p>
    @endif

    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2>Tambah Role</h2>

    <form action="{{ route('admin.tenants.roles.assign', $tenant) }}" method="POST">
        @csrf

        <div>
            <label for="user_id">User</label>
            <select name="user_id" id="user_id" required>
                <option value="">-- Pilih User --</option>

                @foreach ($users as $user)
                    <option value="{{ $user->id }}">
                        {{ $user->name }} - {{ $user->email }}
                    </option>
                @endforeach
            </select>
        </div>

        <br>

        <div>
            <label for="role">Role</label>
            <select name="role" id="role" required>
                <option value="">-- Pilih Role --</option>
                <option value="owner">Owner</option>
                <option value="operator">Operator</option>
                <option value="cashier">Cashier</option>
            </select>
        </div>

        <br>

        <button type="submit">Tambah Role</button>
    </form>

    <hr>

    <h2>Daftar Role Tenant</h2>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Email</th>
                <th>Role</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($roles as $role)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $role->user->name }}</td>
                    <td>{{ $role->user->email }}</td>
                    <td>{{ $role->role }}</td>
                    <td>
                        <form action="{{ route('admin.tenants.roles.remove', $tenant) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <input type="hidden" name="user_id" value="{{ $role->user_id }}">
                            <input type="hidden" name="role" value="{{ $role->role }}">

                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada role untuk tenant ini.</td>
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