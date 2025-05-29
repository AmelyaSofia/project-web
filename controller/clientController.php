<?php
include './model/clientModel.php';

class ControllerClient {
    private $model; 

    public function __construct() {
        $this->model = new ModelClient();
    }

    public function handleRequest($fitur) {
        $id_client = $_GET['id_client'] ?? null;

        switch ($fitur) {
            case 'tambah':
                $this->addClient();
                break;
            case 'update':
                if ($id_client) {
                    $this->updateClient($id_client);
                } else {
                    header("Location: index.php?fitur=client");
                }
                break;
            case 'hapus':
                if($id_client) {
                    $this->deleteClient((int)$id_client);
                } else {
                    header("Location: index.php?fitur=client");
                }
                break;
            default:
                $this->listClients();
                break;
        }
    }

    public function addClient() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_client = $_POST['nama_client'];
            $email_client = $_POST['email_client'];
            $notelp_client = $_POST['notelp_client'];
            $alamat_client = $_POST['alamat_client'];
            $tanggal_daftar = $_POST['tanggal_daftar'];

            $berhasil = $this->model->addClient($nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar);
            if ($berhasil) {
                header("Location: index.php?fitur=list&message=Client berhasil ditambahkan");
            } else {
                header("Location: index.php?fitur=add&message=Gagal menambahkan Client");
            }
            exit;
        } else {
            include './view/client/clientAdd.php';
        }
    }

    public function updateClient($id_client) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_client = $_POST['nama_client'];
            $email_client = $_POST['email_client'];
            $notelp_client = $_POST['notelp_client'];
            $alamat_client = $_POST['alamat_client'];
            $tanggal_daftar = $_POST['tanggal_daftar'];

            $terupdate = $this->model->updateClient($id_client, $nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar);
            if ($terupdate) {
                header("Location: index.php?fitur=list&message=Client berhasil diupdate");
            } else {
                header("Location: index.php?fitur=update&id_client=$id_client&message=Gagal mengupdate Client");
            }
            exit;
        } else {
            $client = $this->model->getClientById($id_client);
            if (!$client) {
                header("Location: index.php?fitur=list&message=Client tidak ditemukan");
                exit;
            }
            include './view/client/clientUpdate.php';
        }
    }

    public function deleteClient($id_client) {
        $terhapus = $this->model->deleteClient($id_client);
        if ($terhapus) {
            header("Location: index.php?fitur=list&message=Client berhasil dihapus");
        } else {
            header("Location: index.php?fitur=list&message=Gagal menghapus Client");
        }
        exit;
    }

    public function listClients() {
        $keyword = $_GET['search'] ?? null;
        if ($keyword) {
            $clients = $this->model->searchClient($keyword);
        } else {
            $clients = $this->model->getClients();
        }
        include './view/client/clientList.php';
    }
}
?>