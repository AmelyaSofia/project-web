<?php
include './model/bookingModel.php';
include './model/clientModel.php';     
include './model/stylistModel.php';     
include './model/layananModel.php';  

class ControllerBooking {
    private $modelBooking;
    private $modelClient;
    private $modelStylist;
    private $modelLayanan;

    public function __construct() {
        $this->modelBooking = new ModelBooking();
        $this->modelClient = new ModelClient();
        $this->modelStylist = new ModelStylist();
        $this->modelLayanan = new ModelLayanan();
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
                    header("Location: index.php?modul=booking&fitur=booking");
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

    private function loadDropdownData() {
        $clients = $this->modelClient->getClients();
        $stylists = $this->modelStylist->getStylists();
        $layanans = $this->modelLayanan->getLayanans();
        return compact('clients', 'stylists', 'layanans');
    }

    public function addBooking() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_client = $_POST['id_client'];
            $id_stylist = $_POST['id_stylist'];
            $id_layanan = $_POST['id_layanan'];
            $tanggal = $_POST['tanggal'];
            $waktu = $_POST['waktu'];
            $catatan = $_POST['catatan'] ?? '';

            $success = $this->modelBooking->addBooking($id_client, $id_stylist, $id_layanan, $tanggal, $waktu, $catatan);
            if ($success) {
                header("Location: index.php?modul=booking&fitur=booking&message=Booking berhasil ditambahkan");
            } else {
                header("Location: index.php?modul=booking&fitur=tambah&message=Gagal menambahkan booking");
            }
            exit;
        } else {
            $dropdownData = $this->loadDropdownData();
            extract($dropdownData);
            $booking = null;
            include './view/bookingList.php';
        }
    }

    public function updateBooking($id_booking) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_client = $_POST['id_client'];
            $id_stylist = $_POST['id_stylist'];
            $id_layanan = $_POST['id_layanan'];
            $tanggal = $_POST['tanggal'];
            $waktu = $_POST['waktu'];
            $status = $_POST['status'];
            $catatan = $_POST['catatan'] ?? '';

            $success = $this->modelBooking->updateBooking($id_booking, $id_client, $id_stylist, $id_layanan, $tanggal, $waktu, $status, $catatan);
            if ($success) {
                header("Location: index.php?modul=booking&fitur=booking&message=Booking berhasil diupdate");
            } else {
                header("Location: index.php?modul=booking&fitur=update&id_booking=$id_booking&message=Gagal mengupdate booking");
            }
            exit;
        } else {
            $booking = $this->modelBooking->getBookingById($id_booking);
            if (!$booking) {
                header("Location: index.php?modul=booking&fitur=booking&message=Booking tidak ditemukan");
                exit;
            }
            $dropdownData = $this->loadDropdownData();
            extract($dropdownData);
            include './view/bookingList.php';
        }
    }

    public function updateStatus($id_booking, $status) {
        $success = $this->modelBooking->updateStatus($id_booking, $status);
        if ($success) {
            header("Location: index.php?modul=booking&fitur=booking&message=Status booking berhasil diubah");
        } else {
            header("Location: index.php?modul=booking&fitur=booking&message=Gagal mengubah status booking");
        }
        exit;
    }

    public function deleteBooking($id_booking) {
        $success = $this->modelBooking->deleteBooking($id_booking);
        if ($success) {
            header("Location: index.php?modul=booking&fitur=booking&message=Booking berhasil dihapus");
        } else {
            header("Location: index.php?modul=booking&fitur=booking&message=Gagal menghapus booking");
        }
        exit;
    }

public function listBookings() {
    $keyword = $_GET['search'] ?? '';
    if ($keyword) {
        $bookings = $this->modelBooking->searchBooking($keyword);
    } else {
        $bookings = $this->modelBooking->getBookings();
    }

    // Load data dropdown supaya $clients, $stylists, $layanans tersedia
    $dropdownData = $this->loadDropdownData();
    extract($dropdownData);

    include './view/bookingList.php';
}


}
?>