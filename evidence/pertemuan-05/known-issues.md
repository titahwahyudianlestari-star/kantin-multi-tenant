# Known Issues Modul 5

## 1. Commission

Validasi tanggal commission sudah tersedia. Sistem juga sudah memiliki validasi untuk mencegah periode commission yang saling overlap.

## 2. Database Guard

Database guard sudah diterapkan untuk mencegah lebih dari satu commission aktif pada tenant dan lebih dari satu rekening bank primary pada tenant.

## 3. Rate Commission

Format nilai commission pada project menggunakan nilai persentase secara langsung, misalnya `10.00` untuk 10%.

## 4. Audit

Audit rekening bank sudah mencatat perubahan data tanpa menyimpan nomor rekening lengkap. Yang disimpan untuk kebutuhan audit adalah 4 digit terakhir nomor rekening.

## 5. Catatan

Tidak terdapat known issue kritis yang ditemukan pada pengujian Modul 5. Quality gate Laravel Test, Laravel Pint, dan Vite Build berhasil dijalankan.