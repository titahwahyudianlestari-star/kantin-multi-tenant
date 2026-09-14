# Kantin Multi-Tenant

Aplikasi praktikum Kantin Multi-Tenant menggunakan Laravel 13.

## Requirements

* PHP 8.4 atau lebih baru
* Composer
* Node.js dan npm
* MySQL
* Redis
* Laravel 13
* Livewire 4
* Laravel Reverb

## Setup

Clone atau buka project, kemudian jalankan:

```bash
composer install
npm install
```

## Database

Pastikan database `kantin` sudah tersedia dan konfigurasi database pada file `.env` sudah sesuai.

Untuk menjalankan migration:

```bash
php artisan migrate
```

Untuk membuat ulang database beserta data demo pada lingkungan development/testing:

```bash
php artisan migrate:fresh --seed
```

Perintah `migrate:fresh --seed` akan menghapus tabel dan data yang ada, kemudian menjalankan seluruh migration dan seeder dari awal.

**Jangan menjalankan `migrate:fresh --seed` pada database production karena data yang sudah ada akan terhapus.**

## Schema Dump

Untuk membuat schema dump database, jalankan:

```bash
php artisan schema:dump
```

File schema akan tersimpan pada:

```text
database/schema/mysql-schema.sql
```

Schema dump digunakan untuk menyimpan struktur database dan membantu proses pembuatan ulang database.

## Testing

Untuk menjalankan pengujian aplikasi:

```bash
php artisan test
```

Untuk memeriksa format kode menggunakan Laravel Pint:

```bash
vendor\bin\pint.bat --test
```
