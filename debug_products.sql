-- Cek struktur tabel products
DESCRIBE products;

-- Cek data yang ada
SELECT id, sku, name, category, image, price, stock FROM products LIMIT 5;
