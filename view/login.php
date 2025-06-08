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
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f0ec;
            color: #4b3e37;
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
        }
        .form-container {
            background-color: #fdfaf6;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        h2 {
            color: #a68a7d;
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #cfa894;
            color: white;
            padding: 10px 15px;
            width: 100%;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
        }
        button:hover {
            background-color: #bfa088;
        }
        .link-register {
            text-align: center;
            margin-top: 15px;
            font-size: 0.95em;
        }
        .link-register a {
            color: #a68a7d;
            text-decoration: none;
        }
        .link-register a:hover {
            text-decoration: underline;
        }
        .message {
            background-color: #ffe5dc;
            color: #d9534f;
            padding: 10px;
            border-left: 4px solid #d9534f;
            margin-top: 15px;
            border-radius: 6px;
        }
        .success {
            background-color: #e6f7e6;
            color: #28a745;
            border-left-color: #28a745;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Login</h2>

    <?php if (!empty($message)): ?>
        <div class="message <?= strpos($message, 'berhasil') !== false ? 'success' : '' ?>">
            <?= htmlspecialchars($message) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="">
        <input type="hidden" name="action" value="login">
        <div>
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Login</button>
    </form>

    <div class="link-register">
        Belum punya akun? <a href="?register=true">Register di sini</a>
    </div>

    <?php if ($show_register): ?>
        <hr style="margin: 20px 0; border: 1px solid #eee;">
        <h2 style="margin-top: 0;">Register Client</h2>
        <form method="POST" action="">
            <input type="hidden" name="action" value="register">
            <div>
                <label>Nama Lengkap:</label>
                <input type="text" name="nama_client" required>
            </div>
            <div>
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            <div>
                <label>No HP:</label>
                <input type="text" name="no_hp" required>
            </div>
            <div>
                <label>Alamat:</label>
                <input type="text" name="alamat" required>
            </div>
            <div>
                <label>Username:</label>
                <input type="text" name="username" required>
            </div>
            <div>
                <label>Password:</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Register</button>
        </form>
    <?php endif; ?>
</div>

</body>
</html>