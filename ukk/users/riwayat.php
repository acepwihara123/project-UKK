<?php
session_start();
include "../koneksi.php";

if (!isset($_SESSION['user_logged_in'])) {
    header("Location: login.php");
    exit();
}

$isLoggedIn = true;
$userName = $_SESSION['user_name'];
$userEmail = $_SESSION['user_email'];

$q = $conn->query("
    SELECT p.*, d.total_denda, d.hari_terlambat
    FROM pemesanan p
    LEFT JOIN denda d ON d.pemesanan_id = p.id
    WHERE p.email = '$userEmail'
    ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pemesanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<!-- NAVBAR -->
<nav class="bg-white shadow-md sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">

        <div class="flex items-center gap-2">
            <i class="fas fa-camera text-red-600 text-xl"></i>
            <span class="font-bold text-lg">RentalKamera</span>
        </div>

        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-700"><?= $userName ?></span>
            <a href="logout.php" class="text-red-500">Logout</a>
        </div>

    </div>
</nav>

<!-- CONTENT -->
<div class="max-w-4xl mx-auto p-6">

    <!-- BACK BUTTON -->
    <a href="index.php" 
    class="inline-block mb-4 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded">
        ← Kembali
    </a>

    <h1 class="text-2xl font-bold mb-6">Riwayat Pemesanan</h1>

    <div class="space-y-4">

    <?php while($data = $q->fetch_assoc()): ?>

    <div class="bg-white p-5 rounded-xl shadow hover:shadow-lg transition">

        <div class="flex justify-between items-center mb-2">
            <h2 class="font-bold text-lg"><?= $data['nama'] ?></h2>

            <?php if($data['total_denda'] > 0): ?>
                <span class="bg-red-100 text-red-600 px-3 py-1 rounded-full text-sm font-semibold">
                    Terlambat
                </span>
            <?php else: ?>
                <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">
                    <?= $data['status'] ?>
                </span>
            <?php endif; ?>
        </div>

        <p class="text-gray-600 text-sm mb-1">
            📅 Kembali: <?= $data['tanggal_kembali'] ?>
        </p>

        <p class="text-gray-600 text-sm mb-2">
            💰 Total: Rp <?= number_format($data['total'],0,',','.') ?>
        </p>

        <!-- DENDA -->
        <?php if($data['total_denda'] > 0): ?>
            <div class="bg-red-50 p-3 rounded mb-2">
                <p class="text-red-600 font-bold">
                    Denda: Rp <?= number_format($data['total_denda'],0,',','.') ?>
                    (<?= $data['hari_terlambat'] ?> hari)
                </p>
            </div>

            <!-- BUTTON -->
            <button onclick="bayarDenda(<?= $data['id'] ?>)"
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Bayar Denda
            </button>

        <?php else: ?>
            <p class="text-green-600 text-sm">✔ Tidak ada denda</p>
        <?php endif; ?>

    </div>

    <?php endwhile; ?>

    </div>
</div>

<script>
function bayarDenda(id){
    if(confirm("Bayar denda sekarang?")){
        fetch("bayar_denda.php?id="+id)
        .then(()=>{
            alert("Denda berhasil dibayar");
            location.reload();
        });
    }
}
</script>

</body>
</html>