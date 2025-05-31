<?php
include './config/dbconnect.php';

class ModelKaryawan {
    public function getKaryawans() {
        global $conn;
        $sql = "SELECT * FROM karyawan";
        $result = mysqli_query($conn, $sql);

        $karyawans = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $karyawans[] = $row;
            }
        }
        return $karyawans;
    }
    public function getKaryawanById($id_karyawan) {
        global $conn;
        $sql = "SELECT * FROM karyawan WHERE id_karyawan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_karyawan);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }
    public function addKaryawan($nama_karyawan, $peran_karyawan, $foto_karyawan) {
        global $conn;
        $sql = "INSERT INTO karyawan (nama_karyawan, peran_karyawan, foto_karyawan) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $nama_karyawan, $peran_karyawan, $foto_karyawan);
        return $stmt->execute();
    }

    public function updateKaryawan($id_karyawan, $nama_karyawan, $peran_karyawan, $foto_karyawan) {
        global $conn;
        $sql = "UPDATE karyawan SET nama_karyawan = ?, peran_karyawan = ?, foto_karyawan = ? WHERE id_karyawan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi", $nama_karyawan, $peran_karyawan, $foto_karyawan, $id_karyawan);
        return $stmt->execute();
    }
    public function deleteKaryawan($id_karyawan) {
        global $conn;
        $sql = "DELETE FROM karyawan WHERE id_karyawan = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_karyawan);
        return $stmt->execute();
    }
    public function searchKaryawan($keyword) {
        global $conn;
        $sql = "SELECT * FROM karyawan WHERE nama_karyawan LIKE ? OR peran_karyawan LIKE ?";
        $stmt = $conn->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();

        $karyawans = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $karyawans[] = $row;
            }
        }
        return $karyawans;
    }
    public function getAllKaryawans() {
        return $this->getKaryawans();
    }
}
?>