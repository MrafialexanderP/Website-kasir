<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title"><i class="bi bi-box-seam"></i> Katalog Produk</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
        <i class="bi bi-plus-circle"></i> Tambah Produk
    </button>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" style="border-radius: 15px; border-left: 4px solid #38ef7d;">
        <i class="bi bi-check-circle-fill"></i> <?= session()->getFlashdata('success') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" style="border-radius: 15px; border-left: 4px solid #f45c43;">
        <i class="bi bi-exclamation-triangle-fill"></i> <?= session()->getFlashdata('error') ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Search & Filter -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row g-3">
            <div class="col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white"><i class="bi bi-search text-primary"></i></span>
                    <input type="text" class="form-control" id="searchInput" placeholder="Cari nama atau SKU produk...">
                </div>
            </div>
            <div class="col-md-3">
                <select class="form-select" id="filterCategory">
                    <option value="">Semua Kategori</option>
                    <option value="Alat Tulis">Alat Tulis</option>
                    <option value="Buku & Kertas">Buku & Kertas</option>
                    <option value="Penyimpanan">Penyimpanan</option>
                    <option value="Alat Kantor">Alat Kantor</option>
                    <option value="Perekat">Perekat</option>
                    <option value="Alat Ukur">Alat Ukur</option>
                    <option value="Elektronik">Elektronik</option>
                    <option value="Kertas Khusus">Kertas Khusus</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="filterStock">
                    <option value="">Semua Stok</option>
                    <option value="available">Tersedia (>0)</option>
                    <option value="low">Stok Rendah (<50)</option>
                    <option value="out">Habis (0)</option>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-select" id="sortBy">
                    <option value="name_asc">Nama A-Z</option>
                    <option value="name_desc">Nama Z-A</option>
                    <option value="price_asc">Harga Terendah</option>
                    <option value="price_desc">Harga Tertinggi</option>
                    <option value="stock_asc">Stok Terendah</option>
                    <option value="stock_desc">Stok Tertinggi</option>
                </select>
            </div>
        </div>
    </div>
</div>

<!-- Product Grid -->
<div class="row" id="productGrid">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $index => $product): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4 product-card" 
                 data-name="<?= strtolower(esc($product['name'])) ?>" 
                 data-sku="<?= strtolower(esc($product['sku'] ?? '')) ?>"
                 data-category="<?= esc($product['category'] ?? '') ?>"
                 data-stock="<?= $product['stock'] ?>"
                 data-price="<?= $product['price'] ?>"
                 style="animation-delay: <?= $index * 0.05 ?>s;">
                <div class="card h-100 product-item">
                    <!-- Product Image -->
                    <div class="product-image">
                        <?php 
                        $imagePath = $product['image'] ?? 'default.jpg';
                        $imageUrl = base_url('uploads/products/' . $imagePath);
                        ?>
                        <img src="<?= $imageUrl ?>" class="card-img-top" alt="<?= esc($product['name']) ?>" 
                             onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                        
                        <!-- Stock Badge -->
                        <?php if ($product['stock'] <= 0): ?>
                            <span class="stock-badge badge-danger">Habis</span>
                        <?php elseif ($product['stock'] < 50): ?>
                            <span class="stock-badge badge-warning">Stok Rendah</span>
                        <?php else: ?>
                            <span class="stock-badge badge-success">Tersedia</span>
                        <?php endif; ?>
                        
                        <!-- Category Badge -->
                        <?php if (!empty($product['category'])): ?>
                            <span class="category-badge"><?= esc($product['category']) ?></span>
                        <?php endif; ?>
                        
                        <!-- Quick Actions Overlay -->
                        <div class="quick-actions">
                            <button class="btn btn-light btn-sm" 
                                onclick="quickView(<?= $product['id'] ?>, '<?= esc($product['name']) ?>', '<?= esc($product['description'] ?? '') ?>', <?= $product['price'] ?>, <?= $product['stock'] ?>)"
                                title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <small class="text-muted mb-1"><i class="bi bi-tag"></i> <?= esc($product['sku'] ?? '-') ?></small>
                        <h6 class="card-title mb-2 product-name"><?= esc($product['name']) ?></h6>
                        
                        <?php if (!empty($product['description'])): ?>
                            <p class="card-text text-muted small product-desc">
                                <?= esc(substr($product['description'], 0, 60)) . (strlen($product['description']) > 60 ? '...' : '') ?>
                            </p>
                        <?php endif; ?>
                        
                        <div class="mt-auto">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="product-price">Rp <?= number_format($product['price'], 0, ',', '.') ?></div>
                                <span class="badge <?= $product['stock'] < 10 ? 'bg-danger' : 'bg-secondary' ?>">
                                    <i class="bi bi-box"></i> <?= $product['stock'] ?>
                                </span>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-sm" 
                                    onclick="addToQuickCart(<?= $product['id'] ?>, '<?= esc($product['name']) ?>', <?= $product['price'] ?>, <?= $product['stock'] ?>)"
                                    <?= $product['stock'] <= 0 ? 'disabled' : '' ?>>
                                    <i class="bi bi-cart-plus"></i> Tambah ke Kasir
                                </button>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-warning btn-sm" 
                                        onclick="editProduct(<?= $product['id'] ?>, '<?= esc($product['sku']) ?>', '<?= esc($product['name']) ?>', '<?= esc($product['category'] ?? '') ?>', '<?= esc($product['image'] ?? '') ?>', '<?= esc($product['description'] ?? '') ?>', <?= $product['price'] ?>, <?= $product['stock'] ?>)">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <button class="btn btn-danger btn-sm" 
                                        onclick="deleteProduct(<?= $product['id'] ?>, '<?= esc($product['name']) ?>')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="col-12">
            <div class="card text-center py-5">
                <div class="card-body">
                    <i class="bi bi-inbox display-1 text-muted mb-3" style="opacity: 0.3;"></i>
                    <h4 class="text-muted">Belum ada produk</h4>
                    <p class="text-muted">Klik "Tambah Produk" untuk memulai menambahkan produk baru</p>
                    <button class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i class="bi bi-plus-circle"></i> Tambah Produk Pertama
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Empty State (Hidden by default) -->
<div class="card text-center py-5 d-none" id="noResults">
    <div class="card-body">
        <i class="bi bi-search display-1 text-muted mb-3" style="opacity: 0.3;"></i>
        <h4 class="text-muted">Tidak ada hasil</h4>
        <p class="text-muted">Tidak ada produk yang sesuai dengan pencarian Anda</p>
    </div>
