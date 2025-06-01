<?php
include './config/dbconnect.php';

class ModelAdmin {
    public function getAdmins() {
        global $conn;
        $sql = "SELECT * FROM admin";
        $result = mysqli_query($conn, $sql);

        $admins = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $admins[] = $row;
            }
        }
        return $admins;
    }

    public function getAdminById($id_admin) {
        global $conn;
        $sql = "SELECT * FROM admin WHERE id_admin = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_admin);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addAdmin($nama_admin, $email_admin, $password_admin, $foto_admin) {
        global $conn;
        $sql = "INSERT INTO admin (nama_admin, email_admin, password_admin, foto_admin) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $nama_admin, $email_admin, $password_admin, $foto_admin);
        return $stmt->execute();
    }

    public function updateAdmin($id_admin, $nama_admin, $email_admin, $password_admin, $foto_admin) {
        global $conn;
        $sql = "UPDATE admin SET nama_admin = ?, email_admin = ?, password_admin = ?, foto_admin = ? WHERE id_admin = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nama_admin, $email_admin, $password_admin, $foto_admin, $id_admin);
        return $stmt->execute();
    }

    public function deleteAdmin($id_admin) {
        global $conn;
        $sql = "DELETE FROM admin WHERE id_admin = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_admin);
        return $stmt->execute();
    }

    public function searchAdmin($keyword) {
        global $conn;
        $sql = "SELECT * FROM admin WHERE nama_admin LIKE ? OR email_admin LIKE ?";
        $stmt = $conn->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();

        $admins = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $admins[] = $row;
            }
        }
        return $admins;
    }

    public function getAllAdmins() {
        return $this->getAdmins();
    }

    public function getAllBookings() {
        global $conn;
        $sql = "SELECT b.*, c.nama_client, l.nama_layanan 
                FROM booking b
                JOIN client c ON b.id_client = c.id_client
                JOIN layanan l ON b.id_layanan = l.id_layanan
                ORDER BY b.tanggal DESC, b.jam DESC";
        $result = mysqli_query($conn, $sql);
        $bookings = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $bookings[] = $row;
            }
        }
        return $bookings;
    }

    public function updateBookingStatus($id_booking, $status) {
        global $conn;
        $sql = "UPDATE booking SET status = ? WHERE id_booking = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $status, $id_booking);
        return $stmt->execute();
    }
}
?>
