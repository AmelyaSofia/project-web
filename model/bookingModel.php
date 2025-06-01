<?php
include './config/dbconnect.php';

class ModelBooking {
    public function getAllBooking() {
        global $conn;
        $sql = "SELECT booking.*, client.nama_client, layanan.nama_layanan 
                FROM booking 
                JOIN client ON booking.id_client = client.id_client 
                JOIN layanan ON booking.id_layanan = layanan.id_layanan
                ORDER BY booking.tanggal DESC";
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

    public function addBooking($id_client, $id_layanan, $tanggal, $jam) {
        global $conn;
        $sql = "INSERT INTO booking (id_client, id_layanan, tanggal, jam, status) 
                VALUES (?, ?, ?, ?, 'menunggu')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $id_client, $id_layanan, $tanggal, $jam);
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

    public function getBookingByClient($id_client) {
        global $conn;
        $sql = "SELECT booking.*, layanan.nama_layanan 
                FROM booking 
                JOIN layanan ON booking.id_layanan = layanan.id_layanan
                WHERE booking.id_client = ? 
                ORDER BY booking.tanggal DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_client);
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
