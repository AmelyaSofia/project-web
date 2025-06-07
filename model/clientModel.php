<?php
include '../config/dbconnect.php';

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

    public function addClient($nama_client, $email, $no_hp, $alamat, $username, $password) {
        global $conn;
        $sql = "INSERT INTO client (nama_client, email, no_hp, alamat, username, password) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $nama_client, $email, $no_hp, $alamat, $username, $password);
        return $stmt->execute();
    }

    public function updateClient($id_client, $nama_client, $email, $no_hp, $alamat, $username, $password) {
        global $conn;
        $sql = "UPDATE client SET nama_client = ?, email = ?, no_hp = ?, alamat = ?, username = ?, password = ?
                WHERE id_client = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $nama_client, $email, $no_hp, $alamat, $username, $password, $id_client);
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
        $sql = "SELECT * FROM client WHERE nama_client LIKE ? OR email LIKE ? OR username LIKE ?";
        $stmt = $conn->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
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

    public function login($username, $password) {
        global $conn;
        $sql = "SELECT * FROM client WHERE username = ? AND password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }


    public function register($data) {
        global $conn;
        $sql = "INSERT INTO client (nama_client, email, no_hp, alamat, username, password)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssss",
            $data['nama_client'],
            $data['email'],
            $data['no_hp'],
            $data['alamat'],
            $data['username'],
            $data['password']
        );
        return $stmt->execute();
    }
}
