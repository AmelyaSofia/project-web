<?php
include './model/clientModel.php';

class ControllerClient {
    private $model;

    public function __construct() {
        $this->model = new ModelClient();
    }

    public function handleRequest($fitur, $id_client = null) {
        $id_booking = $_GET['id_booking'] ?? null;

        switch ($fitur) {
            case 'booking_add':
                if ($id_client) {
                    $this->addBooking($id_client);
                } else {
                    header("Location: index.php?fitur=list");
                }
                break;
            case 'booking_cancel':
                if ($id_booking && $id_client) {
                    $this->cancelBooking($id_booking, $id_client);
                } else {
                    header("Location: index.php?fitur=booking_list&id_client=$id_client");
                }
                break;
            case 'booking_list':
                if ($id_client) {
                    $this->listBookings($id_client);
                } else {
                    header("Location: index.php?fitur=list");
                }
                break;
            case 'list':
                $this->listClients();
                break;
            case 'add':
                $this->addClient();
                break;
            case 'edit':
                if ($id_client) {
                    $this->editClient($id_client);
                }
                break;
            case 'delete':
                if ($id_client) {
                    $this->deleteClient($id_client);
                }
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

    public function addClient() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_client = $_POST['nama_client'];
            $email_client = $_POST['email_client'];
            $notelp_client = $_POST['notelp_client'];
            $alamat_client = $_POST['alamat_client'];
            $tanggal_daftar = $_POST['tanggal_daftar'];

            $berhasil = $this->model->addClient($nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar);

            if ($berhasil) {
                header("Location: index.php?fitur=list&message=Client berhasil ditambahkan");
            } else {
                header("Location: index.php?fitur=add&message=Gagal menambahkan client");
            }
            exit;
        } else {
            include './view/client/clientAdd.php';
        }
    }

    public function editClient($id_client) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_client = $_POST['nama_client'];
            $email_client = $_POST['email_client'];
            $notelp_client = $_POST['notelp_client'];
            $alamat_client = $_POST['alamat_client'];
            $tanggal_daftar = $_POST['tanggal_daftar'];

            $berhasil = $this->model->updateClient($id_client, $nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar);

            if ($berhasil) {
                header("Location: index.php?fitur=list&message=Client berhasil diperbarui");
            } else {
                header("Location: index.php?fitur=edit&id_client=$id_client&message=Gagal memperbarui client");
            }
            exit;
        } else {
            $client = $this->model->getClientById($id_client);
            include './view/client/clientEdit.php';
        }
    }

    public function deleteClient($id_client) {
        $berhasil = $this->model->deleteClient($id_client);
        if ($berhasil) {
            header("Location: index.php?fitur=list&message=Client berhasil dihapus");
        } else {
            header("Location: index.php?fitur=list&message=Gagal menghapus client");
        }
        exit;
    }

    public function listBookings($id_client) {
        $client = $this->model->getClientById($id_client);
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
                header("Location: index.php?fitur=booking_list&id_client=$id_client&message=Booking berhasil dibuat");
            } else {
                header("Location: index.php?fitur=booking_add&id_client=$id_client&message=Gagal membuat booking");
            }
            exit;
        } else {
            include './view/client/bookingAdd.php';
        }
    }

    public function cancelBooking($id_booking, $id_client) {
        $berhasil = $this->model->cancelBooking($id_booking, $id_client);
        if ($berhasil) {
            header("Location: index.php?fitur=booking_list&id_client=$id_client&message=Booking berhasil dibatalkan");
        } else {
            header("Location: index.php?fitur=booking_list&id_client=$id_client&message=Gagal membatalkan booking");
        }
        exit;
    }
}
?>
