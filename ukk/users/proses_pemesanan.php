<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../users/index.php");
    exit();
}

$data = $_POST;

// Validasi
if (empty($data['nama']) || empty($data['no_telepon']) || empty($data['email'])) {
    die("<script>alert('Data tidak lengkap!'); window.history.back();</script>");
}

$kamera_id = intval($data['kamera_id']);
$durasi = intval($data['durasi']);
$tanggal_mulai = $data['tanggal_mulai'];
$tanggal_kembali = date('Y-m-d', strtotime($tanggal_mulai . ' + ' . $durasi . ' days'));

$nama = mysqli_real_escape_string($conn, $data['nama']);
$no_telepon = mysqli_real_escape_string($conn, $data['no_telepon']);
$email = mysqli_real_escape_string($conn, $data['email']);
$alamat = mysqli_real_escape_string($conn, $data['alamat']);
$pembayaran = mysqli_real_escape_string($conn, $data['pembayaran']);
$total = intval($data['total']);

$query = "INSERT INTO pemesanan 
          (nama, no_telepon, email, durasi, tanggal_mulai, tanggal_kembali, total, alamat, pembayaran, kamera_id, status) 
          VALUES 
          ('$nama', '$no_telepon', '$email', '$durasi', '$tanggal_mulai', '$tanggal_kembali', '$total', '$alamat', '$pembayaran', '$kamera_id', 'proses')";

if ($conn->query($query)) {
    $conn->query("UPDATE kamera SET stok = stok - 1 WHERE id = $kamera_id");
    echo "<script>alert('Pemesanan berhasil!'); window.location.href='../users/index.php';</script>";
} else {
    echo "<script>alert('Error: " . $conn->error . "'); window.history.back();</script>";
}

$conn->close();
?>