</div>

<!-- Modal Tambah Produk -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('products/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel"><i class="bi bi-plus-circle"></i> Tambah Produk Baru</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control" name="sku" placeholder="ATK001">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori *</label>
                            <select class="form-select" name="category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Alat Tulis">Alat Tulis</option>
                                <option value="Buku & Kertas">Buku & Kertas</option>
                                <option value="Penyimpanan">Penyimpanan</option>
                                <option value="Alat Kantor">Alat Kantor</option>
                                <option value="Perekat">Perekat</option>
                                <option value="Alat Ukur">Alat Ukur</option>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Kertas Khusus">Kertas Khusus</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Produk *</label>
                        <input type="text" class="form-control" name="name" required placeholder="Contoh: Pulpen Joyko AX-105">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" rows="2" placeholder="Deskripsi singkat produk..."></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <small class="text-muted">Format: JPG, PNG, GIF (Max 2MB). Kosongkan jika tidak ada gambar.</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga *</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="price" required min="0" placeholder="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok *</label>
                            <input type="number" class="form-control" name="stock" required min="0" value="0" placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Produk -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true" data-bs-focus="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form action="<?= base_url('products/update') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel"><i class="bi bi-pencil"></i> Edit Produk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SKU</label>
                            <input type="text" class="form-control" name="sku" id="edit_sku">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori *</label>
                            <select class="form-select" name="category" id="edit_category" required>
                                <option value="">Pilih Kategori</option>
                                <option value="Alat Tulis">Alat Tulis</option>
                                <option value="Buku & Kertas">Buku & Kertas</option>
                                <option value="Penyimpanan">Penyimpanan</option>
                                <option value="Alat Kantor">Alat Kantor</option>
                                <option value="Perekat">Perekat</option>
                                <option value="Alat Ukur">Alat Ukur</option>
                                <option value="Elektronik">Elektronik</option>
                                <option value="Kertas Khusus">Kertas Khusus</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Produk *</label>
                        <input type="text" class="form-control" name="name" id="edit_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="description" id="edit_description" rows="2"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Gambar Produk</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <input type="hidden" name="old_image" id="edit_old_image">
                        <small class="text-muted">Kosongkan jika tidak ingin mengubah gambar.</small>
                        <div id="currentImagePreview" class="mt-2"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Harga *</label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" class="form-control" name="price" id="edit_price" required min="0">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stok *</label>
                            <input type="number" class="form-control" name="stock" id="edit_stock" required min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
/* Prevent page scroll when modal is open */
body.modal-open {
    overflow: hidden;
}

/* Ensure modal is centered and not scrolled to */
.modal.fade .modal-dialog {
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: none;
}

.modal-dialog-centered {
    display: flex;
    align-items: center;
    min-height: calc(100% - 1rem);
}

/* Compact modal width & appearance */
#addProductModal .modal-dialog,
#editProductModal .modal-dialog {
    max-width: 680px;
}

