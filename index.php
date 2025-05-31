<?php
$modul = $_GET['modul'] ?? 'karyawan';
$fitur = $_GET['fitur'] ?? 'list';

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

    // case 'layanan':
    //     include './controller/layananController.php';
    //     $controller = new ControllerLayanan();
    //     $controller->handleRequest($fitur);
    //     break;

    case 'karyawan':
        include './controller/karyawanController.php';
        $controller = new ControllerKaryawan();
        $controller->handleRequest($fitur);
        break;
    
}
