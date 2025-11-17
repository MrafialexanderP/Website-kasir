<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
    <title><?= $title ?? 'Aplikasi Kasir' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        
        html {
            font-size: 14px;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow-x: hidden;
        }
        
        /* Hamburger Menu Button */
        .hamburger-btn {
            position: fixed;
            top: 0.75rem;
            left: 0.75rem;
            z-index: 1050;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            width: 45px;
            height: 45px;
            display: none;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
        }
        
        .hamburger-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.6);
        }
        
        .hamburger-btn i {
            color: white;
            font-size: 1.4rem;
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }
        
        .sidebar { 
            min-height: 100vh; 
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            position: relative;
            z-index: 100;
            transition: transform 0.3s ease;
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
            padding: 1.5rem 1rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }
        
        .sidebar .brand h4 {
            color: #fff;
            font-weight: 700;
            font-size: 1.3rem;
            margin: 0;
            text-shadow: 0 2px 10px rgba(102, 126, 234, 0.5);
        }
        
        .sidebar .brand i {
            font-size: 2rem;
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
            padding: 0.85rem 1.25rem;
            margin: 0.25rem 0.85rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            font-size: 0.95rem;
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
            margin-right: 0.65rem;
            font-size: 1.1rem;
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
            padding: 1.5rem;
            border-radius: 0; /* remove rounded purple corners */
            box-shadow: none; /* remove inner shadow accent */
            position: relative;
        }
        /* Remove decorative gradient bubble */
        .main-content::before { display: none; }
        
        .main-content > * {
            position: relative;
            z-index: 1;
        }
        
        .page-title {
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 1.25rem;
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
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
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
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0,0,0,0.12);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 10px;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
            font-size: 0.95rem;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
        }
        
        .btn-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            border: none;
            border-radius: 10px;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(17, 153, 142, 0.4);
        }
        
        .btn-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            border: none;
            border-radius: 10px;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(235, 51, 73, 0.4);
        }
        
        .btn-warning {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            border: none;
            border-radius: 10px;
            padding: 0.65rem 1.25rem;
            font-weight: 600;
            transition: all 0.3s ease;
            color: white;
            font-size: 0.95rem;
        }
        
        .btn-warning:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(240, 147, 251, 0.4);
            color: white;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 0.65rem 0.85rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.15rem rgba(102, 126, 234, 0.25);
        }
        
        .table {
            border-radius: 12px;
            overflow: hidden;
            font-size: 0.95rem;
        }
        
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table thead th {
            padding: 0.85rem;
            font-weight: 600;
        }
        
        .table tbody td {
            padding: 0.75rem;
        }
        
        .table tbody tr {
            transition: all 0.3s ease;
        }
        
        .table tbody tr:hover {
            background-color: #f8f9fa;
            transform: scale(1.005);
        }
        
        .badge {
            padding: 0.4rem 0.85rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.85rem;
        }
        
        .modal-content {
            border-radius: 15px;
            border: none;
            box-shadow: 0 15px 50px rgba(0,0,0,0.25);
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
            border-radius: 15px 15px 0 0;
            border: none;
            padding: 1.25rem;
        }
        
        .modal-header .modal-title {
            font-weight: 600;
            font-size: 1.1rem;
        }
        
        .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 1;
        }
        
        .modal-body {
            padding: 1.5rem;
        }
        
        .modal-footer {
            border: none;
            padding: 0.85rem 1.5rem 1.25rem;
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
            border-radius: 12px;
            border: none;
            padding: 0.85rem 1.25rem;
            animation: fadeInDown 0.5s ease;
            font-size: 0.95rem;
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
        
        /* User Section Styling */
        .user-section {
            padding: 0.65rem 0.75rem;
            margin-top: auto;
        }
        
        .user-wrapper {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .user-info-box {
            border-radius: 10px;
            background: rgba(255,255,255,0.1);
            padding: 0.6rem 0.75rem;
            display: flex;
            align-items: center;
            width: 100%;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        
        .user-avatar i {
            font-size: 1.1rem;
            color: white;
        }
        
        .user-name {
            font-weight: 600;
            color: white;
            font-size: 0.8rem;
            line-height: 1.2;
            margin-left: 0.6rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .logout-btn {
            border-radius: 10px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            font-weight: 600;
            border: none;
            padding: 0.6rem 0.75rem;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
            color: white;
        }
        
        .logout-btn i {
            font-size: 0.95rem;
            margin-right: 0.4rem;
        }
        
        /* Responsive Design */
        @media (max-width: 767.98px) {
            html {
                font-size: 13px;
            }
            
            .hamburger-btn {
                display: flex;
            }
            
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                width: 260px;
                z-index: 1000;
                transform: translateX(-100%);
                overflow-y: auto;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 4rem 0.85rem 1.5rem 0.85rem;
            }
            
            .sidebar .brand {
                padding: 1.25rem 0.85rem;
            }
            
            .sidebar .brand h4 {
                font-size: 1.2rem;
            }
            
            .sidebar .brand i {
                font-size: 1.75rem;
            }
            
            .sidebar .nav-link {
                padding: 0.75rem 1rem;
                margin: 0.2rem 0.65rem;
                font-size: 0.9rem;
            }
            
            .sidebar .nav-link i {
                font-size: 1rem;
            }
            
            .page-title {
                font-size: 1.4rem;
            }
            
            .card {
                border-radius: 12px;
            }
            
            /* User section responsive */
            .user-section {
                padding: 0.6rem 0.65rem;
            }
            
            .user-info-box {
                padding: 0.55rem 0.65rem;
            }
            
            .user-avatar {
                width: 30px;
                height: 30px;
            }
            
            .user-avatar i {
                font-size: 1rem;
            }
            
            .user-name {
                font-size: 0.75rem;
            }
            
            .logout-btn {
                padding: 0.55rem 0.65rem;
                font-size: 0.75rem;
            }
            
            .logout-btn i {
                font-size: 0.85rem;
            }
        }
        
        @media (min-width: 768px) and (max-width: 991.98px) {
            html {
                font-size: 13.5px;
            }
            
            .sidebar .brand h4 {
                font-size: 1.15rem;
            }
            
            .sidebar .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.9rem;
            }
            
            .main-content {
                padding: 1.25rem;
            }
            
            /* User section for tablet */
            .user-section {
                padding: 0.6rem 0.7rem;
            }
            
            .user-info-box {
                padding: 0.58rem 0.7rem;
            }
            
            .user-avatar {
                width: 31px;
                height: 31px;
            }
            
            .user-name {
                font-size: 0.78rem;
            }
            
            .logout-btn {
                padding: 0.58rem 0.7rem;
                font-size: 0.78rem;
            }
        }
        
        @media (min-width: 992px) {
            .sidebar {
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
            }
        }
        
        @media (min-width: 1200px) {
            html {
                font-size: 15px;
            }
        }
        
        @media (min-width: 1400px) {
            html {
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <!-- Hamburger Menu Button -->
    <button class="hamburger-btn" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" onclick="toggleSidebar()"></div>
    
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 px-0 sidebar" id="sidebar">
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

                <!-- Sidebar User + Logout -->
                <div class="user-section">
                    <div class="user-wrapper">
                        <div class="user-info-box">
                            <div class="user-avatar">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <div class="user-name">
                                <?= esc(session()->get('name') ?? 'User') ?>
                            </div>
                        </div>
                        <a href="<?= base_url('logout') ?>" class="logout-btn">
                            <i class="bi bi-box-arrow-right"></i>Logout
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toggle sidebar for mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
            
            // Prevent body scroll when sidebar is open on mobile
            if (sidebar.classList.contains('show')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }
        
        // Close sidebar when clicking on a nav link (mobile only)
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('.sidebar .nav-link');
            const isMobile = window.innerWidth < 768;
            
            if (isMobile) {
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        setTimeout(toggleSidebar, 200);
                    });
                });
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            if (window.innerWidth >= 768) {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
