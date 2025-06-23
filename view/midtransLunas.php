<?php
require_once __DIR__ . '/../controller/midtransController.php';
require_once __DIR__ . '/../config/dbconnect.php';

$controller = new ControllerMidtrans($conn);

$id_booking = $_GET['id'] ?? null;
if (!$id_booking) {
    echo "ID booking tidak ditemukan.";
    exit;
}

$model = new ModelMidtrans($conn);
$detail = $model->getBookingDetail($id_booking);
if (!$detail || $detail['total'] <= 0) {
    echo "Data booking tidak valid atau belum memilih layanan.";
    exit;
}

$nama = $detail['nama'];
$email = $detail['email'];
$total_pelunasan = round($detail['total'] * 0.7);

$cek_dp = $conn->prepare("SELECT status_pembayaran FROM pembayaran WHERE id_booking = ? AND jenis = 'dp'");
$cek_dp->bind_param("i", $id_booking);
$cek_dp->execute();
$result_dp = $cek_dp->get_result();
$data_dp = $result_dp->fetch_assoc();

if (!$data_dp || $data_dp['status_pembayaran'] !== 'dibayar') {
    echo "Pelunasan hanya dapat dilakukan setelah DP dibayar.";
    exit;
}

$snapToken = $controller->getSnapTokenLangsung($id_booking, 'lunas');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran Pelunasan</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-Flhcf3AX7SroiKgl"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f0f7f1;
            color: #333;
        }

        h2 {
            color: #3b705e;
        }

        p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        button {
            padding: 10px 20px;
            background-color: #77c3b1;
            border: none;
            cursor: pointer;
            color: #fff;
            font-weight: bold;
            border-radius: 8px;
        }

        button:hover {
            background-color: #5eaa98;
        }
    </style>
</head>
<body>

    <h2>Pembayaran Pelunasan (70%)</h2>
    <p>ID Booking: <strong><?= htmlspecialchars($id_booking); ?></strong></p>
    <p>Total yang harus dibayar: <strong>Rp <?= number_format($total_pelunasan, 0, ',', '.'); ?></strong></p>

    <button id="pay-button">Bayar Sekarang</button>

    <script type="text/javascript">
        document.getElementById('pay-button').addEventListener('click', function () {
            snap.pay("<?= $snapToken; ?>", {
                onSuccess: function(result){
                    fetch('../controller/midtrans.php?fitur=simpan', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(result)
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert("Pelunasan berhasil disimpan!");
                        window.location.href = 'riwayatBooking.php';
                    });
                },
                onPending: function(result){
                    alert("Menunggu pelunasan...");
                    console.log(result);
                },
                onError: function(result){
                    alert("Pelunasan gagal!");
                    console.log(result);
                },
                onClose: function(){
                    alert("Kamu menutup popup tanpa menyelesaikan pelunasan.");
                }
            });
        });
    </script>

</body>
</html>