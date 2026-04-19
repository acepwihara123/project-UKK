<?php
session_start();
include "koneksi.php";

// Jika sudah login, redirect sesuai role
if (isset($_SESSION['login']) && $_SESSION['login'] === true) {
    if ($_SESSION['role'] == 'admin') {
        header("Location: admin/dashboard.php");
        exit();
    } else {
        header("Location: users/index.php");
        exit();
    }
}

$error = "";
$success = "";

// PROSES LOGIN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email dan password harus diisi!";
    } else {
        $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email' AND status='active'");

        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);

            // Verifikasi password (support MD5 dan bcrypt)
            if (password_verify($password, $data['password']) || md5($password) == $data['password']) {
                $_SESSION['login'] = true;
                $_SESSION['id'] = $data['id'];
                $_SESSION['nama'] = $data['nama'];
                $_SESSION['email'] = $data['email'];
                $_SESSION['role'] = $data['role'];
                $_SESSION['user_logged_in'] = true;
                $_SESSION['user_name'] = $data['nama'];
                $_SESSION['user_email'] = $data['email'];
                $_SESSION['user_id'] = $data['id'];

                if ($data['role'] == 'admin') {
                    header("Location: admin/dashboard.php");
                } else {
                    header("Location: users/index.php");
                }
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Email tidak ditemukan atau akun tidak aktif!";
        }
    }
}

// PROSES REGISTER - DIPERBAIKI
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if (empty($nama) || empty($email) || empty($telepon) || empty($password)) {
        $error = "Semua field harus diisi!";
    } elseif ($password !== $confirm_password) {
        $error = "Password dan konfirmasi password tidak cocok!";
    } elseif (strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } else {
        $check = mysqli_query($conn, "SELECT id FROM users WHERE email='$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "Email sudah terdaftar!";
        } else {
            // Gunakan MD5 agar langsung bisa login
            $hashed_password = md5($password);
            $sql = "INSERT INTO users (nama, email, telepon, password, role, status) 
                    VALUES ('$nama', '$email', '$telepon', '$hashed_password', 'user', 'active')";
            
            if (mysqli_query($conn, $sql)) {
                $success = "Registrasi berhasil! Silakan login.";
            } else {
                $error = "Registrasi gagal: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login & Register | Rental Kamera</title>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-50">

<div class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8">
        
        <div class="text-center mb-8">
            <i class="fas fa-camera text-red-600 text-4xl mb-3"></i>
            <h1 class="text-2xl font-bold text-gray-800">Rental Kamera</h1>
            <p class="text-gray-500 text-sm">Login atau daftar untuk melanjutkan</p>
        </div>

        <!-- Tab Login/Register -->
        <div class="flex mb-6 rounded-lg overflow-hidden border">
            <button id="tabLoginBtn" class="w-1/2 py-2 font-semibold bg-red-600 text-white transition">Login</button>
            <button id="tabRegisterBtn" class="w-1/2 py-2 font-semibold bg-gray-200 text-gray-600 transition">Daftar</button>
        </div>

        <?php if($error != ""): ?>
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded text-sm">
                <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <?php if($success != ""): ?>
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded text-sm">
                <i class="fas fa-check-circle mr-2"></i> <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <!-- FORM LOGIN -->
        <div id="loginForm">
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" required placeholder="Masukkan email" 
                           class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" required placeholder="Masukkan password" 
                           class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <button type="submit" name="login" 
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition">
                    <i class="fas fa-sign-in-alt mr-2"></i> Login
                </button>
            </form>
        </div>

        <!-- FORM REGISTER -->
        <div id="registerForm" style="display: none;">
            <form method="POST" class="space-y-3">
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" required placeholder="Masukkan nama lengkap" 
                           class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Email</label>
                    <input type="email" name="email" required placeholder="Masukkan email" 
                           class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">No. Handphone</label>
                    <input type="text" name="telepon" required placeholder="Masukkan no. handphone" 
                           class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Password</label>
                    <input type="password" name="password" required placeholder="Minimal 6 karakter" 
                           class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-medium mb-1">Konfirmasi Password</label>
                    <input type="password" name="confirm_password" required placeholder="Ulangi password" 
                           class="w-full p-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <button type="submit" name="register" 
                        class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-3 rounded-lg transition">
                    <i class="fas fa-user-plus mr-2"></i> Daftar
                </button>
            </form>
        </div>

<script>
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const tabLoginBtn = document.getElementById('tabLoginBtn');
    const tabRegisterBtn = document.getElementById('tabRegisterBtn');

    function showLogin() {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        tabLoginBtn.className = 'w-1/2 py-2 font-semibold bg-red-600 text-white transition';
        tabRegisterBtn.className = 'w-1/2 py-2 font-semibold bg-gray-200 text-gray-600 transition';
    }

    function showRegister() {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        tabRegisterBtn.className = 'w-1/2 py-2 font-semibold bg-red-600 text-white transition';
        tabLoginBtn.className = 'w-1/2 py-2 font-semibold bg-gray-200 text-gray-600 transition';
    }

    tabLoginBtn.addEventListener('click', showLogin);
    tabRegisterBtn.addEventListener('click', showRegister);
</script>

</body>
</html>