#addProductModal .modal-content,
#editProductModal .modal-content {
    border: none;
    border-radius: 12px;
    box-shadow: 0 20px 50px rgba(0,0,0,0.25);
    overflow: hidden;
}

#addProductModal .modal-header,
#editProductModal .modal-header {
    background: linear-gradient(135deg, #0d6efd 0%, #2563eb 100%);
    color: #fff;
    padding: 0.85rem 1.2rem;
    border-bottom: none;
}

#addProductModal .modal-title i,
#editProductModal .modal-title i {
    margin-right: 6px;
}

#addProductModal .modal-body,
#editProductModal .modal-body {
    padding: 1.1rem 1.25rem 0.75rem;
    background: #f8f9fc;
}

#addProductModal .modal-footer,
#editProductModal .modal-footer {
    background: #f1f5f9;
    padding: 0.75rem 1.25rem;
    border-top: 1px solid #e2e8f0;
}

#addProductModal .form-control,
#editProductModal .form-control,
#addProductModal .form-select,
#editProductModal .form-select {
    border-radius: 8px;
    border: 1px solid #d0d7e2;
    background-color: #ffffff;
}

#addProductModal .form-control:focus,
#editProductModal .form-control:focus,
#addProductModal .form-select:focus,
#editProductModal .form-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 0.15rem rgba(13,110,253,0.15);
}

#addProductModal label,
#editProductModal label {
    font-weight: 600;
    font-size: 0.85rem;
    color: #334155;
}

/* Reduce vertical gaps */
#addProductModal .mb-3,
#editProductModal .mb-3 { margin-bottom: 0.85rem !important; }

/* Buttons styling tweaks */
#addProductModal .btn-primary,
#editProductModal .btn-warning {
    font-weight: 600;
    padding: 0.55rem 1.1rem;
    border-radius: 8px;
}

#addProductModal .btn-secondary,
#editProductModal .btn-secondary {
    border-radius: 8px;
}

/* Ensure no internal scrolling */
#addProductModal .modal-body,
#editProductModal .modal-body { overflow: visible !important; }

@media (max-height: 680px) {
    /* Safety: allow slight internal scroll if viewport very short */
    #addProductModal .modal-body,
    #editProductModal .modal-body { max-height: calc(100vh - 220px); overflow-y: auto !important; }
}


/* Force popup without internal scrollbar */
.modal {
    overflow: visible !important;
}

.modal .modal-dialog,
.modal .modal-content,
.modal .modal-body {
    max-height: none !important;
}

.modal .modal-body {
    overflow: visible !important;
}



.product-card {
    transition: opacity 0.3s ease;
    animation: fadeInUp 0.6s ease forwards;
    opacity: 0;
}

.product-item {
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    border: none;
    overflow: hidden;
}

.product-item:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(102, 126, 234, 0.2) !important;
}

.product-image {
    position: relative;
    overflow: hidden;
    height: 220px;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.product-item:hover .product-image img {
    transform: scale(1.15);
}

.stock-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.75rem;
    z-index: 2;
    backdrop-filter: blur(10px);
}

.badge-danger {
    background: rgba(235, 51, 73, 0.9);
    color: white;
}

.badge-warning {
    background: rgba(255, 193, 7, 0.9);
    color: #333;
}

.badge-success {
    background: rgba(17, 153, 142, 0.9);
    color: white;
}

.category-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    font-size: 0.7rem;
    z-index: 2;
    backdrop-filter: blur(10px);
}

.quick-actions {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: all 0.3s ease;
}

