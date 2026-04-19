<?php include "../../koneksi.php"; 
if (isset($_POST['pesan'])) {
    $kamera_id = intval($_POST['kamera_id']);
    $jumlah = intval($_POST['jumlah']);

    // Ambil stok sekarang
    $cek = $conn->query("SELECT stok FROM kamera WHERE id = $kamera_id");
    $data = $cek->fetch_assoc();

    if ($data['stok'] >= $jumlah) {
        // Kurangi stok
        $conn->query("UPDATE kamera SET stok = stok - $jumlah WHERE id = $kamera_id");

        // Simpan ke tabel pemesanan
        $conn->query("INSERT INTO pemesanan (kamera_id, jumlah, durasi) VALUES ($kamera_id, $jumlah)");

        echo "<script>alert('Pemesanan berhasil!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Stok tidak cukup!'); history.back();</script>";
    }
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Ambil data pemesanan
    $ambil = $conn->query("SELECT kamera_id, jumlah FROM pemesanan WHERE id = $id");
    $data = $ambil->fetch_assoc();

    $kamera_id = $data['kamera_id'];
    $jumlah = $data['jumlah'];

    // Kembalikan stok
    $conn->query("UPDATE kamera SET stok = stok + $jumlah WHERE id = $kamera_id");

    // Hapus pemesanan
    $conn->query("DELETE FROM pemesanan WHERE id = $id");

    echo "<script>alert('Data berhasil dihapus & stok dikembalikan!'); window.location.href='pemesanan.php';</script>";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Kamera - Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9eef3 100%);
        }
        
        /* Animasi */
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
        
        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }
        
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
        
        @keyframes tableRowFade {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
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
            transform: translateY(-5px);
            box-shadow: 0 20px 30px -12px rgba(0, 0, 0, 0.15);
        }
        
        .btn-primary {
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .table-row {
            animation: tableRowFade 0.4s ease-out forwards;
            opacity: 0;
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
        
        .search-input:focus {
            transform: scale(1.02);
            transition: all 0.2s ease;
        }
        
        .status-badge {
            transition: all 0.2s ease;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body class="min-h-screen">
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
                        <p class="text-xs text-gray-400">Kelola Kamera</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="hidden md:flex items-center space-x-2 bg-gray-100 px-3 py-2 rounded-full">
                        <i class="fas fa-user-circle text-red-500 text-lg"></i>
                        <span class="text-sm font-medium text-gray-700">Admin User</span>
                    </div>
                    <a href="../../logout.php" class="bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white px-5 py-2 rounded-full text-sm font-semibold shadow-md transition-all duration-300 hover:shadow-lg hover:translate-y-[-2px]">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </a>
                </div>
            </div>
        </header>
        
        <!-- KONTAINER UTAMA -->
        <div class="flex flex-1 overflow-hidden">
            
            <!-- SIDEBAR -->
            <aside class="w-64 bg-white/95 backdrop-blur-sm shadow-xl p-4 hidden md:block overflow-y-auto border-r border-gray-100 animate-slideIn" style="animation-delay: 0.1s">
                <ul class="space-y-2">
                    <li>
                        <a href="../dashboard.php" class="flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-300">
                            <i class="fas fa-home w-5"></i>
                            <span class="font-medium">Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="list/kamera.php" class="flex items-center space-x-3 p-3 rounded-xl bg-gradient-to-r from-red-500 to-red-700 text-white shadow-md transition-all duration-300">
                            <i class="fas fa-camera w-5"></i>
                            <span class="font-medium">Kelola Kamera</span>
                        </a>
                    </li>
                    <li>
                        <a href="../verifikasi/pemesanan.php" class="flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-300">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span class="font-medium">Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="../laporan/laporan.php" class="flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-300">
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
                
                <!-- Header Konten -->
                <div class="mb-8 animate-fadeInUp">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                        <div>
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="bg-red-100 rounded-lg p-2">
                                    <i class="fas fa-camera text-red-500"></i>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-800">Kelola <span class="gradient-text">Kamera</span></h2>
                            </div>
                            <p class="text-gray-600 ml-1">Kelola data kamera yang tersedia untuk disewa</p>
                        </div>
                        
                        <div class="mt-4 md:mt-0">
                            <a href="../tambah/kamera.php" class="btn-primary inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-md">
                                <i class="fas fa-plus mr-2"></i>
                                Tambah Kamera Baru
                            </a>
                        </div>
                    </div>
                    
                    <!-- Statistik Ringkas -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-red-500 card-hover">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Total Kamera</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1">
                                        <?php
                                        $total = $conn->query("SELECT COUNT(*) as total FROM kamera")->fetch_assoc();
                                        echo $total['total'];
                                        ?>
                                    </h3>
                                </div>
                                <div class="h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center">
                                    <i class="fas fa-camera text-red-500 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-green-500 card-hover">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Total Stok</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1">
                                        <?php
                                        $stok = $conn->query("SELECT SUM(stok) as total_stok FROM kamera")->fetch_assoc();
                                        echo $stok['total_stok'] ?? 0;
                                        ?>
                                    </h3>
                                </div>
                                <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center">
                                    <i class="fas fa-box text-green-500 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-yellow-500 card-hover">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Merk Berbeda</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1">
                                        <?php
                                        $merk = $conn->query("SELECT COUNT(DISTINCT merk) as total_merk FROM kamera")->fetch_assoc();
                                        echo $merk['total_merk'];
                                        ?>
                                    </h3>
                                </div>
                                <div class="h-12 w-12 rounded-xl bg-yellow-100 flex items-center justify-center">
                                    <i class="fas fa-tag text-yellow-500 text-xl"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-blue-500 card-hover">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-gray-500 text-sm font-medium">Rata-rata Harga</p>
                                    <h3 class="text-2xl font-bold text-gray-800 mt-1">
                                        <?php
                                        $harga = $conn->query("SELECT AVG(harga_sewa) as avg_harga FROM kamera")->fetch_assoc();
                                        echo "Rp " . number_format($harga['avg_harga'] ?? 0, 0, ',', '.');
                                        ?>
                                    </h3>
                                </div>
                                <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-blue-500 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Tabel Kamera -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fadeInUp" style="animation-delay: 0.2s">
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                            <div class="flex items-center">
                                <div class="bg-red-100 rounded-xl p-2 mr-3">
                                    <i class="fas fa-table-list text-red-500"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-gray-800">Daftar Kamera</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">Semua data kamera yang terdaftar</p>
                                </div>
                            </div>
                            
                            <div class="mt-3 md:mt-0">
                                <div class="relative">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                    <input type="text" id="searchInput" placeholder="Cari kamera..." 
                                           class="search-input pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 w-full md:w-80 transition-all duration-300 bg-gray-50">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-red-50 to-orange-50 border-b border-gray-200">
                                    <th class="text-left py-4 px-6 text-gray-700 font-semibold text-sm">ID</th>
                                    <th class="text-left py-4 px-6 text-gray-700 font-semibold text-sm">Nama Kamera</th>
                                    <th class="text-left py-4 px-6 text-gray-700 font-semibold text-sm">Merk</th>
                                    <th class="text-left py-4 px-6 text-gray-700 font-semibold text-sm">Stok</th>
                                    <th class="text-left py-4 px-6 text-gray-700 font-semibold text-sm">Tipe</th>
                                    <th class="text-left py-4 px-6 text-gray-700 font-semibold text-sm">Harga Sewa</th>
                                    <th class="text-left py-4 px-6 text-gray-700 font-semibold text-sm">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody">
                                <?php
                                $data = $conn->query("SELECT * FROM kamera ORDER BY id DESC");
                                $no = 1;
                                $rowIndex = 0;
                                while ($row = $data->fetch_assoc()) :
                                ?>
                                <tr class="table-row border-b border-gray-100 hover:bg-red-50/30 transition-all duration-300" style="animation-delay: <?= $rowIndex * 0.05 ?>s">
                                    <td class="py-4 px-6">
                                        <span class="font-semibold text-gray-800">#<?= $row['id'] ?></span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-red-100 to-orange-100 flex items-center justify-center mr-3 shadow-sm">
                                                <i class="fas fa-camera text-red-500 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800"><?= htmlspecialchars($row['nama']) ?></p>
                                                <p class="text-xs text-gray-500 mt-0.5">Resolusi: <?= htmlspecialchars($row['resolusi']) ?></p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700">
                                            <i class="fas fa-tag mr-1 text-xs"></i>
                                            <?= htmlspecialchars($row['merk']) ?>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex items-center">
                                            <span class="font-semibold <?= $row['stok'] > 0 ? 'text-green-600' : 'text-red-600' ?>">
                                                <?= $row['stok'] ?>
                                            </span>
                                            <?php if($row['stok'] > 0): ?>
                                                <span class="ml-2 text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full status-badge">Tersedia</span>
                                            <?php else: ?>
                                                <span class="ml-2 text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full status-badge">Habis</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <span class="text-gray-700"><?= htmlspecialchars($row['tipe']) ?></span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div>
                                            <span class="font-bold text-red-600 text-lg">Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?></span>
                                            <p class="text-xs text-gray-500">/hari</p>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex space-x-2">
                                            <a href="../edit/kamera.php?id=<?= $row['id'] ?>" 
                                               class="inline-flex items-center px-3 py-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-100 transition-all duration-300 text-sm font-medium group">
                                                <i class="fas fa-edit mr-1 group-hover:rotate-12 transition-transform"></i>
                                                Edit
                                            </a>
                                            <a href="../hapus/kamera.php?id=<?= $row['id'] ?>" 
                                               onclick="return confirm('Yakin ingin menghapus kamera <?= htmlspecialchars(addslashes($row['nama'])) ?>?')"
                                               class="inline-flex items-center px-3 py-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition-all duration-300 text-sm font-medium group">
                                                <i class="fas fa-trash mr-1 group-hover:scale-110 transition-transform"></i>
                                                Hapus
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php 
                                $rowIndex++;
                                $no++;
                                endwhile; 
                                
                                if($no == 1): 
                                ?>
                                <tr>
                                    <td colspan="7" class="py-16 text-center">
                                        <div class="text-gray-400">
                                            <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-camera text-3xl text-gray-300"></i>
                                            </div>
                                            <p class="text-lg font-medium text-gray-500">Belum ada data kamera</p>
                                            <p class="text-sm mt-1 text-gray-400">Mulai dengan menambahkan kamera baru</p>
                                            <a href="../tambah/kamera.php" class="inline-flex items-center px-5 py-2.5 mt-5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-md">
                                                <i class="fas fa-plus mr-2"></i>
                                                Tambah Kamera Pertama
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Footer Tabel -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <p class="text-gray-600 text-sm">
                                <i class="fas fa-database mr-1 text-gray-400"></i>
                                Menampilkan <span class="font-semibold text-gray-800"><?= ($no-1) ?></span> data kamera
                            </p>
                            <div class="mt-2 md:mt-0 flex items-center space-x-2">
                                <div class="h-1 w-8 bg-red-200 rounded-full overflow-hidden">
                                    <div class="h-full w-2/3 bg-gradient-to-r from-red-500 to-red-700 rounded-full"></div>
                                </div>
                                <span class="text-xs text-gray-400">Sistem Rental Kamera</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <footer class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-400 text-sm">
                    <i class="far fa-copyright"></i> <?= date('Y') ?> Rental Kamera System - Kelola Kamera
                    <span class="mx-2">•</span>
                    <i class="fas fa-heart text-red-400 text-xs"></i>
                </footer>
            </main>
        </div>
    </div>
    
    <!-- Mobile Menu Button -->
    <div class="md:hidden fixed bottom-6 right-6 z-10">
        <button id="mobileMenuBtn" class="h-14 w-14 rounded-full bg-gradient-to-r from-red-500 to-red-700 text-white shadow-lg flex items-center justify-center transition-all duration-300 hover:scale-110">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>
    
    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden transition-all duration-300">
        <div class="absolute right-0 top-0 h-full w-64 bg-white shadow-xl p-6 overflow-y-auto animate-slideIn">
            <div class="flex justify-between items-center mb-8">
                <div class="flex items-center space-x-2">
                    <div class="bg-gradient-to-r from-red-500 to-red-700 p-2 rounded-lg">
                        <i class="fas fa-camera text-white text-sm"></i>
                    </div>
                    <h2 class="text-lg font-bold gradient-text">Menu Admin</h2>
                </div>
                <button id="closeMobileMenu" class="text-gray-500 hover:text-red-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="../dashboard.php" class="flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-300">
                            <i class="fas fa-home w-5"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="list/kamera.php" class="flex items-center space-x-3 p-3 rounded-xl bg-gradient-to-r from-red-500 to-red-700 text-white shadow-md">
                            <i class="fas fa-camera w-5"></i>
                            <span>Kelola Kamera</span>
                        </a>
                    </li>
                    <li>
                        <a href="../verifikasi/pemesanan.php" class="flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-300">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span>Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="../laporan/laporan.php" class="flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-300">
                            <i class="fas fa-chart-line w-5"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    
    <script>
        // Mobile menu functionality
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const closeMobileMenu = document.getElementById('closeMobileMenu');
        
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileSidebar.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            });
        }
        
        if (closeMobileMenu) {
            closeMobileMenu.addEventListener('click', function() {
                mobileSidebar.classList.add('hidden');
                document.body.style.overflow = '';
            });
        }
        
        if (mobileSidebar) {
            mobileSidebar.addEventListener('click', function(e) {
                if (e.target.id === 'mobileSidebar') {
                    mobileSidebar.classList.add('hidden');
                    document.body.style.overflow = '';
                }
            });
        }
        
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                const searchTerm = this.value.toLowerCase();
                const rows = document.querySelectorAll('#tableBody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchTerm)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        }
    </script>
</body>
</html>