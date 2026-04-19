<?php
include "../../koneksi.php";

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM kamera WHERE id=$id");
$row = $data->fetch_assoc();

if(isset($_POST['update'])){
    $conn->query("UPDATE kamera SET
    nama='$_POST[nama]',
    merk='$_POST[merk]',
    baterai='$_POST[baterai]',
    stok='$_POST[stok]',
    tipe='$_POST[tipe]',
    resolusi='$_POST[resolusi]',
    berat='$_POST[berat]',
    harga_sewa='$_POST[harga_sewa]'
    WHERE id=$id");

    header("Location:../list/kamera.php");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kamera - Dashboard</title>
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
        
        .input-focus:focus {
            transform: scale(1.02);
            transition: all 0.2s ease;
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #dc2626, #991b1b);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .btn-submit {
            transition: all 0.3s ease;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(220, 38, 38, 0.3);
        }
        
        .btn-cancel {
            transition: all 0.3s ease;
        }
        
        .btn-cancel:hover {
            transform: translateY(-2px);
            background: #f8f9fa;
        }
        
        /* Scrollbar modern */
        ::-webkit-scrollbar {
            width: 8px;
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
    </style>
</head>
<body class="min-h-screen">
    <div class="max-w-5xl mx-auto py-8 px-4">
        
        <!-- Header dengan animasi -->
        <div class="mb-8 animate-slideIn">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center space-x-3 mb-2">
                        <div class="bg-gradient-to-r from-red-500 to-red-700 p-2 rounded-xl shadow-lg">
                            <i class="fas fa-edit text-white text-xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent">Edit <span class="gradient-text">Kamera</span></h1>
                    </div>
                    <p class="text-gray-600 ml-1">ID Kamera: <span class="font-semibold text-red-600">#<?= $row['id'] ?></span></p>
                </div>
                <a href="../list/kamera.php" class="inline-flex items-center px-5 py-2.5 text-gray-700 bg-white border border-gray-300 rounded-xl hover:bg-gray-50 transition-all duration-300 hover:shadow-md group">
                    <i class="fas fa-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Daftar
                </a>
            </div>
            
            <!-- Info Kamera -->
            <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-2xl p-5 mt-6 animate-fadeInUp">
                <div class="flex items-center">
                    <div class="h-14 w-14 rounded-xl bg-gradient-to-r from-red-500 to-red-700 flex items-center justify-center mr-4 shadow-md">
                        <i class="fas fa-camera text-white text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-800 text-lg"><?= htmlspecialchars($row['nama']) ?></h3>
                        <p class="text-sm text-gray-600 mt-1">
                            <i class="fas fa-tag mr-1 text-red-500"></i> Merk: <?= htmlspecialchars($row['merk']) ?> 
                            <span class="mx-2">•</span>
                            <i class="fas fa-camera-retro mr-1 text-red-500"></i> Tipe: <?= htmlspecialchars($row['tipe']) ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Edit -->
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-fadeInUp" style="animation-delay: 0.1s">
            
            <!-- Form Header -->
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                <div class="flex items-center">
                    <div class="bg-red-100 rounded-xl p-2 mr-3">
                        <i class="fas fa-pen text-red-500"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Form Edit Data Kamera</h2>
                        <p class="text-sm text-gray-500 mt-0.5">Perbarui informasi kamera di bawah ini</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <form method="POST" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Kolom Kiri -->
                    <div class="space-y-5">
                        <!-- Nama Kamera -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-camera text-red-500 mr-2"></i>
                                Nama Kamera <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" required
                                   class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300"
                                   placeholder="Masukkan nama kamera">
                        </div>

                        <!-- Merk -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tag text-red-500 mr-2"></i>
                                Merk <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="merk" value="<?= htmlspecialchars($row['merk']) ?>" required
                                   class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300"
                                   placeholder="Contoh: Canon, Nikon, Sony">
                        </div>

                        <!-- Tipe -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-camera-retro text-red-500 mr-2"></i>
                                Tipe Kamera <span class="text-red-500">*</span>
                            </label>
                            <select name="tipe" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300 bg-white cursor-pointer">
                                <option value="DSLR" <?= $row['tipe'] == 'DSLR' ? 'selected' : '' ?>>📷 DSLR</option>
                                <option value="Mirrorless" <?= $row['tipe'] == 'Mirrorless' ? 'selected' : '' ?>>📸 Mirrorless</option>
                                <option value="Point & Shoot" <?= $row['tipe'] == 'Point & Shoot' ? 'selected' : '' ?>>🎥 Point & Shoot</option>
                                <option value="Action Camera" <?= $row['tipe'] == 'Action Camera' ? 'selected' : '' ?>>🏃 Action Camera</option>
                                <option value="Medium Format" <?= $row['tipe'] == 'Medium Format' ? 'selected' : '' ?>>📏 Medium Format</option>
                            </select>
                        </div>

                        <!-- Resolusi -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-expand-alt text-red-500 mr-2"></i>
                                Resolusi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="resolusi" value="<?= htmlspecialchars($row['resolusi']) ?>" required
                                   class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300"
                                   placeholder="Contoh: 45 MP, 24 MP">
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-5">
                        <!-- Baterai -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-battery-full text-red-500 mr-2"></i>
                                Tipe Baterai
                            </label>
                            <input type="text" name="baterai" value="<?= htmlspecialchars($row['baterai']) ?>"
                                   class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300"
                                   placeholder="Contoh: LP-E6NH">
                        </div>

                        <!-- Stok -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-boxes text-red-500 mr-2"></i>
                                Stok <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="stok" value="<?= htmlspecialchars($row['stok']) ?>" required min="0"
                                   class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300"
                                   placeholder="Jumlah stok tersedia">
                        </div>

                        <!-- Berat -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-weight-hanging text-red-500 mr-2"></i>
                                Berat (gram)
                            </label>
                            <div class="relative">
                                <input type="text" name="berat" value="<?= htmlspecialchars($row['berat']) ?>"
                                       class="input-focus w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300"
                                       placeholder="Contoh: 650">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">gram</span>
                                </div>
                            </div>
                        </div>

                        <!-- Harga Sewa -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-money-bill-wave text-red-500 mr-2"></i>
                                Harga Sewa/Hari <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 font-medium">Rp</span>
                                </span>
                                <input type="number" name="harga_sewa" value="<?= htmlspecialchars($row['harga_sewa']) ?>" required min="0"
                                       class="pl-12 w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-all duration-300"
                                       placeholder="Harga sewa per hari">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                    <span class="text-gray-500 text-sm">/hari</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Preview Harga -->
                <div class="mt-6 p-5 bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-2xl">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="text-center sm:text-left">
                            <p class="text-sm font-medium text-red-700">
                                <i class="fas fa-tag mr-1"></i> Harga Sewa Saat Ini
                            </p>
                            <p class="text-2xl font-bold text-red-700 mt-1">
                                Rp <?= number_format($row['harga_sewa'], 0, ',', '.') ?> 
                                <span class="text-sm font-normal text-red-500">/hari</span>
                            </p>
                        </div>
                        <div class="w-px h-12 bg-red-200 hidden sm:block"></div>
                        <div class="text-center sm:text-right">
                            <p class="text-sm font-medium text-red-700">
                                <i class="fas fa-calculator mr-1"></i> Total Nilai Stok
                            </p>
                            <p class="text-2xl font-bold text-red-700 mt-1">
                                Rp <?= number_format($row['harga_sewa'] * $row['stok'], 0, ',', '.') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <a href="../list/kamera.php" class="btn-cancel px-6 py-3 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-300 text-center font-medium">
                            <i class="fas fa-times mr-2"></i>
                            Batalkan
                        </a>
                        <button type="submit" name="update" 
                                class="btn-submit px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-300 shadow-md font-medium">
                            <i class="fas fa-save mr-2"></i>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Catatan Penting -->
        <div class="mt-6 p-5 bg-yellow-50 border border-yellow-200 rounded-2xl animate-fadeInUp" style="animation-delay: 0.2s">
            <div class="flex">
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-xl bg-yellow-100 flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-bold text-yellow-800">Penting!</h3>
                    <div class="mt-2 text-sm text-yellow-700">
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Pastikan semua data yang diubah sudah <span class="font-semibold">benar</span> sebelum disimpan</li>
                            <li>Perubahan akan <span class="font-semibold">langsung diterapkan</span> ke sistem</li>
                            <li>Field dengan tanda <span class="text-red-600 font-semibold">*</span> wajib diisi</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="mt-8 pt-6 border-t border-gray-200 text-center text-gray-400 text-sm">
            <i class="far fa-copyright"></i> <?= date('Y') ?> Rental Kamera System - Edit Kamera
            <span class="mx-2">•</span>
            <i class="fas fa-heart text-red-400 text-xs"></i>
        </footer>
        
    </div>

    <script>
        // Format input harga saat blur
        const hargaInput = document.querySelector('input[name="harga_sewa"]');
        const stokInput = document.querySelector('input[name="stok"]');
        
        function formatNumber(num) {
            return new Intl.NumberFormat('id-ID').format(num);
        }
        
        function updatePreview() {
            let harga = parseInt(hargaInput.value.replace(/[^0-9]/g, '')) || 0;
            let stok = parseInt(stokInput.value) || 0;
            
            const hargaPreview = document.querySelector('.text-2xl.font-bold.text-red-700');
            const totalPreview = document.querySelectorAll('.text-2xl.font-bold.text-red-700')[1];
            
            if (hargaPreview && totalPreview) {
                hargaPreview.innerHTML = 'Rp ' + formatNumber(harga) + ' <span class="text-sm font-normal text-red-500">/hari</span>';
                totalPreview.innerHTML = 'Rp ' + formatNumber(harga * stok);
            }
        }
        
        if (hargaInput) {
            hargaInput.addEventListener('input', updatePreview);
        }
        
        if (stokInput) {
            stokInput.addEventListener('input', updatePreview);
        }
        
        // Confirmation sebelum submit
        document.querySelector('form')?.addEventListener('submit', function(e) {
            if (!confirm('⚠️ Simpan perubahan data kamera?')) {
                e.preventDefault();
            }
        });
        
        // Auto focus ke field pertama
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="nama"]')?.focus();
        });
    </script>
</body>
</html>