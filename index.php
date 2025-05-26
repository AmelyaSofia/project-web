<?php
$modul = $_GET['modul'] ?? 'admin';
$fitur = $_GET['fitur'] ?? 'list';

switch ($modul) {
    // case 'client':
    //     include './controller/clientController.php';
    //     $controller = new ControllerClient();
    //     $controller->handleRequest($fitur);
    //     break;

    case 'admin':
        include './controller/adminController.php';
        $controller = new ControllerAdmin();
        $controller->handleRequest($fitur);
        break;
}
