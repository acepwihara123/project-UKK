<?php
session_start();
include "../../koneksi.php";

$total_transactions = 0; // anti notice ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kamera</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e40af',
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        info: '#06b6d4',
                        dark: '#1f2937',
                        light: '#f9fafb'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        body { 
            font-family: 'Inter', sans-serif;
            overflow: hidden;
        }
        
        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Custom scrollbar */
        .content-scrollable::-webkit-scrollbar {
            width: 8px;
        }
        
        .content-scrollable::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        .content-scrollable::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 10px;
        }
        
        .content-scrollable::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }
        
        /* Untuk konten utama yang discroll */
        .content-main {
            height: calc(100vh - 80px);
            overflow-y: auto;
        }
        
        /* Sidebar mobile penuh layar */
        .mobile-sidebar-full {
            height: 100vh;
            overflow-y: auto;
        }
        
        /* Hover effects */
        .hover-lift {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <header class="bg-white shadow-sm flex-shrink-0">
            <div class="px-6 py-4 flex justify-between items-center">
                <div class="flex items-center">
                    <div class="mr-4">
                        <i class="fas fa-camera text-2xl text-primary"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard Kamera</h1>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <i class="fas fa-bell text-gray-600 text-xl cursor-pointer hover:text-primary transition"></i>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="h-10 w-10 rounded-full bg-primary flex items-center justify-center text-white font-bold">AK</div>
                        <div>
                            <p class="font-medium text-gray-800">Admin User</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        
        <!-- Kontainer utama dengan sidebar dan konten -->
        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside class="w-64 bg-white shadow-sm p-6 hidden md:block flex-shrink-0" style="height: calc(100vh - 80px);">
                <nav>
                    <ul class="space-y-2">
                        <li>
                            <a href="../dashboard.php" class="flex items-center space-x-3 p-3 rounded-lg bg-primary text-white">
                                <i class="fas fa-home"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="../list/kamera.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">
                                <i class="fas fa-camera"></i>
                                <span>Kamera</span>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700 transition">
                                <i class="fas fa-shopping-cart"></i>
                                <span>Transaksi</span>
                            </a>
                        </li>
                       
                    </ul>
                </nav>
            </aside>
            
            <!-- Main Content -->
            <main class="flex-1 p-6 content-main content-scrollable fade-in">
                <!-- Header Konten -->
                <!-- Transactions Table -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kamera</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if ($total_transactions > 0): ?>
                                <?php while($trans = mysqli_fetch_assoc($query)): ?>
                                    <tr class="table-row">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #<?php echo $trans['id']; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?php echo date('d/m/Y H:i', strtotime($trans['tanggal'])); ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <div class="font-medium"><?php echo htmlspecialchars($trans['nama']); ?></div>
                                            <div class="text-xs"><?php echo htmlspecialchars($trans['telepon']); ?></div>
                                            <div class="text-xs text-gray-400"><?php echo htmlspecialchars($trans['email']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600">
                                            <div class="font-medium"><?php echo htmlspecialchars($trans['kamera_nama']); ?></div>
                                            <div class="text-xs"><?php echo htmlspecialchars($trans['merk']); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?php echo $trans['durasi']; ?> hari
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                            Rp <?php echo number_format($trans['total'], 0, ',', '.'); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                            <?php 
                                            $payment_methods = [
                                                'bank_transfer' => 'Bank Transfer',
                                                'ewallet' => 'E-Wallet',
                                                'qris' => 'QRIS',
                                                'cod' => 'COD'
                                            ];
                                            echo $payment_methods[$trans['pembayaran']] ?? ucfirst($trans['pembayaran']);
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="status-badge status-<?php echo $trans['status']; ?>">
                                                <?php 
                                                $status_text = [
                                                    'pending' => 'Pending',
                                                    'confirmed' => 'Confirmed',
                                                    'completed' => 'Completed',
                                                    'cancelled' => 'Cancelled'
                                                ];
                                                echo $status_text[$trans['status']] ?? ucfirst($trans['status']);
                                                ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <div class="flex space-x-2">
                                                <button onclick="openModal(<?php echo $trans['id']; ?>, '<?php echo $trans['status']; ?>')" 
                                                        class="text-blue-600 hover:text-blue-800" title="Edit Status">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button onclick="viewDetail(<?php echo $trans['id']; ?>)" 
                                                        class="text-green-600 hover:text-green-800" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button onclick="deleteTransaction(<?php echo $trans['id']; ?>, '<?php echo addslashes($trans['nama']); ?>')" 
                                                        class="text-red-600 hover:text-red-800" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                        <p>Tidak ada data pemesanan</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Table Footer -->
                <div class="bg-gray-50 px-6 py-3 border-t">
                    <p class="text-sm text-gray-600">Menampilkan <?php echo $total_transactions; ?> data pemesanan</p>
                </div>
            </div>
                
                <!-- Footer -->
                <footer class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-500 text-sm">
                    <p>© <?= date('Y') ?> Dashboard Kamera. Sistem Manajemen Penyewaan Kamera.</p>
                </footer>
            </main>
        </div>
    </div>
    
    <!-- Mobile Menu Button -->
    <div class="md:hidden fixed bottom-6 right-6 z-10">
        <button id="mobileMenuBtn" class="h-14 w-14 rounded-full bg-primary text-white shadow-lg flex items-center justify-center hover-lift">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>
    
    <!-- Mobile Sidebar -->
    <div id="mobileSidebar" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden">
        <div class="absolute right-0 top-0 h-full w-64 bg-white shadow-lg p-6 mobile-sidebar-full">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-xl font-bold text-gray-800">Menu</h2>
                <button id="closeMobileMenu" class="text-gray-500 hover:text-gray-800">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav>
                <ul class="space-y-2">
                    <li>
                        <a href="dashboard.php" class="flex items-center space-x-3 p-3 rounded-lg bg-primary text-white">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="../admin/list/kamera.php" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                            <i class="fas fa-camera"></i>
                            <span>Kamera</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                            <i class="fas fa-shopping-cart"></i>
                            <span>Transaksi</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                            <i class="fas fa-chart-bar"></i>
                            <span>Laporan</span>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-100 text-gray-700">
                            <i class="fas fa-cog"></i>
                            <span>Pengaturan</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    
    <script>
        // Mobile menu functionality
        document.getElementById('mobileMenuBtn').addEventListener('click', function() {
            document.getElementById('mobileSidebar').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });
        
        document.getElementById('closeMobileMenu').addEventListener('click', function() {
            document.getElementById('mobileSidebar').classList.add('hidden');
            document.body.style.overflow = '';
        });
        
        // Close mobile sidebar when clicking outside
        document.getElementById('mobileSidebar').addEventListener('click', function(e) {
            if (e.target.id === 'mobileSidebar') {
                document.getElementById('mobileSidebar').classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('keyup', function() {
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
        
        // Confirmation for delete
        document.querySelectorAll('a[href*="hapus/kamera.php"]').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Yakin ingin menghapus data ini?')) {
                    e.preventDefault();
                }
            });
        });
        
        // Add animation to table rows
        const tableRows = document.querySelectorAll('#tableBody tr');
        tableRows.forEach((row, index) => {
            row.style.animationDelay = `${index * 0.05}s`;
        });
    </script>
</body>
</html>