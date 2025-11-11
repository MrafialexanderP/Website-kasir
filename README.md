# Aplikasi Kasir - CodeIgniter 4

Aplikasi kasir berbasis web menggunakan CodeIgniter 4 dengan fitur:
- Katalog produk (CRUD: tambah, edit, hapus barang)
- Manajemen stok
- Transaksi penjualan dengan update stok atomik
- Laporan penjualan bulanan

## Setup & Instalasi

### 1. Install Dependencies via Composer

Jalankan di PowerShell dari folder `C:\Website-kasir`:

```powershell
composer install
```

Perintah ini akan mengunduh CodeIgniter 4 framework dan dependencies lainnya ke folder `vendor/`.

### 2. Konfigurasi Database

Edit file `.env` di root project dan sesuaikan kredensial database MySQL/MariaDB:

```
database.default.hostname = localhost
database.default.database = kasir_db
database.default.username = root
database.default.password = 
database.default.DBDriver = MySQLi
database.default.port = 3306
```

Pastikan database `kasir_db` sudah dibuat di MySQL. Jika belum, buat dulu:

```sql
CREATE DATABASE kasir_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### 3. Jalankan Migrasi Database

Migrasi akan membuat tabel `products`, `transactions`, dan `transaction_items`:

```powershell
php spark migrate
```

### 4. Jalankan Development Server

```powershell
php spark serve --host=0.0.0.0 --port=8080
```

Akses aplikasi di browser: **http://localhost:8080**

---

## Endpoint API

### Products

- **GET** `/products` — List semua produk
- **POST** `/products` — Tambah produk baru
  - Body (JSON): `{"name":"Produk A","price":15000,"stock":100,"sku":"SKU001"}`
- **DELETE** `/products/{id}` — Hapus produk

### Transactions

- **POST** `/transactions` — Buat transaksi baru
  - Body (JSON): `[{"product_id":1,"qty":2},{"product_id":2,"qty":1}]`
  - Response: `{"success":true,"invoice":"INV20251111123456789","transaction_id":1}`

---

## Testing Manual (Contoh cURL)

### 1. Tambah Produk

```powershell
curl -X POST http://localhost:8080/products `
  -H "Content-Type: application/json" `
  -d '{\"name\":\"Kopi Susu\",\"price\":12000,\"stock\":50,\"sku\":\"KOP001\"}'
```

### 2. List Produk

```powershell
curl http://localhost:8080/products
```

### 3. Buat Transaksi

```powershell
curl -X POST http://localhost:8080/transactions `
  -H "Content-Type: application/json" `
  -d '[{\"product_id\":1,\"qty\":3}]'
```

---

## Struktur Folder

```
C:\Website-kasir\
├── app/
│   ├── Config/
│   │   ├── Paths.php
│   │   └── Routes.php
│   ├── Controllers/
│   │   ├── BaseController.php
│   │   ├── Home.php
│   │   ├── ProductController.php
│   │   └── TransactionController.php
│   ├── Database/
│   │   └── Migrations/
│   │       └── 2025-11-11-000001_CreateProductsTransactions.php
│   └── Models/
│       ├── ProductModel.php
│       └── TransactionModel.php
├── public/
│   ├── index.php
│   └── .htaccess
├── writable/
├── vendor/ (dibuat setelah composer install)
├── .env
├── composer.json
├── spark
└── README.md
```

---

## Troubleshooting

### Error: "Could not open input file: spark"
- Pastikan file `spark` ada di root project.
- Jalankan `composer install` terlebih dahulu.

### Error: Database connection failed
- Cek kredensial di `.env`.
- Pastikan MySQL/MariaDB sudah running.
- Pastikan database `kasir_db` sudah dibuat.

### Error: "Class 'CodeIgniter\...' not found"
- Jalankan `composer install` untuk download framework.

---

## Next Steps

- Implementasi UI (views) dengan Bootstrap untuk katalog dan kasir.
- Tambahkan ReportController untuk laporan bulanan.
- Implementasi autentikasi user (login/register).
- Tambahkan validasi & error handling yang lebih baik.
- Audit trail (stock_logs) untuk jejak perubahan stok.

---

## Kontak

Proyek ini dibuat oleh mahasiswa Akuntansi angkatan 60 untuk tugas aplikasi kasir.
