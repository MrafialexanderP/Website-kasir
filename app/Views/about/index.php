<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="about-page">
    <!-- Header Section -->
    <div class="text-center mb-5" style="animation: fadeInDown 0.8s ease;">
        <h1 class="page-title mb-3">
            <i class="bi bi-people-fill"></i> Tentang Kami
        </h1>
        <p class="lead text-muted">Sistem Informasi Kasir ATK</p>
    </div>

    <!-- Info Kelompok Card -->
    <div class="card shadow-lg border-0 mb-5" style="border-radius: 20px; overflow: hidden; animation: fadeInUp 0.8s ease 0.2s backwards;">
        <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <h3 class="mb-2"><i class="bi bi-award-fill"></i> <?= $kelompok['nama_kelompok'] ?></h3>
            <p class="mb-0 opacity-90">
                <i class="bi bi-book"></i> Program Studi <?= $kelompok['prodi'] ?> Angkatan <?= $kelompok['angkatan'] ?><br>
                <i class="bi bi-building"></i> <?= $kelompok['institusi'] ?>
            </p>
        </div>
    </div>

    <!-- Anggota Section -->
    <div class="mb-4">
        <h3 class="text-center mb-4" style="color: #667eea; font-weight: 700;">
            <i class="bi bi-person-lines-fill"></i> Anggota Kelompok
        </h3>
    </div>

    <div class="row">
        <?php foreach ($anggota as $index => $member): ?>
            <div class="col-md-6 col-lg-4 mb-4" style="animation: fadeInUp 0.8s ease <?= ($index * 0.1) + 0.3 ?>s backwards;">
                <div class="member-card card h-100 border-0 shadow-sm" style="border-radius: 20px; overflow: hidden; transition: all 0.3s ease;">
                    <div class="card-body text-center p-4">
                        <!-- Avatar -->
                        <div class="member-avatar-wrapper mb-3">
                            <img src="<?= $member['foto'] ?>" alt="<?= $member['nama'] ?>" class="member-avatar" style="width: 120px; height: 120px; border-radius: 50%; border: 5px solid #fff; box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3); object-fit: cover;">
                        </div>
                        
                        <!-- Info -->
                        <h5 class="card-title mb-2" style="color: #1a1a2e; font-weight: 600;"><?= $member['nama'] ?></h5>
                        <p class="text-muted mb-2" style="font-size: 0.9rem;">
                            <i class="bi bi-credit-card"></i> <?= $member['nim'] ?>
                        </p>
                        <span class="badge px-3 py-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); font-size: 0.85rem;">
                            <i class="bi bi-star-fill"></i> <?= $member['role'] ?>
                        </span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Project Info Card -->
    <div class="card shadow-lg border-0 mt-5" style="border-radius: 20px; overflow: hidden; animation: fadeInUp 0.8s ease 0.5s backwards;">
        <div class="card-body p-4">
            <h4 class="mb-3" style="color: #667eea; font-weight: 700;">
                <i class="bi bi-info-circle-fill"></i> Tentang Proyek
            </h4>
            <p class="text-muted mb-3" style="line-height: 1.8;">
                Aplikasi Kasir ATK (Alat Tulis Kantor) adalah sistem informasi berbasis web yang dikembangkan untuk memudahkan 
                proses transaksi penjualan alat tulis kantor. Aplikasi ini dilengkapi dengan fitur katalog produk, point of sale (POS), 
                dan laporan penjualan yang lengkap.
            </p>
            
            <div class="row mt-4">
                <div class="col-md-4 mb-3">
                    <div class="feature-box p-3 text-center" style="background: linear-gradient(135deg, #667eea15 0%, #764ba215 100%); border-radius: 15px;">
                        <i class="bi bi-box-seam" style="font-size: 2rem; color: #667eea;"></i>
                        <h6 class="mt-2 mb-0" style="color: #1a1a2e;">Katalog Produk</h6>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="feature-box p-3 text-center" style="background: linear-gradient(135deg, #11998e15 0%, #38ef7d15 100%); border-radius: 15px;">
                        <i class="bi bi-cart3" style="font-size: 2rem; color: #11998e;"></i>
                        <h6 class="mt-2 mb-0" style="color: #1a1a2e;">Point of Sale</h6>
                    </div>
                </div>
                <div class="col-md-4 mb-3">
                    <div class="feature-box p-3 text-center" style="background: linear-gradient(135deg, #f093fb15 0%, #f5576c15 100%); border-radius: 15px;">
                        <i class="bi bi-graph-up" style="font-size: 2rem; color: #f093fb;"></i>
                        <h6 class="mt-2 mb-0" style="color: #1a1a2e;">Laporan Penjualan</h6>
                    </div>
                </div>
            </div>

            <div class="mt-4 p-3" style="background: #f8f9fa; border-radius: 15px; border-left: 4px solid #667eea;">
                <h6 style="color: #667eea; font-weight: 600;"><i class="bi bi-code-slash"></i> Teknologi yang Digunakan:</h6>
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <span class="badge bg-danger">PHP</span>
                    <span class="badge bg-primary">CodeIgniter 4</span>
                    <span class="badge bg-info">MySQL</span>
                    <span class="badge bg-success">Bootstrap 5</span>
                    <span class="badge bg-warning text-dark">JavaScript</span>
                    <span class="badge" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">SweetAlert2</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Copyright -->
    <div class="text-center mt-5 mb-3" style="animation: fadeIn 1s ease 0.8s backwards;">
        <p class="text-muted">
            <i class="bi bi-c-circle"></i> 2025 Kelompok 5 - Akuntansi Angkatan 60<br>
            <small>Sekolah Vokasi IPB University</small>
        </p>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    .member-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .member-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(102, 126, 234, 0.3) !important;
    }
    
    .member-avatar {
        transition: all 0.3s ease;
    }
    
    .member-card:hover .member-avatar {
        transform: scale(1.1);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.5) !important;
    }
    
    .feature-box {
        transition: all 0.3s ease;
    }
    
    .feature-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }
    
    @keyframes fadeInDown {
        from {
            opacity: 0;
            transform: translateY(-30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
</style>

<script>
// Animate on scroll
document.addEventListener('DOMContentLoaded', function() {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    });

    document.querySelectorAll('.member-card, .feature-box').forEach(el => {
        observer.observe(el);
    });
});
</script>
<?= $this->endSection() ?>
