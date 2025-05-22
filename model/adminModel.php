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
        $sql = "SELECT * FROM admin WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_admin);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addAdmin($nama_admin, $email, $password) {
        global $conn;
        $sql = "INSERT INTO admin (nama_admin, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nama_admin, $email, $password);
        return $stmt->execute();
    }

    public function updateAdmin($id_admin, $nama_admin, $email, $password) {
        global $conn;
        $sql = "UPDATE admin SET nama_admin = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nama_admin, $email, $password, $id_admin);
        return $stmt->execute();
    }

    public function deleteAdmin($id_admin) {
        global $conn;
        $sql = "DELETE FROM admin WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_admin);
        return $stmt->execute();
    }

    public function searchAdmin($keyword) {
        global $conn;
        $sql = "SELECT * FROM admin WHERE nama_admin LIKE ? OR email LIKE ?";
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
}
?>