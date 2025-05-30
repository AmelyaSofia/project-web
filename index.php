<?php
$modul = $_GET['modul'] ?? 'layanan';
$fitur = $_GET['fitur'] ?? 'list';
// $id_layanan = $_GET['id_layanan'] ?? null;

switch ($modul) {
    // case 'client':
    //     include './controller/clientController.php';
    //     $controller = new ControllerClient();
    //     $controller->handleRequest($fitur);
    //     break;

    // case 'admin':
    //     include './controller/adminController.php';
    //     $controller = new ControllerAdmin();
    //     $controller->handleRequest($fitur);
    //     break;

    case 'layanan':
        include './controller/layananController.php';
        $controller = new ControllerLayanan();
        $controller->handleRequest($fitur);
        break;
}
