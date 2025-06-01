<?php
include './model/bookingModel.php';

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
            case 'update-status':
                if ($id_booking) {
                    $this->updateStatus($id_booking);
                }
                break;
            case 'hapus':
                if ($id_booking) {
                    $this->deleteBooking($id_booking);
                }
                break;
            default:
                $this->listBooking();
                break;
        }
    }

    public function addBooking() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_client = $_POST['id_client'];
            $id_layanan = $_POST['id_layanan'];
            $tanggal = $_POST['tanggal'];
            $jam = $_POST['jam'];

            $berhasil = $this->model->addBooking($id_client, $id_layanan, $tanggal, $jam);
            if ($berhasil) {
                header("Location: index.php?fitur=list&message=Booking berhasil ditambahkan");
            } else {
                header("Location: index.php?fitur=tambah&message=Gagal menambahkan booking");
            }
        } else {
            include './view/booking/bookingAdd.php';
        }
    }

    public function updateStatus($id_booking) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            $berhasil = $this->model->updateStatus($id_booking, $status);
            if ($berhasil) {
                header("Location: index.php?fitur=list&message=Status booking diperbarui");
            } else {
                header("Location: index.php?fitur=list&message=Gagal memperbarui status");
            }
        } else {
            $booking = $this->model->getBookingById($id_booking);
            include './view/booking/bookingStatusUpdate.php';
        }
    }

    public function deleteBooking($id_booking) {
        $hapus = $this->model->deleteBooking($id_booking);
        if ($hapus) {
            header("Location: index.php?fitur=list&message=Booking dihapus");
        } else {
            header("Location: index.php?fitur=list&message=Gagal menghapus booking");
        }
    }

    public function listBooking() {
        $bookings = $this->model->getAllBooking();
        include './view/booking/bookingList.php';
    }
}
?>
