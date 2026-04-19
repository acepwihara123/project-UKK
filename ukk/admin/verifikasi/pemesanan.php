<?php
include "../../koneksi.php";

// Proses Hapus Pemesanan
if (isset($_POST['delete_pemesanan'])) {
    $id = intval($_POST['delete_id']);
    
    // Hapus data dari tabel verifikasi terlebih dahulu (jika ada)
    $conn->query("DELETE FROM verifikasi WHERE pemesanan_id = $id");
    
    // Hapus data dari tabel pemesanan
    $hapus = $conn->query("DELETE FROM pemesanan WHERE id = $id");
    
    if ($hapus) {
        echo "<script>
            alert('Data pemesanan berhasil dihapus!');
            window.location.href = 'pemesanan.php';
        </script>";
        exit();
    } else {
        echo "<script>
            alert('Gagal menghapus data: " . $conn->error . "');
            window.location.href = 'pemesanan.php';
        </script>";
        exit();
    }
}

// ambil data pemesanan dengan join ke tabel verifikasi
$data = $conn->query("
    SELECT p.*, d.total_denda, d.hari_terlambat
    FROM pemesanan p
    LEFT JOIN denda d ON d.pemesanan_id = p.id
    ORDER BY p.id DESC
");

$tarif_denda = 50000;

// ambil semua pemesanan
$q = $conn->query("SELECT * FROM pemesanan");

while($p = $q->fetch_assoc()){

    $tgl_kembali = strtotime($p['tanggal_kembali']);
$hari_ini = strtotime(date('Y-m-d'));

if($hari_ini > $tgl_kembali){

    $hari_telat = floor(($hari_ini - $tgl_kembali) / (60*60*24));
    $total_denda = $hari_telat * $tarif_denda;

    $cek = $conn->query("SELECT * FROM denda WHERE pemesanan_id=".$p['id']);

    if($cek->num_rows > 0){
        $conn->query("
            UPDATE denda 
            SET hari_terlambat=$hari_telat, total_denda=$total_denda 
            WHERE pemesanan_id=".$p['id']
        );
    } else {
        $conn->query("
            INSERT INTO denda (pemesanan_id,hari_terlambat,total_denda)
            VALUES (".$p['id'].",$hari_telat,$total_denda)
        ");
    }

} else {

            $hari_telat = floor(($hari_ini - $tgl_kembali) / (60*60*24));
            $total_denda = $hari_telat * $tarif_denda;

            // cek sudah ada denda atau belum
            $cek = $conn->query("SELECT * FROM denda WHERE pemesanan_id=".$p['id']);

            if($cek->num_rows > 0){
                // update
                $conn->query("
                    UPDATE denda 
                    SET hari_terlambat=$hari_telat, total_denda=$total_denda 
                    WHERE pemesanan_id=".$p['id']
                );
            } else {
                // insert
                $conn->query("
                    INSERT INTO denda (pemesanan_id,hari_terlambat,total_denda)
                    VALUES (".$p['id'].",$hari_telat,$total_denda)
                ");
            }

        }

    }

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Pemesanan | Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
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
        }
        
        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            transition: all 0.2s ease;
        }
        
        .status-badge:hover {
            transform: scale(1.05);
        }
        
        .status-selesai {
            background-color: #d1fae5;
            color: #059669;
        }
        .status-proses {
            background-color: #fef3c7;
            color: #d97706;
        }
        .status-batal {
            background-color: #fee2e2;
            color: #dc2626;
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
        
        .btn-action {
            transition: all 0.2s ease;
        }
        
        .btn-action:hover {
            transform: scale(1.1);
        }
        
        /* Modal animation */
        .modal-show {
            animation: fadeInUp 0.3s ease-out;
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
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Admin<span class="gradient-text">Panel</span></h1>
                        <p class="text-xs text-gray-400">Verifikasi Pemesanan</p>
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
                        <a href="pemesanan.php" class="flex items-center space-x-3 p-3 rounded-xl bg-gradient-to-r from-red-500 to-red-700 text-white shadow-md transition-all duration-300">
                            <i class="fas fa-shopping-cart w-5"></i>
                            <span class="font-medium">Verifikasi</span>
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
                        <i class="fas fa-clipboard-list text-red-400 text-2xl mb-2"></i>
                        <p class="text-xs text-gray-600">Manajemen Pemesanan<br>Professional</p>
                    </div>
                </div>
            </aside>
            
            <!-- MAIN CONTENT -->
            <main class="flex-1 p-6 content-main bg-gradient-to-br from-gray-50 to-gray-100">
                
                <!-- Header -->
                <div class="mb-8 animate-fadeInUp">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="bg-red-100 rounded-lg p-2">
                            <i class="fas fa-clipboard-list text-red-500"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-800">Verifikasi <span class="gradient-text">Pemesanan</span></h2>
                    </div>
                    <p class="text-gray-600 ml-1">Kelola dan verifikasi semua pemesanan kamera</p>
                </div>

                <!-- Stats Cards -->
                <?php
                // Get statistics from verifikasi table
                $total_pemesanan = mysqli_num_rows($data);
                $total_nominal = 0;
                $status_counts = [
                    'selesai' => 0,
                    'proses' => 0,
                    'batal' => 0
                ];
                
                mysqli_data_seek($data, 0);
                while($row = mysqli_fetch_assoc($data)) {
                    $total_nominal += $row['total'];
                    $status = strtolower($row['status'] ?? 'proses');
                    if (isset($status_counts[$status])) {
                        $status_counts[$status]++;
                    }
                }
                mysqli_data_seek($data, 0);
                ?>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
                    <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-red-500 card-hover animate-fadeInUp" style="animation-delay: 0.1s">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Total Pemesanan</p>
                                <p class="text-2xl font-bold text-gray-800 mt-1"><?php echo $total_pemesanan; ?></p>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-red-100 flex items-center justify-center">
                                <i class="fas fa-shopping-cart text-red-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-green-500 card-hover animate-fadeInUp" style="animation-delay: 0.2s">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Total Pendapatan</p>
                                <p class="text-2xl font-bold text-green-600 mt-1">Rp <?php echo number_format($total_nominal, 0, ',', '.'); ?></p>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-green-100 flex items-center justify-center">
                                <i class="fas fa-money-bill-wave text-green-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-yellow-500 card-hover animate-fadeInUp" style="animation-delay: 0.3s">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Selesai</p>
                                <p class="text-2xl font-bold text-green-600 mt-1"><?php echo $status_counts['selesai']; ?></p>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-yellow-100 flex items-center justify-center">
                                <i class="fas fa-check-circle text-yellow-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-2xl shadow-md p-5 border-l-4 border-blue-500 card-hover animate-fadeInUp" style="animation-delay: 0.4s">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-sm font-medium">Proses</p>
                                <p class="text-2xl font-bold text-blue-600 mt-1"><?php echo $status_counts['proses']; ?></p>
                            </div>
                            <div class="h-12 w-12 rounded-xl bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-spinner text-blue-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Card -->
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fadeInUp" style="animation-delay: 0.5s">
                    
                    <!-- Search Bar -->
                    <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex flex-col md:flex-row gap-4 justify-between">
                            <div class="relative flex-1">
                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="text"
                                    id="searchInput"
                                    placeholder="Cari berdasarkan nama, email, telepon, atau alamat..."
                                    class="search-input w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300 bg-gray-50">
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="bg-gradient-to-r from-red-50 to-orange-50 border-b border-gray-200">
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">ID</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kontak & Alamat</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal Sewa</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Metode</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Denda</th>
                                </tr>
                            </thead>
                            <tbody id="tableBody" class="bg-white divide-y divide-gray-100">
                                <?php if(mysqli_num_rows($data) > 0): ?>
                                    <?php 
                                    $rowIndex = 0;
                                    while($row = mysqli_fetch_assoc($data)): 
                                        $status = strtolower($row['status'] ?? 'proses');
                                    ?>
                                        <tr class="table-row hover:bg-red-50/30 transition-all duration-300" data-status="<?php echo $status; ?>" style="animation-delay: <?= $rowIndex * 0.05 ?>s">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="font-semibold text-gray-800">#<?php echo $row['id']; ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($row['nama']); ?></div>
                                                <div class="text-xs text-gray-500 mt-1">
                                                    <i class="fas fa-envelope mr-1"></i> <?php echo htmlspecialchars($row['email']); ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="space-y-1">
                                                    <div class="flex items-center gap-2 text-sm">
                                                        <i class="fas fa-phone-alt text-gray-400 w-4"></i>
                                                        <span class="text-gray-700"><?php echo htmlspecialchars($row['no_telepon'] ?? '-'); ?></span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-sm">
                                                        <i class="fas fa-map-marker-alt text-gray-400 w-4"></i>
                                                        <span class="text-gray-600 text-xs truncate max-w-[200px]"><?php echo htmlspecialchars(substr($row['alamat'] ?? '-', 0, 50)); ?></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <div class="space-y-1">
                                                    <div class="flex items-center gap-2 text-sm">
                                                        <i class="fas fa-calendar-alt text-blue-500 w-4"></i>
                                                        <span class="text-gray-700"><?php echo date('d/m/Y', strtotime($row['tanggal_mulai'] ?? 'now')); ?></span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-sm">
                                                        <i class="fas fa-calendar-check text-green-500 w-4"></i>
                                                        <span class="text-gray-700"><?php echo !empty($row['tanggal_kembali']) ? date('d/m/Y', strtotime($row['tanggal_kembali'])) : '-'; ?></span>
                                                    </div>
                                                    <div class="flex items-center gap-2 text-xs text-gray-500">
                                                        <i class="fas fa-clock w-4"></i>
                                                        <span>Durasi: <?php echo $row['durasi']; ?> hari</span>
                                                    </div>
                                                </div>
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="font-bold text-red-600 text-lg">Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></span>
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php 
                                                $metode = strtolower($row['pembayaran'] ?? 'transfer');
                                                $metode_text = [
                                                    'transfer' => 'Transfer',
                                                    'cod' => 'Cash',
                                                    'qris' => 'QRIS',
                                                    'ewallet' => 'E-Wallet',
                                                    'cash' => 'Cash'
                                                ];
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
                                                    <span class="text-gray-700"><?php echo $metode_text[$metode] ?? ucfirst($metode); ?></span>
                                                </div>
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php 
                                                $status_class = '';
                                                $status_text = '';
                                                
                                                if ($status == 'selesai') {
                                                    $status_class = 'status-selesai';
                                                    $status_text = 'Selesai';
                                                } elseif ($status == 'proses') {
                                                    $status_class = 'status-proses';
                                                    $status_text = 'Proses';
                                                } else {
                                                    $status_class = 'status-batal';
                                                    $status_text = 'Batal';
                                                }
                                                ?>
                                                <span class="status-badge <?php echo $status_class; ?>">
                                                    <?php echo $status_text; ?>
                                                </span>
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex space-x-2">
                                                    <button onclick="viewDetail(<?php echo $row['id']; ?>)" 
                                                            class="btn-action text-blue-600 hover:text-blue-800 transition p-1" 
                                                            title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <button onclick="updateStatus(<?php echo $row['id']; ?>, '<?php echo $status; ?>')" 
                                                            class="btn-action text-green-600 hover:text-green-800 transition p-1" 
                                                            title="Update Status">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button onclick="deleteOrder(<?php echo $row['id']; ?>, '<?php echo addslashes($row['nama']); ?>')" 
                                                            class="btn-action text-red-600 hover:text-red-800 transition p-1" 
                                                            title="Hapus">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                             </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if($row['total_denda'] > 0): ?>
                                                    <div class="text-center">
                                                        <span class="text-red-600 font-bold">
                                                            Rp <?= number_format($row['total_denda'],0,',','.') ?>
                                                        </span>
                                                        <br>
                                                        <span class="text-xs text-gray-500">(<?= $row['hari_terlambat'] ?? 0 ?> hari)</span>
                                                        <br>
                                                        <button onclick="bayarDenda(<?= $row['id'] ?>)"
                                                                class="text-yellow-600 text-xs mt-1 hover:text-yellow-700 transition">
                                                            <i class="fas fa-money-bill"></i> Bayar
                                                        </button>
                                                    </div>
                                                <?php else: ?>
                                                    <span class="text-gray-400">-</span>
                                                <?php endif; ?>
                                             </td>
                                        </tr>
                                    <?php 
                                    $rowIndex++;
                                    endwhile; 
                                    ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                            <div class="bg-gray-100 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4">
                                                <i class="fas fa-inbox text-3xl text-gray-300"></i>
                                            </div>
                                            <p class="text-lg font-medium text-gray-500">Belum ada data pemesanan</p>
                                            <p class="text-sm mt-1 text-gray-400">Silakan tunggu pemesanan masuk</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Table Footer -->
                    <div class="px-6 py-4 border-t border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                        <div class="flex justify-between items-center">
                            <p class="text-gray-600 text-sm">
                                <i class="fas fa-database mr-1 text-gray-400"></i>
                                Menampilkan <span class="font-semibold text-gray-800" id="totalData"><?php echo $total_pemesanan; ?></span> data pemesanan
                            </p>
                            <div class="flex items-center space-x-2">
                                <div class="h-1 w-8 bg-red-200 rounded-full overflow-hidden">
                                    <div class="h-full w-2/3 bg-gradient-to-r from-red-500 to-red-700 rounded-full"></div>
                                </div>
                                <span class="text-xs text-gray-400">Sistem Rental Kamera</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <footer class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-400 text-sm animate-fadeInUp" style="animation-delay: 0.6s">
                    <i class="far fa-copyright"></i> <?= date('Y') ?> Rental Kamera System - Verifikasi Pemesanan
                    <span class="mx-2">•</span>
                    <i class="fas fa-heart text-red-400 text-xs"></i>
                </footer>
            </main>
        </div>
    </div>
    
    <!-- FORM DELETE -->
    <form id="deleteForm" method="POST">
        <input type="hidden" name="delete_id" id="delete_id">
        <input type="hidden" name="delete_pemesanan">
    </form>

    <!-- Modal Update Status -->
    <div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 modal-show">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-2">
                    <div class="bg-red-100 rounded-lg p-2">
                        <i class="fas fa-edit text-red-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Update Status</h3>
                </div>
                <button onclick="closeModal()" class="text-gray-500 hover:text-red-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <input type="hidden" id="status_id">
            <label class="block mb-2 font-medium text-gray-700">Pilih Status</label>
            <select id="status_value" class="w-full border border-gray-300 rounded-xl p-3 mb-5 focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                <option value="proses">Proses</option>
                <option value="selesai">Selesai</option>
                <option value="batal">Batal</option>
            </select>
            <button onclick="saveStatus()" class="w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-3 rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-md font-semibold">
                Simpan Status
            </button>
        </div>
    </div>

    <!-- MODAL DETAIL -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl p-6 modal-show">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center space-x-2">
                    <div class="bg-blue-100 rounded-lg p-2">
                        <i class="fas fa-info-circle text-blue-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Detail Pemesanan</h3>
                </div>
                <button onclick="closeDetailModal()" class="text-gray-500 hover:text-red-500 transition">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="detailContent" class="max-h-96 overflow-y-auto">
                Loading...
            </div>
        </div>
    </div>

    <script>
    /* =========================
       SEARCH
    ========================= */
    document.getElementById("searchInput")?.addEventListener("keyup", function(){
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll("#tableBody tr");
        let visibleCount = 0;
        rows.forEach(row=>{
            if(row.innerText.toLowerCase().includes(value)){
                row.style.display = "";
                visibleCount++;
            } else {
                row.style.display = "none";
            }
        });
        document.getElementById("totalData").textContent = visibleCount;
    });

    /* =========================
       DELETE
    ========================= */
    function deleteOrder(id,nama){
        if(confirm("⚠️ Hapus pemesanan " + nama + " ?")){
            document.getElementById("delete_id").value=id;
            document.getElementById("deleteForm").submit();
        }
    }

    /* =========================
       MODAL STATUS
    ========================= */
    function updateStatus(id,current){
        document.getElementById("status_id").value=id;
        document.getElementById("status_value").value=current;
        document.getElementById("statusModal").classList.remove("hidden");
        document.getElementById("statusModal").classList.add("flex");
    }

    function closeModal(){
        document.getElementById("statusModal").classList.add("hidden");
        document.getElementById("statusModal").classList.remove("flex");
    }

    /* =========================
       SAVE STATUS
    ========================= */
    function saveStatus(){
        let id=document.getElementById("status_id").value;
        let status=document.getElementById("status_value").value;

        fetch("update_status.php",{
            method:"POST",
            headers:{
                "Content-Type":"application/x-www-form-urlencoded"
            },
            body:"id="+id+"&status="+status
        })
        .then(()=>location.reload());
    }

    /* =========================
       DETAIL MODAL
    ========================= */
    function viewDetail(id){
        const modal = document.getElementById('detailModal');
        const content = document.getElementById('detailContent');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        content.innerHTML = `
            <div class="text-center py-8">
                <i class="fas fa-spinner fa-spin text-2xl text-red-500"></i>
                <p class="mt-2 text-gray-500">Memuat data...</p>
            </div>
        `;
        fetch("get_detail.php?id=" + id)
        .then(res => res.json())
        .then(res => {
            if(res.status !== "success"){
                content.innerHTML = `<div class="text-center py-8 text-red-500">Data tidak ditemukan</div>`;
                return;
            }
            let d = res.data;
            content.innerHTML = `
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">ID Pemesanan</p>
                        <p class="font-semibold text-gray-800">#${d.id}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">Nama Pelanggan</p>
                        <p class="font-semibold text-gray-800">${d.nama}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">No Telepon</p>
                        <p class="text-gray-800">${d.no_telepon || '-'}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">Email</p>
                        <p class="text-gray-800">${d.email}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">Durasi</p>
                        <p class="text-gray-800">${d.durasi} Hari</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">Metode Pembayaran</p>
                        <p class="text-gray-800">${d.pembayaran}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">Tanggal Mulai</p>
                        <p class="text-gray-800">${d.tanggal_mulai}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">Tanggal Kembali</p>
                        <p class="text-gray-800">${d.tanggal_kembali}</p>
                    </div>
                    <div class="col-span-2 bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">Alamat</p>
                        <p class="text-gray-800">${d.alamat}</p>
                    </div>
                    <div class="col-span-2 bg-gradient-to-r from-red-50 to-orange-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">Total Pembayaran</p>
                        <p class="font-bold text-red-600 text-xl">Rp ${Number(d.total).toLocaleString('id-ID')}</p>
                    </div>
                    <div class="col-span-2 bg-gray-50 p-3 rounded-lg">
                        <p class="text-gray-500 text-xs">Status</p>
                        <span class="status-badge ${d.status === 'selesai' ? 'status-selesai' : (d.status === 'proses' ? 'status-proses' : 'status-batal')}">
                            ${d.status.charAt(0).toUpperCase() + d.status.slice(1)}
                        </span>
                    </div>
                </div>
            `;
        });
    }

    function closeDetailModal(){
        const modal=document.getElementById('detailModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    /* =========================
       BAYAR DENDA
    ========================= */
    function bayarDenda(id){
        if(confirm("Bayar denda?")){
            fetch("bayar_denda.php?id="+id)
            .then(()=>location.reload());
        }
    }

    // Close modals when clicking outside
    window.onclick = function(event) {
        const detailModal = document.getElementById('detailModal');
        const statusModal = document.getElementById('statusModal');
        if (event.target === detailModal) closeDetailModal();
        if (event.target === statusModal) closeModal();
    }
    </script>
</body>
</html>