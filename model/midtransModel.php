<?php
require_once __DIR__ . '/../config/midtransconnect.php';
require_once __DIR__ . '/../config/dbconnect.php';

class ModelMidtrans {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function getBookingDetail($id_booking) {
        $stmt = $this->conn->prepare("
            SELECT c.nama_client, c.email 
            FROM booking b 
            JOIN client c ON b.id_client = c.id_client 
            WHERE b.id_booking = ?
        ");
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();

        $stmt2 = $this->conn->prepare("
            SELECT SUM(l.harga) AS total 
            FROM booking_layanan bl 
            JOIN layanan l ON bl.id_layanan = l.id_layanan 
            WHERE bl.id_booking = ?
        ");
        $stmt2->bind_param("i", $id_booking);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $layanan = $result2->fetch_assoc();

        return [
            'nama' => $client['nama_client'] ?? '',
            'email' => $client['email'] ?? '',
            'total' => $layanan['total'] ?? 0
        ];
    }

    public function buatSnapToken($id_booking, $nama, $email, $total_bayar, $jenis = 'dp') {
        $order_id = strtoupper($jenis) . '-' . $id_booking . '-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $total_bayar
            ],
            'customer_details' => [
                'first_name' => $nama,
                'email' => $email
            ]
        ];

        try {
            return \Midtrans\Snap::getSnapToken($params);
        } catch (Exception $e) {
            return 'Error: ' . $e->getMessage();
        }
    }

    public function simpanTransaksi($data) {
        $order_id = $data['order_id'] ?? '';
        $gross_amount = $data['gross_amount'] ?? 0;
        $payment_type = $data['payment_type'] ?? '';
        $midtrans_status = $data['transaction_status'] ?? '';

        if (!$order_id || !$gross_amount || !$payment_type) return false;

        if ($midtrans_status === 'settlement') {
            $status = 'dibayar';
        } elseif ($midtrans_status === 'pending') {
            $status = 'pending';
        } elseif (in_array($midtrans_status, ['expire', 'cancel'])) {
            $status = 'ditolak';
        } else {
            $status = 'belum';
        }

        $parts = explode('-', $order_id);
        if (count($parts) < 3) return false;

        $jenis = strtolower($parts[0]);
        $id_booking = intval($parts[1]);

        $cek = $this->conn->prepare("SELECT * FROM pembayaran WHERE id_booking = ? AND jenis = ?");
        $cek->bind_param("is", $id_booking, $jenis);
        $cek->execute();
        $result = $cek->get_result();

        if ($result->num_rows == 0) {
            $stmt = $this->conn->prepare("
                INSERT INTO pembayaran 
                (id_booking, order_id, jumlah, metode_pembayaran, status_pembayaran, jenis) 
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->bind_param("isdsss", $id_booking, $order_id, $gross_amount, $payment_type, $status, $jenis);
            return $stmt->execute();
        }

        return false;
    }
}