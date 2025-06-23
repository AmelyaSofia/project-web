<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../vendor/autoload.php';
require_once '../config/dbconnect.php';

\Midtrans\Config::$serverKey = 'SB-Mid-server-iTYO2x_nHugYYDfpe4VCNrXR';
\Midtrans\Config::$isProduction = false;

$raw = file_get_contents("php://input");
$data = json_decode($raw);

if (!$data || !isset($data->transaction_status)) {
    http_response_code(400);
    echo "Bad Request";
    exit;
}

$order_id = $data->order_id ?? '';
$transaction_status = $data->transaction_status ?? '';
$gross_amount = $data->gross_amount ?? '0';
$payment_type = $data->payment_type ?? '';
$parts = explode('-', $order_id);
$jenis = strtolower($parts[0] ?? 'dp');
$id_booking = intval($parts[1] ?? 0);

if (!in_array($jenis, ['dp', 'lunas'])) {
    $jenis = 'dp'; // fallback
}

if ($id_booking && in_array($transaction_status, ['settlement', 'capture'])) {
    $metode = match ($payment_type) {
        'bank_transfer', 'credit_card' => 'Transfer Bank',
        'qris' => 'QRIS',
        'gopay', 'shopeepay', 'danamon_online' => 'Dana',
        default => 'Transfer Bank'
    };

    $stmt = $conn->prepare("SELECT id_pembayaran FROM pembayaran WHERE id_booking = ? AND jenis = ?");
    $stmt->bind_param("is", $id_booking, $jenis);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $update = $conn->prepare("UPDATE pembayaran SET status_pembayaran = 'dibayar' WHERE id_booking = ? AND jenis = ?");
        $update->bind_param("is", $id_booking, $jenis);
        $update->execute();
    } else {
        $status = 'dibayar';
        $jumlah = floatval($gross_amount);

        $insert = $conn->prepare("INSERT INTO pembayaran (id_booking, jenis, jumlah, metode_pembayaran, status_pembayaran) VALUES (?, ?, ?, ?, ?)");
        $insert->bind_param("isdss", $id_booking, $jenis, $jumlah, $metode, $status);
        $insert->execute();
    }
}

http_response_code(200);
echo "OK";