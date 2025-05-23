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
                $this->listclients();
                break;
        }
    }

    public function addClient() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_client = $_POST['nama_client'];
            $email_client = $_POST['email_admin'];
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
            include './view/client/addClient.php';
        }
    }

    public function updateClient($id_client) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_client = $_POST['nama_client'];
            $email_client = $_POST['email_admin'];
            $notelp_client = $_POST['notelp_client'];
            $alamat_client = $_POST['alamat_client'];
            $tanggal_daftar = $_POST['tanggal_daftar'];

            $terupdate = $this->model->updateCLient($id_client, $nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar);
            if ($terupdate) {
                header("Location: index.php?fitur=list&message=Client berhasil diupdate");
            } else {
                header("Location: index.php?fitur=update&id_client=$id_client&message=Gagal mengupdate Client");
            }
            exit;
        } else {
            $admin = $this->model->getCLientById($id_client);
            if (!$admin) {
                header("Location: index.php?fitur=list&message=Client tidak ditemukan");
                exit;
            }
            include './view/client/updateAdmin.php';
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

    public function listclients() {
        $keyword = $_GET['search'] ?? null;
        if ($keyword) {
            $admins = $this->model->searchClient    ($keyword);
        } else {
            $admins = $this->model->getClients();
        }
        include './view/client/listAdmin.php';
    }
}
?>