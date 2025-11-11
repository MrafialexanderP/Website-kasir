# Aplikasi Kasir - CodeIgniter 4

Aplikasi kasir berbasis web menggunakan CodeIgniter 4 dengan fitur lengkap:
- ✅ Katalog produk (CRUD: tambah, edit, hapus barang)
- ✅ Manajemen stok real-time
- ✅ Transaksi penjualan (Point of Sale / Kasir)
- ✅ Laporan penjualan bulanan dengan statistik
- ✅ Update stok atomik (mencegah race condition)
- ✅ UI responsif dengan Bootstrap 5

## Screenshot Fitur

### 1. Katalog Produk
- List semua produk dengan info stok
- Tambah produk baru
- Edit produk (nama, harga, stok, SKU)
- Hapus produk

### 2. Kasir (Point of Sale)
- Pilih produk dari katalog
- Keranjang belanja interaktif
- Update qty dengan validasi stok
- Proses transaksi langsung
- Invoice otomatis

### 3. Laporan Penjualan
- Total transaksi & penjualan
- Produk terlaris (top 10)
- Filter per bulan/tahun
- Riwayat transaksi

## Setup & Instalasi

### 1. Install Dependencies via Composer

Jalankan di PowerShell dari folder `C:\Website-kasir`:

```powershell
composer install
```

### 2. Konfigurasi Database

Edit file `.env` di root project dan sesuaikan kredensial database MySQL/MariaDB:

```
database.default.hostname=localhost
database.default.database=kasir_db
database.default.username=root
database.default.password=
database.default.DBDriver=MySQLi
database.default.port=3306
```

Buat database `kasir_db` di phpMyAdmin atau MySQL client:

```sql
CREATE DATABASE kasir_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
```

### 3. Jalankan Migrasi Database

```powershell
php spark migrate
```

### 4. (Opsional) Insert Sample Data

Buka `sample_data.sql` di phpMyAdmin atau jalankan:

```powershell
mysql -u root -p kasir_db < sample_data.sql
```

### 5. Jalankan Development Server

```powershell
php spark serve --host=0.0.0.0 --port=8080
```

Akses aplikasi di browser: **http://localhost:8080**

---

## Halaman Aplikasi

### Homepage / Katalog Produk
**URL:** http://localhost:8080 atau http://localhost:8080/products

Fitur:
- Lihat semua produk dengan badge stok (merah jika < 10)
- Tambah produk baru (modal form)
- Edit produk (modal form)
- Hapus produk dengan konfirmasi

### Kasir (POS)
**URL:** http://localhost:8080/kasir

Fitur:
- Search produk real-time
- Tambah ke keranjang
- Update qty (+/-)
- Hapus item dari keranjang
- Total otomatis
- Proses transaksi AJAX
- Modal invoice sukses

### Laporan
**URL:** http://localhost:8080/laporan

Fitur:
- Statistik card (total transaksi, penjualan, item terjual, produk terlaris)
- Filter per bulan & tahun
- Tabel transaksi terbaru
- Top 10 produk terlaris dengan revenue

---

## Struktur Database

### Tabel `products`
- id (PK)
- sku (VARCHAR, unique, optional)
- name (VARCHAR, NOT NULL)
- price (DECIMAL)
- stock (INT)
- created_at, updated_at

### Tabel `transactions`
- id (PK)
- invoice_no (VARCHAR, unique)
- user_id (INT, optional)
- total_qty (INT)
- total_price (DECIMAL)
- created_at

### Tabel `transaction_items`
- id (PK)
- transaction_id (FK)
- product_id (FK)
- qty (INT)
- price (DECIMAL) -- snapshot harga saat transaksi
- subtotal (DECIMAL)

---

## Alur Transaksi (Business Logic)

