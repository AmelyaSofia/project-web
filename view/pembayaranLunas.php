<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['id_client'])) {
    header("Location: ../index.php");
    exit();
}

require_once __DIR__ . '/../model/pembayaranModel.php';
require_once __DIR__ . '/../model/bookingModel.php';

$modelPembayaran = new ModelPembayaran();
$modelBooking = new ModelBooking();

$id_booking = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_booking <= 0) {
    $_SESSION['error_message'] = 'ID Booking tidak valid.';
    header("Location: riwayatBooking.php");
    exit();
}

$booking = $modelBooking->getBookingById($id_booking);

if (!$booking || $booking['id_client'] != $_SESSION['id_client']) {
    $_SESSION['error_message'] = 'Anda tidak berhak mengakses booking ini.';
    header("Location: riwayatBooking.php");
    exit();
}

$pembayaranLunas = $modelPembayaran->cekPembayaranLunas($id_booking);
if ($pembayaranLunas) {
    $_SESSION['error_message'] = 'Pembayaran lunas untuk booking ini sudah dilakukan.';
    header("Location: riwayatBooking.php");
    exit();
}

$total_harga = $modelBooking->hitungTotalHarga($id_booking);
$jumlah_dp = ceil($total_harga * 0.3);
$jumlah_lunas = $total_harga - $jumlah_dp;

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
    $jumlah_lunas_post = intval($_POST['jumlah_lunas']);

    if (empty($metode)) {
        $_SESSION['error_message'] = 'Silakan pilih metode pembayaran.';
        header("Location: pembayaranLunas.php?id=$id_booking");
        exit();
    }

    if (!isset($_FILES['bukti']) || $_FILES['bukti']['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error_message'] = 'Harap unggah bukti pembayaran.';
        header("Location: pembayaranLunas.php?id=$id_booking");
        exit();
    }

    $file = $_FILES['bukti'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];

    if (!in_array($file['type'], $allowed_types)) {
        $_SESSION['error_message'] = 'Format file tidak didukung. Hanya JPG, PNG, atau GIF.';
        header("Location: pembayaranLunas.php?id=$id_booking");
        exit();
    } elseif ($file['size'] > 2097152) {
        $_SESSION['error_message'] = 'Ukuran file terlalu besar. Maksimal 2MB.';
        header("Location: pembayaranLunas.php?id=$id_booking");
        exit();
    }

    $upload_dir = __DIR__ . '/../uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = 'bukti_lunas_' . $id_booking . '_' . time() . '.' . $ext;
    $upload_path = $upload_dir . $filename;

    if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
        $_SESSION['error_message'] = 'Gagal mengunggah file.';
        header("Location: pembayaranLunas.php?id=$id_booking");
        exit();
    }

    $result = $modelPembayaran->simpanPembayaran(
    $id_booking,
    'lunas', 
    $jumlah_lunas_post,
    $filename,
    $metode,
    'pending' // Status pembayaran
);

    if ($result) {
        $modelBooking->updateStatus($id_booking, 'terjadwal');
        $_SESSION['success_message'] = 'Pelunasan berhasil diupload!';
        header("Location: riwayatBooking.php");
        exit();
    } else {
        $_SESSION['error_message'] = 'Gagal menyimpan data pembayaran.';
        header("Location: pembayaranLunas.php?id=$id_booking");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pelunasan Pembayaran - Royal Beauty</title>
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
            border: 1px solid var(--primary-light);
            border-radius: var(--radius-md);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.25rem rgba(139, 69, 19, 0.1);
        }
        
        .btn {
            padding: 0.5rem 1.25rem;
            font-size: 0.9rem;
            border-radius: var(--radius-md);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border: none;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
        }
        
        .btn-secondary {
            background-color: var(--primary-light);
            color: var(--text);
        }
        
        .btn-secondary:hover {
            background-color: #C19A6B;
        }
        
        .alert {
            border-radius: var(--radius-md);
            padding: 0.75rem;
        }
        
        .qr-code {
            max-width: 180px;
            margin: 0 auto;
            display: block;
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
        <h2 class="card-title"><i class="fas fa-wallet me-2"></i>Pelunasan Pembayaran</h2>
        <p class="text-muted small mb-3">Booking ID: <strong><?= htmlspecialchars($id_booking ?? '') ?></strong></p>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger small py-2"><?= htmlspecialchars($_SESSION['error_message']) ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="jumlah_lunas" value="<?= htmlspecialchars($jumlah_lunas ?? '') ?>">
            
            <div class="amount-box">
                <div class="amount-main text-center">Rp <?= number_format($jumlah_lunas ?? 0, 0, ',', '.') ?></div>
                <div class="amount-note text-center">
                    Total: Rp<?= number_format($total_harga ?? 0, 0, ',', '.') ?> | 
                    DP: Rp<?= number_format($jumlah_dp ?? 0, 0, ',', '.') ?>
                </div>
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
                    <img src="../image/qris.png" alt="QRIS" class="qr-code">
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
                <a href="riwayatBooking.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane me-1"></i>Kirim Bukti
                </button>
            </div>
        </form>
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