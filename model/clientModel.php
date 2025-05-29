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

    public function getCLientById($id_client) {
        global $conn;
        $sql = "SELECT * FROM client WHERE id_client = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_client);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addCLient($nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar) {
        global $conn;
        $sql = "INSERT INTO client (nama_client, email_client, notelp_client, alamat_client, tanggal_daftar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar);
        return $stmt->execute();
    }

    public function updateCLient($id_client, $nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar) {
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
}
?>