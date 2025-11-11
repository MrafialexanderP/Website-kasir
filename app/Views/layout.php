<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Aplikasi Kasir' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .sidebar { min-height: 100vh; background: #212529; }
        .sidebar .nav-link { color: #adb5bd; padding: 0.75rem 1rem; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: #fff; background: #495057; }
        .main-content { background: #f8f9fa; min-height: 100vh; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 px-0 sidebar">
                <div class="text-white text-center py-4">
                    <h4><i class="bi bi-shop"></i> Kasir App</h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link <?= (uri_string() == '' || uri_string() == 'products') ? 'active' : '' ?>" href="<?= base_url('products') ?>">
                        <i class="bi bi-box-seam"></i> Katalog Produk
                    </a>
                    <a class="nav-link <?= (uri_string() == 'kasir') ? 'active' : '' ?>" href="<?= base_url('kasir') ?>">
                        <i class="bi bi-cart3"></i> Kasir (POS)
                    </a>
                    <a class="nav-link <?= (uri_string() == 'laporan') ? 'active' : '' ?>" href="<?= base_url('laporan') ?>">
                        <i class="bi bi-graph-up"></i> Laporan
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 px-4 py-4 main-content">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
