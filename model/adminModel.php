<?php
include '../config/dbconnect.php';

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

    public function addAdmin($username, $password, $nama_admin) {
        global $conn;
        $sql = "INSERT INTO admin (username, password, nama_admin) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $password, $nama_admin);
        return $stmt->execute();
    }

    public function updateAdmin($id_admin, $username, $password, $nama_admin) {
        global $conn;
        $sql = "UPDATE admin SET username = ?, password = ?, nama_admin = ? WHERE id_admin = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $username, $password, $nama_admin, $id_admin);
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
        $sql = "SELECT * FROM admin WHERE username LIKE ? OR nama_admin LIKE ?";
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

public function login($username, $password) {
    global $conn;
    $sql = "SELECT * FROM admin WHERE username = ? AND password = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
}
