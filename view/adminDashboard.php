<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
echo "Selamat datang Admin, " . $_SESSION['username'];
?>
<a href="logout.php">Logout</a>
