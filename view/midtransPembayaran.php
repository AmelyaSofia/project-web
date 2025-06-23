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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Beauty - Pembayaran DP</title>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-Flhcf3AX7SroiKgl"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600&family=Montserrat:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --nude-dark: #6B4F4F;
            --nude-medium: #A38F84;
            --nude-light: #E3D5C5;
            --nude-soft: #F0E6D2;
            --nude-accent: #C7A27C;
            --text-dark: #3E3E3E;
            --text-light: #F8F4F0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--nude-soft);
            color: var(--text-dark);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .container {
            width: 100%;
            max-width: 500px;
        }
        
        .payment-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(107, 79, 79, 0.1);
            padding: 2rem;
            position: relative;
            border: 1px solid rgba(163, 143, 132, 0.15);
            overflow: hidden;
        }
        
        /* Dekorasi daun DI DALAM CARD */
        .leaf-decoration {
            position: absolute;
            color: var(--nude-accent);
            opacity: 0.15;
            z-index: 0;
            pointer-events: none;
            filter: drop-shadow(1px 1px 1px rgba(0,0,0,0.05));
            transition: all 0.3s ease;
        }
        
        .leaf-1 {
            top: 15px;
            right: 15px;
            font-size: 3.5rem;
            transform: rotate(25deg);
        }
        
        .leaf-2 {
            bottom: 15px;
            left: 15px;
            font-size: 4rem;
            transform: rotate(-15deg);
        }
        
        .payment-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 6px;
            background: linear-gradient(90deg, var(--nude-accent), var(--nude-medium));
        }
        
        .header {
            text-align: center;
            margin-bottom: 1.5rem;
            position: relative;
            z-index: 2;
        }
        
        .header h2 {
            color: var(--nude-dark);
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            font-family: 'Playfair Display', serif;
            font-weight: 600;
        }
        
        .header p {
            color: var(--nude-medium);
            font-size: 0.9rem;
        }
        
        .salon-icon {
            font-size: 2rem;
            color: var(--nude-accent);
            margin-bottom: 0.5rem;
        }
        
        .payment-details {
            background: var(--nude-soft);
            border-radius: 12px;
            padding: 1.2rem;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(163, 143, 132, 0.1);
            position: relative;
            z-index: 2;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            font-size: 0.9rem;
        }
        
        .detail-label {
            font-weight: 500;
            color: var(--nude-dark);
        }
        
        .detail-value {
            font-weight: 600;
            color: var(--nude-medium);
        }
        
        .total-amount {
            font-size: 1.3rem;
            text-align: center;
            margin: 1.2rem 0;
            font-family: 'Playfair Display', serif;
            color: var(--nude-dark);
            position: relative;
            z-index: 2;
        }
        
        .total-amount span {
            color: var(--nude-accent);
            font-weight: 700;
        }
        
        .payment-button {
            display: block;
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, var(--nude-accent), var(--nude-dark));
            color: var(--text-light);
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 4px 15px rgba(107, 79, 79, 0.15);
            position: relative;
            z-index: 2;
        }
        
        .payment-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 79, 79, 0.2);
        }
        
        .payment-button i {
            margin-right: 8px;
        }
        
        .payment-note {
            text-align: center;
            margin-top: 1.2rem;
            font-size: 0.75rem;
            color: var(--nude-medium);
            line-height: 1.5;
            position: relative;
            z-index: 2;
        }

        /* Efek hover untuk daun */
        .payment-card:hover .leaf-decoration {
            opacity: 0.2;
            transform: rotate(5deg);
        }

        @media (max-width: 480px) {
            .payment-card {
                padding: 1.5rem;
            }
            
            .leaf-1 {
                font-size: 3rem;
                top: 10px;
                right: 10px;
            }
            
            .leaf-2 {
                font-size: 3rem;
                bottom: 10px;
                left: 10px;
            }
            
            .header h2 {
                font-size: 1.3rem;
            }
            
            .payment-details {
                padding: 1rem;
            }
            
            .detail-row {
                font-size: 0.85rem;
                padding: 0.5rem 0;
            }
            
            .total-amount {
                font-size: 1.2rem;
                margin: 1rem 0;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-card">
            <!-- Dekorasi daun DI DALAM CARD -->
            <i class="fas fa-leaf leaf-decoration leaf-1"></i>
            <i class="fas fa-spa leaf-decoration leaf-2"></i>
            
            <div class="header">
                <div class="salon-icon">
                    <i class="fas fa-spa"></i>
                </div>
                <h2>Pembayaran Deposit</h2>
                <p>Konfirmasi reservasi perawatan Anda</p>
            </div>
            
            <div class="payment-details">
                <div class="detail-row">
                    <span class="detail-label">ID Booking:</span>
                    <span class="detail-value"><?= htmlspecialchars($id_booking); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Jenis Pembayaran:</span>
                    <span class="detail-value">DP 30%</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Metode:</span>
                    <span class="detail-value">Midtrans</span>
                </div>
            </div>
            
            <div class="total-amount">
                Total: <span>Rp <?= number_format($total_dp, 0, ',', '.'); ?></span>
            </div>
            
            <button id="pay-button" class="payment-button">
                <i class="fas fa-credit-card"></i> Bayar Sekarang
            </button>
            
            <p class="payment-note">
                Pembayaran aman melalui Midtrans.<br>
                Data kartu tidak disimpan dalam sistem kami.
            </p>
        </div>
    </div>

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
                        alert("Pembayaran berhasil! Terima kasih.");
                        window.location.href = 'riwayatBooking.php';
                    });
                },
                onPending: function(result){
                    alert("Pembayaran Anda sedang diproses. Silakan selesaikan pembayaran Anda.");
                },
                onError: function(result){
                    alert("Pembayaran gagal. Silakan coba lagi.");
                },
                onClose: function(){
                    alert("Silakan lanjutkan pembayaran untuk mengamankan reservasi Anda.");
                }
            });
        });
    </script>
</body>
</html>