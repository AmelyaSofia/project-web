<?php
session_start();

$modul = $_GET['modul'] ?? '';
$fitur = $_GET['fitur'] ?? 'list';

if (!isset($_SESSION['role'])) {
    header('Location: ./view/login.php');
    exit;
}

if (empty($modul)) {
    if ($_SESSION['role'] === 'admin') {
        header('Location: ./view/adminDashboard.php');
    } elseif ($_SESSION['role'] === 'client') {
        header('Location: ./view/clientDashboard.php');
    }
    exit;
}

switch ($modul) {
    case 'client':
        include './controller/clientController.php';
        $controllerClient = new ControllerClient();
        $controllerClient->handleRequest($fitur);
        break;
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
    case 'booking':
        include './controller/bookingController.php';
        $controllerBooking = new ControllerBooking();
        $controllerBooking->handleRequest($fitur);
        break;
    default:
        header('Location: ./view/adminDashboard.php');
        exit;
}

// $modul = $_GET['modul'] ?? 'booking';
// $fitur = $_GET['fitur'] ?? 'list';

// switch ($modul) {
//     case 'layanan':
//         include './controller/layananController.php';
//         $controllerLayanan = new ControllerLayanan();
//         $controllerLayanan->handleRequest($fitur);
//         break;
//     case 'stylist':
//         include './controller/stylistController.php';
//         $controllerStylist = new ControllerStylist();
//         $controllerStylist->handleRequest($fitur);
//         break;
//     case 'booking':
//         include './controller/bookingController.php';
//         $controllerBooking = new ControllerBooking();
//         $controllerBooking->handleRequest($fitur);
//         break;
// }