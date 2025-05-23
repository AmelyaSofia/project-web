<?php
include './model/adminModel.php';

class ControllerAdmin {
    private $model; 

    public function __construct() {
        $this->model = new ModelAdmin();
    }

    public function handleRequest($fitur) {
        $id_admin = $_GET['id_admin'] ?? null;

        switch ($fitur) {
            case 'tambah':
                $this->addAdmin();
                break;
            case 'update':
                if ($id_admin) {
                    $this->updateAdmin($id_admin);
                } else {
                    header("Location: index.php?fitur=admin");
                }
                break;
            case 'hapus':
                if($id_admin) {
                    $this->deleteAdmin((int)$id_admin);
                } else {
                    header("Location: index.php?fitur=admin");
                }
                break;
            default:
                $this->listadmins();
                break;
        }
    }

    public function addAdmin() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_admin = $_POST['nama_admin'];
            $email_admin = $_POST['email_admin'];
            $password_admin = $_POST['password_admin'];
            $foto_admin = $_POST['foto_admin'];

            $berhasil = $this->model->addAdmin($nama_admin, $email_admin, $password_admin, $foto_admin);
            if ($berhasil) {
                header("Location: index.php?fitur=list&message=Admin berhasil ditambahkan");
            } else {
                header("Location: index.php?fitur=add&message=Gagal menambahkan admin");
            }
            exit;
        } else {
            include './view/admin/addAdmin.php';
        }
    }

    public function updateAdmin($id_admin) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_admin = $_POST['nama_admin'];
            $email_admin = $_POST['email_admin'];
            $password_admin = $_POST['password_admin'];
            $foto_admin = $_POST['foto_admin'];

            $terupdate = $this->model->updateAdmin($id_admin, $nama_admin, $email_admin, $password_admin, $foto_admin);
            if ($terupdate) {
                header("Location: index.php?fitur=list&message=Admin berhasil diupdate");
            } else {
                header("Location: index.php?fitur=update&id_admin=$id_admin&message=Gagal mengupdate admin");
            }
            exit;
        } else {
            $admin = $this->model->getAdminById($id_admin);
            if (!$admin) {
                header("Location: index.php?fitur=list&message=Admin tidak ditemukan");
                exit;
            }
            include './view/admin/updateAdmin.php';
        }
    }

    public function deleteAdmin($id_admin) {
        $terhapus = $this->model->deleteAdmin($id_admin);
        if ($terhapus) {
            header("Location: index.php?fitur=list&message=Admin berhasil dihapus");
        } else {
            header("Location: index.php?fitur=list&message=Gagal menghapus admin");
        }
        exit;
    }

    public function listadmins() {
        $keyword = $_GET['search'] ?? null;
        if ($keyword) {
            $admins = $this->model->searchAdmin($keyword);
        } else {
            $admins = $this->model->getAdmins();
        }
        include './view/admin/listAdmin.php';
    }
}
?>