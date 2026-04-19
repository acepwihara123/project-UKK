<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "rental_kamera";  // Nama database Anda

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Set charset ke UTF-8
mysqli_set_charset($conn, "utf8");
?>