.product-item:hover .quick-actions {
    opacity: 1;
}

.product-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 1rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-desc {
    font-size: 0.8rem;
    color: #718096;
    line-height: 1.4;
}

.product-price {
    font-size: 1.5rem;
    font-weight: 700;
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

/* Toast notification */
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 9999;
    min-width: 300px;
    animation: slideInRight 0.3s ease;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Quick View Modal */
#quickViewModal .modal-dialog {
    max-width: 600px;
}

#quickViewModal .quick-view-image {
    width: 100%;
    height: 300px;
    object-fit: cover;
    border-radius: 15px;
}
</style>

<script>
// Quick View Function
function quickView(id, name, description, price, stock) {
    const modalHtml = `
        <div class="modal fade" id="quickViewModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="bi bi-eye"></i> Detail Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <h4>${name}</h4>
                        <p class="text-muted">${description || 'Tidak ada deskripsi'}</p>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="product-price">Rp ${new Intl.NumberFormat('id-ID').format(price)}</div>
                            <span class="badge ${stock < 10 ? 'bg-danger' : 'bg-success'}">
                                Stok: ${stock}
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="button" class="btn btn-primary" onclick="addToQuickCart(${id}, '${name}', ${price}, ${stock}); bootstrap.Modal.getInstance(document.getElementById('quickViewModal')).hide();">
                            <i class="bi bi-cart-plus"></i> Tambah ke Kasir
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('quickViewModal');
    if (existingModal) existingModal.remove();
    
    // Add new modal
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    const modal = new bootstrap.Modal(document.getElementById('quickViewModal'));
    modal.show();
    
    // Clean up on hide
    document.getElementById('quickViewModal').addEventListener('hidden.bs.modal', function () {
        this.remove();
    });
}

// Edit Product Function (Updated with new fields)
function editProduct(id, sku, name, category, image, description, price, stock) {
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_sku').value = sku || '';
    document.getElementById('edit_name').value = name;
    document.getElementById('edit_category').value = category || '';
    document.getElementById('edit_description').value = description || '';
    document.getElementById('edit_price').value = price;
    document.getElementById('edit_stock').value = stock;
    document.getElementById('edit_old_image').value = image || '';
    
    // Show current image preview
    const previewDiv = document.getElementById('currentImagePreview');
    if (image) {
        const imageUrl = '<?= base_url('uploads/products/') ?>' + image;
        previewDiv.innerHTML = `
            <small class="text-muted">Gambar saat ini:</small><br>
            <img src="${imageUrl}" alt="Current" class="img-thumbnail mt-1" style="max-width: 150px;" 
                 onerror="this.src='https://via.placeholder.com/150?text=No+Image'">
        `;
    } else {
        previewDiv.innerHTML = '<small class="text-muted">Belum ada gambar</small>';
    }
    
    new bootstrap.Modal(document.getElementById('editProductModal')).show();
}

// Delete Product
function deleteProduct(id, name) {
    if (confirm('Hapus produk "' + name + '"?\n\nProduk yang sudah dihapus tidak dapat dikembalikan.')) {
        window.location.href = '<?= base_url('products/delete/') ?>' + id;
    }
}

// Add to Quick Cart (Save to localStorage for Kasir page)
function addToQuickCart(id, name, price, stock) {
    // Get existing cart from localStorage
    let cart = JSON.parse(localStorage.getItem('kasirCart') || '[]');
    
    // Check if product already exists in cart
    const existingIndex = cart.findIndex(item => item.id === id);
    
    if (existingIndex !== -1) {
        // Increment qty if product exists
        if (cart[existingIndex].qty < stock) {
            cart[existingIndex].qty++;
            showToast(`${name} ditambahkan (${cart[existingIndex].qty})`, 'success');
        } else {
            showToast(`Stok ${name} tidak mencukupi!`, 'warning');
            return;
        }
    } else {
        // Add new product to cart
        cart.push({ id, name, price, qty: 1, maxStock: stock });
        showToast(`${name} ditambahkan ke keranjang kasir`, 'success');
    }
    
    // Save to localStorage
    localStorage.setItem('kasirCart', JSON.stringify(cart));
    
    // Update cart badge count
    updateCartBadge(cart);
}

// Update cart badge in navigation
function updateCartBadge(cart) {
    const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
    const badge = document.getElementById('cartBadge');
    if (badge) {
        badge.textContent = totalItems;
        badge.classList.remove('d-none');
    }
}

// Show toast notification
function showToast(message, type = 'info') {
    const bgColors = {
        'success': 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
        'warning': 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
        'info': 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)'
    };
    
    const icons = {
        'success': 'check-circle-fill',
        'warning': 'exclamation-triangle-fill',
        'info': 'info-circle-fill'
    };
    
    const toastHtml = `
        <div class="toast-notification">
            <div class="card shadow-lg border-0">
                <div class="card-body" style="background: ${bgColors[type]}; color: white; border-radius: 15px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-${icons[type]} fs-3 me-3"></i>
                        <div class="flex-grow-1">${message}</div>
                        <button type="button" class="btn-close btn-close-white ms-3" onclick="this.closest('.toast-notification').remove()"></button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const temp = document.createElement('div');
    temp.innerHTML = toastHtml;
    const toastElement = temp.firstElementChild;
    document.body.appendChild(toastElement);
    
    setTimeout(() => toastElement.remove(), 3500);
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    filterProducts();
});

