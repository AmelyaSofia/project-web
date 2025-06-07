<?php
include './config/dbconnect.php';

class ModelBooking {
    public function getBookings() {
        global $conn;
        $sql = "SELECT b.*, c.nama_client, s.nama_stylist, l.nama_layanan 
                FROM booking b
                JOIN client c ON b.id_client = c.id_client
                JOIN stylist s ON b.id_stylist = s.id_stylist
                JOIN layanan l ON b.id_layanan = l.id_layanan
                ORDER BY b.tanggal DESC, b.waktu DESC";
        $result = mysqli_query($conn, $sql);

        $bookings = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
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
        return $result->fetch_assoc();
    }

    public function addBooking($id_client, $id_stylist, $id_layanan, $tanggal, $waktu, $catatan) {
        global $conn;
        $sql = "INSERT INTO booking (id_client, id_stylist, id_layanan, tanggal, waktu, status, catatan) 
                VALUES (?, ?, ?, ?, ?, 'menunggu', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiisss", $id_client, $id_stylist, $id_layanan, $tanggal, $waktu, $catatan);
        return $stmt->execute();
    }

    public function updateBooking($id_booking, $id_client, $id_stylist, $id_layanan, $tanggal, $waktu, $status, $catatan) {
        global $conn;
        $sql = "UPDATE booking SET id_client = ?, id_stylist = ?, id_layanan = ?, tanggal = ?, waktu = ?, status = ?, catatan = ? WHERE id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiissssi", $id_client, $id_stylist, $id_layanan, $tanggal, $waktu, $status, $catatan, $id_booking);
        return $stmt->execute();
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
        $sql = "DELETE FROM booking WHERE id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_booking);
        return $stmt->execute();
    }

    public function searchBooking($keyword) {
        global $conn;
        $keyword = "%$keyword%";
        $sql = "SELECT b.*, c.nama_client, s.nama_stylist, l.nama_layanan
                FROM booking b
                JOIN client c ON b.id_client = c.id_client
                JOIN stylist s ON b.id_stylist = s.id_stylist
                JOIN layanan l ON b.id_layanan = l.id_layanan
                WHERE c.nama_client LIKE ? OR s.nama_stylist LIKE ? OR l.nama_layanan LIKE ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $bookings[] = $row;
            }
        }
        return $bookings;
    }
}
?>
