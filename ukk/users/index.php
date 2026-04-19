<?php
session_start();
include "../koneksi.php";

// Cek apakah user sudah login
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$userName = $isLoggedIn ? $_SESSION['user_name'] : '';
$userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';

$search = $_GET['search'] ?? '';

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $sql = "SELECT * FROM kamera
            WHERE (nama LIKE '%$search%'
            OR merk LIKE '%$search%')
            AND stok > 0";
} else {
    $sql = "SELECT * FROM kamera WHERE stok > 0 ORDER BY id DESC";
}

$query = mysqli_query($conn, $sql);
$total = mysqli_num_rows($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Kamera - Sewa Kamera Profesional</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }
        .hero-bg {
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('https://images.unsplash.com/photo-1516035069371-29a1b244cc32?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
            background-size: cover;
            background-position: center;
        }
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(220, 38, 38, 0.15);
        }
        .price-tag {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #dc2626;
            color: white;
            padding: 3px 10px;
            border-radius: 15px;
            font-weight: 600;
            font-size: 12px;
        }
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            z-index: 1000;
            min-width: 220px;
        }
        .dropdown-menu.show {
            display: block;
        }
        .user-avatar {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-3">
                <div class="flex items-center">
                    <div class="flex items-center">
                        <i class="fas fa-camera text-red-600 text-2xl mr-2"></i>
                        <span class="text-xl font-bold text-gray-900">Rental<span class="text-red-600">Kamera</span></span>
                    </div>
                </div>
                <div class="hidden md:flex space-x-6">
                    <a href="index.php" class="text-red-600 font-medium border-b-2 border-red-600">Home</a>
                   
                  
                </div>
                <div class="flex items-center space-x-3">
                    <?php if ($isLoggedIn): ?>
                        <div class="relative">
                            <button onclick="toggleDropdown()" class="flex items-center space-x-2 hover:bg-gray-100 rounded-full px-3 py-1.5 transition duration-300">
                                <div class="user-avatar w-8 h-8 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    <?php echo strtoupper(substr($userName, 0, 1)); ?>
                                </div>
                                <span class="text-gray-700 font-medium text-sm"><?php echo htmlspecialchars($userName); ?></span>
                                <i class="fas fa-chevron-down text-gray-500 text-xs"></i>
                            </button>
                            <div id="dropdownMenu" class="dropdown-menu">
                                <div class="py-2">
                                    <div class="px-4 py-3 border-b border-gray-100">
                                        <p class="text-sm text-gray-500">Login sebagai</p>
                                        <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($userName); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo htmlspecialchars($userEmail); ?></p>
                                    </div>
                                    <div class="border-t border-gray-100 my-1"></div>
                                    <a href="riwayat.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                                        <i class="fas fa-history w-5"></i>
                                        <span class="ml-3">Riwayat Pemesanan</span>
                                    </a>
                                    <a href="logout.php" class="flex items-center px-4 py-3 text-red-600 hover:bg-red-50 transition">
    <i class="fas fa-sign-out-alt w-5"></i>
    <span class="ml-3">Logout</span>
</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex space-x-2">
                            <a href="login.php" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1.5 px-4 rounded-full transition duration-300 text-sm">
                                <i class="fas fa-sign-in-alt mr-1"></i>Login
                            </a>
                            <a href="login.php?tab=register" class="border-2 border-red-600 text-red-600 hover:bg-red-600 hover:text-white font-medium py-1.5 px-4 rounded-full transition duration-300 text-sm">
                                <i class="fas fa-user-plus mr-1"></i>Daftar
                            </a>
                        </div>
                        <button onclick="alert('Silakan login terlebih dahulu untuk memesan kamera')" class="bg-red-600 hover:bg-red-700 text-white font-medium py-1.5 px-4 rounded-full transition duration-300 text-sm">
                            <i class="fas fa-calendar-alt mr-1"></i>Pesan
                        </button>
                    <?php endif; ?>
                    <button class="md:hidden text-gray-700">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-bg text-white py-16 md:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-3xl md:text-5xl font-bold mb-4">Sewa Kamera Profesional <span class="text-red-400">Terlengkap</span></h1>
            <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto">Tersedia berbagai jenis kamera DSLR dan Mirrorless dengan kualitas terbaik untuk kebutuhan fotografi Anda.</p>
            <form method="GET" class="mb-8 flex justify-center">
                <input type="text" name="search"
                    placeholder="Cari nama / merk kamera..."
                    value="<?= $_GET['search'] ?? '' ?>"
                    class="w-full md:w-1/2 border p-2 rounded-l-lg focus:outline-none text-black placeholder-gray-500 bg-white">
                <button type="submit"
                    class="bg-red-600 text-white px-5 rounded-r-lg hover:bg-red-700">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            
            <?php if (!empty($search)): ?>
                <div class="text-left mb-4">
                    <h2 class="text-xl font-bold">Hasil Pencarian: "<?= htmlspecialchars($search) ?>"</h2>
                    <p class="text-gray-300 text-sm">Ditemukan <?= $total ?> kamera</p>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <?php if($total > 0): ?>
                    <?php while($data = mysqli_fetch_assoc($query)) { ?>
                        <div class="bg-white rounded-xl overflow-hidden shadow-md card-hover">
                            <div class="relative">
                                <img class="w-full h-44 object-cover"
                                     src="../admin/uploads/<?php echo $data['gambar']; ?>"
                                     alt="<?php echo $data['nama']; ?>"
                                     onerror="this.src='https://images.unsplash.com/photo-1516035069371-29a1b244cc32?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                                <div class="price-tag">
                                    Rp <?php echo number_format($data['harga_sewa']); ?>.000/hari
                                </div>
                                <div class="absolute top-2 left-2">
                                    <?php if($data['stok'] > 0) { ?>
                                        <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                                            TERSEDIA
                                        </span>
                                    <?php } else { ?>
                                        <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                                            HABIS
                                        </span>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="p-3">
                                <h3 class="text-base font-bold text-gray-900 mb-1 truncate">
                                    <?php echo $data['nama']; ?>
                                </h3>
                                <p class="text-gray-600 text-xs mb-2">
                                    <i class="fas fa-tag mr-1"></i> Merk: <?php echo $data['merk']; ?>
                                </p>
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-700 text-xs font-medium">
                                        <i class="fas fa-box mr-1"></i> Stok: <?php echo $data['stok']; ?>
                                    </span>
                                    <?php if($data['stok'] > 0) { ?>
                                        <span class="text-green-600 text-xs font-semibold">
                                            ✔ Siap disewa
                                        </span>
                                    <?php } else { ?>
                                        <span class="text-red-600 text-xs font-semibold">
                                            ✖ Tidak tersedia
                                        </span>
                                    <?php } ?>
                                </div>
                                <?php if(isset($_SESSION['user_logged_in'])): ?>
                                    <a href="detail.php?id=<?php echo $data['id']; ?>" 
                                       class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-1.5 rounded-lg transition duration-300 text-center block text-sm">
                                       <i class="fas fa-shopping-cart mr-1"></i> Sewa Sekarang
                                    </a>
                                <?php else: ?>
                                    <a href="login.php" 
                                       class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-1.5 rounded-lg transition duration-300 text-center block text-sm">
                                       <i class="fas fa-shopping-cart mr-1"></i> Sewa Sekarang
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php } ?>
                <?php else: ?>
                    <div class="col-span-4 text-center py-12">
                        <i class="fas fa-camera text-5xl text-gray-400 mb-3"></i>
                        <p class="text-gray-500 text-lg">Tidak ada kamera tersedia</p>
                        <p class="text-gray-400 text-sm mt-1">Silakan cek kembali nanti</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="text-center mb-10">
            <p class="text-red-600 font-semibold text-base mb-1">KATEGORI KAMERA</p>
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-3">Temukan Kamera <span class="text-red-600">Terbaik</span> untuk Kebutuhan Anda</h2>
            <p class="text-gray-600 text-sm max-w-2xl mx-auto">Kami menyediakan berbagai jenis kamera dengan spesifikasi terbaru dan harga yang kompetitif.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            <?php 
            if (empty($search)) {
                $sql_all = "SELECT * FROM kamera WHERE stok > 0 ORDER BY id DESC LIMIT 8";
                $query_all = mysqli_query($conn, $sql_all);
                $total_all = mysqli_num_rows($query_all);
                
                if($total_all > 0) {
                    while($data = mysqli_fetch_assoc($query_all)) { 
            ?>
                <div class="bg-white rounded-xl overflow-hidden shadow-md card-hover">
                    <div class="relative">
                        <img class="w-full h-44 object-cover"
                             src="../admin/uploads/<?php echo $data['gambar']; ?>"
                             alt="<?php echo $data['nama']; ?>"
                             onerror="this.src='https://images.unsplash.com/photo-1516035069371-29a1b244cc32?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80'">
                        <div class="price-tag">
                            Rp <?php echo number_format($data['harga_sewa']); ?>/hari
                        </div>
                        <div class="absolute top-2 left-2">
                            <?php if($data['stok'] > 0) { ?>
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    TERSEDIA
                                </span>
                            <?php } else { ?>
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    HABIS
                                </span>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="p-3">
                        <h3 class="text-base font-bold text-gray-900 mb-1 truncate">
                            <?php echo $data['nama']; ?>
                        </h3>
                        <p class="text-gray-600 text-xs mb-2">
                            <i class="fas fa-tag mr-1"></i> Merk: <?php echo $data['merk']; ?>
                        </p>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-700 text-xs font-medium">
                                <i class="fas fa-box mr-1"></i> Stok: <?php echo $data['stok']; ?>
                            </span>
                            <?php if($data['stok'] > 0) { ?>
                                <span class="text-green-600 text-xs font-semibold">
                                    ✔ Siap disewa
                                </span>
                            <?php } else { ?>
                                <span class="text-red-600 text-xs font-semibold">
                                    ✖ Tidak tersedia
                                </span>
                            <?php } ?>
                        </div>
                        <a href="detail.php?id=<?php echo $data['id']; ?>" 
                           class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-1.5 rounded-lg transition duration-300 text-center block text-sm">
                           <i class="fas fa-shopping-cart mr-1"></i> Sewa Sekarang
                        </a>
                    </div>
                </div>
            <?php 
                    }
                } else {
                    echo '<div class="col-span-4 text-center py-12">
                            <i class="fas fa-camera text-5xl text-gray-400 mb-3"></i>
                            <p class="text-gray-500 text-lg">Belum ada kamera tersedia</p>
                            <p class="text-gray-400 text-sm mt-1">Silakan cek kembali nanti</p>
                          </div>';
                }
            }
            ?>
        </div>

        <!-- Info Section -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-5">
            <div class="bg-red-50 p-5 rounded-xl text-center">
                <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-shipping-fast text-red-600 text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Gratis Pengantaran</h3>
                <p class="text-gray-600 text-sm">Gratis pengantaran untuk wilayah Jabodetabek dengan pemesanan minimal 3 hari.</p>
            </div>
            
            <div class="bg-red-50 p-5 rounded-xl text-center">
                <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-shield-alt text-red-600 text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Asuransi Peralatan</h3>
                <p class="text-gray-600 text-sm">Semua peralatan dilindungi asuransi untuk rasa aman selama sewa.</p>
            </div>
            
            <div class="bg-red-50 p-5 rounded-xl text-center">
                <div class="bg-red-100 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-headset text-red-600 text-xl"></i>
                </div>
                <h3 class="text-base font-bold text-gray-900 mb-2">Dukungan 24/7</h3>
                <p class="text-gray-600 text-sm">Tim ahli siap membantu Anda 24 jam selama masa sewa.</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white pt-8 pb-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <div class="flex items-center mb-4">
                        <i class="fas fa-camera text-red-500 text-xl mr-2"></i>
                        <span class="text-lg font-bold">Rental<span class="text-red-500">Kamera</span></span>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">Menyediakan layanan sewa kamera profesional terlengkap.</p>
                    <div class="flex space-x-3">
                        <a href="#" class="bg-gray-800 hover:bg-red-600 w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="fab fa-instagram text-sm"></i>
                        </a>
                        <a href="#" class="bg-gray-800 hover:bg-red-600 w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="fab fa-facebook-f text-sm"></i>
                        </a>
                        <a href="#" class="bg-gray-800 hover:bg-red-600 w-8 h-8 rounded-full flex items-center justify-center">
                            <i class="fab fa-youtube text-sm"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-6 pt-4 text-center text-gray-500 text-xs">
                <p>&copy; 2026 RentalKamera.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/6281234567890" target="_blank" class="fixed bottom-6 right-6 bg-green-500 hover:bg-green-600 text-white w-12 h-12 rounded-full flex items-center justify-center shadow-lg z-50">
        <i class="fab fa-whatsapp text-xl"></i>
    </a>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownMenu');
            dropdown.classList.toggle('show');
        }
        
        window.onclick = function(event) {
            if (!event.target.closest('.relative')) {
                const dropdowns = document.getElementsByClassName('dropdown-menu');
                for (let i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('show')) {
                        dropdowns[i].classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>