<?php
require_once __DIR__.'/../model/bookingModel.php';
require_once __DIR__.'/../model/clientModel.php';
require_once __DIR__.'/../model/stylistModel.php';
require_once __DIR__.'/../model/layananModel.php';

class ControllerBooking {
    public $modelBooking;
    public $modelClient;
    public $modelStylist;
    public $modelLayanan;

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
            case 'riwayat_client':
                $this->riwayatBookingClient();
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
            $tanggal = $_POST['tanggal'];
            $waktu = $_POST['waktu'];
            $catatan = $_POST['catatan'] ?? '';
            $layanan_list = $_POST['id_layanan'] ?? [];

            if (empty($layanan_list)) {
                header("Location: index.php?modul=booking&fitur=tambah&message=Harap pilih minimal satu layanan");
                exit;
            }

            $success = $this->modelBooking->addBooking($id_client, $id_stylist, $tanggal, $waktu, $catatan, $layanan_list);
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
            $tanggal = $_POST['tanggal'];
            $waktu = $_POST['waktu'];
            $status = $_POST['status'];
            $catatan = $_POST['catatan'] ?? '';
            $layanan_list = $_POST['id_layanan'] ?? [];

            if (empty($layanan_list)) {
                header("Location: index.php?modul=booking&fitur=update&id_booking=$id_booking&message=Harap pilih minimal satu layanan");
                exit;
            }

            $success = $this->modelBooking->updateBooking($id_booking, $id_client, $id_stylist, $tanggal, $waktu, $status, $catatan, $layanan_list);
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

            $layananTerpilih = $this->modelBooking->getLayananByBooking($id_booking);

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

        $dropdownData = $this->loadDropdownData();
        extract($dropdownData);

        include './view/bookingList.php';
    }

    public function riwayatBookingClient() {
        if(session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $id_client = $_SESSION['id_client'] ?? null;
        if (!$id_client) {
            echo "Silakan login terlebih dahulu.";
            exit;
        }
        $riwayat = $this->modelBooking->getBookingByClient($id_client);

        include './view/riwayatBooking.php';
    }
}
?>