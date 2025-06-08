<?php
include './model/ModelBooking.php';

class ControllerBooking {
    private $model;

    public function __construct() {
        $this->model = new ModelBooking();
    }

    public function handleRequest($fitur) {
        $id_booking = $_GET['id_booking'] ?? null;

        switch ($fitur) {
            case 'tambah':
                $this->addBooking();
                break;
            case 'update':
                if ($id_booking) {
                    $this->updateBooking($id_booking);
                } else {
                    header("Location: index.php?fitur=booking");
                    exit;
                }
                break;
            case 'hapus':
                if ($id_booking) {
                    $this->deleteBooking((int)$id_booking);
                } else {
                    header("Location: index.php?modul=booking&fitur=booking");
                    exit;
                }
                break;
            case 'ubah_status':
                if ($id_booking && isset($_POST['status'])) {
                    $this->updateStatus($id_booking, $_POST['status']);
                } else {
                    header("Location: index.php?modul=booking&fitur=booking");
                    exit;
                }
                break;
            default:
                $this->listBookings();
                break;
        }
    }

    public function addBooking() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_client = $_POST['id_client'];
            $id_stylist = $_POST['id_stylist'];
            $id_layanan = $_POST['id_layanan'];
            $tanggal = $_POST['tanggal'];
            $waktu = $_POST['waktu'];
            $catatan = $_POST['catatan'] ?? '';

            $berhasil = $this->model->addBooking($id_client, $id_stylist, $id_layanan, $tanggal, $waktu, $catatan);
            if ($berhasil) {
                header("Location: index.php?modul=booking&fitur=booking&message=Booking berhasil ditambahkan");
            } else {
                header("Location: index.php?modul=booking&fitur=tambah&message=Gagal menambahkan booking");
            }
            exit;
        } else {
            include './model/ModelClient.php';
            include './model/ModelStylist.php';
            include './model/ModelLayanan.php';

            $clientModel = new ModelClient();
            $stylistModel = new ModelStylist();
            $layananModel = new ModelLayanan();

            $clients = $clientModel->getClients();
            $stylists = $stylistModel->getStylists();
            $layanans = $layananModel->getLayanans();

            include './view/bookingList.php';
        }
    }

    public function updateBooking($id_booking) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_client = $_POST['id_client'];
            $id_stylist = $_POST['id_stylist'];
            $id_layanan = $_POST['id_layanan'];
            $tanggal = $_POST['tanggal'];
            $waktu = $_POST['waktu'];
            $status = $_POST['status'];
            $catatan = $_POST['catatan'] ?? '';

            $terupdate = $this->model->updateBooking($id_booking, $id_client, $id_stylist, $id_layanan, $tanggal, $waktu, $status, $catatan);
            if ($terupdate) {
                header("Location: index.php?modul=booking&fitur=booking&message=Booking berhasil diupdate");
            } else {
                header("Location: index.php?modul=booking&fitur=update&id_booking=$id_booking&message=Gagal mengupdate booking");
            }
            exit;
        } else {
            $booking = $this->model->getBookingById($id_booking);
            if (!$booking) {
                header("Location: index.php?modul=booking&fitur=booking&message=Booking tidak ditemukan");
                exit;
            }

            include './model/ModelClient.php';
            include './model/ModelStylist.php';
            include './model/ModelLayanan.php';

            $clientModel = new ModelClient();
            $stylistModel = new ModelStylist();
            $layananModel = new ModelLayanan();

            $clients = $clientModel->getClients();
            $stylists = $stylistModel->getStylists();
            $layanans = $layananModel->getLayanans();

            include './view/bookingList.php';
        }
    }

    public function updateStatus($id_booking, $status) {
        $valid_status = ['menunggu', 'terjadwal', 'selesai', 'batal'];
        if (in_array($status, $valid_status)) {
            $berhasil = $this->model->updateStatus($id_booking, $status);
            if ($berhasil) {
                header("Location: index.php?modul=booking&fitur=booking&message=Status booking berhasil diubah");
            } else {
                header("Location: index.php?modul=booking&fitur=booking&message=Gagal mengubah status booking");
            }
        } else {
            header("Location: index.php?modul=booking&fitur=booking&message=Status tidak valid");
        }
        exit;
    }

    public function deleteBooking($id_booking) {
        $berhasil = $this->model->deleteBooking($id_booking);
        if ($berhasil) {
            header("Location: index.php?modul=booking&fitur=booking&message=Booking berhasil dihapus");
        } else {
            header("Location: index.php?modul=booking&fitur=booking&message=Gagal menghapus booking");
        }
        exit;
    }

    public function listBookings() {
        $keyword = $_GET['search'] ?? null;
        if ($keyword) {
            $bookings = $this->model->searchBooking($keyword);
        } else {
            $bookings = $this->model->getBookings();
        }
        include './view/bookingList.php';
    }
}
?>