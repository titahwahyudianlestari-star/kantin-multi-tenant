\# Arsitektur Modular Monolith



\## Pembagian Modul



| Modul | Tanggung Jawab |

|---|---|

| Admin | Pengelolaan pengguna, akun, role, dan hak akses |

| Catalog | Pengelolaan produk/menu, harga, ukuran, dan stok bahan |

| Ordering | Pengelolaan pesanan dan detail pesanan |

| Payments | Pengelolaan pembayaran dan status pembayaran |

| Kitchen | Pengelolaan proses pesanan untuk disiapkan |

| Reporting | Pengelolaan laporan transaksi, pendapatan, pengeluaran, dan keuntungan |



\## Kepemilikan Requirement



Setiap requirement utama memiliki satu modul sebagai pemilik agar tanggung jawab sistem tetap jelas.



\- Pengelolaan pengguna dan akses -> Admin

\- Pengelolaan produk dan stok -> Catalog

\- Pemesanan -> Ordering

\- Pembayaran -> Payments

\- Proses pesanan -> Kitchen

\- Laporan -> Reporting



\## Dependency Modul



```text

Admin

&#x20; |

&#x20; +--> Catalog

&#x20; +--> Ordering

&#x20; +--> Payments

&#x20; +--> Kitchen

&#x20; +--> Reporting



Customer --> Catalog --> Ordering --> Payments

&#x20;                        |

&#x20;                        +--> Kitchen



Reporting --> Ordering

Reporting --> Payments

