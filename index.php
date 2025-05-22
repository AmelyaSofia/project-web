<?php

switch ($modul) {
    case 'admin':
        $adminController = new ControllerAdmin();
        $adminController->handleRequest($id_admin);
        break;
}
?>