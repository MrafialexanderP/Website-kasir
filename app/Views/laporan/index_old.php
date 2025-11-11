<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<h2 class="mb-4"><i class="bi bi-graph-up"></i> Laporan Penjualan</h2>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h6 class="card-title">Total Transaksi</h6>
                <h3><?= $totalTransactions ?? 0 ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h6 class="card-title">Total Penjualan</h6>
                <h3>Rp <?= number_format($totalSales ?? 0, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h6 class="card-title">Total Item Terjual</h6>
                <h3><?= $totalItems ?? 0 ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h6 class="card-title">Produk Terlaris</h6>
                <h6><?= $topProduct['name'] ?? '-' ?></h6>
                <small><?= ($topProduct['total_qty'] ?? 0) ?> terjual</small>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Filter Laporan</h5>
            <div>
                <a href="<?= base_url('laporan/exportPdf?month=' . $selectedMonth . '&year=' . $selectedYear) ?>" 
                   class="btn btn-danger btn-sm" target="_blank">
                    <i class="bi bi-file-pdf"></i> Download PDF
                </a>
                <a href="<?= base_url('laporan/exportExcel?month=' . $selectedMonth . '&year=' . $selectedYear) ?>" 
                   class="btn btn-success btn-sm">
                    <i class="bi bi-file-excel"></i> Download Excel
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <form method="get" action="<?= base_url('laporan') ?>">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Bulan</label>
                    <select name="month" class="form-select">
                        <?php for ($m = 1; $m <= 12; $m++): ?>
                            <option value="<?= $m ?>" <?= ($selectedMonth == $m) ? 'selected' : '' ?>>
                                <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                            </option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tahun</label>
                    <select name="year" class="form-select">
                        <?php for ($y = date('Y'); $y >= 2020; $y--): ?>
                            <option value="<?= $y ?>" <?= ($selectedYear == $y) ? 'selected' : '' ?>><?= $y ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-search"></i> Tampilkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-secondary text-white">
        <h5 class="mb-0">Riwayat Transaksi</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-dark">
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Invoice</th>
                        <th width="20%">Tanggal</th>
                        <th width="15%">Waktu</th>
                        <th width="15%">Total Qty</th>
                        <th width="20%">Total Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($transactions)): ?>
                        <?php 
                        // Set timezone ke Asia/Jakarta (WIB) atau sesuaikan dengan zona waktu lokal Anda
                        date_default_timezone_set('Asia/Jakarta');
                        $no = 1; 
                        foreach ($transactions as $trans): 
                            // Konversi ke timezone lokal
                            $datetime = new DateTime($trans['created_at'], new DateTimeZone('UTC'));
                            $datetime->setTimezone(new DateTimeZone('Asia/Jakarta')); // Zona waktu lokal
                        ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><strong><?= esc($trans['invoice_no']) ?></strong></td>
                                <td><?= $datetime->format('d/m/Y') ?></td>
                                <td><span class="badge bg-primary"><?= $datetime->format('H:i:s') ?></span></td>
                                <td><?= $trans['total_qty'] ?> item</td>
                                <td><strong class="text-success">Rp <?= number_format($trans['total_price'], 0, ',', '.') ?></strong></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">Tidak ada transaksi pada periode ini.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-info text-white">
        <h5 class="mb-0">Produk Terlaris (Top 10)</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Produk</th>
                        <th>Terjual</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($topProducts)): ?>
                        <?php $no = 1; foreach ($topProducts as $prod): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= esc($prod['name']) ?></td>
                                <td><?= $prod['total_qty'] ?> item</td>
                                <td>Rp <?= number_format($prod['total_revenue'], 0, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">Belum ada data produk terjual.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
