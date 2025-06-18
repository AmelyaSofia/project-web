<?php
session_start();

$id_booking = 0;
$upload_error = '';
$success_message = '';
$total_harga = 0;
$jumlah_dp = 0;

if (!isset($_SESSION['id_client'])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/../model/pembayaranModel.php';
require_once __DIR__ . '/../model/bookingModel.php';

$modelPembayaran = new ModelPembayaran();
$modelBooking = new ModelBooking();

$id_booking = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_booking > 0) {
    $booking = $modelBooking->getBookingById($id_booking);
    if (!$booking || $booking['id_client'] != $_SESSION['id_client']) {
        header("Location: riwayatBooking.php");
        exit();
    }
    $total_harga = $modelBooking->hitungTotalHarga($id_booking);
    $jumlah_dp = ceil($total_harga * 0.3); 
}

$metode_pembayaran = [
    'QRIS' => [
        'type' => 'qris',
        'instruksi' => 'Scan QR code berikut untuk melakukan pembayaran',
        'qr_code' => '../image/qris.png'
    ],
    'Transfer BANK' => [
        'type' => 'transfer',
        'instruksi' => 'Transfer ke rekening berikut:',
        'bank' => 'Bank BCA',
        'nama' => 'Royal Beauty Salon',
        'rekening' => '1234567890'
    ],
    'Dana' => [
        'type' => 'dana',
        'instruksi' => 'Transfer ke akun Dana berikut:',
        'nama' => 'Royal Beauty Salon',
        'dana' => '081234567890'
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metode = $_POST['metode'] ?? '';

    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['bukti'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

        if (!in_array($file['type'], $allowed_types)) {
            $upload_error = 'Format file tidak didukung. Harus JPG, JPEG, PNG, atau GIF.';
        } elseif ($file['size'] > 2097152) {
            $upload_error = 'Ukuran file terlalu besar. Maksimal 2MB.';
        } else {
            $upload_dir = __DIR__ . '/../uploads/';
            if (!file_exists($upload_dir)) mkdir($upload_dir, 0777, true);

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'bukti_dp_' . $id_booking . '_' . time() . '.' . $ext;
            $upload_path = $upload_dir . $filename;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $result = $modelPembayaran->simpanPembayaran(
                    $id_booking,
                    'dp',
                    $jumlah_dp,
                    $filename,
                    $metode
                );

                if ($result) {
                    $modelBooking->updateStatus($id_booking, 'menunggu');
                    $_SESSION['success_message'] = 'Bukti pembayaran DP berhasil diupload!';
                    header("Location: riwayatBooking.php");
                    exit();
                } else {
                    $upload_error = 'Gagal menyimpan data pembayaran.';
                }
            } else {
                $upload_error = 'Gagal mengunggah file.';
            }
        }
    } else {
        $upload_error = 'Harap pilih file bukti pembayaran.';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran DP - Royal Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary: #8B4513;
            --primary-light: #D2B48C;
            --primary-dark: #5D4037;
            --secondary: #D4AF37;
            --background: #F5F5DC;
            --text: #5D4037;
            --text-light: #8B8B8B;
            --white: #FFFFFF;
            
            --space-sm: 0.5rem;
            --space-md: 1rem;
            --space-lg: 1.5rem;
            
            --radius-sm: 5px;
            --radius-md: 8px;
            --radius-lg: 12px;
            
            --shadow-sm: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        body {
            background-color: var(--background);
            font-family: 'Poppins', sans-serif;
        }
        
        .compact-card {
            max-width: 600px;
            margin: 2rem auto;
            padding: 1.5rem;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            background: var(--white);
        }
        
        .card-title {
            font-size: 1.4rem;
            color: var(--primary);
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid var(--secondary);
        }
        
        .amount-box {
            background: linear-gradient(to right, #f8f3e6, #f5f5dc);
            border-left: 4px solid var(--secondary);
            padding: 0.75rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1.25rem;
        }
        
        .amount-main {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .amount-note {
            font-size: 0.85rem;
            color: var(--text-light);
        }
        
        .payment-method-box {
            display: none;
            background: rgba(210, 180, 140, 0.1);
            padding: 0.75rem;
            border-radius: var(--radius-sm);
            margin-bottom: 1rem;
            border-left: 3px solid var(--secondary);
        }
        
        .form-control, .form-select {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
        
        .btn {
            padding: 0.5rem 1.25rem;
            font-size: 0.9rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            border: none;
        }
        
        .btn-secondary {
            background-color: var(--primary-light);
            color: var(--text);
        }
        
        @media (max-width: 576px) {
            .compact-card {
                margin: 1rem;
                padding: 1rem;
            }
            
            .card-title {
                font-size: 1.2rem;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="compact-card">
        <h2 class="card-title"><i class="fas fa-wallet me-2"></i>Pembayaran DP</h2>
        <p class="text-muted small mb-3">Booking ID: <strong><?= htmlspecialchars($id_booking) ?></strong></p>

        <?php if ($upload_error): ?>
            <div class="alert alert-danger small py-2"><?= htmlspecialchars($upload_error) ?></div>
        <?php elseif ($success_message): ?>
            <div class="alert alert-success small py-2"><?= htmlspecialchars($success_message) ?></div>
            <div class="text-center mt-3">
                <a href="riwayatBooking.php" class="btn btn-primary btn-sm">Kembali ke Riwayat</a>
            </div>
        <?php else: ?>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="jumlah_dp" value="<?= htmlspecialchars($jumlah_dp) ?>">

                <div class="amount-box">
                    <div class="amount-main text-center">Rp <?= number_format($jumlah_dp, 0, ',', '.') ?></div>
                    <div class="amount-note text-center">DP 30% dari total Rp <?= number_format($total_harga, 0, ',', '.') ?></div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Metode Pembayaran</label>
                    <select name="metode" id="metode" class="form-select form-select-sm" required onchange="showPaymentMethod(this.value)">
                        <option value="">Pilih Metode</option>
                        <option value="QRIS">QRIS</option>
                        <option value="Transfer BANK">Transfer BANK</option>
                        <option value="Dana">Dana</option>
                    </select>
                </div>

                <div id="qris-method" class="payment-method-box">
                    <h6 class="small fw-bold"><i class="fas fa-qrcode me-2"></i>Scan QR Code:</h6>
                    <div class="text-center">
                        <img src="../image/qris.png" alt="QRIS" style="max-width: 180px;" class="img-fluid">
                    </div>
                </div>

                <div id="transfer-method" class="payment-method-box">
                    <h6 class="small fw-bold"><i class="fas fa-university me-2"></i>Transfer Bank:</h6>
                    <div class="small">
                        <div><strong>Bank:</strong> BCA</div>
                        <div><strong>Nama:</strong> Royal Beauty Salon</div>
                        <div><strong>Rekening:</strong> 1234567890</div>
                    </div>
                </div>

                <div id="dana-method" class="payment-method-box">
                    <h6 class="small fw-bold"><i class="fas fa-mobile-alt me-2"></i>Dana:</h6>
                    <div class="small">
                        <div><strong>Nama:</strong> Royal Beauty Salon</div>
                        <div><strong>Nomor:</strong> 081234567890</div>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold">Bukti Pembayaran</label>
                    <input type="file" name="bukti" id="bukti" class="form-control form-control-sm" required>
                    <div class="text-muted small mt-1">Format: JPG/PNG (max 2MB)</div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="riwayatBooking.php" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i>Kirim
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script>
function showPaymentMethod(method) {
    document.querySelectorAll('.payment-method-box').forEach(el => el.style.display = 'none');
    if (method === 'QRIS') document.getElementById('qris-method').style.display = 'block';
    else if (method === 'Transfer BANK') document.getElementById('transfer-method').style.display = 'block';
    else if (method === 'Dana') document.getElementById('dana-method').style.display = 'block';
}
</script>
</body>
</html>