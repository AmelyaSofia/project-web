<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
echo "Selamat datang Admin, " . $_SESSION['username'];
// Tambahkan menu dan fungsi admin disini
?>
<a href="logout.php">Logout</a>
