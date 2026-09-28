\# ADR-0008: Tenant Isolation



\## Status



Accepted



\## Context



Aplikasi menggunakan konsep multi-tenant, sehingga data dari satu tenant tidak boleh dapat diakses atau dimodifikasi oleh tenant lain.



Beberapa tabel memiliki `tenant\_id` sebagai penanda kepemilikan data. Karena itu, aplikasi membutuhkan mekanisme yang konsisten untuk menentukan tenant aktif pada setiap request dan membatasi akses data berdasarkan tenant tersebut.



\## Decision



Tenant aktif akan ditentukan melalui `TenantContext` yang bersifat request-scoped.



Middleware digunakan untuk menentukan tenant dari request dan mengisi `TenantContext`. Setelah tenant ditentukan, model yang memiliki data tenant-owned harus menggunakan pembatasan berdasarkan `tenant\_id`.



Selain pembatasan pada query, akses terhadap data tenant juga diperiksa melalui policy dan scoped binding jika diperlukan.



Data yang bersifat public dapat menggunakan query khusus yang tidak bergantung pada tenant aktif, tetapi hanya untuk data yang memang dinyatakan aman untuk diakses secara publik.



\## Tenant-Owned Data



Data yang memiliki `tenant\_id` dan berkaitan langsung dengan operasional tenant dianggap sebagai tenant-owned data.



Pembatasan tenant diterapkan pada data seperti:



\- menu dan kategori menu

\- modifier group dan modifier option

\- pesanan dan detail pesanan

\- pembayaran

\- ledger dan data keuangan

\- data operasional tenant lainnya yang memiliki `tenant\_id`



\## Access Rules



1\. User hanya boleh mengakses data dari tenant yang sedang aktif.

2\. Query terhadap data tenant-owned harus dibatasi berdasarkan `tenant\_id`.

3\. User tidak boleh memilih atau mengganti tenant secara bebas untuk memperoleh akses ke data tenant lain.

4\. Policy digunakan untuk memeriksa izin ketika suatu resource akan diakses atau dimodifikasi.

5\. Scoped binding digunakan agar resource pada URL tetap berada dalam tenant yang sedang aktif.

6\. Jalur public hanya boleh melewati pembatasan tenant apabila data tersebut memang ditetapkan sebagai public.



\## Allowed Bypass



Bypass terhadap tenant scope hanya diperbolehkan untuk kebutuhan yang memang membutuhkan akses lintas tenant, seperti proses administrasi tertentu atau proses sistem yang telah ditentukan.



Bypass harus dilakukan secara eksplisit dan tidak boleh menjadi perilaku default query aplikasi.



\## Security Principle



Tenant isolation diterapkan secara berlapis:



\- TenantContext menentukan tenant aktif.

\- Middleware memastikan tenant tersedia pada request.

\- Global scope membatasi query model tenant-owned.

\- Policy memeriksa hak akses pengguna.

\- Scoped binding mencegah resource lintas tenant diakses melalui URL.

\- Security test digunakan untuk memastikan data tenant lain tidak dapat diakses secara tidak sengaja.

