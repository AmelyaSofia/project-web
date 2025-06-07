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
                    exit;
                }
                break;
            case 'hapus':
                if ($id_layanan) {
                    $this->deleteLayanan((int)$id_layanan);
                } else {
                    header("Location: index.php?fitur=layanan");
                    exit;
                }
                break;
            default:
                $this->listLayanans();
                break;
        }
    }

    public function addLayanan() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_layanan = htmlspecialchars(trim($_POST['nama_layanan']));
            $deskripsi = htmlspecialchars(trim($_POST['deskripsi']));
            $harga = (int)$_POST['harga'];
            $durasi = (int)$_POST['durasi'];

            $gambar_layanan = $this->handleImageUpload();
            if ($gambar_layanan === false) {
                header("Location: index.php?fitur=tambah&message=Gagal mengupload gambar");
                exit;
            }

            $berhasil = $this->model->addLayanan($nama_layanan, $deskripsi, $harga, $durasi, $gambar_layanan);
            if ($berhasil) {
                header("Location: index.php?fitur=layanan&message=Layanan berhasil ditambahkan");
            } else {
                header("Location: index.php?fitur=tambah&message=Gagal menambahkan layanan");
            }
            exit;
        } else {
            include './view/layananList.php';
        }
    }

    public function updateLayanan($id_layanan) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_layanan = htmlspecialchars(trim($_POST['nama_layanan']));
            $deskripsi = htmlspecialchars(trim($_POST['deskripsi']));
            $harga = (int)$_POST['harga'];
            $durasi = (int)$_POST['durasi'];

            $layanan_lama = $this->model->getLayananById($id_layanan);
            $gambar_layanan = $layanan_lama['gambar_layanan'];

            if (isset($_POST['hapus_gambar']) && $_POST['hapus_gambar'] == 'on') {
                $this->deleteImageFile($gambar_layanan);
                $gambar_layanan = null;
            }

            if (!empty($_FILES['gambar_layanan']['name'])) {
                $new_image = $this->handleImageUpload();
                if ($new_image === false) {
                    header("Location: index.php?fitur=update&id_layanan=$id_layanan&message=Gagal mengupload gambar");
                    exit;
                }
                if ($gambar_layanan) {
                    $this->deleteImageFile($gambar_layanan);
                }
                $gambar_layanan = $new_image;
            }

            $terupdate = $this->model->updateLayanan($id_layanan, $nama_layanan, $deskripsi, $harga, $durasi, $gambar_layanan);
            if ($terupdate) {
                header("Location: index.php?fitur=layanan&message=Layanan berhasil diupdate");
            } else {
                header("Location: index.php?fitur=update&id_layanan=$id_layanan&message=Gagal mengupdate layanan");
            }
            exit;
        } else {
            $layanan = $this->model->getLayananById($id_layanan);
            if (!$layanan) {
                header("Location: index.php?fitur=layanan&message=Layanan tidak ditemukan");
                exit;
            }
            include './view/layananList.php';
        }
    }

    public function deleteLayanan($id_layanan) {
        $layanan = $this->model->getLayananById($id_layanan);
        if ($layanan && $layanan['gambar_layanan']) {
            $this->deleteImageFile($layanan['gambar_layanan']);
        }

        $berhasil = $this->model->deleteLayanan($id_layanan);
        if ($berhasil) {
            header("Location: index.php?fitur=layanan&message=Layanan berhasil dihapus");
        } else {
            header("Location: index.php?fitur=layanan&message=Gagal menghapus layanan");
        }
        exit;
    }

    public function listLayanans() {
        $keyword = $_GET['search'] ?? null;
        if ($keyword) {
            $layanans = $this->model->searchLayanan(htmlspecialchars(trim($keyword)));
        } else {
            $layanans = $this->model->getLayanans();
        }
        include './view/layananList.php';
    }

    private function handleImageUpload() {
        if (!isset($_FILES['gambar_layanan']) || $_FILES['gambar_layanan']['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $file = $_FILES['gambar_layanan'];
        $targetDir = "./image/layanan/";
        $namaFile = uniqid() . '_' . basename($file['name']);
        $targetFile = $targetDir . $namaFile;

        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png'];

        if (!in_array($fileType, $allowedTypes)) {
            return false;
        }

        if ($file['size'] > 10 * 1024 * 1024) {
            return false;
        }

        if (move_uploaded_file($file['tmp_name'], $targetFile)) {
            return $namaFile;
        }

        return false;
    }

    private function deleteImageFile($filename) {
        $filePath = './image/layanan/' . $filename;
        if ($filename && file_exists($filePath)) {
            unlink($filePath);
        }
    }
}
?>
