<?php
session_start();
include '../model/adminModel.php';
include '../model/clientModel.php';

$modelAdmin = new ModelAdmin();
$modelClient = new ModelClient();

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; // 'login' atau 'register'

    if ($action === 'register') {
        // ambil data register
        $nama_client = $_POST['nama_client'];
        $email = $_POST['email'];
        $no_hp = $_POST['no_hp'];
        $alamat = $_POST['alamat'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // register client
        $success = $modelClient->addClient($nama_client, $email, $no_hp, $alamat, $username, $password);

        if ($success) {
            $message = "Registrasi berhasil, silakan login.";
        } else {
            $message = "Registrasi gagal, coba lagi.";
        }
    } elseif ($action === 'login') {
        // ambil data login
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Cek login admin dulu
        $admins = $modelAdmin->getAdmins();
        $isAdmin = false;
        foreach ($admins as $admin) {
            if ($admin['username'] === $username && $password === 'admin') {
                // password admin disamakan 111
                $_SESSION['role'] = 'admin'; // GANTI dari user_type
                $_SESSION['username'] = $admin['username']; // biar konsisten dengan dashboard
                $_SESSION['user_name'] = $admin['nama_admin'];
                $_SESSION['user_id'] = $admin['id_admin'];
                $isAdmin = true;
                header('Location: adminDashboard.php'); // ganti dengan halaman admin kamu
                exit;
            }
        }

        if (!$isAdmin) {
            // cek client berdasarkan username dan password biasa
            $clients = $modelClient->getClients();
            $isClient = false;
            foreach ($clients as $client) {
                if ($client['username'] === $username && $client['password'] === $password) {
                    $_SESSION['role'] = 'client'; // GANTI dari user_type
                    $_SESSION['username'] = $client['username'];
                    $_SESSION['user_name'] = $client['nama_client'];
                    $_SESSION['user_id'] = $client['id_client'];
                    $isClient = true;
                    header('Location: clientDashboard.php'); // ganti dengan halaman client kamu
                    exit;
                }
            }
            if (!$isClient) {
                $message = "Login gagal, username atau password salah.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Login & Register</title>
</head>
<body>
<h2>Login</h2>
<form method="POST" action="">
    <input type="hidden" name="action" value="login" />
    Username: <input type="text" name="username" required /><br/>
    Password: <input type="password" name="password" required /><br/>
    <button type="submit">Login</button>
</form>

<h2>Register Client</h2>
<form method="POST" action="">
    <input type="hidden" name="action" value="register" />
    Nama Client: <input type="text" name="nama_client" required /><br/>
    Email: <input type="email" name="email" required /><br/>
    No HP: <input type="text" name="no_hp" required /><br/>
    Alamat: <input type="text" name="alamat" required /><br/>
    Username: <input type="text" name="username" required /><br/>
    Password: <input type="password" name="password" required /><br/>
    <button type="submit">Register</button>
</form>

<p style="color:red;"><?= $message ?></p>
</body>
</html>
