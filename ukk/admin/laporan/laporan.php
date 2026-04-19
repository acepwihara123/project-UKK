<?php
include "../../koneksi.php";

$tgl_awal  = $_GET['tgl_awal'] ?? '';
$tgl_akhir = $_GET['tgl_akhir'] ?? '';

$where = "";

if ($tgl_awal && $tgl_akhir) {
    $where = "WHERE tanggal_mulai BETWEEN '$tgl_awal' AND '$tgl_akhir'";
}

$query = $conn->query("
SELECT * FROM pemesanan
$where
ORDER BY id DESC
");

$total_pesanan = $query->num_rows;

// total pendapatan
$result = $conn->query("
SELECT SUM(total) as total
FROM pemesanan $where
");
$rowTotal = $result->fetch_assoc();
$total_pendapatan = $rowTotal['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Laporan Pemesanan | Admin Panel</title>

<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

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
    
    .gradient-text {
        background: linear-gradient(135deg, #dc2626, #991b1b);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .table-row {
        animation: tableRowFade 0.4s ease-out forwards;
        opacity: 0;
        transition: all 0.3s ease;
    }
    
    .table-row:hover {
        background-color: #fef2f2 !important;
        transform: scale(1.01);
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
    
    .btn-print {
        transition: all 0.3s ease;
    }
    
    .btn-print:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3);
    }
    
    .btn-filter {
        transition: all 0.3s ease;
    }
    
    .btn-filter:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
    }
    
    /* Print Style */
    @media print {
        body {
            background: white !important;
            font-size: 12px;
        }
        
        aside, .no-print, .btn-print, .btn-filter, form, .filter-section {
            display: none !important;
        }
        
        main {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .shadow, .rounded-xl, .rounded-2xl {
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        
        .bg-white, .bg-gradient-to-r {
            background: white !important;
        }
        
        table {
            border-collapse: collapse;
            width: 100%;
        }
        
        table th, table td {
            border: 1px solid black;
            padding: 8px;
        }
        
        .print\:block {
            display: block !important;
        }
        
        h1, h2, h3, p {
            text-align: center;
        }
    }
</style>
</head>

<body class="min-h-screen">

<div class="min-h-screen flex flex-col">

    <!-- HEADER -->
    <header class="bg-white/80 backdrop-blur-md shadow-lg sticky top-0 z-10 border-b border-gray-100 animate-slideIn">
        <div class="px-6 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-gradient-to-r from-red-500 to-red-700 p-2 rounded-xl shadow-lg">
                    <i class="fas fa-chart-line text-white text-xl"></i>
                </div>
                <div>
                    <h1 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Admin<span class="gradient-text">Panel</span></h1>
                    <p class="text-xs text-gray-400">Laporan Pemesanan</p>
                </div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="hidden md:flex items-center space-x-2 bg-gray-100 px-3 py-2 rounded-full">
                    <i class="fas fa-user-circle text-red-500 text-lg"></i>
                    <span class="text-sm font-medium text-gray-700">Admin User</span>
                </div>
                <a href="../../../logout.php" class="bg-gradient-to-r from-red-500 to-red-700 hover:from-red-600 hover:to-red-800 text-white px-5 py-2 rounded-full text-sm font-semibold shadow-md transition-all duration-300 hover:shadow-lg hover:translate-y-[-2px]">
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
                    <a href="../list/kamera.php" class="flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-300">
                        <i class="fas fa-camera w-5"></i>
                        <span class="font-medium">Kelola Kamera</span>
                    </a>
                </li>
                <li>
                    <a href="../verifikasi/pemesanan.php" class="flex items-center space-x-3 p-3 rounded-xl text-gray-700 hover:bg-red-50 hover:text-red-600 transition-all duration-300">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span class="font-medium">Verifikasi</span>
                    </a>
                </li>
                <li>
                    <a href="laporan.php" class="flex items-center space-x-3 p-3 rounded-xl bg-gradient-to-r from-red-500 to-red-700 text-white shadow-md transition-all duration-300">
                        <i class="fas fa-chart-line w-5"></i>
                        <span class="font-medium">Laporan</span>
                    </a>
                </li>
            </ul>
            
            <!-- Decorative element -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <div class="bg-gradient-to-r from-red-50 to-orange-50 rounded-xl p-4 text-center">
                    <i class="fas fa-file-alt text-red-400 text-2xl mb-2"></i>
                    <p class="text-xs text-gray-600">Cetak Laporan<br>Pemesanan</p>
                </div>
            </div>
        </aside>
        
        <!-- MAIN CONTENT -->
        <main class="flex-1 p-6 content-main bg-gradient-to-br from-gray-50 to-gray-100">
            
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fadeInUp">
                
                <!-- Header Laporan -->
                <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex items-center space-x-2">
                        <div class="bg-red-100 rounded-lg p-2">
                            <i class="fas fa-chart-line text-red-500"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Laporan <span class="gradient-text">Pemesanan</span></h2>
                            <p class="text-xs text-gray-500 mt-0.5">Data laporan pemesanan kamera</p>
                        </div>
                    </div>
                </div>
                
                <!-- FILTER SECTION -->
                <div class="px-6 py-5 bg-gray-50/50 border-b border-gray-100 no-print">
                    <form method="GET" class="flex flex-wrap gap-3 items-end">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Awal</label>
                            <input type="date" name="tgl_awal" value="<?= $tgl_awal ?>"
                                   class="border border-gray-300 p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Tanggal Akhir</label>
                            <input type="date" name="tgl_akhir" value="<?= $tgl_akhir ?>"
                                   class="border border-gray-300 p-2.5 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                        </div>
                        <div>
                            <button type="submit" class="btn-filter bg-gradient-to-r from-red-600 to-red-700 text-white px-5 py-2.5 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-md">
                                <i class="fas fa-filter mr-2"></i> Filter
                            </button>
                        </div>
                        <div>
                            <button type="button" onclick="window.print()" class="btn-print bg-gradient-to-r from-green-600 to-green-700 text-white px-5 py-2.5 rounded-xl hover:from-green-700 hover:to-green-800 transition-all duration-300 shadow-md">
                                <i class="fas fa-print mr-2"></i> Cetak Laporan
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- INFO PERIODE -->
                <div class="px-6 py-4 bg-gradient-to-r from-red-50 to-orange-50 border-b border-red-100">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-calendar-alt text-red-500"></i>
                            <span class="text-sm text-gray-700">
                                <span class="font-semibold">Periode:</span> 
                                <?= $tgl_awal ? date('d/m/Y', strtotime($tgl_awal)) : 'Semua Tanggal' ?> 
                                s/d 
                                <?= $tgl_akhir ? date('d/m/Y', strtotime($tgl_akhir)) : 'Sekarang' ?>
                            </span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <div class="h-2 w-2 bg-green-500 rounded-full"></div>
                                <span class="text-xs text-gray-500">Total Pesanan: <strong class="text-gray-800"><?= $total_pesanan ?></strong></span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="h-2 w-2 bg-red-500 rounded-full"></div>
                                <span class="text-xs text-gray-500">Total Pendapatan: <strong class="text-red-600">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></strong></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- STATISTIK CARD -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-6 border-b border-gray-100">
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-4 text-center card-hover">
                        <i class="fas fa-shopping-cart text-blue-500 text-2xl mb-2"></i>
                        <p class="text-gray-600 text-sm">Total Pesanan</p>
                        <p class="text-2xl font-bold text-blue-600"><?= $total_pesanan ?></p>
                    </div>
                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 text-center card-hover">
                        <i class="fas fa-money-bill-wave text-green-500 text-2xl mb-2"></i>
                        <p class="text-gray-600 text-sm">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-green-600">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></p>
                    </div>
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-4 text-center card-hover">
                        <i class="fas fa-chart-line text-purple-500 text-2xl mb-2"></i>
                        <p class="text-gray-600 text-sm">Rata-rata Pesanan</p>
                        <p class="text-2xl font-bold text-purple-600"><?= $total_pesanan > 0 ? round($total_pendapatan / $total_pesanan) : 0 ?></p>
                    </div>
                </div>
                
                <!-- TABLE -->
                <div class="overflow-x-auto p-6 pt-0">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-red-50 to-orange-50 rounded-xl">
                                <th class="p-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                                <th class="p-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                                <th class="p-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                                <th class="p-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Durasi</th>
                                <th class="p-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pembayaran</th>
                                <th class="p-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total</th>
                                <th class="p-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <?php 
                            $rowIndex = 0;
                            while($row = $query->fetch_assoc()): 
                            ?>
                            <tr class="table-row hover:bg-red-50/30 transition-all duration-300" style="animation-delay: <?= $rowIndex * 0.05 ?>s">
                                <td class="p-3 whitespace-nowrap">
                                    <span class="font-semibold text-gray-800">#<?= $row['id'] ?></span>
                                 </td>
                                <td class="p-3 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <div class="h-8 w-8 rounded-full bg-red-100 flex items-center justify-center">
                                            <i class="fas fa-user text-red-500 text-xs"></i>
                                        </div>
                                        <span class="font-medium text-gray-700"><?= htmlspecialchars($row['nama']) ?></span>
                                    </div>
                                 </td>
                                <td class="p-3 whitespace-nowrap">
                                    <div class="flex items-center gap-1">
                                        <i class="fas fa-calendar-alt text-gray-400 text-xs"></i>
                                        <span class="text-gray-600"><?= date('d/m/Y', strtotime($row['tanggal_mulai'])) ?></span>
                                    </div>
                                 </td>
                                <td class="p-3 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">
                                        <i class="fas fa-clock mr-1 text-xs"></i> <?= $row['durasi'] ?> Hari
                                    </span>
                                 </td>
                                <td class="p-3 whitespace-nowrap">
                                    <?php 
                                    $metode = strtolower($row['pembayaran'] ?? 'transfer');
                                    $metode_icon = [
                                        'transfer' => 'fa-university',
                                        'cod' => 'fa-truck',
                                        'qris' => 'fa-qrcode',
                                        'ewallet' => 'fa-mobile-alt',
                                        'cash' => 'fa-money-bill-wave'
                                    ];
                                    $icon = $metode_icon[$metode] ?? 'fa-credit-card';
                                    ?>
                                    <div class="flex items-center gap-2">
                                        <i class="fas <?php echo $icon; ?> text-gray-500"></i>
                                        <span class="text-gray-700"><?= ucfirst($row['pembayaran']) ?></span>
                                    </div>
                                 </td>
                                <td class="p-3 whitespace-nowrap">
                                    <span class="font-bold text-red-600">Rp <?= number_format($row['total'], 0, ',', '.') ?></span>
                                 </td>
                                <td class="p-3 whitespace-nowrap">
                                    <?php 
                                    $status = strtolower($row['status'] ?? 'proses');
                                    $status_class = '';
                                    $status_text = '';
                                    if ($status == 'selesai') {
                                        $status_class = 'bg-green-100 text-green-700';
                                        $status_text = 'Selesai';
                                    } elseif ($status == 'proses') {
                                        $status_class = 'bg-yellow-100 text-yellow-700';
                                        $status_text = 'Proses';
                                    } else {
                                        $status_class = 'bg-red-100 text-red-700';
                                        $status_text = 'Batal';
                                    }
                                    ?>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold <?= $status_class ?>">
                                        <?= $status_text ?>
                                    </span>
                                 </td>
                             </tr>
                            <?php 
                            $rowIndex++;
                            endwhile; 
                            if($total_pesanan == 0):
                            ?>
                            <tr>
                                <td colspan="7" class="p-12 text-center text-gray-500">
                                    <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                    </div>
                                    <p class="text-lg font-medium text-gray-500">Belum ada data pemesanan</p>
                                    <p class="text-sm mt-1 text-gray-400">Silakan pilih periode lain</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- FOOTER -->
                <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
                        <p class="text-gray-500 text-xs">
                            <i class="fas fa-database mr-1 text-gray-400"></i>
                            Menampilkan <span class="font-semibold text-gray-800"><?= $total_pesanan ?></span> data pemesanan
                        </p>
                        <div class="flex items-center space-x-2">
                            <div class="h-1 w-8 bg-red-200 rounded-full overflow-hidden">
                                <div class="h-full w-2/3 bg-gradient-to-r from-red-500 to-red-700 rounded-full"></div>
                            </div>
                            <span class="text-xs text-gray-400">Sistem Rental Kamera</span>
                        </div>
                    </div>
                </div>
                
                <!-- PRINT FOOTER (hanya muncul saat print) -->
                <div class="hidden print:block text-center mt-4 text-gray-500 text-xs">
                    <p>Dicetak pada: <?= date('d/m/Y H:i:s') ?></p>
                    <p>© <?= date('Y') ?> Rental Kamera System - Laporan Pemesanan</p>
                </div>
            </div>
            
            <!-- FOOTER -->
            <footer class="mt-6 text-center text-gray-400 text-sm animate-fadeInUp" style="animation-delay: 0.6s">
                <i class="far fa-copyright"></i> <?= date('Y') ?> Rental Kamera System - Laporan Pemesanan
                <span class="mx-2">•</span>
                <i class="fas fa-heart text-red-400 text-xs"></i>
            </footer>
        </main>
    </div>
</div>

</body>
</html>