# Demo Script Modul 5

## Login

1. Buka aplikasi Laravel.
2. Login menggunakan akun yang tersedia pada seeder.
3. Gunakan email admin:
   `admin@kantin.test`

## Modul Admin

1. Buka halaman Admin.
2. Tampilkan daftar tenant.
3. Tampilkan proses tambah tenant.
4. Tampilkan proses edit tenant.
5. Tampilkan pengelolaan role tenant.
6. Tampilkan pengelolaan commission.
7. Tampilkan pengelolaan rekening bank.

## Struktur Modular

Tunjukkan bahwa:
- Controller berada di `app/Modules/Admin/Http/Controllers`
- Service berada di `app/Modules/Admin/Services`
- Routing berada di `app/Modules/Admin/routes`
- View berada di `app/Modules/Admin/resources/views`
- Model berada di `app/Models`

## Pengujian

Tunjukkan hasil:
- `php artisan test`
- `vendor\bin\pint --test`
- `npm run build`

Semua quality gate berhasil.
