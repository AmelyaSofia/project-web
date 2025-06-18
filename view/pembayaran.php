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

// Data metode pembayaran
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

// Penanganan form submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $metode = $_POST['metode'] ?? '';
    
    // Validasi file upload
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['bukti'];
        
        // Validasi tipe file
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            $upload_error = 'Format file tidak didukung. Harus JPG, JPEG, PNG, atau GIF.';
        } 
        // Validasi ukuran file (max 2MB)
        elseif ($file['size'] > 2097152) {
            $upload_error = 'Ukuran file terlalu besar. Maksimal 2MB.';
        } else {
            // Pastikan folder uploads ada
            $upload_dir = __DIR__ . '/../uploads/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate nama file unik
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'bukti_dp_' . $id_booking . '_' . time() . '.' . $ext;
            $upload_path = $upload_dir . $filename;
            
            // Pindahkan file
            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                // Simpan data pembayaran ke database
                $result = $modelPembayaran->simpanPembayaran(
                    $id_booking,
                    'dp',
                    $jumlah_dp,
                    $filename,
                    $metode
                );
                
                if ($result) {
                    // Update status booking
                    $status = 'menunggu';
                    $modelBooking->updateStatus($id_booking, $status);
                    
                    // Set session success message
                    $_SESSION['success_message'] = 'Bukti pembayaran DP berhasil diupload!';
                    
                    // Redirect ke halaman riwayat
                    header("Location: riwayatBooking.php");
                    exit();
                } else {
                    $upload_error = 'Gagal menyimpan data pembayaran. Silakan coba lagi.';
                }
            } else {
                $upload_error = 'Gagal mengunggah file. Silakan coba lagi.';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6a1b9a;
            --secondary: #9c27b0;
            --light: #f3e5f5;
            --dark: #4a148c;
            --success: #4caf50;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            max-width: 800px;
            margin-top: 40px;
            margin-bottom: 60px;
        }
        
        .payment-card {
            background-color: white;
            border-radius: 15px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .payment-header {
            border-bottom: 2px solid var(--light);
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        
        .payment-method {
            display: none;
            animation: fadeIn 0.5s;
            background-color: var(--light);
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
        }
        
        .payment-method.active {
            display: block;
        }
        
        .qr-code-container {
            text-align: center;
            margin: 20px 0;
        }
        
        .qr-code {
            max-width: 250px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background: white;
        }
        
        .bank-details {
            background-color: white;
            border-radius: 8px;
            padding: 15px;
            margin-top: 15px;
        }
        
        .bank-details p {
            margin-bottom: 8px;
        }
        
        .bank-details strong {
            color: var(--dark);
        }
        
        .btn-primary {
            background-color: var(--primary);
            border-color: var(--primary);
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
        }
        
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .method-icon {
            font-size: 1.2rem;
            margin-right: 8px;
            color: var(--primary);
        }
        
        .dp-amount {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--dark);
            background-color: var(--light);
            padding: 8px 20px;
            border-radius: 8px;
            display: inline-block;
            margin: 10px 0;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="payment-card">
        <div class="payment-header">
            <h2><i class="fas fa-wallet"></i> Pembayaran DP</h2>
            <p class="text-muted">Silakan unggah bukti pembayaran DP untuk Booking ID: <strong><?= htmlspecialchars($id_booking) ?></strong></p>
        </div>

        <?php if ($upload_error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($upload_error) ?>
            </div>
        <?php elseif ($success_message): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success_message) ?>
            </div>
            <a href="riwayatBooking.php" class="btn btn-success mt-3">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Riwayat Booking
            </a>
        <?php else: ?>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="jumlah_dp" value="<?= htmlspecialchars($jumlah_dp) ?>">
                
            <div class="mb-4">
                <h5><i class="fas fa-money-bill-wave method-icon"></i>Jumlah DP</h5>
                <div class="dp-amount">Rp <?= number_format($jumlah_dp, 0, ',', '.') ?></div>
                <p class="text-muted"><i class="fas fa-info-circle"></i> Jumlah DP adalah 30% dari total harga booking (<?= number_format($total_harga, 0, ',', '.') ?>)</p>
                <p class="text-muted"><i class="fas fa-clock"></i> Pembayaran akan diverifikasi dalam 1x24 jam</p>
            </div>
                
                <div class="mb-4">
                    <label for="metode" class="form-label">
                        <i class="fas fa-credit-card method-icon"></i>Metode Pembayaran
                    </label>
                    <select name="metode" id="metode" class="form-select" required onchange="showPaymentMethod(this.value)">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="QRIS">QRIS</option>
                        <option value="Transfer BANK">Transfer BANK</option>
                        <option value="Dana">Dana</option>
                    </select>
                </div>

                <!-- QRIS Method -->
                <div id="qris-method" class="payment-method">
                    <h5><i class="fas fa-qrcode"></i> QRIS</h5>
                    <p>Scan QR code berikut untuk melakukan pembayaran:</p>
                    <div class="qr-code-container">
                        <img src="../image/qris.png" alt="QR Code Pembayaran" class="qr-code">
                        <p class="text-muted mt-2"><i class="fas fa-info-circle"></i> Scan menggunakan aplikasi mobile banking/e-wallet</p>
                    </div>
                </div>

                <!-- Transfer Bank Method -->
                <div id="transfer-method" class="payment-method">
                    <h5><i class="fas fa-university"></i> Transfer BANK</h5>
                    <p>Transfer ke rekening berikut:</p>
                    <div class="bank-details">
                        <p><strong><i class="fas fa-bank"></i> Bank:</strong> BCA</p>
                        <p><strong><i class="fas fa-user"></i> Nama:</strong> Royal Beauty Salon</p>
                        <p><strong><i class="fas fa-credit-card"></i> No. Rekening:</strong> 1234567890</p>
                    </div>
                </div>

                <!-- Dana Method -->
                <div id="dana-method" class="payment-method">
                    <h5><i class="fas fa-mobile-alt"></i> Dana</h5>
                    <p>Transfer ke akun Dana berikut:</p>
                    <div class="bank-details">
                        <p><strong><i class="fas fa-user"></i> Nama:</strong> Royal Beauty Salon</p>
                        <p><strong><i class="fas fa-phone"></i> No. Dana:</strong> 081234567890</p>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="bukti" class="form-label">
                        <i class="fas fa-file-image method-icon"></i> Unggah Bukti Pembayaran
                    </label>
                    <input type="file" name="bukti" id="bukti" class="form-control" required>
                    <div class="form-text">Format: JPG, JPEG, PNG, GIF (Maks. 2MB)</div>
                </div>

                <div class="d-flex gap-3 mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane me-1"></i> Kirim Bukti
                    </button>
                    <a href="riwayatBooking.php" class="btn btn-secondary">
                        <i class="fas fa-times me-1"></i> Batal
                    </a>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function showPaymentMethod(method) {
        // Sembunyikan semua metode pembayaran terlebih dahulu
        document.querySelectorAll('.payment-method').forEach(el => {
            el.classList.remove('active');
        });
        
        if (method === 'QRIS') {
            document.getElementById('qris-method').classList.add('active');
        } 
        else if (method === 'Transfer BANK') {
            document.getElementById('transfer-method').classList.add('active');
        } 
        else if (method === 'Dana') {
            document.getElementById('dana-method').classList.add('active');
        }
    }
</script>
</body>
</html>