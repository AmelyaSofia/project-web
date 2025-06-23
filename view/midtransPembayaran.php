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
$total_dp = round($detail['total'] * 0.3); 

$snapToken = $controller->getSnapTokenLangsung($id_booking);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran DP Salon</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-Flhcf3AX7SroiKgl"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f9f1ea;
            color: #5a4e4e;
        }

        h2 {
            color: #9d5c63;
        }

        button {
            padding: 10px 20px;
            background-color: #f2c6b4;
            border: none;
            cursor: pointer;
            color: #000;
            font-weight: bold;
            border-radius: 8px;
        }

        button:hover {
            background-color: #efa58f;
        }
    </style>
</head>
<body>

    <h2>Pembayaran DP 30%</h2>
    <p>ID Booking: <strong><?= htmlspecialchars($id_booking); ?></strong></p>
    <p>Total yang harus dibayar: <strong>Rp <?= number_format($total_dp, 0, ',', '.'); ?></strong></p>
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
                        alert("Pembayaran berhasil disimpan!");
                        window.location.href = 'riwayatBooking.php';
                    });
                },
                onPending: function(result){
                    alert("Menunggu pembayaran...");
                    console.log(result);
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                    console.log(result);
                },
                onClose: function(){
                    alert("Kamu menutup popup tanpa menyelesaikan pembayaran.");
                }
            });
        });
    </script>

</body>
</html>