1. User pilih produk di halaman Kasir
2. Produk masuk keranjang (client-side JS)
3. User klik "Proses Transaksi"
4. AJAX POST ke `/transactions/store` dengan data cart JSON
5. Server validasi stok untuk setiap item
6. **DB Transaction START**
7. Update stok atomik: `UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?`
8. Insert ke tabel `transactions`
9. Insert detail ke tabel `transaction_items`
10. **DB Transaction COMMIT**
11. Return invoice number
12. Modal sukses muncul, keranjang dikosongkan

---

## Testing Manual

### Test CRUD Produk

1. Buka http://localhost:8080/products
2. Klik "Tambah Produk" → isi form → Simpan
3. Klik "Edit" pada produk → ubah data → Update
4. Klik "Hapus" → konfirmasi → produk terhapus

### Test Transaksi Kasir

1. Buka http://localhost:8080/kasir
2. Klik "Tambah" pada beberapa produk
3. Update qty di keranjang
4. Klik "Proses Transaksi"
5. Lihat modal invoice
6. Cek stok produk berkurang di Katalog
7. Cek transaksi muncul di Laporan

### Test Laporan

1. Buka http://localhost:8080/laporan
2. Pilih bulan & tahun
3. Klik "Tampilkan"
4. Lihat statistik card berubah
5. Lihat tabel transaksi dan produk terlaris

---

## Teknologi

- **Backend:** CodeIgniter 4.6.3 (PHP 8.1+)
- **Database:** MySQL / MariaDB (via phpMyAdmin)
- **Frontend:** Bootstrap 5.3 + Bootstrap Icons
- **JavaScript:** Vanilla JS (AJAX Fetch API)

---

## File Struktur Penting

```
C:\Website-kasir\
├── app/
│   ├── Config/
│   │   ├── Database.php (konfigurasi DB)
│   │   └── Routes.php (routing URL)
│   ├── Controllers/
│   │   ├── ProductController.php (CRUD produk)
│   │   ├── KasirController.php (halaman kasir)
│   │   ├── TransactionController.php (proses transaksi)
│   │   └── LaporanController.php (laporan)
│   ├── Database/
│   │   └── Migrations/
│   │       └── 2025-11-11-000001_CreateProductsTransactions.php
│   ├── Models/
│   │   ├── ProductModel.php
│   │   └── TransactionModel.php
│   └── Views/
│       ├── layout.php (template master)
│       ├── products/index.php (katalog)
│       ├── kasir/index.php (POS)
│       └── laporan/index.php (laporan)
├── public/
│   └── index.php (front controller)
├── writable/
│   ├── cache/
│   └── logs/
├── .env (konfigurasi environment & DB)
├── composer.json
├── spark (CLI tool)
├── sample_data.sql (data contoh)
└── README.md
```

---

## Troubleshooting

### Server tidak jalan / Error 500
- Cek `writable/logs/log-YYYY-MM-DD.php` untuk error details
- Pastikan folder `writable/` writable (chmod 777 di Linux)

### Database connection failed
- Cek kredensial di `.env`
- Pastikan MySQL/MariaDB running (XAMPP/Laragon)
- Pastikan database `kasir_db` sudah dibuat
- Test koneksi: `php spark db:table migrations`

### Migrasi gagal
- Drop database lalu buat ulang:
  ```sql
  DROP DATABASE kasir_db;
  CREATE DATABASE kasir_db;
  ```
- Jalankan ulang: `php spark migrate`

### Transaksi gagal / stok tidak update
- Cek apakah ada error di console browser (F12)
- Cek response AJAX di Network tab
- Pastikan produk punya stok cukup

---

## Fitur Tambahan (Next Steps)

- [ ] Autentikasi user (login/register)
- [ ] Role-based access (admin, kasir)
- [ ] Print / export laporan PDF
- [ ] Multi-payment method
- [ ] Diskon & promo
- [ ] Barcode scanner integration
- [ ] Audit trail (stock_logs)

---

## Kontak & Credits

Aplikasi ini dibuat oleh **mahasiswa Akuntansi angkatan 60** untuk tugas aplikasi kasir berbasis website.

Database terhubung dengan **phpMyAdmin (MySQL)**.
Framework: **CodeIgniter 4.6.3**
