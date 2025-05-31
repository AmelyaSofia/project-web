<?php   
include './model/karyawanModel.php';

class ControllerKaryawan {
    private $model;

    public function __construct() {
        $this->model = new ModelKaryawan();
    }

    public function handleRequest($fitur) {
        $id_karyawan = $_GET['id_karyawan'] ?? null;

        switch ($fitur) {
            case 'tambah':
                $this->addKaryawan();
                break;
            case 'update':
                if ($id_karyawan) {
                    $this->updateKaryawan($id_karyawan);
                } else {
                    header("Location: index.php?fitur=karyawan");
                }
                break;
            case 'hapus':
                if ($id_karyawan) {
                    $this->deleteKaryawan((int)$id_karyawan);
                } else {
                    header("Location: index.php?fitur=karyawan");
                }
                break;
            default:
                $this->listKaryawans();
                break;
        }
    }
    public function addKaryawan() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_karyawan = $_POST['nama_karyawan'];
            $peran_karyawan = $_POST['peran_karyawan'];
            $foto_karyawan = $_POST['foto_karyawan'];

            $berhasil = $this->model->addKaryawan($nama_karyawan, $peran_karyawan, $foto_karyawan); 
            if ($berhasil) {
                header("Location: index.php?fitur=list&message=Karyawan berhasil ditambahkan");
            } else {
                header("Location: index.php?fitur=add&message=Gagal menambahkan Client");
            }
            exit;
        } else {
            include './view/karyawan/karyawanAdd.php';
        }
    }
    public function updateKaryawan($id_karyawan) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_karyawan = $_POST['nama_karyawan'];
            $peran_karyawan = $_POST['peran_karyawan'];
            $foto_karyawan = $_POST['foto_karyawan'];

            $berhasil = $this->model->updateKaryawan($id_karyawan, $nama_karyawan, $peran_karyawan, $foto_karyawan);
            if ($berhasil) {
                header("Location: index.php?fitur=list&message=Karyawan berhasil diupdate");
            } else {
                header("Location: index.php?fitur=update&id_karyawan=$id_karyawan&message=Gagal mengupdate Karyawan");
            }
            exit;
        } else {
            $karyawan = $this->model->getKaryawanById($id_karyawan);
            if (!$karyawan) {
                header("Location: index.php?fitur=list&message=Karyawan tidak ditemukan");
                exit;
            }
            include './view/karyawan/karyawanUpdate.php';
        }
    }
    public function deleteKaryawan($id_karyawan) {
        $berhasil = $this->model->deleteKaryawan($id_karyawan);
        if ($berhasil) {
            header("Location: index.php?fitur=list&message=Karyawan berhasil dihapus");
        } else {
            header("Location: index.php?fitur=list&message=Gagal menghapus Karyawan");
        }
        exit;
    }
    public function listKaryawans() {
        $karyawans = $this->model->getKaryawans();
        include './view/karyawan/karyawanList.php';
    }
}
?>