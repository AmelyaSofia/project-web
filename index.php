<?php

switch ($modul) {
    case 'admin':
        $adminController = new ControllerAdmin();
        $adminController->handleRequest($id_admin);
        break;
    case 'client':
        $clientController = new ControllerClient();
        $clientController->handleRequest($id_client);
        break;
}
?>