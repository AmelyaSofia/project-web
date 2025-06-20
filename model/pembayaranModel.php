<?php
include __DIR__. '/../config/dbconnect.php';
require_once __DIR__ . '/bookingModel.php';

class ModelPembayaran {
    private $bookingModel;
    private $conn;
    private $limit = 10; 

    public function __construct() {
        global $conn;
        $this->conn = $conn;
        $this->bookingModel = new ModelBooking();
    }

    public function simpanPembayaran($id_booking, $jenis, $jumlah, $bukti_pembayaran, $metode_pembayaran) {
        $sql = "INSERT INTO pembayaran (id_booking, jenis, jumlah, bukti_pembayaran, metode_pembayaran, status_pembayaran) 
                VALUES (?, ?, ?, ?, ?, 'pending')";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("issss", $id_booking, $jenis, $jumlah, $bukti_pembayaran, $metode_pembayaran);
        return $stmt->execute();
    }

    public function getPembayaranByBooking($id_booking) {
        $stmt = $this->conn->prepare("SELECT * FROM pembayaran WHERE id_booking = ?");
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function verifikasiPembayaran($id_pembayaran, $status_pembayaran, $alasan = null) {
        $pembayaran = $this->getPembayaranById($id_pembayaran);

        if (!$pembayaran) return false;

        if ($status_pembayaran === 'ditolak') {
            $stmt = $this->conn->prepare("UPDATE pembayaran SET status_pembayaran = ?, alasan_penolakan = ? WHERE id_pembayaran = ?");
            $stmt->bind_param("ssi", $status_pembayaran, $alasan, $id_pembayaran);
        } else {
            $stmt = $this->conn->prepare("UPDATE pembayaran SET status_pembayaran = ?, alasan_penolakan = NULL WHERE id_pembayaran = ?");
            $stmt->bind_param("si", $status_pembayaran, $id_pembayaran);
        }

        $stmt->execute();

        if ($status_pembayaran === 'dibayar' && $pembayaran['jenis'] === 'pelunasan') {
            $this->bookingModel->updateStatus($pembayaran['id_booking'], 'terjadwal');
        }

        return true;
    }

    public function cekDPTelahDibayar($id_booking) {
        $sql = "SELECT * FROM pembayaran 
                WHERE id_booking = ? AND jenis = 'dp' 
                ORDER BY id_pembayaran DESC LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            return $result->fetch_assoc(); 
        }

        return null; 
    }

    public function getPembayaranById($id_pembayaran) {
        $stmt = $this->conn->prepare("SELECT * FROM pembayaran WHERE id_pembayaran = ?");
        $stmt->bind_param("i", $id_pembayaran);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function cekDPTerbayar($id_booking) {
        $stmt = $this->conn->prepare("SELECT * FROM pembayaran 
                                    WHERE id_booking = ? AND jenis = 'dp' AND status_pembayaran = 'dibayar'");
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function cekPembayaranLunas($id_booking) {
        $sql = "SELECT * FROM pembayaran 
                WHERE id_booking = ? AND jenis = 'lunas'";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getAllPembayaran($page = 1, $start_date = null, $end_date = null) {
        $start = ($page > 1) ? ($page * $this->limit) - $this->limit : 0;
        
        $base_query = "SELECT p.*, c.nama_client, b.tanggal as tanggal_booking
                      FROM pembayaran p
                      JOIN booking b ON p.id_booking = b.id_booking
                      JOIN client c ON b.id_client = c.id_client";
        
        $where_conditions = [];
        $params = [];
        $types = '';
        
        if (!empty($start_date)) {
            $where_conditions[] = "p.tanggal_pembayaran >= ?";
            $params[] = $start_date;
            $types .= 's';
        }
        
        if (!empty($end_date)) {
            $where_conditions[] = "p.tanggal_pembayaran <= ?";
            $params[] = $end_date . ' 23:59:59';
            $types .= 's';
        }

        $count_query = "SELECT COUNT(*) as total FROM pembayaran p";
        if (!empty($where_conditions)) {
            $count_query .= " WHERE " . implode(' AND ', $where_conditions);
        }
        
        $count_stmt = $this->conn->prepare($count_query);
        if (!empty($params)) {
            $count_stmt->bind_param($types, ...$params);
        }
        $count_stmt->execute();
        $total_row = $count_stmt->get_result()->fetch_assoc();
        $total_data = $total_row['total'];
        $total_pages = ceil($total_data / $this->limit);
        
        // Main query
        $query = $base_query;
        if (!empty($where_conditions)) {
            $query .= " WHERE " . implode(' AND ', $where_conditions);
        }
        $query .= " ORDER BY p.id_pembayaran ASC LIMIT ?, ?";
        
        $params[] = $start;
        $params[] = $this->limit;
        $types .= 'ii';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pembayarans = [];
        while ($row = $result->fetch_assoc()) {
            $pembayarans[] = $row;
        }
        
        return [
            'pembayarans' => $pembayarans,
            'total_data' => $total_data,
            'total_pages' => $total_pages,
            'current_page' => $page
        ];
    }

    public function getPembayaranWithFilter($filters = []) {
        $page = $filters['page'] ?? 1;
        $start_date = $filters['start_date'] ?? null;
        $end_date = $filters['end_date'] ?? null;
        $status = $filters['status'] ?? null;
        
        $start = ($page > 1) ? ($page * $this->limit) - $this->limit : 0;
        
        $base_query = "SELECT p.*, c.nama_client, b.tanggal as tanggal_booking
                      FROM pembayaran p
                      JOIN booking b ON p.id_booking = b.id_booking
                      JOIN client c ON b.id_client = c.id_client";
        
        $where_conditions = [];
        $params = [];
        $types = '';
        
        if (!empty($start_date)) {
            $where_conditions[] = "p.tanggal_pembayaran >= ?";
            $params[] = $start_date;
            $types .= 's';
        }
        
        if (!empty($end_date)) {
            $where_conditions[] = "p.tanggal_pembayaran <= ?";
            $params[] = $end_date . ' 23:59:59';
            $types .= 's';
        }
        
        if (!empty($status)) {
            $where_conditions[] = "p.status_pembayaran = ?";
            $params[] = $status;
            $types .= 's';
        }

        $count_query = "SELECT COUNT(*) as total FROM pembayaran p";
        if (!empty($where_conditions)) {
            $count_query .= " WHERE " . implode(' AND ', $where_conditions);
        }
        
        $count_stmt = $this->conn->prepare($count_query);
        if (!empty($params)) {
            $count_stmt->bind_param($types, ...$params);
        }
        $count_stmt->execute();
        $total_row = $count_stmt->get_result()->fetch_assoc();
        $total_data = $total_row['total'];
        $total_pages = ceil($total_data / $this->limit);

        $query = $base_query;
        if (!empty($where_conditions)) {
            $query .= " WHERE " . implode(' AND ', $where_conditions);
        }
        $query .= " ORDER BY p.id_pembayaran ASC LIMIT ?, ?";
        
        $params[] = $start;
        $params[] = $this->limit;
        $types .= 'ii';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param($types, ...$params);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $pembayarans = [];
        while ($row = $result->fetch_assoc()) {
            $pembayarans[] = $row;
        }
        
        return [
            'pembayarans' => $pembayarans,
            'total_data' => $total_data,
            'total_pages' => $total_pages,
            'current_page' => $page
        ];
    }
}