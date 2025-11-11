<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2 class="mb-4"><i class="bi bi-cart3"></i> Kasir (Point of Sale)</h2>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Daftar Produk -->
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Daftar Produk</h5>
            </div>
            <div class="card-body">
                <input type="text" class="form-control mb-3" id="searchProduct" placeholder="Cari produk...">
                <div class="row" id="productList">
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-6 mb-3 product-item" data-name="<?= strtolower(esc($product['name'])) ?>">
                                <div class="card h-100 <?= $product['stock'] < 1 ? 'border-danger' : '' ?>">
                                    <div class="card-body">
                                        <h6 class="card-title"><?= esc($product['name']) ?></h6>
                                        <p class="card-text mb-1">
                                            <strong>Rp <?= number_format($product['price'], 0, ',', '.') ?></strong>
                                        </p>
                                        <p class="card-text text-muted mb-2">
                                            Stok: <span class="badge <?= $product['stock'] < 10 ? 'bg-warning' : 'bg-success' ?>"><?= $product['stock'] ?></span>
                                        </p>
                                        <button class="btn btn-sm btn-primary w-100" 
                                            onclick="addToCart(<?= $product['id'] ?>, '<?= esc($product['name']) ?>', <?= $product['price'] ?>, <?= $product['stock'] ?>)"
                                            <?= $product['stock'] < 1 ? 'disabled' : '' ?>>
                                            <i class="bi bi-plus-circle"></i> Tambah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-center text-muted">Tidak ada produk tersedia.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Keranjang -->
    <div class="col-md-5">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Keranjang Belanja</h5>
            </div>
            <div class="card-body">
                <div id="cartItems" style="max-height: 400px; overflow-y: auto;">
                    <p class="text-center text-muted" id="emptyCart">Keranjang kosong</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Total:</h5>
                    <h4 class="text-success" id="totalPrice">Rp 0</h4>
                </div>
                <button class="btn btn-success btn-lg w-100" onclick="processTransaction()" id="btnCheckout" disabled>
                    <i class="bi bi-cash-coin"></i> Proses Transaksi
                </button>
                <button class="btn btn-outline-secondary btn-sm w-100 mt-2" onclick="clearCart()">
                    <i class="bi bi-trash"></i> Kosongkan Keranjang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sukses -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="bi bi-check-circle"></i> Transaksi Berhasil</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <h3>Invoice: <span id="invoiceNo"></span></h3>
                <p class="mb-0">Total: <strong id="modalTotal"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
let cart = [];

// Search produk
document.getElementById('searchProduct').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(item => {
        const name = item.getAttribute('data-name');
        item.style.display = name.includes(search) ? '' : 'none';
    });
});

function addToCart(id, name, price, maxStock) {
    const existing = cart.find(item => item.id === id);
    if (existing) {
        if (existing.qty < maxStock) {
            existing.qty++;
        } else {
            alert('Stok tidak mencukupi!');
            return;
        }
    } else {
        cart.push({id, name, price, qty: 1, maxStock});
    }
    renderCart();
}

function updateQty(id, change) {
    const item = cart.find(i => i.id === id);
    if (item) {
        item.qty += change;
        if (item.qty <= 0) {
            cart = cart.filter(i => i.id !== id);
        } else if (item.qty > item.maxStock) {
            alert('Stok tidak mencukupi!');
            item.qty = item.maxStock;
        }
        renderCart();
    }
}

function removeItem(id) {
    cart = cart.filter(i => i.id !== id);
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    const emptyCart = document.getElementById('emptyCart');
    
    if (cart.length === 0) {
        emptyCart.style.display = 'block';
        container.innerHTML = '<p class="text-center text-muted" id="emptyCart">Keranjang kosong</p>';
        document.getElementById('totalPrice').textContent = 'Rp 0';
        document.getElementById('btnCheckout').disabled = true;
        return;
    }
    
    emptyCart.style.display = 'none';
    let html = '';
    let total = 0;
    
    cart.forEach(item => {
        const subtotal = item.price * item.qty;
        total += subtotal;
        html += `
            <div class="card mb-2">
                <div class="card-body p-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${item.name}</strong><br>
                            <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')}</small>
                        </div>
                        <button class="btn btn-sm btn-danger" onclick="removeItem(${item.id})">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-secondary" onclick="updateQty(${item.id}, -1)">-</button>
                            <button class="btn btn-outline-secondary" disabled>${item.qty}</button>
                            <button class="btn btn-outline-secondary" onclick="updateQty(${item.id}, 1)">+</button>
                        </div>
                        <strong>Rp ${subtotal.toLocaleString('id-ID')}</strong>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('btnCheckout').disabled = false;
}

function clearCart() {
    if (cart.length > 0 && confirm('Kosongkan keranjang?')) {
        cart = [];
        renderCart();
    }
}

async function processTransaction() {
    if (cart.length === 0) return;
    
    const data = cart.map(item => ({
        product_id: item.id,
        qty: item.qty
    }));
    
    try {
        const response = await fetch('<?= base_url('transactions/store') ?>', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            document.getElementById('invoiceNo').textContent = result.invoice;
            document.getElementById('modalTotal').textContent = document.getElementById('totalPrice').textContent;
            new bootstrap.Modal(document.getElementById('successModal')).show();
            cart = [];
            renderCart();
            setTimeout(() => location.reload(), 2000);
        } else {
            alert('Error: ' + result.error);
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
    }
}
</script>
<?= $this->endSection() ?>
