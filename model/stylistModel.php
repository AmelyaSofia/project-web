<?php
include __DIR__.'/../config/dbconnect.php';

class ModelStylist {
    public function getStylists() {
        global $conn;
        $sql = "SELECT * FROM stylist";
        $result = mysqli_query($conn, $sql);

        $stylists = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $stylists[] = $row;
            }
        }
        return $stylists;
    }

    public function getStylistById($id_stylist) {
        global $conn;
        $sql = "SELECT * FROM stylist WHERE id_stylist = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_stylist);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function addStylist($nama_stylist, $keahlian) {
        global $conn;
        $sql = "INSERT INTO stylist (nama_stylist, keahlian) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nama_stylist, $keahlian);
        return $stmt->execute();
    }

    public function updateStylist($id_stylist, $nama_stylist, $keahlian) {
        global $conn;
        $sql = "UPDATE stylist SET nama_stylist = ?, keahlian = ? WHERE id_stylist = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $nama_stylist, $keahlian, $id_stylist);
        return $stmt->execute();
    }

    public function deleteStylist($id_stylist) {
        global $conn;
        $sql = "DELETE FROM stylist WHERE id_stylist = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_stylist);
        return $stmt->execute();
    }

    public function searchStylist($keyword) {
        global $conn;
        $sql = "SELECT * FROM stylist WHERE nama_stylist LIKE ? OR keahlian LIKE ?";
        $stmt = $conn->prepare($sql);
        $keyword = "%$keyword%";
        $stmt->bind_param("ss", $keyword, $keyword);
        $stmt->execute();
        $result = $stmt->get_result();

        $stylists = [];
        if ($result->num_rows > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $stylists[] = $row;
            }
        }
        return $stylists;
    }

    public function getAllStylists() {
        return $this->getStylists();
    }
}