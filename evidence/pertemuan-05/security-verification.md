# Security Verification Modul 5

## 1. Login dan Hak Akses

- [x] Halaman admin menggunakan middleware `auth`
- [x] Halaman admin menggunakan middleware `verified`
- [x] Halaman admin menggunakan middleware `role:admin`
- [x] Guest diarahkan ke halaman login
- [x] User dengan role tenant tidak dapat mengakses dashboard admin
- [x] Admin yang inactive tidak dapat mengakses dashboard admin

## 2. Role Tenant

- [x] Penambahan role tenant berhasil diuji
- [x] Penghapusan role tenant berhasil diuji
- [x] Sistem mencegah penghapusan owner terakhir

Hasil pengujian:
`Tidak boleh menghapus owner terakhir tenant.`

## 3. Rekening Bank

- [x] Nomor rekening disimpan dalam bentuk terenkripsi
- [x] Nomor rekening tidak ditampilkan secara penuh pada halaman
- [x] Tampilan rekening hanya menggunakan 4 digit terakhir
- [x] Verify rekening berhasil diuji
- [x] Reject rekening berhasil diuji

Contoh tampilan:
`****7890`

## 4. Audit

- [x] Perubahan rekening dicatat pada audit
- [x] Verify rekening dicatat pada audit
- [x] Reject rekening dicatat pada audit
- [x] Audit menyimpan actor dan perubahan status
- [x] Audit tidak menyimpan nomor rekening lengkap

## 5. Testing

- [x] `php artisan test` berhasil dengan 42 tests passed dan 97 assertions
- [x] `vendor\bin\pint --test` berhasil
- [x] `npm run build` berhasil

## Catatan

Verifikasi keamanan di atas berdasarkan pengujian yang dilakukan pada project Modul 5. Pengamanan lanjutan yang belum diimplementasikan tidak dicentang sebagai hasil verifikasi.
