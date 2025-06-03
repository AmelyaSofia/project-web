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

    public function addClient($nama_client, $email_client, $notelp_client, $alamat_client) {
        global $conn;
        $tanggal_daftar = date("Y-m-d");
        $sql = "INSERT INTO client (nama_client, email_client, notelp_client, alamat_client, tanggal_daftar) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $nama_client, $email_client, $notelp_client, $alamat_client, $tanggal_daftar);
        return $stmt->execute();
    }

    public function updateClient($id_client, $nama_client, $email_client, $notelp_client, $alamat_client) {
        global $conn;
        $sql = "UPDATE client 
                SET nama_client = ?, email_client = ?, notelp_client = ?, alamat_client = ? 
                WHERE id_client = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $nama_client, $email_client, $notelp_client, $alamat_client, $id_client);
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
    
    public function getStylists() {
        global $conn;
        $result = $conn->query("SELECT id_karyawan, nama_karyawan, peran_karyawan FROM karyawan");
        
        $stylists = [];
        while ($row = $result->fetch_assoc()) {
            $stylists[$row['id_karyawan']] = [
                'id' => $row['id_karyawan'],
                'nama' => $row['nama_karyawan'],
                'spesialisasi' => $row['peran_karyawan']
            ];
        }
        return $stylists;
    }

    public function getServices() {
        global $conn;
        $result = $conn->query("SELECT id_layanan, nama_layanan, harga_layanan FROM layanan");
        
        $services = [];
        while ($row = $result->fetch_assoc()) {
            $services[$row['id_layanan']] = [
                'id' => $row['id_layanan'],
                'nama' => $row['nama_layanan'],
                'harga' => $row['harga_layanan']
            ];
        }
        return $services;
    }

    public function createBooking($bookingData) {
        global $conn;
        
        $stmt = $conn->prepare("INSERT INTO booking (id_client, id_layanan, tanggal, jam, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", 
            $bookingData['id_client'],
            $bookingData['service_id'],
            $bookingData['tanggal'],
            $bookingData['jam'],
            $bookingData['status']
        );
        
        return $stmt->execute();
    }

    public function getPaymentMethods() {
        return [
            'transfer' => 'Transfer Bank',
            'qris' => 'QRIS',
            'cash' => 'Cash di Tempat'
        ];
    }

}
?>
