<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "salon";

try {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli($host, $user, $password, $db);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    echo "gagal ter koneksi: " . $e->getMessage();
    exit;
}
?>