// Category filter
document.getElementById('filterCategory').addEventListener('change', function(e) {
    filterProducts();
});

// Stock filter
document.getElementById('filterStock').addEventListener('change', function(e) {
    filterProducts();
});

// Sort functionality
document.getElementById('sortBy').addEventListener('change', function(e) {
    sortProducts(e.target.value);
});

// Filter products based on search, category, and stock
function filterProducts() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const categoryFilter = document.getElementById('filterCategory').value;
    const stockFilter = document.getElementById('filterStock').value;
    
    const products = Array.from(document.querySelectorAll('.product-card'));
    let visibleCount = 0;
    
    products.forEach(card => {
        const name = card.getAttribute('data-name');
        const sku = card.getAttribute('data-sku');
        const category = card.getAttribute('data-category');
        const stock = parseInt(card.getAttribute('data-stock'));
        
        // Check search term (name or SKU)
        const matchesSearch = name.includes(searchTerm) || sku.includes(searchTerm);
        
        // Check category filter
        const matchesCategory = !categoryFilter || category === categoryFilter;
        
        // Check stock filter
        let matchesStock = true;
        if (stockFilter === 'available') {
            matchesStock = stock > 0;
        } else if (stockFilter === 'low') {
            matchesStock = stock < 50 && stock > 0;
        } else if (stockFilter === 'out') {
            matchesStock = stock === 0;
        }
        
        // Show/hide card
        if (matchesSearch && matchesCategory && matchesStock) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Show/hide "no results" message
    const noResults = document.getElementById('noResults');
    if (visibleCount === 0 && products.length > 0) {
        noResults.classList.remove('d-none');
    } else {
        noResults.classList.add('d-none');
    }
}

// Sort products
function sortProducts(sortBy) {
    const grid = document.getElementById('productGrid');
    const products = Array.from(grid.querySelectorAll('.product-card'));
    
    products.sort((a, b) => {
        const aName = a.getAttribute('data-name');
        const bName = b.getAttribute('data-name');
        const aPrice = parseFloat(a.getAttribute('data-price'));
        const bPrice = parseFloat(b.getAttribute('data-price'));
        const aStock = parseInt(a.getAttribute('data-stock'));
        const bStock = parseInt(b.getAttribute('data-stock'));
        
        switch(sortBy) {
            case 'name_asc':
                return aName.localeCompare(bName);
            case 'name_desc':
                return bName.localeCompare(aName);
            case 'price_asc':
                return aPrice - bPrice;
            case 'price_desc':
                return bPrice - aPrice;
            case 'stock_asc':
                return aStock - bStock;
            case 'stock_desc':
                return bStock - aStock;
            default:
                return 0;
        }
    });
    
    // Re-append sorted elements
    products.forEach(card => grid.appendChild(card));
}

// Initialize cart badge on page load
document.addEventListener('DOMContentLoaded', function() {
    const cart = JSON.parse(localStorage.getItem('kasirCart') || '[]');
    updateCartBadge(cart);

    // Move modals to <body> to prevent any page jump/scroll when opening
    function portalModal(id) {
        const modal = document.getElementById(id);
        if (modal && modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
    }
    portalModal('addProductModal');
    portalModal('editProductModal');
});
</script>
<?= $this->endSection() ?>
