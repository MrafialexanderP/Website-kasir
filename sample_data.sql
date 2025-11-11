-- Sample data untuk testing aplikasi kasir
-- Jalankan di phpMyAdmin atau MySQL client

USE kasir_db;

-- Insert sample products
INSERT INTO products (sku, name, price, stock, created_at) VALUES
('BRG001', 'Kopi Susu', 15000, 50, NOW()),
('BRG002', 'Teh Manis', 8000, 75, NOW()),
('BRG003', 'Nasi Goreng', 18000, 30, NOW()),
('BRG004', 'Mie Ayam', 15000, 40, NOW()),
('BRG005', 'Es Jeruk', 7000, 60, NOW()),
('BRG006', 'Roti Bakar', 12000, 25, NOW()),
('BRG007', 'Kentang Goreng', 10000, 35, NOW()),
('BRG008', 'Juice Alpukat', 18000, 20, NOW()),
('BRG009', 'Pisang Goreng', 8000, 45, NOW()),
('BRG010', 'Soto Ayam', 20000, 30, NOW());
