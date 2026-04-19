<?php
session_start();
include "../koneksi.php";

/* ===============================
   CEK LOGIN
================================ */
if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

/* ===============================
   AMBIL DATA STATISTIK
================================ */

// TOTAL USER
$total_users = 0;
$qUser = mysqli_query($conn, "SELECT COUNT(*) as total FROM users");
if ($qUser && mysqli_num_rows($qUser) > 0) {
    $data = mysqli_fetch_assoc($qUser);
    $total_users = $data['total'] ?? 0;
}

// TOTAL TRANSAKSI
$total_transactions = 0;
$qTrans = mysqli_query($conn, "SELECT COUNT(*) as total FROM pemesanan");
if ($qTrans && mysqli_num_rows($qTrans) > 0) {
    $data = mysqli_fetch_assoc($qTrans);
    $total_transactions = $data['total'] ?? 0;
}

// TOTAL PENDAPATAN (STATUS SELESAI)
$total_pendapatan = 0;
$qUang = mysqli_query($conn, "SELECT SUM(total) as total FROM pemesanan WHERE status='selesai'");
if ($qUang && mysqli_num_rows($qUang) > 0) {
    $data = mysqli_fetch_assoc($qUang);
    $total_pendapatan = $data['total'] ?? 0;
}

// KAMERA TERSEDIA
$total_kamera_ready = 0;
$qKamera = mysqli_query($conn, "SELECT COUNT(*) as total FROM kamera WHERE stok > 0");
if ($qKamera && mysqli_num_rows($qKamera) > 0) {
    $data = mysqli_fetch_assoc($qKamera);
    $total_kamera_ready = $data['total'] ?? 0;
}

// TRANSAKSI PENDING
$total_pending = 0;
$qPending = mysqli_query($conn, "SELECT COUNT(*) as total FROM pemesanan WHERE status='pending'");
if ($qPending && mysqli_num_rows($qPending) > 0) {
    $data = mysqli_fetch_assoc($qPending);
    $total_pending = $data['total'] ?? 0;
}

// TOTAL LENSA
$total_lensa = 0;
$qLensa = mysqli_query($conn, "SELECT COUNT(*) as total FROM lensa");
if ($qLensa && mysqli_num_rows($qLensa) > 0) {
    $data = mysqli_fetch_assoc($qLensa);
    $total_lensa = $data['total'] ?? 0;
}

// Data pendapatan per bulan
$tahun_ini = date('Y');
$pendapatan_bulanan = [];
for ($i = 1; $i <= 12; $i++) {
    $query_bulan = mysqli_query($conn, "SELECT COALESCE(SUM(total), 0) as total FROM pemesanan 
                                        WHERE status='selesai' AND MONTH(tanggal_pesan) = $i AND YEAR(tanggal_pesan) = $tahun_ini");
    if ($query_bulan && mysqli_num_rows($query_bulan) > 0) {
        $data_bulan = mysqli_fetch_assoc($query_bulan);
        $pendapatan_bulanan[$i] = $data_bulan['total'];
    } else {
        $pendapatan_bulanan[$i] = 0;
    }
}
$max_pendapatan = max($pendapatan_bulanan) ?: 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin | Rental Kamera</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e9eef3 100%);
    }
    
    /* Animasi fade-in */
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
    
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes glow {
        0% {
            box-shadow: 0 0 5px rgba(239, 68, 68, 0.3);
        }
        100% {
            box-shadow: 0 0 20px rgba(239, 68, 68, 0.6);
        }
    }
    
    .animate-fadeInUp {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .animate-slideIn {
        animation: slideIn 0.5s ease-out forwards;
    }
    
    .card-hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .card-hover:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.2);
    }
    
    .stat-card {
        transition: all 0.3s ease;
    }
    
    .stat-card:hover .stat-icon {
        animation: pulse 0.5s ease-in-out;
    }
    
    .sidebar-link {
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .sidebar-link::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 0;
        height: 100%;
        background: rgba(239, 68, 68, 0.1);
        transition: width 0.3s ease;
    }
    
    .sidebar-link:hover::before {
        width: 100%;
    }
    
    .gradient-text {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .btn-logout {
        transition: all 0.3s ease;
    }
    
    .btn-logout:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
    }
    
    /* Scrollbar modern */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        border-radius: 10px;
    }
    
    ::-webkit-scrollbar-thumb:hover {
        background: #dc2626;
    }
    
    .content-main {
        height: calc(100vh - 80px);
        overflow-y: auto;
        scroll-behavior: smooth;
    }
    
    .progress-bar {
        transition: width 1s ease-in-out;
    }
