<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Aplikasi Kasir' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow-x: hidden;
        }
        
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            position: relative;
            z-index: 100;
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 200px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            opacity: 0.1;
        }
        
        .sidebar .brand {
            padding: 2rem 1rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        
        .sidebar .brand h4 {
            color: #fff;
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
            text-shadow: 0 2px 10px rgba(102, 126, 234, 0.5);
        }
        
        .sidebar .brand i {
            font-size: 2.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            display: block;
            margin-bottom: 0.5rem;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .sidebar .nav-link { 
            color: #adb5bd; 
            padding: 1rem 1.5rem;
            margin: 0.3rem 1rem;
            border-radius: 12px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            z-index: -1;
        }
        
        .sidebar .nav-link:hover::before,
        .sidebar .nav-link.active::before {
            left: 0;
        }
        
        .sidebar .nav-link:hover, 
        .sidebar .nav-link.active { 
            color: #fff; 
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }
        
        .sidebar .nav-link .badge {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .main-content { 
            background: #f8f9fa;
            min-height: 100vh;
            padding: 2rem;
            border-radius: 30px 0 0 30px;
            box-shadow: -10px 0 30px rgba(0,0,0,0.1);
            position: relative;
        }
        
        .main-content::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 500px;
            height: 500px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            opacity: 0.05;
            z-index: 0;
        }
        
        .main-content > * {
            position: relative;
            z-index: 1;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.5rem;
            animation: fadeInDown 0.5s ease;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            animation: fadeInUp 0.6s ease;
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
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(17, 153, 142, 0.4);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(235, 51, 73, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 12px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(240, 147, 251, 0.4);
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 12px;
            border: 2px solid #e9ecef;
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .table {
            border-radius: 15px;
            overflow: hidden;
        }
        
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.01);
        }
        
        .badge {
            padding: 0.5rem 1rem;
            border-radius: 10px;
            font-weight: 600;
        }
        
        .modal-content {
            border-radius: 20px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: modalSlideIn 0.3s ease;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 20px 20px 0 0;
            border: none;
            padding: 1.5rem;
        }
        
        .modal-header .modal-title {
            font-weight: 600;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 1;
        }
        
        .modal-body {
            padding: 2rem;
        }
        
        .modal-footer {
            border: none;
            padding: 1rem 2rem 1.5rem;
        }
        
        .modal-backdrop {
            background-color: rgba(26, 26, 46, 0.8);
        }
        
        /* Toast Notification */
        .toast-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 99999 !important;
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
        
        /* Alert Styles */
        .alert {
            border-radius: 15px;
            border: none;
            padding: 1rem 1.5rem;
            animation: fadeInDown 0.5s ease;
        }
        
        .alert-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        
        .alert-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }
        
        .alert-info {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .alert .btn-close {
            filter: brightness(0) invert(1);
        }
        
        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 col-lg-2 px-0 sidebar">
                <div class="brand">
                    <i class="bi bi-shop"></i>
                    <h4>Kasir ATK</h4>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link <?= (uri_string() == '' || uri_string() == 'products') ? 'active' : '' ?>" href="<?= base_url('products') ?>">
                        <i class="bi bi-box-seam"></i> Katalog Produk
                    </a>
                    <a class="nav-link <?= (uri_string() == 'kasir') ? 'active' : '' ?>" href="<?= base_url('kasir') ?>">
                        <i class="bi bi-cart3"></i> Kasir (POS)
                        <span class="badge bg-danger d-none" id="cartBadge">0</span>
                    </a>
                    <a class="nav-link <?= (uri_string() == 'laporan') ? 'active' : '' ?>" href="<?= base_url('laporan') ?>">
                        <i class="bi bi-graph-up"></i> Laporan
                    </a>
                    <a class="nav-link <?= (uri_string() == 'about') ? 'active' : '' ?>" href="<?= base_url('about') ?>">
                        <i class="bi bi-info-circle"></i> Tentang
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 col-lg-10 main-content">
                <!-- User Info & Logout (Top Right) -->
                <div style="position: fixed; top: 20px; right: 30px; z-index: 1000;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center bg-white shadow-sm px-3 py-2" style="border-radius: 50px;">
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-person-circle text-white" style="font-size: 1.3rem;"></i>
                            </div>
                            <div class="ms-2 me-2">
                                <div class="fw-semibold" style="font-size: 0.85rem; color: #1a1a2e;"><?= session()->get('name') ?? 'User' ?></div>
                                <div style="font-size: 0.7rem;">
                                    <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.65rem;">
                                        <?= ucfirst(session()->get('role') ?? 'kasir') ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <a href="<?= base_url('logout') ?>" class="btn shadow-sm" style="background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%); color: white; border-radius: 50px; padding: 0.6rem 1.5rem; font-weight: 600; font-size: 0.9rem; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 20px rgba(235, 51, 73, 0.4)';" onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 8px rgba(0,0,0,0.1)';">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </div>
                </div>
                
                <!-- Content with top padding -->
                <div style="padding-top: 80px;">
                    <?= $this->renderSection('content') ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
