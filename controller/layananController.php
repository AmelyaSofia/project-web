<?php
include './model/layananModel.php';    

class ControllerLayanan {
    private $model;

    public function __construct() {
        $this->model = new ModelLayanan();
    }

    public function handleRequest($fitur) {
        $id_layanan = $_GET['id_layanan'] ?? null;

        switch ($fitur) {
            case 'tambah':
                $this->addLayanan();
                break;
            case 'update':
                if ($id_layanan) {
                    $this->updateLayanan($id_layanan);
                } else {
                    header("Location: index.php?fitur=layanan");
                }
                break;
            case 'hapus':
                if ($id_layanan) {
                    $this->deleteLayanan((int)$id_layanan);
                } else {
                    header("Location: index.php?fitur=layanan");
                }
                break;
            default:
                $this->listLayanans();
                break;
        }
    }

    public function addLayanan() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_layanan = $_POST['nama_layanan'];
            $deskripsi_layanan = $_POST['deskripsi_layanan'];
            $harga_layanan = $_POST['harga_layanan'];

            $berhasil = $this->model->addLayanan($nama_layanan, $deskripsi_layanan, $harga_layanan);
            if ($berhasil) {
                header("Location: index.php?fitur=list&message=Layanan berhasil ditambahkan");
            } else {
                header("Location: index.php?fitur=tambah&message=Gagal menambahkan Layanan");
            }
            exit;
        } else {
            include './view/layanan/layananAdd.php';
        }
    }

    public function updateLayanan($id_layanan) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_layanan = $_POST['nama_layanan'];
            $deskripsi_layanan = $_POST['deskripsi_layanan'];
            $harga_layanan = $_POST['harga_layanan'];

            $terupdate = $this->model->updateLayanan($id_layanan, $nama_layanan, $deskripsi_layanan, $harga_layanan);
            if ($terupdate) {
                header("Location: index.php?fitur=list&message=Layanan berhasil diupdate");
            } else {
                header("Location: index.php?fitur=update&id_layanan=$id_layanan&message=Gagal mengupdate Layanan");
            }
            exit;   
        } else {
            $layanan = $this->model->getLayananById($id_layanan);
            if (!$layanan) {
                header("Location: index.php?fitur=list&message=Layanan tidak ditemukan");
                exit;
            }
            include './view/layanan/layananUpdate.php';
        }
    }
    public function deleteLayanan($id_layanan) {
        $berhasil = $this->model->deleteLayanan($id_layanan);
        if ($berhasil) {
            header("Location: index.php?fitur=list&message=Layanan berhasil dihapus");
        } else {
            header("Location: index.php?fitur=layanan&message=Gagal menghapus Layanan");
        }
        exit;
    }
    public function listLayanans() {
        $keyword = $_GET['search'] ?? null;
        if ($keyword) {
            $layanans = $this->model->searchLayanan($keyword);
        } else {
            $layanans = $this->model->getLayanans();
        }
        include './view/layanan/layananList.php';
    }
}
?>