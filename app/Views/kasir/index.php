<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="mb-4">
    <h1 class="page-title"><i class="bi bi-cart3"></i> Kasir (Point of Sale)</h1>
</div>

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
            <div class="card-body p-4">
                <div class="text-center mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-muted mb-0"><i class="bi bi-check-circle-fill text-success"></i> Transaksi Berhasil</h5>
                        <button type="button" class="btn-close no-print" onclick="hideInvoice()" aria-label="Close"></button>
                    </div>
                    <div class="invoice-icon mb-3">
                        <i class="bi bi-receipt text-success" style="font-size: 3rem;"></i>
                    </div>
                </div>
                
                <div class="invoice-details mb-3">
                    <div class="row mb-2">
                        <div class="col-5 text-start">
                            <strong>Invoice:</strong>
                        </div>
                        <div class="col-7 text-end">
                            <span class="badge bg-light text-dark border" id="invoiceNo" style="font-size: 0.9rem;"></span>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-start">
                            <strong>Tanggal:</strong>
                        </div>
                        <div class="col-7 text-end" id="transactionDate"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-start">
                            <strong>Waktu:</strong>
                        </div>
                        <div class="col-7 text-end" id="transactionTime"></div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-5 text-start">
                            <strong>Total Item:</strong>
                        </div>
                        <div class="col-7 text-end" id="invoiceTotalItems"></div>
                    </div>
                    <div class="row">
                        <div class="col-5 text-start">
                            <strong>Total Bayar:</strong>
                        </div>
                        <div class="col-7 text-end">
                            <strong class="text-success fs-5" id="invoiceTotal"></strong>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-info text-center mb-3" role="alert">
                    <i class="bi bi-info-circle"></i> Terima kasih atas transaksi Anda!
                </div>
                
                <div class="d-grid gap-2 no-print">
                    <button type="button" class="btn btn-outline-secondary" onclick="hideInvoice()">
                        <i class="bi bi-x-circle"></i> Tutup
                    </button>
                    <button type="button" class="btn btn-primary" onclick="printInvoice()">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>
                
                <!-- Hidden detailed items table for print only -->
                <div class="print-only" style="display: none;">
                    <h6 class="fw-bold mb-3 mt-4">Detail Pembelian:</h6>
                    <table class="table table-bordered table-sm" id="invoiceItemsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="8%" class="text-center">No</th>
                                <th width="45%">Nama Produk</th>
                                <th width="15%" class="text-center">Qty</th>
                                <th width="16%" class="text-end">Harga</th>
                                <th width="16%" class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceItemsBody">
                            <!-- Items will be inserted here -->
                        </tbody>
                    </table>
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
    /* Page setup */
    @page {
        size: A4;
        margin: 8mm;
    }
    
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        height: 100vh !important;
        overflow: hidden !important;
    }
    
    /* Hide everything except invoice using visibility */
    body * {
        visibility: hidden;
    }
    
    /* Show invoice and all its children */
    #invoiceSection,
    #invoiceSection * {
        visibility: visible;
    }
    
    /* Force invoice to top of page with max height */
    #invoiceSection {
        position: fixed !important;
        left: 0 !important;
        top: 0 !important;
        width: 100% !important;
        max-height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
        page-break-after: always !important;
    }
    
    #invoiceSection .row {
        margin: 0 !important;
        padding: 0 !important;
    }
    
    #invoiceSection .col-12 {
        padding: 0 !important;
    }
    
    /* Hide buttons */
    .no-print,
    .btn-close {
        display: none !important;
    }
    
    /* Show print-only content */
    .print-only {
        display: block !important;
    }
    
    /* Card styling - maximum compact for single page */
    #invoiceSection .card {
        border: 1px solid #ddd !important;
        box-shadow: none !important;
        max-width: 100% !important;
        max-height: 100vh !important;
        margin: 0 !important;
        padding: 0 !important;
        overflow: hidden !important;
    }
    
    #invoiceSection .card-body {
        padding: 5px !important;
        margin: 0 !important;
        max-height: 100vh !important;
        overflow: hidden !important;
    }
    
    /* Hide icon in print to save space */
    #invoiceSection .invoice-icon {
        display: none !important;
    }
    
    /* Title - minimal */
    #invoiceSection h5 {
        font-size: 0.75rem !important;
        margin: 1px 0 !important;
        padding: 0 !important;
        line-height: 1.2 !important;
    }
    
    #invoiceSection .mb-3 {
        margin-bottom: 3px !important;
    }
    
    /* Invoice details - minimal */
    #invoiceSection .invoice-details {
        margin-bottom: 3px !important;
        margin-top: 0 !important;
        font-size: 0.7rem !important;
    }
    
    #invoiceSection .invoice-details .row {
        margin-bottom: 1px !important;
        margin-top: 0 !important;
        line-height: 1.1 !important;
    }
    
    #invoiceSection .invoice-details .col-5,
    #invoiceSection .invoice-details .col-7 {
        padding: 0 2px !important;
    }
    
    /* Badges - minimal */
    #invoiceSection .badge {
        border: 1px solid #ddd !important;
        padding: 0px 4px !important;
        margin: 0 !important;
        background-color: #f8f9fa !important;
        color: #000 !important;
        font-size: 0.65rem !important;
        line-height: 1.2 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Text colors */
    #invoiceSection .text-success {
        color: #198754 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    #invoiceSection .text-muted {
        color: #6c757d !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Alert - minimal */
    #invoiceSection .alert-info {
        background-color: #cfe2ff !important;
        border: 1px solid #9ec5fe !important;
        color: #084298 !important;
        padding: 3px !important;
        margin: 3px 0 !important;
        border-radius: 2px !important;
        font-size: 0.7rem !important;
        line-height: 1.2 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Detail items table - minimal */
    #invoiceSection .print-only h6 {
        color: #000 !important;
        font-weight: bold !important;
        margin: 2px 0 !important;
        padding: 0 !important;
        font-size: 0.75rem !important;
    }
    
    #invoiceSection .table {
        border: 1px solid #000 !important;
        border-collapse: collapse !important;
        width: 100% !important;
        font-size: 0.6rem !important;
        margin: 0 !important;
        line-height: 1.1 !important;
    }
    
    #invoiceSection .table th,
    #invoiceSection .table td {
        border: 1px solid #000 !important;
        padding: 1px !important;
        margin: 0 !important;
    }
    
    #invoiceSection .table thead th {
        font-size: 0.6rem !important;
        padding: 1px !important;
    }
    
    #invoiceSection .table-light {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    /* Font sizes - minimal */
    #invoiceSection .fs-5 {
        font-size: 0.75rem !important;
    }
    
    #invoiceSection h5 {
        font-size: 0.75rem !important;
        margin: 1px 0 !important;
        line-height: 1.2 !important;
    }
    
    /* Text alignment */
    #invoiceSection .text-center {
        text-align: center !important;
    }
    
    #invoiceSection .text-end {
        text-align: right !important;
    }
    
    #invoiceSection .text-start {
        text-align: left !important;
    }
    
    /* Font weights */
    #invoiceSection .fw-bold {
        font-weight: bold !important;
    }
    
    /* Icons - minimal */
    #invoiceSection .bi-check-circle-fill {
        color: #198754 !important;
        font-size: 0.7rem !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    
    #invoiceSection .bi-info-circle {
        font-size: 0.7rem !important;
    }
    
    /* Center and limit width */
    #invoiceSection .card {
        max-width: 100% !important;
        margin: 0 !important;
    }
    
    /* Reduce all spacing to absolute minimum */
    #invoiceSection .d-flex {
        margin-bottom: 2px !important;
    }
    
    #invoiceSection .text-center {
        margin-bottom: 2px !important;
    }

}
</style>
<script>
// Store cart items for invoice
let invoiceItems = [];
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
            Swal.fire({
                icon: 'warning',
                title: 'Stok tidak cukup',
                text: `Stok ${name} tidak mencukupi! Maksimal ${maxStock} item.`,
                confirmButtonText: 'Mengerti'
            });
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

