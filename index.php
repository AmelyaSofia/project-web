<?php
session_start();

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: ./view/adminDashboard.php');
    } elseif ($_SESSION['role'] === 'client') {
        header('Location: ./view/clientDashboard.php');
    }
    exit;
}

header('Location: ./view/login.php');
exit;
