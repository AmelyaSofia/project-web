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
                $this->handleListPembayaran();
                break;

            case 'verifikasi':
                $this->handleVerifikasi();
                break;

            case 'upload':
                $this->handleUpload();
                break;

            default:
                include __DIR__ . '/../view/404.php';
                break;
        }
    }

    private function handleListPembayaran() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $start_date = $_GET['start_date'] ?? null;
        $end_date = $_GET['end_date'] ?? null;
        $status = $_GET['status'] ?? null;

        $data = $this->model->getPembayaranWithFilter([
            'page' => $page,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status
        ]);

        $pembayarans = $data['pembayarans'];
        $total_pages = $data['total_pages'];
        $current_page = $data['current_page'];
        $total_data = $data['total_data'];

        $message = $_GET['message'] ?? null;

        include __DIR__ . '/../view/pembayaranList.php';
    }

    private function handleVerifikasi() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id']) && isset($_POST['aksi'])) {
            $id_pembayaran = $_GET['id'];
            $aksi = $_POST['aksi'];
            $status = $aksi === 'terima' ? 'dibayar' : 'ditolak';
            $alasan = $status === 'ditolak' ? ($_POST['alasan'] ?? 'Bukti tidak valid') : null;

            $hasil = $this->model->verifikasiPembayaran($id_pembayaran, $status, $alasan);

            $pesan = $hasil ? 'Verifikasi berhasil' : 'Verifikasi gagal';

            $query_params = [
                'modul' => 'pembayaran',
                'fitur' => 'list',
                'message' => $pesan
            ];

            if (isset($_GET['page'])) $query_params['page'] = $_GET['page'];
            if (isset($_GET['start_date'])) $query_params['start_date'] = $_GET['start_date'];
            if (isset($_GET['end_date'])) $query_params['end_date'] = $_GET['end_date'];
            if (isset($_GET['status'])) $query_params['status'] = $_GET['status'];

            header("Location: index.php?" . http_build_query($query_params));
            exit();
        } else {
            include __DIR__ . '/../view/404.php';
        }
    }

    private function handleUpload() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_pembayaran'])) {
            $id_booking = intval($_POST['id_booking']);
            $jenis = $_POST['jenis']; 
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
    }
}