<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title"><i class="bi bi-graph-up"></i> Laporan Penjualan</h1>
    <a href="<?= base_url('laporan/exportPdf?month=' . $selectedMonth . '&year=' . $selectedYear) ?>" 
       class="btn btn-danger" target="_blank">
        <i class="bi bi-file-pdf"></i> Download PDF
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card stat-card-primary">
            <div class="stat-icon">
                <i class="bi bi-receipt"></i>
            </div>
            <div class="stat-content">
                <h6>Total Transaksi</h6>
                <h2 class="stat-value"><?= $totalTransactions ?? 0 ?></h2>
                <span class="stat-label">transaksi</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card stat-card-success">
            <div class="stat-icon">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div class="stat-content">
                <h6>Total Penjualan</h6>
                <h2 class="stat-value">Rp <?= number_format($totalSales ?? 0, 0, ',', '.') ?></h2>
                <span class="stat-label">rupiah</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card stat-card-warning">
            <div class="stat-icon">
                <i class="bi bi-box-seam"></i>
            </div>
            <div class="stat-content">
                <h6>Total Item Terjual</h6>
                <h2 class="stat-value"><?= $totalItems ?? 0 ?></h2>
                <span class="stat-label">item</span>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="stat-card stat-card-info">
            <div class="stat-icon">
                <i class="bi bi-trophy"></i>
            </div>
            <div class="stat-content">
                <h6>Produk Terlaris</h6>
                <h2 class="stat-value" style="font-size: 1.2rem;"><?= substr($topProduct['name'] ?? '-', 0, 15) ?></h2>
                <span class="stat-label"><?= ($topProduct['total_qty'] ?? 0) ?> terjual</span>
            </div>
        </div>
    </div>
</div>

<!-- Filter Card -->
<div class="card mb-4">
    <div class="card-body">
        <form method="get" action="<?= base_url('laporan') ?>" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-calendar"></i> Bulan
                </label>
                <select name="month" class="form-select">
                    <?php 
                    $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                               'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    for ($m = 1; $m <= 12; $m++): 
                    ?>
                        <option value="<?= $m ?>" <?= ($selectedMonth == $m) ? 'selected' : '' ?>>
                            <?= $months[$m-1] ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">
                    <i class="bi bi-calendar-event"></i> Tahun
                </label>
                <select name="year" class="form-select">
                    <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                        <option value="<?= $y ?>" <?= ($selectedYear == $y) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel"></i> Terapkan Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Transaction History -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="bi bi-clock-history"></i> Riwayat Transaksi</h5>
        <!-- Sort Filter -->
        <div class="d-flex align-items-center">
            <label class="text-white me-2 mb-0">
                <i class="bi bi-sort-down"></i> Urutkan:
            </label>
            <select id="sortFilter" class="form-select form-select-sm" style="width: auto; min-width: 150px;" onchange="changeSortOrder(this.value)">
                <option value="newest" <?= ($selectedSort ?? 'newest') === 'newest' ? 'selected' : '' ?>>
                    <i class="bi bi-arrow-down"></i> Terbaru
                </option>
                <option value="oldest" <?= ($selectedSort ?? 'newest') === 'oldest' ? 'selected' : '' ?>>
                    <i class="bi bi-arrow-up"></i> Terlama
                </option>
            </select>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($transactions)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="5%">No</th>
                            <th width="25%">Invoice</th>
                            <th width="15%">Tanggal</th>
                            <th width="15%">Waktu</th>
                            <th width="15%" class="text-center">Total Qty</th>
                            <th width="25%" class="text-end">Total Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        date_default_timezone_set('Asia/Jakarta');
                        $no = ($pager['currentPage'] - 1) * $pager['perPage'] + 1; 
                        foreach ($transactions as $trx): 
                            // Convert UTC to Asia/Jakarta timezone
                            $datetime = new DateTime($trx['created_at'], new DateTimeZone('UTC'));
                            $datetime->setTimezone(new DateTimeZone('Asia/Jakarta'));
                        ?>
                            <tr style="animation: fadeInUp 0.6s ease <?= ($no - (($pager['currentPage'] - 1) * $pager['perPage'])) * 0.05 ?>s backwards;">
                                <td class="text-center"><?= $no++ ?></td>
                                <td><span class="badge bg-primary"><?= esc($trx['invoice_no']) ?></span></td>
                                <td><?= $datetime->format('d/m/Y') ?></td>
                                <td><span class="badge bg-info"><?= $datetime->format('H:i:s') ?></span></td>
                                <td class="text-center"><strong><?= $trx['total_qty'] ?> item</strong></td>
                                <td class="text-end">
                                    <strong class="text-success">Rp <?= number_format($trx['total_price'], 0, ',', '.') ?></strong>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr class="table-light fw-bold">
                            <td colspan="4" class="text-end">TOTAL KESELURUHAN:</td>
                            <td class="text-center"><?= $totalItems ?? 0 ?> item</td>
                            <td class="text-end text-success">
                                Rp <?= number_format($totalSales ?? 0, 0, ',', '.') ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($pager['totalPages'] > 1): ?>
                <?php 
                // Build base URL with all parameters
                $sortParam = isset($selectedSort) ? '&sort=' . $selectedSort : '';
                ?>
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Menampilkan <?= min($pager['perPage'], $pager['total']) ?> dari <?= $pager['total'] ?> transaksi
                    </div>
                    <nav>
                        <ul class="pagination mb-0">
                            <!-- Previous Button -->
                            <li class="page-item <?= $pager['currentPage'] <= 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= base_url('laporan?month=' . $selectedMonth . '&year=' . $selectedYear . $sortParam . '&page=' . ($pager['currentPage'] - 1)) ?>">
                                    <i class="bi bi-chevron-left"></i> Previous
                                </a>
                            </li>
                            
                            <!-- Page Numbers -->
                            <?php 
                            $startPage = max(1, $pager['currentPage'] - 2);
                            $endPage = min($pager['totalPages'], $pager['currentPage'] + 2);
                            
                            // Show first page
                            if ($startPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('laporan?month=' . $selectedMonth . '&year=' . $selectedYear . $sortParam . '&page=1') ?>">1</a>
                                </li>
                                <?php if ($startPage > 2): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <!-- Middle pages -->
                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <li class="page-item <?= $pager['currentPage'] == $i ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= base_url('laporan?month=' . $selectedMonth . '&year=' . $selectedYear . $sortParam . '&page=' . $i) ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <!-- Show last page -->
                            <?php if ($endPage < $pager['totalPages']): ?>
                                <?php if ($endPage < $pager['totalPages'] - 1): ?>
                                    <li class="page-item disabled"><span class="page-link">...</span></li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= base_url('laporan?month=' . $selectedMonth . '&year=' . $selectedYear . $sortParam . '&page=' . $pager['totalPages']) ?>">
                                        <?= $pager['totalPages'] ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <!-- Next Button -->
                            <li class="page-item <?= $pager['currentPage'] >= $pager['totalPages'] ? 'disabled' : '' ?>">
                                <a class="page-link" href="<?= base_url('laporan?month=' . $selectedMonth . '&year=' . $selectedYear . $sortParam . '&page=' . ($pager['currentPage'] + 1)) ?>">
                                    Next <i class="bi bi-chevron-right"></i>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox display-1 text-muted" style="opacity: 0.3;"></i>
                <h4 class="text-muted mt-3">Tidak ada transaksi</h4>
                <p class="text-muted">Belum ada transaksi untuk periode yang dipilih</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
.stat-card {
    border-radius: 20px;
    padding: 1.5rem;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    animation: fadeInUp 0.6s ease;
    color: white;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.15);
}

