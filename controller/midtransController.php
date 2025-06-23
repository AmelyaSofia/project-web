<?php
require_once __DIR__ . '/../model/midtransModel.php';
require_once __DIR__ . '/../config/dbconnect.php';

class ControllerMidtrans {
    public $model;

    public function __construct($conn) {
        $this->model = new ModelMidtrans($conn);
    }

    public function handleRequest($fitur) {
        switch ($fitur) {
            case 'snap_token':
                $this->buatSnapToken();
                break;
            case 'simpan':
                $this->simpanTransaksi();
                break;
            case 'list':
                include './view/midtransList.php';
                break;
            default:
                echo "Fitur tidak ditemukan";
        }
    }

    private function buatSnapToken() {
        $id_booking = $_GET['id_booking'] ?? null;
        $jenis = $_GET['jenis'] ?? 'dp'; 

        if (!$id_booking) {
            echo "ID booking tidak ditemukan";
            return;
        }

        $detail = $this->model->getBookingDetail($id_booking);
        if (!$detail || $detail['total'] <= 0) {
            echo "Booking tidak valid atau belum pilih layanan";
            return;
        }

        $bayar = ($jenis === 'lunas') 
            ? round($detail['total'] * 0.7)
            : round($detail['total'] * 0.3);

        $token = $this->model->buatSnapToken($id_booking, $detail['nama'], $detail['email'], $bayar, $jenis);
        echo $token;
    }

    private function simpanTransaksi() {
        $data = json_decode(file_get_contents("php://input"), true);

        file_put_contents(__DIR__ . '/../log_midtrans.json', json_encode($data, JSON_PRETTY_PRINT));

        if (!$data || !isset($data['order_id'])) {
            echo "Data transaksi tidak valid";
            return;
        }

        if ($this->model->simpanTransaksi($data)) {
            echo "Berhasil disimpan";
        } else {
            echo "Transaksi sudah ada atau gagal disimpan";
        }
    }

    public function getSnapTokenLangsung($id_booking, $jenis = 'dp') {
        $detail = $this->model->getBookingDetail($id_booking);
        if (!$detail || $detail['total'] <= 0) return 'Data booking tidak valid';

        $bayar = ($jenis === 'lunas') 
            ? round($detail['total'] * 0.7)
            : round($detail['total'] * 0.3);

        return $this->model->buatSnapToken($id_booking, $detail['nama'], $detail['email'], $bayar, $jenis);
    }
}