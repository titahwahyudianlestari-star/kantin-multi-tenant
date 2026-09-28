# Stage Checklist Modul 5

## Struktur Modular

- [x] Controller disimpan di `app/Modules/Admin/Http/Controllers`
- [x] Request disimpan di `app/Modules/Admin/Http/Requests`
- [x] Service disimpan di `app/Modules/Admin/Services`
- [x] Routing disimpan di `app/Modules/Admin/routes`
- [x] View disimpan di `app/Modules/Admin/resources/views`

## Model

- [x] Model Tenant digunakan
- [x] Model UserTenantRole digunakan
- [x] Model Commission digunakan
- [x] Model TenantBankAccount digunakan
- [x] Model AuditLog digunakan

## Service

- [x] CreateTenant
- [x] UpdateTenant
- [x] AssignTenantRole
- [x] CommissionService
- [x] TenantBankAccountService
- [x] AuditLogger

## Tenant

- [x] Index tenant
- [x] Create tenant
- [x] Update tenant
- [x] Status tenant dapat diperbarui

## Role Tenant

- [x] Menambahkan role
- [x] Menghapus role
- [x] Perlindungan terhadap penghapusan owner terakhir

## Commission

- [x] Menampilkan commission
- [x] Membuat commission
- [x] Mengubah commission
- [x] Validasi tanggal commission

## Bank Account

- [x] Menampilkan rekening bank
- [x] Menambahkan rekening bank
- [x] Mengubah rekening bank
- [x] Nomor rekening disimpan dalam bentuk terenkripsi
- [x] Tampilan hanya menggunakan 4 digit terakhir
- [x] Verify rekening
- [x] Reject rekening
- [x] Perubahan rekening dicatat melalui audit

## Login dan Pengujian

- [x] Login menggunakan autentikasi Laravel
- [x] Email admin menggunakan data dari seeder
- [x] `php artisan test` berhasil
- [x] `vendor\bin\pint --test` berhasil
- [x] `npm run build` berhasil

## Catatan

Beberapa pengamanan lanjutan dari spesifikasi Modul 5, seperti database guard untuk mencegah periode commission yang overlap dan beberapa aturan database lainnya, belum dicentang sebagai fitur yang selesai pada checklist ini.