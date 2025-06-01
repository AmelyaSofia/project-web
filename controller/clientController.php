<?php
include './model/clientModel.php';

class ControllerClient {
    private $model;

    public function __construct() {
        $this->model = new ModelClient();
    }

    public function handleRequest($fitur, $id_client) {
        $id_booking = $_GET['id_booking'] ?? null;

        switch ($fitur) {
            case 'booking_add':
                $this->addBooking($id_client);
                break;
            case 'booking_cancel':
                if ($id_booking) {
                    $this->cancelBooking($id_booking, $id_client);
                } else {
                    header("Location: index.php?fitur=booking_list");
                }
                break;
            case 'booking_list':
                $this->listBookings($id_client);
                break;
            default:
                $this->listClients();
                break;
        }
    }

    public function listClients() {
        $keyword = $_GET['search'] ?? null;
        if ($keyword) {
            $clients = $this->model->searchClient($keyword);
        } else {
            $clients = $this->model->getClients();
        }
        include './view/client/clientList.php';
    }

    public function listBookings($id_client) {
        $bookings = $this->model->getClientBookings($id_client);
        include './view/client/bookingList.php';
    }

    public function addBooking($id_client) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_layanan = $_POST['id_layanan'];
            $tanggal = $_POST['tanggal'];
            $jam = $_POST['jam'];

            $berhasil = $this->model->addBooking($id_client, $id_layanan, $tanggal, $jam);
            if ($berhasil) {
                header("Location: index.php?fitur=booking_list&message=Booking berhasil dibuat");
            } else {
                header("Location: index.php?fitur=booking_add&message=Gagal membuat booking");
            }
            exit;
        } else {
            include './view/client/bookingAdd.php';
        }
    }

    public function cancelBooking($id_booking, $id_client) {
        $berhasil = $this->model->cancelBooking($id_booking, $id_client);
        if ($berhasil) {
            header("Location: index.php?fitur=booking_list&message=Booking berhasil dibatalkan");
        } else {
            header("Location: index.php?fitur=booking_list&message=Gagal membatalkan booking");
        }
        exit;
    }
}
?>
