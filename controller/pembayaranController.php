<?php
require_once __DIR__ . '/../model/pembayaranModel.php';
require_once __DIR__ . '/../model/bookingModel.php';

class ControllerPembayaran {
    private $model;
    private $bookingModel;

    public function __construct() {
        $this->model = new ModelPembayaran();
        $this->bookingModel = new ModelBooking();
    }

    public function handleRequest($fitur) {
        switch ($fitur) {

            case 'list':
                $pembayarans = $this->model->getAllPembayaran();
                include __DIR__ . '/../view/pembayaranList.php';
                break;

            case 'verifikasi':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id']) && isset($_POST['aksi'])) {
                    $id_pembayaran = $_GET['id'];
                    $aksi = $_POST['aksi'];
                    $status = $aksi === 'terima' ? 'dibayar' : 'ditolak';
                    $alasan = $status === 'ditolak' ? ($_POST['alasan'] ?? 'Bukti tidak valid') : null;

                    $hasil = $this->model->verifikasiPembayaran($id_pembayaran, $status, $alasan);

                    if ($hasil && $status === 'dibayar') {
                        $pembayaran = $this->model->getPembayaranById($id_pembayaran);
                        if ($pembayaran['jenis'] === 'pelunasan') {
                            $this->bookingModel->updateStatus($pembayaran['id_booking'], 'terjadwal');
                        }
                    }

                    $pesan = $hasil ? 'Verifikasi berhasil' : 'Verifikasi gagal';
                    header("Location: index.php?modul=pembayaran&fitur=list&message=" . urlencode($pesan));
                    exit();
                } else {
                    include __DIR__ . '/../view/404.php';
                }
                break;

            case 'upload':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_pembayaran'])) {
                    $id_booking = $_POST['id_booking'];
                    $jenis = $_POST['jenis'];
                    $jumlah = $_POST['jumlah'];

                    $fileName = null;
                    if (!empty($_FILES['bukti']['name'])) {
                        $uploadDir = __DIR__ . '/../uploads/';
                        $fileName = time() . '_' . basename($_FILES['bukti']['name']);
                        $destination = $uploadDir . $fileName;

                        if (!move_uploaded_file($_FILES['bukti']['tmp_name'], $destination)) {
                            die('Upload file gagal. Pastikan folder uploads dapat ditulisi.');
                        }
                    }

                    $sukses = $this->model->simpanPembayaran($id_booking, $jenis, $jumlah, $fileName);

                    if ($sukses) {
                        header("Location: ../view/riwayatBooking.php?status=berhasil");
                    } else {
                        header("Location: ../view/riwayatBooking.php?status=gagal");
                    }
                    exit();
                } else {
                    include __DIR__ . '/../view/404.php';
                }
                break;

            default:
                include __DIR__ . '/../view/404.php';
                break;
        }
    }
}
