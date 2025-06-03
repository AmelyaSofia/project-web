<?php
include './config/dbconnect.php';

class ModelBooking {
    public function getBookings() {
        global $conn;
        $sql = "SELECT b.*, c.nama_client, l.nama_layanan 
                FROM booking b
                JOIN client c ON b.id_client = c.id_client
                JOIN layanan l ON b.id_layanan = l.id_layanan
                ORDER BY b.tanggal DESC, b.jam DESC";
        $result = mysqli_query($conn, $sql);
        $bookings = [];

        if ($result && $result->num_rows > 0) {
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

    public function addBooking($id_client, $id_layanan, $tanggal, $jam, $status = 'menunggu') {
        global $conn;
        $sql = "INSERT INTO booking (id_client, id_layanan, tanggal, jam, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisss", $id_client, $id_layanan, $tanggal, $jam, $status);
        return $stmt->execute();
    }

    public function updateBooking($id_booking, $id_client, $id_layanan, $tanggal, $jam, $status) {
        global $conn;
        $sql = "UPDATE booking 
                SET id_client = ?, id_layanan = ?, tanggal = ?, jam = ?, status = ? 
                WHERE id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisssi", $id_client, $id_layanan, $tanggal, $jam, $status, $id_booking);
        return $stmt->execute();
    }

    public function deleteBooking($id_booking) {
        global $conn;
        $sql = "DELETE FROM booking WHERE id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_booking);
        return $stmt->execute();
    }

    public function updateStatus($id_booking, $status) {
        global $conn;
        $sql = "UPDATE booking SET status = ? WHERE id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id_booking);
        return $stmt->execute();
    }
}
?>
