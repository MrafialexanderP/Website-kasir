-- Update sample data dengan produk alat tulis kantor (ATK)
-- Jalankan di phpMyAdmin atau MySQL client

USE kasir_db;

-- Hapus data lama
TRUNCATE TABLE transaction_items;
TRUNCATE TABLE transactions;
TRUNCATE TABLE products;

-- Insert produk alat tulis kantor dengan kategori dan gambar
INSERT INTO products (sku, name, category, image, description, price, stock, created_at) VALUES
('ATK001', 'Pulpen Joyko AX-105', 'Alat Tulis', 'pulpen.jpg', 'Pulpen gel hitam 0.5mm untuk menulis halus', 3500, 150, NOW()),
('ATK002', 'Pensil 2B Faber Castell', 'Alat Tulis', 'pensil.jpg', 'Pensil kayu berkualitas tinggi untuk menulis dan sketsa', 2500, 200, NOW()),
('ATK003', 'Penghapus Steadtler', 'Alat Tulis', 'penghapus.jpg', 'Penghapus putih bebas PVC, tidak meninggalkan noda', 5000, 100, NOW()),
('ATK004', 'Spidol Whiteboard Snowman', 'Alat Tulis', 'spidol.jpg', 'Spidol untuk papan tulis putih, mudah dihapus', 8000, 80, NOW()),
('ATK005', 'Stabilo Boss Original', 'Alat Tulis', 'stabilo.jpg', 'Highlighter warna kuning neon untuk menandai teks penting', 12000, 60, NOW()),
('ATK006', 'Buku Tulis Sinar Dunia 58 Lembar', 'Buku & Kertas', 'buku-tulis.jpg', 'Buku tulis folio bergaris 58 lembar cover tebal', 6000, 120, NOW()),
('ATK007', 'Kertas HVS A4 70gsm Sidu (1 Rim)', 'Buku & Kertas', 'hvs-a4.jpg', 'Kertas HVS A4 untuk print dan fotocopy 500 lembar/rim', 45000, 50, NOW()),
('ATK008', 'Map Plastik Bantex', 'Penyimpanan', 'map-plastik.jpg', 'Map plastik transparan ukuran folio untuk dokumen', 4000, 90, NOW()),
('ATK009', 'Ordner Bantex 1450-10', 'Penyimpanan', 'ordner.jpg', 'Ordner lever arch file ukuran folio tebal 5cm', 28000, 40, NOW()),
('ATK010', 'Stapler Joyko HD-10D', 'Alat Kantor', 'stapler.jpg', 'Stapler kecil untuk kertas maksimal 20 lembar', 15000, 70, NOW()),
('ATK011', 'Isi Stapler Max No.10', 'Alat Kantor', 'isi-stapler.jpg', 'Isi stapler standar 1000 pcs per box', 3000, 150, NOW()),
('ATK012', 'Gunting Joyko SC-828', 'Alat Kantor', 'gunting.jpg', 'Gunting stainless steel tajam 8 inch', 12000, 55, NOW()),
('ATK013', 'Lem Kertas Glukol 250ml', 'Perekat', 'lem-cair.jpg', 'Lem putih cair untuk kertas dan kerajinan', 8500, 65, NOW()),
('ATK014', 'Lem Stick Joyko', 'Perekat', 'lem-stick.jpg', 'Lem batang praktis untuk kertas tanpa berantakan', 6500, 80, NOW()),
('ATK015', 'Lakban Coklat Daimaru 48mm', 'Perekat', 'lakban.jpg', 'Lakban packing coklat lebar 48mm panjang 90 yard', 11000, 45, NOW()),
('ATK016', 'Penggaris Plastik 30cm', 'Alat Ukur', 'penggaris.jpg', 'Penggaris transparan dengan skala cm dan inch', 3000, 100, NOW()),
('ATK017', 'Kalkulator Casio MJ-120D Plus', 'Elektronik', 'kalkulator.jpg', 'Kalkulator 12 digit dengan layar besar', 85000, 25, NOW()),
('ATK018', 'Correction Tape Kenko', 'Alat Tulis', 'tip-ex.jpg', 'Tip-ex roll untuk koreksi tulisan instant', 7500, 75, NOW()),
('ATK019', 'Post-it Notes 3x3 inch', 'Kertas Khusus', 'post-it.jpg', 'Sticky notes warna kuning 100 lembar per pad', 18000, 50, NOW()),
('ATK020', 'Klip Kertas Paperclip No.3', 'Alat Kantor', 'klip.jpg', 'Penjepit kertas ukuran sedang 100 pcs per box', 4500, 120, NOW());
