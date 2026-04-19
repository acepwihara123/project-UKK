<?php
include "../koneksi.php";

// ambil kamera_id dari URL
$kamera_id = $_GET['kamera_id'] ?? 0;

// ambil data kamera
$query = mysqli_query($conn, "SELECT * FROM kamera WHERE id = '$kamera_id'");
$kamera = mysqli_fetch_assoc($query);

// kalau kamera tidak ditemukan
if (!$kamera) {
    die("Data kamera tidak ditemukan");
}

// ambil data lensa dengan merk terkenal
$lensa_query = mysqli_query($conn, "SELECT * FROM lensa WHERE stok > 0 AND status = 'aktif' ORDER BY merk, nama_lensa");
$lensa_list = mysqli_fetch_all($lensa_query, MYSQLI_ASSOC);

// Jika tidak ada data lensa di database, gunakan data default
if (empty($lensa_list)) {
    $lensa_list = [
        ['id' => 1, 'nama_lensa' => 'EF 24-70mm f/2.8L II USM', 'merk' => 'Canon', 'harga_sewa' => 150000],
        ['id' => 2, 'nama_lensa' => 'EF 50mm f/1.8 STM', 'merk' => 'Canon', 'harga_sewa' => 50000],
        ['id' => 3, 'nama_lensa' => 'EF 70-200mm f/2.8L IS III USM', 'merk' => 'Canon', 'harga_sewa' => 200000],
        ['id' => 4, 'nama_lensa' => 'AF-S 24-70mm f/2.8E ED VR', 'merk' => 'Nikon', 'harga_sewa' => 150000],
        ['id' => 5, 'nama_lensa' => 'AF-S 50mm f/1.8G', 'merk' => 'Nikon', 'harga_sewa' => 50000],
        ['id' => 6, 'nama_lensa' => 'AF-S 70-200mm f/2.8E FL ED VR', 'merk' => 'Nikon', 'harga_sewa' => 200000],
        ['id' => 7, 'nama_lensa' => 'FE 24-70mm f/2.8 GM II', 'merk' => 'Sony', 'harga_sewa' => 180000],
        ['id' => 8, 'nama_lensa' => 'FE 50mm f/1.2 GM', 'merk' => 'Sony', 'harga_sewa' => 120000],
        ['id' => 9, 'nama_lensa' => 'FE 70-200mm f/2.8 GM OSS II', 'merk' => 'Sony', 'harga_sewa' => 220000],
        ['id' => 10, 'nama_lensa' => 'SP 24-70mm f/2.8 Di VC USD G2', 'merk' => 'Tamron', 'harga_sewa' => 120000],
        ['id' => 11, 'nama_lensa' => 'SP 70-200mm f/2.8 Di VC USD G2', 'merk' => 'Tamron', 'harga_sewa' => 160000],
        ['id' => 12, 'nama_lensa' => '17-50mm f/2.8 EX DC OS HSM', 'merk' => 'Sigma', 'harga_sewa' => 100000],
        ['id' => 13, 'nama_lensa' => '24-70mm f/2.8 DG OS HSM Art', 'merk' => 'Sigma', 'harga_sewa' => 140000],
    ];
}

// Kelompokkan lensa berdasarkan merk
$lensa_by_merk = [];
foreach ($lensa_list as $lensa) {
    $merk = $lensa['merk'];
    if (!isset($lensa_by_merk[$merk])) {
        $lensa_by_merk[$merk] = [];
    }
    $lensa_by_merk[$merk][] = $lensa;
}

// data tripod
$tripod_list = [
    ['id' => 1, 'nama' => 'Tanpa Tripod', 'harga' => 0, 'icon' => 'fa-times-circle'],
    ['id' => 2, 'nama' => 'Tripod Standard', 'harga' => 50000, 'icon' => 'fa-camera-stand'],
    ['id' => 3, 'nama' => 'Tripod Professional', 'harga' => 100000, 'icon' => 'fa-camera-stand'],
    ['id' => 4, 'nama' => 'Tripod Carbon Fiber', 'harga' => 150000, 'icon' => 'fa-camera-stand']
];

