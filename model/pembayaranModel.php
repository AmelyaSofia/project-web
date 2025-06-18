<?php
include __DIR__. '/../config/dbconnect.php';
require_once __DIR__ . '/bookingModel.php';

class ModelPembayaran {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new ModelBooking();
    }

public function simpanPembayaran($id_booking, $jenis, $jumlah, $bukti_pembayaran, $metode_pembayaran) {
    global $conn;
    $sql = "INSERT INTO pembayaran (id_booking, jenis, jumlah, bukti_pembayaran, metode_pembayaran, status_pembayaran) 
            VALUES (?, ?, ?, ?, ?, 'pending')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $id_booking, $jenis, $jumlah, $bukti_pembayaran, $metode_pembayaran);
    return $stmt->execute();
}



    public function getPembayaranByBooking($id_booking) {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM pembayaran WHERE id_booking = ?");
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function verifikasiPembayaran($id_pembayaran, $status_pembayaran, $alasan = null) {
        global $conn;

        // Ambil data pembayaran untuk keperluan logika tambahan
        $pembayaran = $this->getPembayaranById($id_pembayaran);

        if (!$pembayaran) return false;

        // Update status_pembayaran dan alasan penolakan
        if ($status_pembayaran === 'ditolak') {
            $stmt = $conn->prepare("UPDATE pembayaran SET status_pembayaran = ?, alasan_penolakan = ? WHERE id_pembayaran = ?");
            $stmt->bind_param("ssi", $status_pembayaran, $alasan, $id_pembayaran);
        } else {
            $stmt = $conn->prepare("UPDATE pembayaran SET status_pembayaran = ?, alasan_penolakan = NULL WHERE id_pembayaran = ?");
            $stmt->bind_param("si", $status_pembayaran, $id_pembayaran);
        }

        $stmt->execute();

        // Jika pelunasan diterima, update status_pembayaran booking jadi 'terjadwal'
        if ($status_pembayaran === 'dibayar' && $pembayaran['jenis'] === 'pelunasan') {
            $this->bookingModel->updateStatus($pembayaran['id_booking'], 'terjadwal');
        }

        return true;
    }

public function cekDPTelahDibayar($id_booking) {
    global $conn;

    $sql = "SELECT * FROM pembayaran 
            WHERE id_booking = ? AND jenis = 'dp' 
            ORDER BY id_pembayaran DESC LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_booking);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc(); // bisa status 'dibayar', 'pending', atau 'ditolak'
    }

    return null; // Belum bayar DP
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
                                WHERE id_booking = ? AND jenis = 'dp' AND status_pembayaran = 'dibayar'");
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // public function cekPelunasan($id_booking) {
    //     global $conn;
    //     $stmt = $conn->prepare("SELECT * FROM pembayaran 
    //                             WHERE id_booking = ? AND jenis = 'pelunasan' AND status_pembayaran = 'dibayar'");
    //     $stmt->bind_param("i", $id_booking);
    //     $stmt->execute();
    //     return $stmt->get_result()->fetch_assoc();
    // }
    // public function cekPembayaranLunas($id_booking) {
    // global $conn;
    
    // try {
    //     $stmt = $conn->prepare("SELECT * FROM pembayaran 
    //                           WHERE id_booking = ? AND jenis_pembayaran = 'lunas'");
    //     if (!$stmt) {
    //         throw new Exception("Prepare statement error: " . $conn->error);
    //     }
        
    //     $stmt->bind_param("i", $id_booking);
        
    //     if (!$stmt->execute()) {
    //         throw new Exception("Execute statement error: " . $stmt->error);
    //     }
        
    //     $result = $stmt->get_result();
    //     return $result->fetch_assoc();
        
    // } catch (Exception $e) {
    //     error_log("Error in cekPembayaranLunas: " . $e->getMessage());
    //     return false;
    // }
    public function cekPembayaranLunas($id_booking) {
    global $conn;
    $sql = "SELECT * FROM pembayaran 
            WHERE id_booking = ? AND jenis = 'lunas'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_booking);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


    public function getAllPembayaran() {
        global $conn;
        $sql = "SELECT p.id_pembayaran, c.nama_client, p.jenis, p.status_pembayaran, p.bukti_pembayaran
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