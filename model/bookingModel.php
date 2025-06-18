<?php
include __DIR__.'/../config/dbconnect.php';

class ModelBooking {
    public function getBookings() {
    global $conn;
    $sql = "SELECT 
                b.*, 
                c.nama_client, 
                s.nama_stylist
            FROM booking b
            JOIN client c ON b.id_client = c.id_client
            JOIN stylist s ON b.id_stylist = s.id_stylist
            ORDER BY b.tanggal DESC, b.waktu DESC";
    
    $result = mysqli_query($conn, $sql);

    $bookings = [];
    if ($result && $result->num_rows > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            // Ambil semua layanan untuk booking ini
            $layanans = $this->getLayananByBooking($row['id_booking']);
            
            // Gabungkan nama layanan menjadi satu string
            $nama_layanan = array_column($layanans, 'nama_layanan');
            $row['nama_layanan'] = implode(', ', $nama_layanan);

            // Hitung total harga
            $total_harga = array_sum(array_column($layanans, 'harga'));
            $row['harga'] = $total_harga;

            $bookings[] = $row;
        }
    }

    return $bookings;
}


    public function getBookingById($id_booking) {
        global $conn;
        $sql = "SELECT * FROM booking WHERE id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();

        if ($booking) {
            $booking['layanans'] = $this->getLayananByBooking($id_booking);
        }

        return $booking;
    }

public function addBooking($id_client, $id_stylist, $tanggal, $waktu, $catatan, $layanan_list) {
    global $conn;

    $sql = "INSERT INTO booking (id_client, id_stylist, tanggal, waktu, status, catatan) 
            VALUES (?, ?, ?, ?, 'menunggu', ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iisss", $id_client, $id_stylist, $tanggal, $waktu, $catatan);

    if (!$stmt->execute()) {
        return false;
    }

    $id_booking = $conn->insert_id;

    $sqlLayanan = "INSERT INTO booking_layanan (id_booking, id_layanan) VALUES (?, ?)";
    $stmtLayanan = $conn->prepare($sqlLayanan);

    foreach ($layanan_list as $id_layanan) {
        $stmtLayanan->bind_param("ii", $id_booking, $id_layanan);
        $stmtLayanan->execute();
    }

    return $id_booking;
}

    public function updateBooking($id_booking, $id_client, $id_stylist, $tanggal, $waktu, $status, $catatan, $layanan_list) {
        global $conn;

        $sql = "UPDATE booking 
                SET id_client = ?, id_stylist = ?, tanggal = ?, waktu = ?, status = ?, catatan = ?
                WHERE id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissssi", $id_client, $id_stylist, $tanggal, $waktu, $status, $catatan, $id_booking);

        if (!$stmt->execute()) {
            return false;
        }

        $conn->query("DELETE FROM booking_layanan WHERE id_booking = $id_booking");

        $sqlLayanan = "INSERT INTO booking_layanan (id_booking, id_layanan) VALUES (?, ?)";
        $stmtLayanan = $conn->prepare($sqlLayanan);

        foreach ($layanan_list as $id_layanan) {
            $stmtLayanan->bind_param("ii", $id_booking, $id_layanan);
            $stmtLayanan->execute();
        }

        return true;
    }

    public function updateStatus($id_booking, $status) {
        global $conn;
        $sql = "UPDATE booking SET status = ? WHERE id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id_booking);
        return $stmt->execute();
    }

    public function deleteBooking($id_booking) {
        global $conn;

        $conn->query("DELETE FROM booking_layanan WHERE id_booking = $id_booking");

        $sql = "DELETE FROM booking WHERE id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_booking);
        return $stmt->execute();
    }

    public function searchBooking($keyword) {
        global $conn;
        $keyword = "%$keyword%";

        $sql = "SELECT DISTINCT b.*, c.nama_client, s.nama_stylist
                FROM booking b
                JOIN client c ON b.id_client = c.id_client
                JOIN stylist s ON b.id_stylist = s.id_stylist
                LEFT JOIN booking_layanan bl ON b.id_booking = bl.id_booking
                LEFT JOIN layanan l ON bl.id_layanan = l.id_layanan
                WHERE c.nama_client LIKE ? 
                   OR s.nama_stylist LIKE ?
                   OR l.nama_layanan LIKE ?
                ORDER BY b.tanggal DESC, b.waktu DESC";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $row['layanans'] = $this->getLayananByBooking($row['id_booking']);
                $bookings[] = $row;
            }
        }

        return $bookings;
    }

    public function getBookingByClient($id_client) {
        global $conn;
        $sql = "SELECT b.*, c.nama_client, s.nama_stylist
                FROM booking b
                JOIN client c ON b.id_client = c.id_client
                JOIN stylist s ON b.id_stylist = s.id_stylist
                WHERE b.id_client = ?
                ORDER BY b.tanggal DESC, b.waktu DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_client);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        if ($result && $result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $row['layanans'] = $this->getLayananByBooking($row['id_booking']);
                $bookings[] = $row;
            }
        }

        return $bookings;
    }

    public function getLayananByBooking($id_booking) {
        global $conn;
        $sql = "SELECT l.id_layanan, l.nama_layanan, l.harga 
                FROM booking_layanan bl
                JOIN layanan l ON bl.id_layanan = l.id_layanan
                WHERE bl.id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_booking);
        $stmt->execute();
        $result = $stmt->get_result();

        $layanans = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $layanans[] = $row;
        }

        return $layanans;
    }
    public function hitungTotalHarga($id_booking) {
    global $conn;

    $sql = "SELECT SUM(l.harga) AS total 
            FROM booking_layanan bl
            JOIN layanan l ON bl.id_layanan = l.id_layanan
            WHERE bl.id_booking = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_booking);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['total'] ?: 0;
}
}