$harga_per_hari = $kamera['harga_sewa'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pemesanan Sewa Kamera</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
    * {
        font-family: 'Inter', sans-serif;
    }
    .input-focus:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .option-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .option-card:hover {
        border-color: #3b82f6;
        background-color: #eff6ff;
        transform: translateY(-2px);
    }
    .option-card.selected {
        border-color: #3b82f6;
        background-color: #dbeafe;
    }
    .lensa-group {
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 12px;
        overflow: hidden;
    }
    .lensa-group-header {
        background-color: #f9fafb;
        padding: 10px 15px;
        font-weight: 600;
        border-bottom: 1px solid #e5e7eb;
        cursor: pointer;
    }
    .lensa-group-header:hover {
        background-color: #f3f4f6;
    }
    .lensa-items {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    .lensa-items.open {
        max-height: 500px;
    }
    .lensa-item {
        padding: 10px 15px;
        border-bottom: 1px solid #f3f4f6;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .lensa-item:hover {
        background-color: #eff6ff;
    }
    .lensa-item.selected {
        background-color: #dbeafe;
        border-left: 3px solid #3b82f6;
    }
</style>
</head>
<body class="bg-gray-50">
<!-- Navigation -->
<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center">
                <a href="index.php" class="flex items-center">
                    <i class="fas fa-camera text-red-600 text-2xl mr-2"></i>
                    <span class="text-xl font-bold text-gray-900">Rental<span class="text-red-600">Kamera</span></span>
                </a>
            </div>
            <div class="flex items-center space-x-4">
                <a href="index.php" class="text-gray-700 hover:text-red-600 font-medium">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex flex-col lg:flex-row gap-8">
        <div class="max-w-2xl mx-auto bg-white p-8 rounded-xl shadow">
            <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">
                <i class="fas fa-camera text-primary mr-2"></i>
                Form Pemesanan Sewa
            </h2>

            <!-- DATA KAMERA -->
            <div class="flex gap-4 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                <img src="../admin/uploads/<?= $kamera['gambar'] ?>" 
                     class="w-32 h-32 object-cover rounded"
                     onerror="this.src='https://via.placeholder.com/150?text=Kamera'">
                <div>
                    <h3 class="text-xl font-bold text-gray-800"><?= htmlspecialchars($kamera['nama']) ?></h3>
                    <p class="text-gray-600 mt-1">
                        <i class="fas fa-tag text-primary mr-1"></i>
                        Merk: <?= htmlspecialchars($kamera['merk']) ?>
                    </p>
                    <p class="text-gray-600 mt-1">
                        <i class="fas fa-money-bill-wave text-green-600 mr-1"></i>
                        Harga sewa / hari:
                        <span class="font-semibold text-green-600">
                            Rp <?= number_format($kamera['harga_sewa'], 0, ",", ".") ?>.000
                        </span>
                    </p>
                    <p class="text-gray-600 mt-1">
                        <i class="fas fa-box text-blue-600 mr-1"></i>
                        Stok: <?= $kamera['stok'] ?> unit
                    </p>
                </div>
            </div>

            <form method="POST" action="proses_pemesanan.php" class="space-y-4" id="pemesananForm">
                <!-- kamera id -->
                <input type="hidden" name="kamera_id" value="<?= $kamera_id ?>">

                <!-- nama -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">
                        <i class="fas fa-user mr-1"></i> Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nama" required 
                           class="w-full border border-gray-300 p-2.5 rounded-lg focus:outline-none input-focus"
                           placeholder="Masukkan nama lengkap">
                </div>

                <!-- telepon -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">
                        <i class="fas fa-phone mr-1"></i> No Telepon <span class="text-red-500">*</span>
                    </label>
                    <input type="tel" name="no_telepon" required 
                           class="w-full border border-gray-300 p-2.5 rounded-lg focus:outline-none input-focus"
                           placeholder="08xxxxxxxxxx"
                           pattern="[0-9]{10,13}">
                </div>

                <!-- email -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">
                        <i class="fas fa-envelope mr-1"></i> Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" required 
                           class="w-full border border-gray-300 p-2.5 rounded-lg focus:outline-none input-focus"
                           placeholder="nama@email.com">
                </div>

                <!-- alamat -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">
                        <i class="fas fa-map-marker-alt mr-1"></i> Alamat Detail
                    </label>
                    <textarea name="alamat" rows="2" 
                              class="w-full border border-gray-300 p-2.5 rounded-lg focus:outline-none input-focus"
                              placeholder="Masukkan alamat lengkap"></textarea>
                </div>

                <!-- Pilihan Lensa dengan Merk Terkenal -->
                <div class="border-t border-gray-200 pt-4">
                    <label class="block text-gray-700 font-medium mb-3">
                        <i class="fas fa-lens mr-1 text-primary"></i> Pilih Lensa (opsional)
                    </label>
                    
                    <div id="lensaContainer">
                        <?php foreach($lensa_by_merk as $merk => $lensa_merk): ?>
                        <div class="lensa-group">
                            <div class="lensa-group-header" onclick="toggleGroup(this)">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <?php 
                                        $merk_icon = [
                                            'Canon' => 'fab fa-canon',
                                            'Nikon' => 'fab fa-nikon',
                                            'Sony' => 'fab fa-sony',
                                            'Tamron' => 'fas fa-camera',
                                            'Sigma' => 'fas fa-camera'
                                        ];
                                        $icon = $merk_icon[$merk] ?? 'fas fa-camera';
                                        ?>
                                        <i class="<?= $icon ?> text-primary mr-2"></i>
                                        <span class="font-semibold"><?= htmlspecialchars($merk) ?></span>
                                        <span class="text-xs text-gray-500 ml-2">(<?= count($lensa_merk) ?> lensa)</span>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            <div class="lensa-items">
                                <?php foreach($lensa_merk as $lensa): ?>
                                <div class="lensa-item" data-lensa-id="<?= $lensa['id'] ?>" data-lensa-harga="<?= $lensa['harga_sewa'] ?>" data-lensa-nama="<?= htmlspecialchars($lensa['nama_lensa']) ?>" onclick="selectLensa(this, <?= $lensa['id'] ?>, <?= $lensa['harga_sewa'] ?>, '<?= addslashes($lensa['nama_lensa']) ?>')">
                                    <div class="flex justify-between items-center">
                                        <div>
                                            <p class="font-medium text-gray-800"><?= htmlspecialchars($lensa['nama_lensa']) ?></p>
                                            <p class="text-xs text-gray-500">Stok tersedia</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-semibold text-primary">Rp <?= number_format($lensa['harga_sewa'], 0, ",", ".") ?></p>
                                            <p class="text-xs text-gray-500">/hari</p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <input type="hidden" name="lensa_id" id="lensa_id" value="0">
                    <input type="hidden" name="lensa_harga" id="lensa_harga_input" value="0">
                    <div id="selectedLensaInfo" class="mt-2 text-sm text-green-600 hidden">
                        <i class="fas fa-check-circle mr-1"></i> Lensa terpilih: <span id="selectedLensaNama"></span> - <span id="selectedLensaHarga"></span>/hari
                    </div>
                </div>

                <!-- Pilihan Tripod -->
                <div class="border-t border-gray-200 pt-4">
                    <label class="block text-gray-700 font-medium mb-3">
                        <i class="fas fa-camera-stand mr-1 text-primary"></i> Pilih Tripod (opsional)
                    </label>
                    
                    <div class="grid grid-cols-4 gap-2">
                        <?php foreach($tripod_list as $tripod): ?>
                        <div class="option-card border rounded-lg p-2 text-center <?= $tripod['id'] == 1 ? 'selected' : '' ?>" 
                             data-tripod-id="<?= $tripod['id'] ?>" 
                             data-tripod-harga="<?= $tripod['harga'] ?>" 
                             data-tripod-nama="<?= htmlspecialchars($tripod['nama']) ?>"
                             onclick="selectTripod(this, <?= $tripod['id'] ?>, <?= $tripod['harga'] ?>, '<?= addslashes($tripod['nama']) ?>')">
                            <i class="fas <?= $tripod['icon'] ?> text-primary text-xl mb-1"></i>
                            <p class="text-xs font-medium"><?= htmlspecialchars($tripod['nama']) ?></p>
                            <?php if($tripod['harga'] > 0): ?>
                            <p class="text-xs text-gray-500">Rp <?= number_format($tripod['harga'], 0, ",", ".") ?></p>
                            <?php else: ?>
                            <p class="text-xs text-green-500">Gratis</p>
                            <?php endif; ?>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <input type="hidden" name="tripod_id" id="tripod_id" value="1">
                    <input type="hidden" name="tripod_harga" id="tripod_harga_input" value="0">
                </div>

                <!-- pembayaran -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">
                        <i class="fas fa-credit-card mr-1"></i> Metode Pembayaran <span class="text-red-500">*</span>
                    </label>
                    <select name="pembayaran" required class="w-full border border-gray-300 p-2.5 rounded-lg focus:outline-none input-focus">
                        <option value="">-- Pilih Metode Pembayaran --</option>
                        <option value="transfer">Transfer</option>
                        <option value="cod">Cash </option>
                    </select>
                </div>

                <!-- tanggal mulai -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">
                        <i class="fas fa-calendar-alt mr-1"></i> Tanggal Mulai Sewa <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" required 
                           class="w-full border border-gray-300 p-2.5 rounded-lg focus:outline-none input-focus">
                </div>

                <!-- durasi -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">
                        <i class="fas fa-clock mr-1"></i> Durasi Sewa (hari) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="durasi" name="durasi" min="1" required 
                           class="w-full border border-gray-300 p-2.5 rounded-lg focus:outline-none input-focus"
                           placeholder="Masukkan jumlah hari">
                </div>

                <!-- tanggal pengembalian (otomatis) -->
                <div>
                    <label class="block text-gray-700 font-medium mb-1">
                        <i class="fas fa-calendar-check mr-1"></i> Tanggal Pengembalian
                    </label>
                    <input type="date" id="tanggal_kembali" name="tanggal_kembali" readonly
                           class="w-full border border-gray-300 p-2.5 rounded-lg bg-gray-100 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">*Tanggal pengembalian akan dihitung otomatis</p>
                </div>

                <!-- total -->
                <div class="bg-gray-100 p-4 rounded-lg">
                    <p class="text-gray-700">
                        <i class="fas fa-tag text-primary mr-1"></i>
                        Harga Kamera per hari: 
                        <b class="text-primary">Rp <?= number_format($harga_per_hari, 0, ",", ".") ?>.000</b>
                    </p>
                    <p class="text-gray-700 mt-1" id="lensaText" style="display: none;">
                        <i class="fas fa-lens mr-1"></i>
                        Lensa: <span id="lensaHargaText">0</span>/hari
                    </p>
                    <p class="text-gray-700 mt-1" id="tripodText" style="display: none;">
                        <i class="fas fa-camera-stand mr-1"></i>
                        Tripod: <span id="tripodHargaText">0</span>/hari
                    </p>
                    <p class="text-gray-700 mt-2">
                        <i class="fas fa-calendar-week mr-1"></i>
                        Durasi: <span id="durasiTampil">0</span> hari
                    </p>
                    <p class="text-lg font-bold mt-2 text-gray-800">
                        <i class="fas fa-money-bill-wave text-green-600 mr-1"></i>
                        Total: <span id="totalText" class="text-green-600">Rp 0</span>
                    </p>
                </div>

                <input type="hidden" name="total" id="totalInput">

                <button type="submit"
                        class="w-full bg-primary text-white p-3 rounded-lg hover:bg-secondary transition duration-300 font-semibold">
                    <i class="fas fa-shopping-cart mr-2"></i> Pesan Sekarang
                </button>
            </form>
        </div>

        <!-- Info Sidebar -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-xl shadow p-6 sticky top-8">
                <h3 class="text-lg font-bold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-primary mr-2"></i>
                    Informasi Pemesanan
                </h3>
                <ul class="space-y-3 text-gray-600">
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Pastikan data yang diisi sudah benar</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Konfirmasi akan dikirim via email/WhatsApp</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Pembayaran dilakukan setelah pesanan dikonfirmasi</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                        <span>Pengembalian kamera tepat waktu</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
const hargaKamera = <?= $harga_per_hari ?>;
const durasiInput = document.getElementById("durasi");
const tanggalMulai = document.getElementById("tanggal_mulai");
const tanggalKembali = document.getElementById("tanggal_kembali");
const totalText = document.getElementById("totalText");
const totalInput = document.getElementById("totalInput");
const durasiTampil = document.getElementById("durasiTampil");

let hargaLensa = 0;
let hargaTripod = 0;
let lensaId = 0;
let tripodId = 1;

// Set min date to today
const today = new Date().toISOString().split('T')[0];
tanggalMulai.min = today;
tanggalMulai.value = today;

// Fungsi hitung tanggal kembali
function hitungTanggalKembali() {
    let durasi = parseInt(durasiInput.value) || 0;
    let mulai = tanggalMulai.value;
    
    if (mulai && durasi > 0) {
        let startDate = new Date(mulai);
        let endDate = new Date(startDate);
        endDate.setDate(startDate.getDate() + durasi);
        
        let year = endDate.getFullYear();
        let month = String(endDate.getMonth() + 1).padStart(2, '0');
        let day = String(endDate.getDate()).padStart(2, '0');
        
        tanggalKembali.value = `${year}-${month}-${day}`;
    } else {
        tanggalKembali.value = '';
    }
}

// Fungsi hitung total
function hitungTotal() {
    let durasi = parseInt(durasiInput.value) || 0;
    let totalKamera = hargaKamera * durasi;
    let totalLensa = hargaLensa * durasi;
    let totalTripod = hargaTripod * durasi;
    let total = totalKamera + totalLensa + totalTripod;
    
    durasiTampil.innerText = durasi;
    totalText.innerText = "Rp " + total.toLocaleString("id-ID");
    totalInput.value = total;
    
    hitungTanggalKembali();
}

// Fungsi toggle grup lensa
function toggleGroup(header) {
    let items = header.nextElementSibling;
    let icon = header.querySelector('.fa-chevron-down');
    
    if (items.classList.contains('open')) {
        items.classList.remove('open');
        icon.style.transform = 'rotate(0deg';
    } else {
        items.classList.add('open');
        icon.style.transform = 'rotate(180deg)';
    }
}

// Fungsi select lensa
function selectLensa(element, id, harga, nama) {
    // Remove selected class from all lensa items
    document.querySelectorAll('.lensa-item').forEach(item => {
        item.classList.remove('selected');
    });
    
    // Add selected class to clicked element
    element.classList.add('selected');
    
    lensaId = id;
    hargaLensa = harga;
    document.getElementById('lensa_id').value = id;
    document.getElementById('lensa_harga_input').value = harga;
    
    if (hargaLensa > 0) {
        document.getElementById('lensaHargaText').innerText = "Rp " + hargaLensa.toLocaleString("id-ID");
        document.getElementById('lensaText').style.display = "block";
        document.getElementById('selectedLensaNama').innerText = nama;
        document.getElementById('selectedLensaHarga').innerText = "Rp " + hargaLensa.toLocaleString("id-ID");
        document.getElementById('selectedLensaInfo').classList.remove('hidden');
    } else {
        document.getElementById('lensaText').style.display = "none";
        document.getElementById('selectedLensaInfo').classList.add('hidden');
    }
    
    hitungTotal();
}

// Fungsi select tripod
function selectTripod(element, id, harga, nama) {
    // Remove selected class from all tripod options
    document.querySelectorAll('.option-card').forEach(card => {
        card.classList.remove('selected');
        card.style.borderColor = '#e5e7eb';
        card.style.backgroundColor = 'white';
    });
    
    // Add selected class to clicked element
    element.classList.add('selected');
    element.style.borderColor = '#3b82f6';
    element.style.backgroundColor = '#dbeafe';
    
    tripodId = id;
    hargaTripod = harga;
    document.getElementById('tripod_id').value = id;
    document.getElementById('tripod_harga_input').value = harga;
    
    if (hargaTripod > 0) {
        document.getElementById('tripodHargaText').innerText = "Rp " + hargaTripod.toLocaleString("id-ID");
        document.getElementById('tripodText').style.display = "block";
    } else {
        document.getElementById('tripodText').style.display = "none";
    }
    
    hitungTotal();
}

// Buka semua grup lensa secara default
document.querySelectorAll('.lensa-items').forEach(items => {
    items.classList.add('open');
});

// Event listeners
durasiInput.addEventListener("input", () => {
    hitungTotal();
});

tanggalMulai.addEventListener("change", () => {
    hitungTanggalKembali();
});

// Hitung awal
hitungTotal();

// Validasi form sebelum submit
document.getElementById("pemesananForm").addEventListener("submit", function(e) {
    let durasi = parseInt(durasiInput.value) || 0;
    let tanggalMulaiVal = tanggalMulai.value;
    
    if (durasi <= 0) {
        e.preventDefault();
        alert("Durasi sewa minimal 1 hari!");
        return false;
    }
    
    if (!tanggalMulaiVal) {
        e.preventDefault();
        alert("Silakan pilih tanggal mulai sewa!");
        return false;
    }
    
    return true;
});
</script>

<style>
    .bg-primary { background-color: #3b82f6; }
    .bg-secondary { background-color: #1e40af; }
    .text-primary { color: #3b82f6; }
    .hover\:bg-secondary:hover { background-color: #1e40af; }
    .lensa-items {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out;
    }
    .lensa-items.open {
        max-height: 500px;
    }
    .fa-chevron-down {
        transition: transform 0.3s ease;
    }
    .lensa-group-header.open .fa-chevron-down {
        transform: rotate(180deg);
    }
</style>
</body>
</html>