</style>
</head>
<body>

<div class="min-h-screen flex flex-col">

<!-- HEADER dengan animasi -->
<header class="bg-white/80 backdrop-blur-md shadow-lg sticky top-0 z-10 border-b border-gray-100 animate-slideIn">
    <div class="px-6 py-4 flex justify-between items-center">
        <div class="flex items-center space-x-3">
            <div class="bg-gradient-to-r from-red-500 to-red-700 p-2 rounded-xl shadow-lg">
                <i class="fas fa-camera text-white text-xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Admin<span class="gradient-text">Panel</span></h1>
                <p class="text-xs text-gray-400">Rental Kamera System</p>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <div class="hidden md:flex items-center space-x-2 bg-gray-100 px-3 py-2 rounded-full">
                <i class="fas fa-user-circle text-red-500 text-lg"></i>
                <span class="text-sm font-medium text-gray-700"><?php echo $_SESSION['nama'] ?? 'Admin'; ?></span>
            </div>
            <a href="/ukk/logout.php" class="btn-logout bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white px-5 py-2 rounded-full text-sm font-semibold shadow-md">
                <i class="fas fa-sign-out-alt mr-2"></i> Logout
            </a>
        </div>
    </div>
</header>

<div class="flex flex-1 overflow-hidden">

<!-- SIDEBAR dengan animasi -->
<aside class="w-64 bg-white/95 backdrop-blur-sm shadow-xl p-4 hidden md:block overflow-y-auto border-r border-gray-100 animate-slideIn" style="animation-delay: 0.1s">
    <ul class="space-y-2">
        <li>
            <a href="dashboard.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-xl bg-gradient-to-r from-red-500 to-red-700 text-white shadow-md transition-all">
                <i class="fas fa-home w-5"></i>
                <span class="font-medium">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="list/kamera.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all">
                <i class="fas fa-camera w-5"></i>
                <span class="font-medium">Kelola Kamera</span>
            </a>
        </li>
        <li>
            <a href="verifikasi/pemesanan.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all">
                <i class="fas fa-shopping-cart w-5"></i>
                <span class="font-medium">Transaksi</span>
            </a>
        </li>
        <li>
            <a href="laporan/laporan.php" class="sidebar-link flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all">
                <i class="fas fa-chart-line w-5"></i>
                <span class="font-medium">Laporan</span>
            </a>
        </li>
    </ul>
    
    <!-- Decorative element -->
    <div class="mt-8 pt-6 border-t border-gray-200">
        <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-xl p-4 text-center">
            <i class="fas fa-camera-retro text-red-400 text-2xl mb-2"></i>
            <p class="text-xs text-gray-600">Sistem Rental Kamera<br>Professional</p>
        </div>
    </div>
</aside>

