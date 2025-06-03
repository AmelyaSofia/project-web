<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "salon";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli($host, $user, $password, $db);
    $conn->set_charset("utf8mb4");
} catch (mysqli_sql_exception $e) {
    echo "Gagal terkoneksi ke database: " . $e->getMessage();
    exit;
}
?>
