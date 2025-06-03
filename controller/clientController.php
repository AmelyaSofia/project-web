<?php
include './model/clientModel.php';

class ControllerClient {
    private $model;

    public function __construct() {
        $this->model = new ModelClient();
    }

    public function handleRequest($fitur) {
        $id_client = $_GET['id_client'] ?? null;

        switch ($fitur) {
            case 'tambah':
                $this->addClient();
                break;
            case 'update':
                if ($id_client) {
                    $this->updateClient($id_client);
                } else {
                    header("Location: index.php?fitur=client");
                }
                break;
            case 'hapus':
                if ($id_client) {
                    $this->deleteClient((int)$id_client);
                } else {
                    header("Location: index.php?fitur=client");
                }
                break;
            default:
                $this->listClients();
                break;
        }
    }

    public function addClient() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_client = $_POST['nama_client'];
            $email_client = $_POST['email_client'];
            $notelp_client = $_POST['notelp_client'];
            $alamat_client = $_POST['alamat_client'];

            $berhasil = $this->model->addClient($nama_client, $email_client, $notelp_client, $alamat_client);
            if ($berhasil) {
                header("Location: index.php?fitur=client&message=Client berhasil ditambahkan");
            } else {
                header("Location: index.php?fitur=tambah&message=Gagal menambahkan client");
            }
            exit;
        } else {
            include './view/client/clientAdd.php';
        }
    }

    public function updateClient($id_client) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama_client = $_POST['nama_client'];
            $email_client = $_POST['email_client'];
            $notelp_client = $_POST['notelp_client'];
            $alamat_client = $_POST['alamat_client'];

            $berhasil = $this->model->updateClient($id_client, $nama_client, $email_client, $notelp_client, $alamat_client);
            if ($berhasil) {
                header("Location: index.php?fitur=client&message=Client berhasil diupdate");
            } else {
                header("Location: index.php?fitur=update&id_client=$id_client&message=Gagal mengupdate client");
            }
            exit;
        } else {
            $client = $this->model->getClientById($id_client);
            if (!$client) {
                header("Location: index.php?fitur=client&message=Client tidak ditemukan");
                exit;
            }
            include './view/client/clientUpdate.php';
        }
    }

    public function deleteClient($id_client) {
        $berhasil = $this->model->deleteClient($id_client);
        if ($berhasil) {
            header("Location: index.php?fitur=client&message=Client berhasil dihapus");
        } else {
            header("Location: index.php?fitur=client&message=Gagal menghapus client");
        }
        exit;
    }

    public function listClients() {
        $clients = $this->model->getClients();
        include './view/client/clientList.php';
    }

    public function handleBooking() {
        session_start();
        $step = $_GET['step'] ?? 'pilih_layanan';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleBookingPost();
            // Redirect to prevent form resubmission
            header("Location: ?step=" . ($_SESSION['booking']['step'] ?? 'pilih_layanan'));
            exit;
        }
        
        // Prepare data for view
        $data = [
            'step' => $step,
            'stylists' => $this->model->getStylists(),
            'services' => $this->model->getServices(),
            'paymentMethods' => $this->model->getPaymentMethods(),
            'booking' => $_SESSION['booking'] ?? []
        ];
        
        include './view/client/clientList.php';
    }

    private function handleBookingPost() {
        if (isset($_POST['service_id'])) {
            $services = $this->model->getServices();
            $_SESSION['booking'] = [
                'service_id' => $_POST['service_id'],
                'service_name' => $services[$_POST['service_id']]['nama'],
                'service_price' => $services[$_POST['service_id']]['harga'],
                'step' => 'pilih_stylist'
            ];
        }
        elseif (isset($_POST['stylist_id'])) {
            $stylists = $this->model->getStylists();
            $_SESSION['booking']['stylist_id'] = $_POST['stylist_id'];
            $_SESSION['booking']['stylist_name'] = $stylists[$_POST['stylist_id']]['nama'];
            $_SESSION['booking']['step'] = 'pilih_jadwal';
        }
        elseif (isset($_POST['tanggal']) && isset($_POST['jam'])) {
            $_SESSION['booking']['tanggal'] = $_POST['tanggal'];
            $_SESSION['booking']['jam'] = $_POST['jam'];
            $_SESSION['booking']['step'] = 'konfirmasi';
        }
        elseif (isset($_POST['confirm'])) {
            $_SESSION['booking']['payment_method'] = $_POST['payment_method'];
            $_SESSION['booking']['catatan'] = $_POST['catatan'] ?? '';
            $_SESSION['booking']['step'] = 'selesai';
            
            $bookingData = [
                'id_client' => $_SESSION['id_client'] ?? 2,
                'service_id' => $_SESSION['booking']['service_id'],
                'tanggal' => $_SESSION['booking']['tanggal'],
                'jam' => $_SESSION['booking']['jam'],
                'status' => 'menunggu'
            ];
            
            $this->model->createBooking($bookingData);
        }
    }
}
?>
