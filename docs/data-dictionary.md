# Data Dictionary — Aplikasi Kantin Multi-Tenant

Dokumen ini berisi daftar tabel dan atribut utama yang digunakan pada basis data Aplikasi Kantin Multi-Tenant. Struktur disusun berdasarkan ERD Baseline Fase 0 dan implementasi migration pada Modul 3.

## A. Identity dan Access

### 1. canteens
Menyimpan data utama kantin.

- `id` — BIGINT UNSIGNED, Primary Key
- `code` — VARCHAR, kode kantin
- `slug` — VARCHAR, identitas kantin dalam bentuk slug
- `tax_rate` — DECIMAL, persentase pajak
- `service_fee_rate` — DECIMAL, persentase biaya layanan
- `status` — status kantin

### 2. users
Menyimpan data pengguna aplikasi.

- `id` — BIGINT UNSIGNED, Primary Key
- `name` — nama pengguna
- `email` — alamat email
- `email_verified_at` — waktu verifikasi email
- `password` — password pengguna
- `account_status` — status akun
- `role` — role pengguna

### 3. tenants
Menyimpan data tenant atau penjual yang berada di dalam kantin.

- `id` — BIGINT UNSIGNED, Primary Key
- `canteen_id` — BIGINT UNSIGNED, Foreign Key ke `canteens`
- `code` — kode tenant
- `slug` — slug tenant
- `status` — status tenant

### 4. user_canteen_roles
Menghubungkan pengguna dengan kantin berdasarkan role.

- `id` — BIGINT UNSIGNED, Primary Key
- `user_id` — Foreign Key ke `users`
- `canteen_id` — Foreign Key ke `canteens`
- `role` — role pengguna pada kantin

### 5. user_tenant_roles
Menghubungkan pengguna dengan tenant berdasarkan role.

- `id` — BIGINT UNSIGNED, Primary Key
- `user_id` — Foreign Key ke `users`
- `tenant_id` — Foreign Key ke `tenants`
- `role` — role pengguna pada tenant

### 6. tenant_balances
Menyimpan saldo milik tenant.

- `tenant_id` — BIGINT UNSIGNED, Primary Key sekaligus Foreign Key ke `tenants`
- `balance_amount` — jumlah saldo

### 7. tenant_bank_accounts
Menyimpan informasi rekening bank tenant.

- `id` — BIGINT UNSIGNED, Primary Key
- `tenant_id` — Foreign Key ke `tenants`
- `bank_name` — nama bank
- `account_number_cipher` — nomor rekening yang disimpan secara terenkripsi
- `account_number_last4` — empat digit terakhir nomor rekening
- `account_name` — nama pemilik rekening
- `is_primary` — penanda rekening utama

## B. Meja dan Session

### 8. dining_tables
Menyimpan data meja pada kantin.

- `id` — BIGINT UNSIGNED, Primary Key
- `canteen_id` — Foreign Key ke `canteens`
- `code` — kode meja
- `label` — nama atau label meja
- `zone` — area meja
- `status` — status meja

### 9. table_qr_tokens
Menyimpan token QR untuk meja.

- `id` — BIGINT UNSIGNED, Primary Key
- `dining_table_id` — Foreign Key ke `dining_tables`
- `token_hash` — hash token
- `expires_at` — waktu kedaluwarsa token
- `revoked_at` — waktu token dicabut

### 10. customer_sessions
Menyimpan session pelanggan.

- `id` — BIGINT UNSIGNED, Primary Key
- `dining_table_id` — Foreign Key ke `dining_tables`
- `session_token` — token session
- `started_at` — waktu session dimulai
- `ended_at` — waktu session berakhir

## C. Catalog

### 11. tenant_operating_hours
Menyimpan jam operasional tenant.

- `id` — BIGINT UNSIGNED, Primary Key
- `tenant_id` — Foreign Key ke `tenants`
- `day_of_week` — hari dalam satu minggu
- `open_time` — waktu buka
- `close_time` — waktu tutup
- `is_closed` — penanda apakah tenant tutup

### 12. commission_schemes
Menyimpan aturan komisi tenant.

- `id` — BIGINT UNSIGNED, Primary Key
- `tenant_id` — Foreign Key ke `tenants`
- `commission_rate` — persentase komisi
- `valid_from` — tanggal mulai berlaku
- `valid_to` — tanggal akhir berlaku

### 13. menu_categories
Menyimpan kategori menu.

- `id` — BIGINT UNSIGNED, Primary Key
- `tenant_id` — Foreign Key ke `tenants`
- `name` — nama kategori
- `slug` — slug kategori
- `sort_order` — urutan kategori
- `is_active` — status kategori

### 14. menus
Menyimpan daftar menu yang dijual tenant.

- `id` — BIGINT UNSIGNED, Primary Key
- `tenant_id` — Foreign Key ke `tenants`
- `category_id` — Foreign Key ke `menu_categories`
- `name` — nama menu
- `slug` — slug menu
- `price_amount` — harga menu dalam Rupiah
- `stock_qty` — jumlah stok
- `is_available` — ketersediaan menu
- `prep_minutes` — estimasi waktu persiapan

### 15. modifier_groups
Menyimpan kelompok pilihan tambahan menu.

- `id` — BIGINT UNSIGNED, Primary Key
- `tenant_id` — Foreign Key ke `tenants`
- `min_select` — jumlah minimum pilihan
- `max_select` — jumlah maksimum pilihan
- `is_active` — status kelompok modifier

### 16. modifier_options
Menyimpan pilihan tambahan pada modifier group.

- `id` — BIGINT UNSIGNED, Primary Key
- `tenant_id` — Foreign Key ke `tenants`
- `group_id` — Foreign Key ke `modifier_groups`
- `name` — nama pilihan
- `price_delta` — perubahan harga
- `stock_qty` — jumlah stok pilihan
- `is_available` — ketersediaan pilihan

### 17. menu_modifier_groups
Menghubungkan menu dengan kelompok modifier.

- `id` — BIGINT UNSIGNED, Primary Key
- `tenant_id` — Foreign Key ke `tenants`
- `menu_id` — Foreign Key ke `menus`
- `modifier_group_id` — Foreign Key ke `modifier_groups`
- `sort_order` — urutan modifier

## D. Order

### 18. orders
Menyimpan data pesanan utama.

- `id` — BIGINT UNSIGNED, Primary Key
- `order_number` — nomor pesanan
- `dining_table_id` — Foreign Key ke `dining_tables`
- `customer_session_id` — Foreign Key ke `customer_sessions`
- `status` — status pesanan

### 19. tenant_orders
Menyimpan bagian pesanan berdasarkan tenant.

- `id`