<!-- MAIN CONTENT -->
<main class="flex-1 p-6 content-main bg-gradient-to-br from-gray-50 to-gray-100">

    <!-- Welcome Section dengan animasi -->
    <div class="bg-gradient-to-r from-red-600 via-red-500 to-red-700 rounded-2xl p-6 text-white mb-6 shadow-xl animate-fadeInUp">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Selamat Datang, <?php echo $_SESSION['nama'] ?? 'Admin'; ?>! 👋</h2>
                <p class="text-red-100 mt-1 opacity-90">Semoga harimu menyenangkan. Berikut ringkasan sistem rental kamera.</p>
            </div>
            <div class="hidden md:block">
                <div class="bg-white/20 backdrop-blur-sm rounded-full p-3">
                    <i class="fas fa-chart-simple text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- CARD STATISTIK UTAMA -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-l-4 border-blue-500 card-hover animate-fadeInUp" style="animation-delay: 0.1s">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pengguna</p>
                    <h3 class="text-3xl font-bold mt-2 text-gray-800"><?= number_format($total_users) ?></h3>
                </div>
                <div class="stat-icon bg-blue-100 rounded-2xl p-3">
                    <i class="fas fa-users text-2xl text-blue-500"></i>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-xs text-gray-400"><i class="fas fa-user-plus mr-1"></i> Terdaftar di sistem</p>
            </div>
        </div>

        <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-l-4 border-green-500 card-hover animate-fadeInUp" style="animation-delay: 0.2s">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Pendapatan</p>
                    <h3 class="text-3xl font-bold mt-2 text-gray-800">Rp <?= number_format($total_pendapatan,0,',','.') ?></h3>
                </div>
                <div class="stat-icon bg-green-100 rounded-2xl p-3">
                    <i class="fas fa-money-bill-wave text-2xl text-green-500"></i>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-xs text-gray-400"><i class="fas fa-check-circle mr-1"></i> Dari transaksi selesai</p>
            </div>
        </div>

        <div class="stat-card bg-white rounded-2xl shadow-lg p-6 border-l-4 border-yellow-500 card-hover animate-fadeInUp" style="animation-delay: 0.3s">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Transaksi</p>
                    <h3 class="text-3xl font-bold mt-2 text-gray-800"><?= number_format($total_transactions) ?></h3>
                </div>
                <div class="stat-icon bg-yellow-100 rounded-2xl p-3">
                    <i class="fas fa-receipt text-2xl text-yellow-500"></i>
                </div>
            </div>
            <div class="mt-3">
                <p class="text-xs text-gray-400"><i class="fas fa-chart-line mr-1"></i> Semua status transaksi</p>
            </div>
        </div>
    </div>

    <!-- STATISTIK TAMBAHAN -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-6">
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-2xl shadow-lg p-6 text-white card-hover animate-fadeInUp" style="animation-delay: 0.4s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 font-medium">Kamera Tersedia</p>
                    <h3 class="text-3xl font-bold mt-1"><?= $total_kamera_ready ?></h3>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-camera text-3xl opacity-80"></i>
                </div>
            </div>
            <p class="text-xs opacity-75 mt-2"><i class="fas fa-check-circle mr-1"></i> Siap disewa</p>
        </div>

        <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-2xl shadow-lg p-6 text-white card-hover animate-fadeInUp" style="animation-delay: 0.5s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 font-medium">Transaksi Pending</p>
                    <h3 class="text-3xl font-bold mt-1"><?= $total_pending ?></h3>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-clock text-3xl opacity-80"></i>
                </div>
            </div>
            <p class="text-xs opacity-75 mt-2"><i class="fas fa-hourglass-half mr-1"></i> Perlu diverifikasi</p>
        </div>

        <div class="bg-gradient-to-r from-teal-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white card-hover animate-fadeInUp" style="animation-delay: 0.6s">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 font-medium">Total Lensa</p>
                    <h3 class="text-3xl font-bold mt-1"><?= $total_lensa ?></h3>
                </div>
                <div class="bg-white/20 rounded-full p-3">
                    <i class="fas fa-circle-notch text-3xl opacity-80"></i>
                </div>
            </div>
            <p class="text-xs opacity-75 mt-2"><i class="fas fa-box mr-1"></i> Kelengkapan rental</p>
        </div>
    </div>

    <!-- GRAFIK & AKTIVITAS -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Grafik Pendapatan -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover animate-fadeInUp" style="animation-delay: 0.7s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-chart-line text-green-500 mr-2"></i>
                    Pendapatan per Bulan <?= $tahun_ini ?>
                </h3>
                <div class="bg-green-100 rounded-full px-3 py-1">
                    <span class="text-xs text-green-600 font-semibold">Tahunan</span>
                </div>
            </div>
            <div class="space-y-3 max-h-80 overflow-y-auto pr-2">
                <?php
                $nama_bulan = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                for ($i = 1; $i <= 12; $i++) {
                    $persen = ($pendapatan_bulanan[$i] / $max_pendapatan) * 100;
                    $warna = $i == date('m') ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-blue-500 to-blue-600';
                ?>
                <div>
                    <div class="flex justify-between text-sm mb-1">
                        <span class="font-medium text-gray-700"><?= $nama_bulan[$i-1] ?></span>
                        <span class="text-gray-600 font-semibold">Rp <?= number_format($pendapatan_bulanan[$i], 0, ',', '.') ?></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 overflow-hidden">
                        <div class="<?= $warna ?> h-2.5 rounded-full progress-bar" style="width: <?= $persen ?>%"></div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover animate-fadeInUp" style="animation-delay: 0.8s">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-history text-yellow-500 mr-2"></i>
                    Transaksi Terbaru
                </h3>
                <div class="bg-yellow-100 rounded-full px-3 py-1">
                    <span class="text-xs text-yellow-600 font-semibold">5 Terbaru</span>
                </div>
            </div>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                <?php
                $query_transaksi = mysqli_query($conn, "SELECT p.*, u.nama as user_nama 
                                                         FROM pemesanan p 
                                                         JOIN users u ON p.user_id = u.id 
                                                         ORDER BY p.tanggal_pesan DESC 
                                                         LIMIT 5");
                if ($query_transaksi && mysqli_num_rows($query_transaksi) > 0) {
                    while ($trans = mysqli_fetch_assoc($query_transaksi)) {
                        $status_warna = [
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'proses' => 'bg-blue-100 text-blue-700',
                            'selesai' => 'bg-green-100 text-green-700',
                            'batal' => 'bg-red-100 text-red-700'
                        ][$trans['status']] ?? 'bg-gray-100 text-gray-700';
                ?>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-all duration-300 hover:transform hover:scale-105 cursor-pointer">
                    <div class="flex-1">
                        <p class="font-semibold text-gray-800 text-sm"><?= htmlspecialchars(substr($trans['user_nama'], 0, 25)) ?></p>
                        <p class="text-xs text-gray-500"><i class="far fa-calendar-alt mr-1"></i> <?= date('d/m/Y H:i', strtotime($trans['tanggal_pesan'])) ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-red-600 text-sm">Rp <?= number_format($trans['total'], 0, ',', '.') ?></p>
                        <span class="text-xs px-2 py-1 rounded-full <?= $status_warna ?> font-medium"><?= ucfirst($trans['status']) ?></span>
                    </div>
                </div>
                <?php 
                    }
                } else { 
                ?>
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2 opacity-50"></i>
                        <p>Belum ada transaksi</p>
                    </div>
                <?php } ?>
            </div>
            <a href="verifikasi/pemesanan.php" class="block mt-4 text-center text-red-600 hover:text-red-700 text-sm font-semibold transition-all hover:translate-x-1">
                Lihat Semua Transaksi <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>
    </div>

    <!-- SHORTCUT -->
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
        <a href="list/kamera.php" class="group bg-white rounded-2xl shadow-lg p-5 text-center hover:shadow-2xl transition-all duration-300 hover:transform hover:-translate-y-2 animate-fadeInUp" style="animation-delay: 0.9s">
            <div class="bg-gradient-to-r from-red-100 to-red-50 rounded-2xl p-3 inline-block mb-3 group-hover:scale-110 transition-transform">
                <i class="fas fa-camera text-red-500 text-2xl"></i>
            </div>
            <p class="font-semibold text-gray-800">Kelola Kamera</p>
            <p class="text-xs text-gray-400 mt-1">Tambah/Edit/Hapus</p>
        </a>
        <a href="verifikasi/pemesanan.php" class="group bg-white rounded-2xl shadow-lg p-5 text-center hover:shadow-2xl transition-all duration-300 hover:transform hover:-translate-y-2 animate-fadeInUp" style="animation-delay: 1s">
            <div class="bg-gradient-to-r from-green-100 to-green-50 rounded-2xl p-3 inline-block mb-3 group-hover:scale-110 transition-transform">
                <i class="fas fa-check-double text-green-500 text-2xl"></i>
            </div>
            <p class="font-semibold text-gray-800">Verifikasi</p>
            <p class="text-xs text-gray-400 mt-1">Konfirmasi pesanan</p>
        </a>
        <a href="laporan/laporan.php" class="group bg-white rounded-2xl shadow-lg p-5 text-center hover:shadow-2xl transition-all duration-300 hover:transform hover:-translate-y-2 animate-fadeInUp" style="animation-delay: 1.1s">
            <div class="bg-gradient-to-r from-blue-100 to-blue-50 rounded-2xl p-3 inline-block mb-3 group-hover:scale-110 transition-transform">
                <i class="fas fa-file-alt text-blue-500 text-2xl"></i>
            </div>
            <p class="font-semibold text-gray-800">Laporan</p>
            <p class="text-xs text-gray-400 mt-1">Cetak laporan</p>
        </a>
    </div>

    <!-- FOOTER -->
    <footer class="mt-8 text-center text-gray-400 text-sm py-4 border-t border-gray-200 animate-fadeInUp" style="animation-delay: 1.2s">
        <i class="far fa-copyright"></i> <?= date('Y') ?> Rental Kamera System - Admin Dashboard
        <span class="mx-2">•</span>
        <i class="fas fa-heart text-red-400 text-xs"></i>
    </footer>

</main>
</div>
</div>

<script>
    // Animasi progress bar saat load
    document.addEventListener('DOMContentLoaded', function() {
        const progressBars = document.querySelectorAll('.progress-bar');
        progressBars.forEach(bar => {
            const width = bar.style.width;
            bar.style.width = '0%';
            setTimeout(() => {
                bar.style.width = width;
            }, 100);
        });
    });
</script>

</body>
</html>