<?php
include './model/stylistModel.php';

class ControllerStylist {
    private $model;

    public function __construct() {
        $this->model = new ModelStylist();
    }

    public function handleRequest($fitur) {
        $id_stylist = $_GET['id_stylist'] ?? null;

        switch ($fitur) {
            case 'tambah':
                $this->addStylist();
                break;
            case 'update':
                if ($id_stylist) {
                    $this->updateStylist($id_stylist);
                } else {
                    header("Location: index.php?fitur=stylist");
                }
                break;
            case 'hapus':
                if ($id_stylist) {
                    $this->deleteStylist((int)$id_stylist);
                } else {
                    header("Location: index.php?fitur=stylist");
                }
                break;
            default:
                $this->listStylists();
                break;
        }
    }

    public function addStylist() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_stylist = $_POST['nama_stylist'];
            $keahlian = $_POST['keahlian'];

            $berhasil = $this->model->addStylist($nama_stylist, $keahlian);
            if ($berhasil) {
                header("Location: index.php?fitur=stylist&message=Stylist berhasil ditambahkan");
            } else {
                header("Location: index.php?fitur=tambah&message=Gagal menambahkan stylist");
            }
            exit;
        } else {
            include './view/stylistList.php';
        }
    }

    public function updateStylist($id_stylist) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_stylist = $_POST['nama_stylist'];
            $keahlian = $_POST['keahlian'];

            $terupdate = $this->model->updateStylist($id_stylist, $nama_stylist, $keahlian);
            if ($terupdate) {
                header("Location: index.php?fitur=stylist&message=Stylist berhasil diupdate");
            } else {
                header("Location: index.php?fitur=update&id_stylist=$id_stylist&message=Gagal mengupdate stylist");
            }
            exit;
        } else {
            $stylist = $this->model->getStylistById($id_stylist);
            if (!$stylist) {
                header("Location: index.php?fitur=stylist&message=Stylist tidak ditemukan");
                exit;
            }
            include './view/stylistList.php';
        }
    }

    public function deleteStylist($id_stylist) {
        $berhasil = $this->model->deleteStylist($id_stylist);
        if ($berhasil) {
            header("Location: index.php?fitur=stylist&message=Stylist berhasil dihapus");
        } else {
            header("Location: index.php?fitur=stylist&message=Gagal menghapus stylist");
        }
        exit;
    }

    public function listStylists() {
        $keyword = $_GET['search'] ?? null;
        if ($keyword) {
            $stylists = $this->model->searchStylist($keyword);
        } else {
            $stylists = $this->model->getStylists();
        }
        include './view/stylistList.php';
    }
}
?>
