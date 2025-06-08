<?php
include __DIR__.'/../model/clientModel.php';

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
                if ($id_client) {
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
            $email = $_POST['email'];
            $no_hp = $_POST['no_hp'];
            $alamat = $_POST['alamat'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            $berhasil = $this->model->addClient($nama_client, $email, $no_hp, $alamat, $username, $password);
            if ($berhasil) {
                header("Location: index.php?fitur=client&message=Client berhasil ditambahkan");
            } else {
                header("Location: index.php?fitur=tambah&message=Gagal menambahkan client");
            }
            exit;
        } else {
            include './view/clientList.php';
        }
    }

    public function updateClient($id_client) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_client = $_POST['nama_client'];
            $email = $_POST['email'];
            $no_hp = $_POST['no_hp'];
            $alamat = $_POST['alamat'];
            $username = $_POST['username'];
            $password = $_POST['password'];

            $terupdate = $this->model->updateClient($id_client, $nama_client, $email, $no_hp, $alamat, $username, $password);
            if ($terupdate) {
                header("Location: index.php?fitur=client&message=Client berhasil diupdate");
            } else {
                header("Location: index.php?fitur=update&id_client=$id_client&message=Gagal mengupdate client");
            }
            exit;
        } else {
            $client = $this->model->getClientById($id_client);
            if (!$client) {
                header("Location: index.php?fitur=client&message=Client tidak ditemukan");
                exit;
            }
            include './view/clientList.php';
        }
    }

    public function deleteClient($id_client) {
        $berhasil = $this->model->deleteClient($id_client);
        if ($berhasil) {
            header("Location: index.php?fitur=client&message=Client berhasil dihapus");
        } else {
            header("Location: index.php?fitur=client&message=Gagal menghapus client");
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
        include './view/clientList.php';
    }

    public function authLogin($username, $password) {
        $client = $this->model->login($username, $password);
        if ($client) {
            $_SESSION['role'] = 'client';
            $_SESSION['username'] = $client['username'];
            $_SESSION['user_name'] = $client['nama_client'];
            $_SESSION['user_id'] = $client['id_client'];
            header("Location: ../view/clientDashboard.php");
            exit;
        }
        return false;
    }

public function authRegister($data) {
    return $this->model->register($data);
}

}
?>
