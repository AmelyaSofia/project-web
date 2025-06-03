<?php
session_start();
include_once('config/dbconnect.php');
global $conn;

$stylists = [];
$result = $conn->query("SELECT id_karyawan, nama_karyawan, peran_karyawan FROM karyawan");
while ($row = $result->fetch_assoc()) {
    $stylists[$row['id_karyawan']] = [
        'id' => $row['id_karyawan'],
        'nama' => $row['nama_karyawan'],
        'spesialisasi' => $row['peran_karyawan']
    ];
}

$services = [];
$resultService = $conn->query("SELECT id_layanan, nama_layanan, harga_layanan FROM layanan");
while ($row = $resultService->fetch_assoc()) {
    $services[$row['id_layanan']] = [
        'id' => $row['id_layanan'],
        'nama' => $row['nama_layanan'],
        'harga' => $row['harga_layanan']
    ];
}

$paymentMethods = [
    'transfer' => 'Transfer Bank',
    'qris' => 'QRIS',
    'cash' => 'Cash di Tempat'
];

$step = $_GET['step'] ?? 'pilih_layanan';
$selectedStylist = $_GET['stylist_id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['service_id'])) {
        $_SESSION['booking'] = [
            'service_id' => $_POST['service_id'],
            'service_name' => $services[$_POST['service_id']]['nama'],
            'service_price' => $services[$_POST['service_id']]['harga']
        ];
        $step = 'pilih_stylist';
    }
    elseif (isset($_POST['stylist_id'])) {
        $_SESSION['booking']['stylist_id'] = $_POST['stylist_id'];
        $_SESSION['booking']['stylist_name'] = $stylists[$_POST['stylist_id']]['nama'];
        $step = 'pilih_jadwal';
    }
    elseif (isset($_POST['tanggal']) && isset($_POST['jam'])) {
        $_SESSION['booking']['tanggal'] = $_POST['tanggal'];
        $_SESSION['booking']['jam'] = $_POST['jam'];
        $step = 'konfirmasi';
    }
    elseif (isset($_POST['confirm'])) {
        $_SESSION['booking']['payment_method'] = $_POST['payment_method'];
        $_SESSION['booking']['catatan'] = $_POST['catatan'] ?? '';

        $service_id = $_SESSION['booking']['service_id'];
        $tanggal = $_SESSION['booking']['tanggal'];
        $jam = $_SESSION['booking']['jam'];
        $id_client = $_SESSION['id_client'] ?? 2; 

        $status = 'menunggu';

        $stmt = $conn->prepare("INSERT INTO booking (id_client, id_layanan, tanggal, jam, status) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("iisss", $id_client, $service_id, $tanggal, $jam, $status);
        $stmt->execute();
        $stmt->close();

        $step = 'selesai';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Salon - Glamour Beauty</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="view/client/booking.css">
</head>
<body>
   <header>
    <div class="container" style="display: flex; align-items: center; justify-content: center; gap: 5px;">
        <div style="width: 120px; height: 120px; background: white; display: flex; align-items: center; justify-content: center; border-radius: 8px;">
            <img src="image/logo.png" alt="Royal Beauty Logo" class="logo-image" style="max-width: 100%; max-height: 100%; object-fit: contain;">
        </div>
        <div class="logo" style="font-size: 24px; font-weight: 600; color: #93552f;">Royal Beauty Salon</div>
    </div>
</header>

    
    <div class="container">
        <!-- Progress Steps -->
        <div class="booking-steps">
            <div class="step <?= in_array($step, ['pilih_stylist', 'pilih_jadwal', 'konfirmasi', 'selesai']) ? 'completed' : '' ?> <?= $step === 'pilih_stylist' ? 'active' : '' ?>">
                <div class="step-number">1</div>
                <div class="step-label">Pilih Stylist</div>
            </div>
            <div class="step <?= in_array($step, ['pilih_jadwal', 'konfirmasi', 'selesai']) ? 'completed' : '' ?> <?= $step === 'pilih_jadwal' ? 'active' : '' ?>">
                <div class="step-number">2</div>
                <div class="step-label">Pilih Jadwal</div>
            </div>
            <div class="step <?= in_array($step, ['konfirmasi', 'selesai']) ? 'completed' : '' ?> <?= $step === 'konfirmasi' ? 'active' : '' ?>">
                <div class="step-number">3</div>
                <div class="step-label">Konfirmasi</div>
            </div>
        </div>
            
        <?php if ($step === 'pilih_layanan'): ?>
            <h2>Pilih Layanan</h2>
            <p>Silakan pilih layanan yang Anda inginkan</p>
            <form method="POST">
                <div class="card-container">
                    <?php foreach ($services as $service): ?>
                        <label>
                            <input type="radio" name="service_id" value="<?= $service['id'] ?>" style="display:none;" required>
                            <div class="card">
                                <div class="card-body">
                                    <h3 class="card-title"><?= htmlspecialchars($service['nama']) ?></h3>
                                    <div class="card-price">Rp <?= number_format($service['harga'], 0, ',', '.') ?></div>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
                <div class="action-buttons">
                    <button type="submit" class="btn" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                        Lanjutkan
                    </button>
                </div>
            </form>
        <?php elseif ($step === 'pilih_stylist'): ?>
            <h2>Pilih Stylist</h2>
            <p>Silakan pilih stylist yang Anda inginkan</p>
            
            <form method="POST">
                <div class="card-container">
                    <?php foreach ($stylists as $stylist): ?>
                        <label>
                            <input type="radio" name="stylist_id" value="<?= $stylist['id'] ?>" style="display: none;" <?= ($selectedStylist == $stylist['id']) ? 'checked' : '' ?>>
                            <div class="card <?= ($selectedStylist == $stylist['id']) ? 'selected' : '' ?>">
                                <div class="card-image">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="card-body">
                                    <h3 class="card-title"><?= $stylist['nama'] ?></h3>
                                    <div class="card-duration"><?= $stylist['spesialisasi'] ?></div>
                                </div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
                
                <div class="action-buttons" style="font-family: 'Poppins', sans-serif;">
                <a href="?step=pilih_stylist&service_id=<?= $_SESSION['booking']['service_id'] ?>" 
                  class="btn btn-secondary" 
                  style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                  Kembali
                </a>
                <button type="submit" class="btn" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                        Lanjutkan
                </button>
                </div>
            </form>
            
        <?php elseif ($step === 'pilih_jadwal'): ?>
            <h2>Pilih Jadwal</h2>
            <p>Silakan pilih tanggal dan waktu booking</p>
            
            <form method="POST">
                <div class="booking-form">
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" id="tanggal" name="tanggal" required min="<?= date('Y-m-d') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="jam">Waktu</label>
                        <select id="jam" name="jam" required>
                            <option value="">Pilih Waktu</option>
                            <option value="09:00">09:00</option>
                            <option value="10:00">10:00</option>
                            <option value="11:00">11:00</option>
                            <option value="13:00">13:00</option>
                            <option value="14:00">14:00</option>
                            <option value="15:00">15:00</option>
                            <option value="16:00">16:00</option>
                        </select>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="?step=pilih_stylist&service_id=<?= $_SESSION['booking']['service_id'] ?>" class="btn btn-secondary">Kembali</a>
                    <button type="submit" class="btn" style="font-family: 'Poppins', sans-serif; font-weight: 500;">
                        Lanjutkan
                    </button>                
                </div>
            </form>
            
        <?php elseif ($step === 'konfirmasi'): ?>
            <h2>Konfirmasi Booking</h2>
            <p>Silakan periksa detail booking Anda</p>
            
            <div class="booking-summary">
                <div class="summary-item">
                    <span class="summary-label">Layanan</span>
                    <span class="summary-value"><?= $_SESSION['booking']['service_name'] ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Stylist</span>
                    <span class="summary-value"><?= $_SESSION['booking']['stylist_name'] ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Tanggal</span>
                    <span class="summary-value"><?= date('d F Y', strtotime($_SESSION['booking']['tanggal'])) ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Waktu</span>
                    <span class="summary-value"><?= $_SESSION['booking']['jam'] ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Harga</span>
                    <span class="summary-value">Rp <?= number_format($_SESSION['booking']['service_price'], 0, ',', '.') ?></span>
                </div>
            </div>
            
            <form method="POST">
                <div class="booking-form">
                    <div class="form-group">
                        <label>Metode Pembayaran</label>
                        <div class="payment-methods">
                            <label class="payment-option <?= ($_SESSION['booking']['payment_method'] ?? '') === 'transfer' ? 'selected' : '' ?>">
                                <input type="radio" name="payment_method" value="transfer" <?= ($_SESSION['booking']['payment_method'] ?? '') === 'transfer' ? 'checked' : '' ?> required>
                                <div class="payment-icon"><i class="fas fa-university"></i></div>
                                <div class="payment-info">
                                    <div class="payment-title">Transfer Bank</div>
                                    <div class="payment-desc">Transfer ke rekening BCA 1234567890 a.n Glamour Beauty</div>
                                </div>
                            </label>
                            
                            <label class="payment-option <?= ($_SESSION['booking']['payment_method'] ?? '') === 'qris' ? 'selected' : '' ?>">
                                <input type="radio" name="payment_method" value="qris" <?= ($_SESSION['booking']['payment_method'] ?? '') === 'qris' ? 'checked' : '' ?>>
                                <div class="payment-icon"><i class="fas fa-qrcode"></i></div>
                                <div class="payment-info">
                                    <div class="payment-title">QRIS</div>
                                    <div class="payment-desc">Scan QR code melalui aplikasi e-wallet Anda</div>
                                </div>
                            </label>
                            
                            <label class="payment-option <?= ($_SESSION['booking']['payment_method'] ?? '') === 'cash' ? 'selected' : '' ?>">
                                <input type="radio" name="payment_method" value="cash" <?= ($_SESSION['booking']['payment_method'] ?? '') === 'cash' ? 'checked' : '' ?>>
                                <div class="payment-icon"><i class="fas fa-money-bill-wave"></i></div>
                                <div class="payment-info">
                                    <div class="payment-title">Cash di Tempat</div>
                                    <div class="payment-desc">Bayar langsung saat datang ke salon</div>
                                </div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="catatan">Catatan Tambahan (Opsional)</label>
                        <textarea id="catatan" name="catatan" rows="3" class="form-control" placeholder="Contoh: Minta pewarna rambut tanpa ammonia"><?= $_SESSION['booking']['catatan'] ?? '' ?></textarea>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="?step=pilih_jadwal&service_id=<?= $_SESSION['booking']['service_id'] ?>&stylist_id=<?= $_SESSION['booking']['stylist_id'] ?>" class="btn btn-secondary">Kembali</a>
                    <button type="submit" name="confirm" class="btn" style="font-family: 'Poppins', sans-serif; font-weight: 500;"
                    >Konfirmasi Booking</button>
                </div>
            </form>
            
        <?php elseif ($step === 'selesai'): ?>
            <div class="success-message">
                <div class="success-icon">✓</div>
                <h2 class="success-title">Booking Berhasil!</h2>
                <p class="success-text">Terima kasih telah melakukan booking di Glamour Beauty Salon.</p>
                
                <div class="booking-summary">
                    <div class="summary-item">
                        <span class="summary-label">Nomor Booking</span>
                        <span class="summary-value">GB-<?= rand(1000, 9999) ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Layanan</span>
                        <span class="summary-value"><?= $_SESSION['booking']['service_name'] ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Stylist</span>
                        <span class="summary-value"><?= $_SESSION['booking']['stylist_name'] ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Tanggal</span>
                        <span class="summary-value"><?= date('d F Y', strtotime($_SESSION['booking']['tanggal'])) ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Waktu</span>
                        <span class="summary-value"><?= $_SESSION['booking']['jam'] ?></span>
                    </div>
                    <div class="summary-item">
                        <span class="summary-label">Metode Pembayaran</span>
                        <span class="summary-value"><?= $paymentMethods[$_SESSION['booking']['payment_method']] ?></span>
                    </div>
                    <div class="summary-item summary-total">
                        <span class="summary-label">Total Pembayaran</span>
                        <span class="summary-value">Rp <?= number_format($_SESSION['booking']['service_price'], 0, ',', '.') ?></span>
                    </div>
                </div>
                
                <?php if ($_SESSION['booking']['payment_method'] === 'transfer'): ?>
                    <div class="payment-instruction" style="margin-top: 20px; padding: 15px; background: #f8f8f8; border-radius: 8px;">
                        <h3 style="margin-bottom: 10px;">Instruksi Pembayaran:</h3>
                        <p>Silakan transfer ke rekening berikut:</p>
                        <p><strong>Bank BCA</strong><br>
                        No. Rekening: 1234567890<br>
                        A.n: Glamour Beauty Salon<br>
                        Jumlah: Rp <?= number_format($_SESSION['booking']['service_price'], 0, ',', '.') ?></p>
                    </div>
                <?php elseif ($_SESSION['booking']['payment_method'] === 'qris'): ?>
                    <div class="payment-instruction" style="margin-top: 20px; padding: 15px; background: #f8f8f8; border-radius: 8px;">
                        <h3 style="margin-bottom: 10px;">Instruksi Pembayaran:</h3>
                        <p>Silakan scan QR code berikut melalui aplikasi e-wallet Anda:</p>
                        <div style="text-align: center; margin: 20px 0;">
                        <!-- Container untuk QR Code -->
                        <div style="width: 200px; height: 200px; margin: 0 auto; border: 1px solid #eee; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: white; padding: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                            <img src="../../image/qris.png" alt="QRIS Payment" style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                        <p style="margin-top: 15px; font-size: 14px; color: #666;">Scan QR code di atas untuk pembayaran</p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <a href="/" class="btn" style="margin-top: 20px;">Kembali ke Beranda</a>
            </div>
        <?php endif; ?>
    </div>
    
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script>
        document.querySelectorAll('.payment-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.payment-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
                this.querySelector('input[type="radio"]').checked = true;
            });
        });

    document.querySelectorAll('.card-container label').forEach(label => {
        label.addEventListener('click', () => {
            document.querySelectorAll('.card').forEach(card => {
                card.classList.remove('selected');
            });

            const card = label.querySelector('.card');
            card.classList.add('selected');
        });
    });
    </script>
</body>
</html>