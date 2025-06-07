<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit;
}
echo "Selamat datang Client, " . $_SESSION['username'];
// Tambahkan menu dan fungsi client disini
?>
<a href="logout.php">Logout</a>