.stat-card-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.stat-card-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.stat-card-warning {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.stat-card-info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

.stat-icon {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 4rem;
    opacity: 0.2;
}

.stat-content {
    position: relative;
    z-index: 1;
}

.stat-content h6 {
    font-size: 0.85rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
    font-weight: 500;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    margin: 0.5rem 0;
}

.stat-label {
    font-size: 0.8rem;
    opacity: 0.8;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 1.25rem;
    font-weight: 600;
}

.table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.table thead th {
    border: none;
    padding: 1rem;
    font-weight: 600;
}

.table tbody tr {
    border-bottom: 1px solid #e9ecef;
    transition: all 0.3s ease;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
    transform: scale(1.01);
}

.table tfoot {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.table tfoot td {
    padding: 1rem;
    font-size: 1.1rem;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Pagination Styling */
.pagination {
    gap: 5px;
}

.pagination .page-link {
    border-radius: 8px;
    border: 1px solid #dee2e6;
    color: #667eea;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.pagination .page-link:hover {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-color: #667eea;
    transform: translateY(-2px);
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
    color: white;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}

.pagination .page-item.disabled .page-link {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
    cursor: not-allowed;
}

.pagination .page-link i {
    font-size: 0.85rem;
}
</style>

<script>
// Add loading animation
document.addEventListener('DOMContentLoaded', function() {
    // Animate stat cards on load
    document.querySelectorAll('.stat-card').forEach((card, index) => {
        card.style.animationDelay = (index * 0.1) + 's';
    });
});

// Function to change sort order
function changeSortOrder(sortValue) {
    const urlParams = new URLSearchParams(window.location.search);
    urlParams.set('sort', sortValue);
    urlParams.delete('page'); // Reset to page 1 when changing sort
    window.location.href = '<?= base_url('laporan') ?>?' + urlParams.toString();
}
</script>
<?= $this->endSection() ?>
