<?php
include './config/dbconnect.php';

class ModelClient {
    public function getClients() {
        global $conn;
        $sql = "SELECT * FROM client";
        $result = mysqli_query($conn, $sql);

        $clients = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $clients[] = $row;
            }
        }
        return $clients;
    }

    public function getClientById($id_client) {
        global $conn;
        $sql = "SELECT * FROM client WHERE id_client = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_client);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addClient($nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar) {
        global $conn;
        $sql = "INSERT INTO client (nama_client, email_client, notelp_client, alamat_client, tanggal_daftar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar);
        return $stmt->execute();
    }

    public function updateClient($id_client, $nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar) {
        global $conn;
        $sql = "UPDATE client SET nama_client = ?, email_client = ?, notelp_client = ?, alamat_client = ?, tanggal_daftar = ? WHERE id_client = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar, $id_client);
        return $stmt->execute();
    }

    public function deleteClient($id_client) {
        global $conn;
        $sql = "DELETE FROM client WHERE id_client = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_client);
        return $stmt->execute();
    }

    public function searchClient($keyword) {
        global $conn;
        $sql = "SELECT * FROM client WHERE nama_client LIKE ? OR email_client LIKE ?";
        $stmt = $conn->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();

        $clients = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $clients[] = $row;
            }
        }
        return $clients;
    }

    public function getAllClients() {
        return $this->getClients();
    }

    public function getClientBookings($id_client) {
        global $conn;
        $sql = "SELECT b.*, l.nama_layanan FROM booking b 
                JOIN layanan l ON b.id_layanan = l.id_layanan
                WHERE b.id_client = ?
                ORDER BY b.tanggal DESC, b.jam DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_client);
        $stmt->execute();
        $result = $stmt->get_result();
        $bookings = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row;
            }
        }
        return $bookings;
    }

    public function addBooking($id_client, $id_layanan, $tanggal, $jam) {
        global $conn;
        $sql = "INSERT INTO booking (id_client, id_layanan, tanggal, jam, status) VALUES (?, ?, ?, ?, 'terjadwal')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $id_client, $id_layanan, $tanggal, $jam);
        return $stmt->execute();
    }

    public function cancelBooking($id_booking, $id_client) {
        global $conn;
        // hanya client yang punya booking bisa batalkan
        $sql = "UPDATE booking SET status = 'batal' WHERE id_booking = ? AND id_client = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $id_booking, $id_client);
        return $stmt->execute();
    }
}
?>