async function updateQty(id, change) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    
    const newQty = item.qty + change;
    
    // Validasi qty minimal
    if (newQty <= 0) {
        const res = await Swal.fire({
            title: 'Hapus item?',
            html: `Hapus <strong>${item.name}</strong> dari keranjang?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280'
        });
        if (res.isConfirmed) {
            cart = cart.filter(i => i.id !== id);
        } else {
            return;
        }
    } 
    // Validasi qty maksimal (stok)
    else if (newQty > item.maxStock) {
        Swal.fire({
            icon: 'warning',
            title: 'Stok tidak cukup',
            text: `Stok ${item.name} tidak mencukupi! Maksimal ${item.maxStock} item.`,
            confirmButtonText: 'Mengerti'
        });
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

async function clearCart() {
    if (cart.length === 0) return;
    const res = await Swal.fire({
        title: 'Kosongkan keranjang?',
        text: 'Semua item akan dihapus dari keranjang.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, kosongkan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280'
    });
    if (res.isConfirmed) {
        cart = [];
        localStorage.removeItem('kasirCart');
        renderCart();
    }
}

function hideInvoice() {
    document.getElementById('invoiceSection').classList.add('d-none');
    invoiceItems = []; // Clear invoice items
    location.reload();
}

function printInvoice() {
    window.print();
}

function fillInvoiceItems() {
    const tbody = document.getElementById('invoiceItemsBody');
    if (!tbody) {
        console.error('Invoice items body not found!');
        return;
    }
    
    let html = '';
    invoiceItems.forEach((item, index) => {
        const subtotal = item.price * item.qty;
        html += `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>${item.name}</td>
                <td class="text-center">${item.qty}</td>
                <td class="text-end">Rp ${item.price.toLocaleString('id-ID')}</td>
                <td class="text-end">Rp ${subtotal.toLocaleString('id-ID')}</td>
            </tr>
        `;
    });
    
    tbody.innerHTML = html;
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
            
            // Get total items and save cart items for invoice
            const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
            invoiceItems = [...cart]; // Save cart items before clearing
            
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
                
                // Fill invoice items table
                fillInvoiceItems();
            } else {
                console.error('Invoice elements not found!');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal membuat invoice',
                    text: 'Elemen invoice tidak ditemukan. Silakan muat ulang halaman.'
                });
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
            Swal.fire({
                icon: 'error',
                title: 'Transaksi gagal',
                text: result.error || 'Terjadi kesalahan saat memproses transaksi.'
            });
        }
    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Terjadi kesalahan',
            text: error.message
        });
    }
}
</script>
<?= $this->endSection() ?>
