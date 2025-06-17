<?php
include __DIR__ . '/../config/dbconnect.php';
require_once __DIR__ . '/bookingModel.php'; 

class ModelPembayaran {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new ModelBooking();
    }

    public function simpanPembayaran($id_booking, $jenis, $jumlah, $bukti_pembayaran) {
        global $conn;
        $sql = "INSERT INTO pembayaran (id_booking, jenis, jumlah, bukti_pembayaran, status) VALUES (?, ?, ?, ?, 'pending')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss", $id_booking, $jenis, $jumlah, $bukti_pembayaran);
        return $stmt->execute();
    }

    public function getPembayaranByBooking($id_booking) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM pembayaran WHERE id_booking = ?");
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function verifikasiPembayaran($id_pembayaran, $status, $alasan = null) {
        global $conn;

        // Ambil data pembayaran untuk keperluan logika tambahan
        $pembayaran = $this->getPembayaranById($id_pembayaran);

        if (!$pembayaran) return false;

        // Update status dan alasan penolakan
        if ($status === 'ditolak') {
            $stmt = $conn->prepare("UPDATE pembayaran SET status = ?, alasan_penolakan = ? WHERE id_pembayaran = ?");
            $stmt->bind_param("ssi", $status, $alasan, $id_pembayaran);
        } else {
            $stmt = $conn->prepare("UPDATE pembayaran SET status = ?, alasan_penolakan = NULL WHERE id_pembayaran = ?");
            $stmt->bind_param("si", $status, $id_pembayaran);
        }

        $stmt->execute();

        // Jika pelunasan diterima, update status booking jadi 'terjadwal'
        if ($status === 'dibayar' && $pembayaran['jenis'] === 'pelunasan') {
            $this->bookingModel->updateStatus($pembayaran['id_booking'], 'terjadwal');
        }

        return true;
    }

    public function cekDPTelahDibayar($id_booking) {
        global $conn;

        $sql = "SELECT * FROM pembayaran 
                WHERE id_booking = ? AND jenis = 'dp' AND status = 'dibayar' 
                LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc();
        }

        return null; // Kembalikan null jika tidak ditemukan
    }

    public function getPembayaranById($id_pembayaran) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM pembayaran WHERE id_pembayaran = ?");
        $stmt->bind_param("i", $id_pembayaran);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function cekDPTerbayar($id_booking) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM pembayaran 
                                WHERE id_booking = ? AND jenis = 'dp' AND status = 'dibayar'");
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function cekPelunasan($id_booking) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM pembayaran 
                                WHERE id_booking = ? AND jenis = 'pelunasan' AND status = 'dibayar'");
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllPembayaran() {
        global $conn;
        $sql = "SELECT p.id_pembayaran, c.nama_client, p.jenis, p.status, p.bukti_pembayaran
                FROM pembayaran p
                JOIN booking b ON p.id_booking = b.id_booking
                JOIN client c ON b.id_client = c.id_client";
        $result = $conn->query($sql);
        $pembayarans = [];
        while ($row = $result->fetch_assoc()) {
            $pembayarans[] = $row;
        }
        return $pembayarans;
    }

}
