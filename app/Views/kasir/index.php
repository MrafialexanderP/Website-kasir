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
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="bi bi-cart3"></i> Keranjang Belanja</h5>
                    <span class="badge bg-light text-success" id="cartCount">0 Item</span>
                </div>
            </div>
            <div class="card-body">
                <div id="cartItems" style="max-height: 400px; overflow-y: auto;">
                    <p class="text-center text-muted" id="emptyCart">Keranjang kosong</p>
                </div>
                <hr>
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted">Total Item:</span>
                    <strong id="totalItems">0</strong>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Total Bayar:</h5>
                    <h4 class="text-success mb-0" id="totalPrice">Rp 0</h4>
                </div>
                <button class="btn btn-success btn-lg w-100" onclick="processTransaction()" id="btnCheckout" disabled>
                    <i class="bi bi-cash-coin"></i> Proses Transaksi
                </button>
                <button class="btn btn-outline-danger btn-sm w-100 mt-2" onclick="clearCart()">
                    <i class="bi bi-trash"></i> Kosongkan Keranjang
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Invoice Section (Hidden by default) -->
<div class="row mt-4 d-none" id="invoiceSection">
    <div class="col-12">
        <div class="card shadow-lg border-0">
            <div class="card-header bg-success text-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-check-circle-fill"></i> Transaksi Berhasil</h4>
                    <button class="btn btn-light btn-sm" onclick="hideInvoice()">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <i class="bi bi-receipt text-success" style="font-size: 4rem;"></i>
                    <h5 class="mt-3 text-success">Invoice Transaksi</h5>
                </div>
                
                <div class="row">
                    <div class="col-md-8 offset-md-2">
                        <table class="table table-borderless table-lg">
                            <tr>
                                <td width="40%"><strong>Invoice:</strong></td>
                                <td class="text-end"><span class="badge bg-primary fs-5" id="invoiceNo"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td class="text-end fs-6" id="transactionDate"></td>
                            </tr>
                            <tr>
                                <td><strong>Waktu:</strong></td>
                                <td class="text-end"><span class="badge bg-info fs-6" id="transactionTime"></span></td>
                            </tr>
                            <tr>
                                <td><strong>Total Item:</strong></td>
                                <td class="text-end fs-5" id="invoiceTotalItems"></td>
                            </tr>
                            <tr class="border-top">
                                <td><strong>Total Bayar:</strong></td>
                                <td class="text-end"><h3 class="text-success mb-0" id="invoiceTotal"></h3></td>
                            </tr>
                        </table>
                        
                        <div class="alert alert-success mb-3">
                            <i class="bi bi-info-circle"></i> Terima kasih atas transaksi Anda!
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-primary btn-lg" onclick="window.print()">
                                <i class="bi bi-printer"></i> Print Invoice
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="hideInvoice()">
                                <i class="bi bi-x-circle"></i> Tutup & Transaksi Baru
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
/* Prevent modal from causing page scroll */
body.modal-open {
    overflow: hidden !important;
}

#invoiceSection {
    animation: slideDown 0.5s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media print {
    /* Hide everything except invoice when printing */
    body * {
        visibility: hidden;
    }
    #invoiceSection, #invoiceSection * {
        visibility: visible;
    }
    #invoiceSection {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    #invoiceSection .btn {
        display: none !important;
    }
}
</style>
<script>
// Load cart from localStorage on page load
let cart = JSON.parse(localStorage.getItem('kasirCart') || '[]');

// Render cart on page load
document.addEventListener('DOMContentLoaded', function() {
    renderCart();
});

// Search produk
document.getElementById('searchProduct').addEventListener('input', function(e) {
    const search = e.target.value.toLowerCase();
    document.querySelectorAll('.product-item').forEach(item => {
        const name = item.getAttribute('data-name');
        item.style.display = name.includes(search) ? '' : 'none';
    });
});

function addToCart(id, name, price, maxStock) {
    // Cek apakah produk sudah ada di keranjang
    const existing = cart.find(item => item.id === id);
    
    if (existing) {
        // Jika sudah ada, tambah qty
        if (existing.qty < maxStock) {
            existing.qty++;
            showToast(`${name} ditambahkan (${existing.qty})`, 'success');
        } else {
            alert(`Stok ${name} tidak mencukupi! Maksimal ${maxStock} item.`);
            return;
        }
    } else {
        // Jika belum ada, tambah produk baru
        cart.push({id, name, price, qty: 1, maxStock});
        showToast(`${name} ditambahkan ke keranjang`, 'success');
    }
    
    saveCartToLocalStorage();
    renderCart();
}

// Save cart to localStorage
function saveCartToLocalStorage() {
    localStorage.setItem('kasirCart', JSON.stringify(cart));
}

// Helper function untuk toast notification
function showToast(message, type = 'info') {
    // Buat toast element
    const toastHtml = `
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
            <div class="toast show align-items-center text-white bg-${type === 'success' ? 'success' : 'primary'} border-0" role="alert">
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="bi bi-check-circle"></i> ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            </div>
        </div>
    `;
    
    const temp = document.createElement('div');
    temp.innerHTML = toastHtml;
    const toastElement = temp.firstElementChild;
    document.body.appendChild(toastElement);
    
    // Auto remove after 2 seconds
    setTimeout(() => {
        toastElement.remove();
    }, 2000);
}

