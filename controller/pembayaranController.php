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

            // ✅ Admin melihat semua pembayaran
            case 'list':
                $pembayarans = $this->model->getAllPembayaran();
                include __DIR__ . '/../view/pembayaranList.php';
                break;

            // ✅ Admin melakukan verifikasi pembayaran
            case 'verifikasi':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id']) && isset($_POST['aksi'])) {
                    $id_pembayaran = $_GET['id'];
                    $aksi = $_POST['aksi'];
                    $status = $aksi === 'terima' ? 'dibayar' : 'ditolak';
                    $alasan = $status === 'ditolak' ? ($_POST['alasan'] ?? 'Bukti tidak valid') : null;

                    $hasil = $this->model->verifikasiPembayaran($id_pembayaran, $status, $alasan);

                    $pesan = $hasil ? 'Verifikasi berhasil' : 'Verifikasi gagal';
                    header("Location: index.php?modul=pembayaran&fitur=list&message=" . urlencode($pesan));
                    exit();
                } else {
                    include __DIR__ . '/../view/404.php';
                }
                break;

            // ✅ Client mengupload bukti pembayaran (DP atau pelunasan)
            case 'upload':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_pembayaran'])) {
                    $id_booking = intval($_POST['id_booking']);
                    $jenis = $_POST['jenis']; // dp / pelunasan
                    $jumlah = $_POST['jumlah'];
                    $metode_pembayaran = $_POST['metode'];

                    $fileName = null;
                    if (!empty($_FILES['bukti']['name'])) {
                        $uploadDir = __DIR__ . '/../uploads/';
                        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
                        $fileName = time() . '_' . basename($_FILES['bukti']['name']);
                        $destination = $uploadDir . $fileName;

                        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
                        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                        if (!in_array($ext, $allowed_ext)) {
                            header("Location: ../view/riwayatBooking.php?status=gagal_format");
                            exit();
                        }

                        if (!move_uploaded_file($_FILES['bukti']['tmp_name'], $destination)) {
                            header("Location: ../view/riwayatBooking.php?status=gagal_upload");
                            exit();
                        }
                    }

                    $sukses = $this->model->simpanPembayaran($id_booking, $jenis, $jumlah, $fileName, $metode_pembayaran);

                    if ($sukses) {
                        header("Location: ../view/riwayatBooking.php?status=berhasil");
                    } else {
                        header("Location: ../view/riwayatBooking.php?status=gagal_simpan");
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
