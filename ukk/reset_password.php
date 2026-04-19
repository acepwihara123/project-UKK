<?php
include "koneksi.php";

// Password baru
$password_baru = "admin123";

// Buat hash baru
$hash_baru = password_hash($password_baru, PASSWORD_DEFAULT);

// Update semua admin
$sql = "UPDATE users SET password = '$hash_baru' WHERE role = 'admin'";
mysqli_query($conn, $sql);

echo "Password admin telah direset!<br>";
echo "Hash baru: " . $hash_baru . "<br><br>";

// Verifikasi
$cek = mysqli_query($conn, "SELECT email, password FROM users WHERE email = 'admin@gmail.com'");
$data = mysqli_fetch_assoc($cek);

if (password_verify("admin123", $data['password'])) {
    echo "✅ VERIFIKASI BERHASIL!<br>";
    echo "Email: admin@gmail.com<br>";
    echo "Password: admin123<br>";
    echo "<br><a href='login.php'>Klik Login</a>";
} else {
    echo "❌ Verifikasi gagal. Coba metode lain.";
}
?>