function updateQty(id, change) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    
    const newQty = item.qty + change;
    
    // Validasi qty minimal
    if (newQty <= 0) {
        if (confirm(`Hapus ${item.name} dari keranjang?`)) {
            cart = cart.filter(i => i.id !== id);
        }
    } 
    // Validasi qty maksimal (stok)
    else if (newQty > item.maxStock) {
        alert(`Stok ${item.name} tidak mencukupi! Maksimal ${item.maxStock} item.`);
        return;
    } 
    // Update qty
    else {
        item.qty = newQty;
    }
    
    saveCartToLocalStorage();
    renderCart();
}

function removeItem(id) {
    cart = cart.filter(i => i.id !== id);
    saveCartToLocalStorage();
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cartItems');
    
    if (cart.length === 0) {
        container.innerHTML = '<p class="text-center text-muted" id="emptyCart">Keranjang kosong</p>';
        document.getElementById('totalPrice').textContent = 'Rp 0';
        document.getElementById('btnCheckout').disabled = true;
        updateCartInfo(0, 0);
        return;
    }
    
    let html = '';
    let total = 0;
    let totalQty = 0;
    
    cart.forEach(item => {
        const subtotal = item.price * item.qty;
        total += subtotal;
        totalQty += item.qty;
        html += `
            <div class="card mb-2 shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="flex-grow-1">
                            <strong class="d-block">${item.name}</strong>
                            <small class="text-muted">@ Rp ${item.price.toLocaleString('id-ID')}</small>
                        </div>
                        <button class="btn btn-sm btn-danger" onclick="removeItem(${item.id})" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-primary" onclick="updateQty(${item.id}, -1)" ${item.qty <= 1 ? 'disabled' : ''}>
                                <i class="bi bi-dash"></i>
                            </button>
                            <button type="button" class="btn btn-outline-primary" disabled style="min-width: 50px;">
                                <strong>${item.qty}</strong>
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="updateQty(${item.id}, 1)" ${item.qty >= item.maxStock ? 'disabled' : ''}>
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <div class="text-end">
                            <strong class="text-success">Rp ${subtotal.toLocaleString('id-ID')}</strong>
                        </div>
                    </div>
                    <div class="mt-1">
                        <small class="text-muted">Stok tersedia: ${item.maxStock}</small>
                    </div>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
    document.getElementById('totalPrice').textContent = 'Rp ' + total.toLocaleString('id-ID');
    document.getElementById('btnCheckout').disabled = false;
    
    // Update info jumlah item di keranjang
    updateCartInfo(totalQty, total);
}

function updateCartInfo(qty, total) {
    // Update badge jumlah item di header keranjang
    const cartCount = document.getElementById('cartCount');
    const totalItems = document.getElementById('totalItems');
    
    if (cartCount) {
        cartCount.textContent = `${qty} Item${qty > 1 ? 's' : ''}`;
    }
    
    if (totalItems) {
        totalItems.textContent = qty;
    }
}

function clearCart() {
    if (cart.length > 0 && confirm('Kosongkan keranjang?')) {
        cart = [];
        localStorage.removeItem('kasirCart');
        renderCart();
    }
}

function hideInvoice() {
    document.getElementById('invoiceSection').classList.add('d-none');
    location.reload();
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
            // Get current date and time
            const now = new Date();
            const dateOptions = { year: 'numeric', month: '2-digit', day: '2-digit' };
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
            
            const formattedDate = now.toLocaleDateString('id-ID', dateOptions);
            const formattedTime = now.toLocaleTimeString('id-ID', timeOptions);
            
            // Get total items
            const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
            
            // Fill invoice section with transaction details
            const invoiceNo = document.getElementById('invoiceNo');
            const transactionDate = document.getElementById('transactionDate');
            const transactionTime = document.getElementById('transactionTime');
            const invoiceTotalItems = document.getElementById('invoiceTotalItems');
            const invoiceTotal = document.getElementById('invoiceTotal');
            
            if (invoiceNo && transactionDate && transactionTime && invoiceTotalItems && invoiceTotal) {
                invoiceNo.textContent = result.invoice;
                transactionDate.textContent = formattedDate;
                transactionTime.textContent = formattedTime;
                invoiceTotalItems.textContent = totalItems + ' item';
                invoiceTotal.textContent = document.getElementById('totalPrice').textContent;
            } else {
                console.error('Invoice elements not found!');
                alert('Error: Invoice elements not found. Please refresh the page.');
                return;
            }
            
            // Clear cart
            cart = [];
            localStorage.removeItem('kasirCart');
            renderCart();
            
            // Show invoice section and scroll to it
            const invoiceSection = document.getElementById('invoiceSection');
            if (invoiceSection) {
                invoiceSection.classList.remove('d-none');
                
                // Smooth scroll to invoice
                setTimeout(() => {
                    invoiceSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 100);
            } else {
                console.error('Invoice section not found!');
            }
        } else {
            alert('Error: ' + result.error);
        }
    } catch (error) {
        alert('Terjadi kesalahan: ' + error.message);
    }
}
</script>
<?= $this->endSection() ?>
