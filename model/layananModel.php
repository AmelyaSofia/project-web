<?php
include './config/dbconnect.php';

class ModelLayanan {
    public function getLayanans() {
        global $conn;
        $sql = "SELECT * FROM layanan";
        $result = mysqli_query($conn, $sql);

        $layanans = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $layanans[] = $row;
            }
        }
        return $layanans;
    }
    public function getLayananById($id_layanan) {
        global $conn;
        $sql = "SELECT * FROM layanan WHERE id_layanan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_layanan);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function addLayanan($nama_layanan, $deskripsi_layanan, $harga_layanan) {
        global $conn;
        $sql = "INSERT INTO layanan (nama_layanan, deskripsi_layanan, harga_layanan) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssd", $nama_layanan, $deskripsi_layanan, $harga_layanan);
        return $stmt->execute();
    }
    public function updateLayanan($id_layanan, $nama_layanan, $deskripsi_layanan, $harga_layanan) {
        global $conn;
        $sql = "UPDATE layanan SET nama_layanan = ?, deskripsi_layanan = ?, harga_layanan = ? WHERE id_layanan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $nama_layanan, $deskripsi_layanan, $harga_layanan, $id_layanan);
        return $stmt->execute();
    }
    public function deleteLayanan($id_layanan) {
        global $conn;
        $sql = "DELETE FROM layanan WHERE id_layanan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_layanan);
        return $stmt->execute();
    }
    public function searchLayanan($keyword) {
        global $conn;
        $sql = "SELECT * FROM layanan WHERE nama_layanan LIKE ? OR deskripsi_layanan LIKE ?";
        $stmt = $conn->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();

        $layanans = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $layanans[] = $row;
            }
        }
        return $layanans;
    }
    public function getAllLayanans() {
        return $this->getLayanans();
    }
}
?>