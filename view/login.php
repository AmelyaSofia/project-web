<?php
session_start();
require_once '../controller/adminController.php';
require_once '../controller/clientController.php';

$message = '';
$show_register = isset($_GET['register']); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'login') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        $adminController = new ControllerAdmin();
        $clientController = new ControllerClient();

        if ($adminController->authLogin($username, $password)) {
            header("Location: ../view/adminDashboard.php");
            exit;
        }

        if ($clientController->authLogin($username, $password)) {
            header("Location: ../view/clientDashboard.php");
            exit;
        }

        $message = "Username atau password salah!";

    } elseif ($action === 'register') {
        $data = [
            'nama_client' => trim($_POST['nama_client']),
            'email' => trim($_POST['email']),
            'no_hp' => trim($_POST['no_hp']),
            'alamat' => trim($_POST['alamat']),
            'username' => trim($_POST['username']),
            'password' => trim($_POST['password'])
        ];

        $clientController = new ControllerClient();

        if ($clientController->authRegister($data)) {
            $message = "Registrasi berhasil! Silakan login.";
        } else {
            $message = "Registrasi gagal. Username mungkin sudah digunakan.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= $show_register ? 'Register' : 'Login' ?> | Royal Beauty</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        :root {
            --primary-color: #d4a998;
            --primary-dark: #b38b7d;
            --secondary-color: #f8f1e9;
            --text-color: #5a4a42;
            --light-text: #8a7d76;
            --error-color: #e74c3c;
            --success-color: #2ecc71;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('../image/login.png');
            background-size: cover;
            background-position: center;
            background-blend-mode: overlay;
            background-color: rgba(248, 241, 233, 0.9);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            padding: 40px 30px;
        }

        .logo {
            display: block;
            margin: 0 auto 15px;
            width: 150px;
            height: auto;
        }

        h2 {
            text-align: center;
            color: var(--primary-dark);
            margin-bottom: 20px;
            font-size: 24px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd; 
            border-radius: 8px;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(212, 169, 152, 0.2);
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
        }

        .link-register,
        .link-login {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .link-register a,
        .link-login a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .link-register a:hover,
        .link-login a:hover {
            text-decoration: underline;
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            display: flex;
            align-items: center;
            border-left: 4px solid var(--error-color);
        }

        .message.success {
            border-left-color: var(--success-color);
            background-color: #e8f5e9;
            color: var(--success-color);
        }

        .message.error {
            background-color: #fdecea;
            color: var(--error-color);
        }

        hr {
            margin: 25px 0;
            border: none;
            border-top: 1px solid #eee;
        }

        @media (max-width: 500px) {
            .form-container {
                padding: 30px 20px;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>

<div class="form-container">
    <!-- Logo Salon -->
    <img src="..\image\logo.png" alt="Logo Royal Beauty" class="logo">

    <?php if (!$show_register): ?>
        <h2>Selamat Datang Kembali</h2>

        <?php if (!empty($message)): ?>
            <div class="message error">
                <i class="fas fa-exclamation-circle" style="margin-right: 10px;"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Masukkan username Anda">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Masukkan password Anda">
            </div>
            <button type="submit">Masuk</button>
        </form>

        <div class="link-register">
            Belum punya akun? <a href="?register=true">Daftar sekarang</a>
        </div>
    <?php else: ?>
        <h2>Buat Akun Baru</h2>

        <?php if (!empty($message)): ?>
            <div class="message <?= strpos($message, 'berhasil') !== false ? 'success' : 'error' ?>">
                <i class="fas <?= strpos($message, 'berhasil') !== false ? 'fa-check-circle' : 'fa-exclamation-circle' ?>" style="margin-right: 10px;"></i>
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <input type="hidden" name="action" value="register">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_client" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="form-group">
                <label>No HP</label>
                <input type="text" name="no_hp" required>
            </div>
            <div class="form-group">
                <label>Alamat</label>
                <input type="text" name="alamat" required>
            </div>
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Daftar</button>
        </form>

        <div class="link-login">
            Sudah punya akun? <a href="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>?form=login">Masuk di sini</a>
        </div>
    <?php endif; ?>
</div>

</body>
</html>