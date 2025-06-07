<?php
session_start();

// if (isset($_SESSION['role'])) {
//     if ($_SESSION['role'] === 'admin') {
//         header('Location: ./view/adminDashboard.php');
//     } elseif ($_SESSION['role'] === 'client') {
//         header('Location: ./view/clientDashboard.php');
//     }
//     exit;
// }
// header('Location: ./view/login.php');
// exit;


$modul = $_GET['modul'] ?? 'layanan';
$fitur = $_GET['fitur'] ?? 'list';

switch ($modul) {
    case 'layanan':
        include './controller/layananController.php';
        $controllerLayanan = new ControllerLayanan();
        $controllerLayanan->handleRequest($fitur);
        break;
        case 'stylist':
        include './controller/stylistController.php';
        $controllerStylist = new ControllerStylist();
        $controllerStylist->handleRequest($fitur);
        break;
}