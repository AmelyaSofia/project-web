<?php
session_start();

if (!isset($_SESSION['id_client'])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/../model/bookingModel.php';
require_once __DIR__ . '/../model/clientModel.php';
require_once __DIR__ . '/../model/stylistModel.php';
require_once __DIR__ . '/../model/layananModel.php';
require_once __DIR__ . '/../model/pembayaranModel.php';

$bookingModel = new ModelBooking();
$clientModel = new ModelClient();
$stylistModel = new ModelStylist();
$layananModel = new ModelLayanan();
$pembayaranModel = new ModelPembayaran();

$id_client = $_SESSION['id_client'];

$riwayatBookings = $bookingModel->getBookingByClient($id_client);
$clientData = $clientModel->getClientById($id_client);

foreach ($riwayatBookings as &$booking) {
    $stylist = $stylistModel->getStylistById($booking['id_stylist']);
    $booking['nama_stylist'] = $stylist['nama_stylist'] ?? 'Tidak Diketahui';

    $layanan_list = $bookingModel->getLayananByBooking($booking['id_booking']);
    $booking['layanan_list'] = $layanan_list ?: [];

    $booking['total_harga'] = array_sum(array_column($layanan_list, 'harga'));
    $booking['total_durasi'] = array_sum(array_column($layanan_list, 'durasi'));

    $pembayaran = $pembayaranModel->cekDPTelahDibayar($booking['id_booking']);
    if ($pembayaran) {
        $booking['pembayaran_dp_status'] = $pembayaran['status_pembayaran']; 
        $booking['bukti_pembayaran'] = $pembayaran['bukti_pembayaran'];
    } else {
        $booking['pembayaran_dp_status'] = 'belum';
        $booking['bukti_pembayaran'] = null;
    }
    $pembayaranLunas = $pembayaranModel->cekPembayaranLunas($booking['id_booking']);
    if ($pembayaranLunas) {
        $booking['pembayaran_lunas_status'] = $pembayaranLunas['status_pembayaran'];
        $booking['bukti_pembayaran_lunas'] = $pembayaranLunas['bukti_pembayaran'];
    } else {
        $booking['pembayaran_lunas_status'] = 'belum';
        $booking['bukti_pembayaran_lunas'] = null;
    }
}
unset($booking);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Booking - Royal Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #D7C0AE;
            --secondary-color: #967E76;
            --dark-color: #5F5B5B;
            --light-color: #EEE3CB;
            --accent-color: #B7C4CF;
            --bg-color: #f8f9fa;
        }
        
        body {
            background-color: var(--bg-color);
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        .navbar {
            background-color: white !important;
            color: var(--dark-color) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 10px 0;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--secondary-color) !important;
        }
        
        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }
        
        .navbar-nav {
            margin: 0 auto;
        }
        
        .nav-link {
            color: var(--dark-color) !important;
            margin: 0 15px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            position: relative;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--secondary-color) !important;
        }
        
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            background: var(--secondary-color);
            bottom: 0;
            left: 0;
            transition: width 0.3s;
        }
        
        .nav-link:hover:after, .nav-link.active:after {
            width: 100%;
        }
        
        /* Main Content */
        .booking-history {
            padding: 80px 0;
            flex: 1;
        }
        
        .page-title {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 30px;
            position: relative;
            text-align: center;
        }
        
        .page-title:after {
            content: "";
            display: block;
            width: 100px;
            height: 4px;
            background: var(--secondary-color);
            margin: 15px auto 0;
            border-radius: 2px;
        }
        
        .greeting {
            text-align: center;
            margin-bottom: 40px;
            color: var(--dark-color);
            font-size: 1.1rem;
        }
        
        .history-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
            margin-bottom: 25px;
            background-color: white;
        }
        
        .history-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: var(--dark-color);
            font-weight: 600;
            padding: 15px 20px;
            border-bottom: none;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .booking-detail {
            margin-bottom: 10px;
            display: flex;
            align-items: flex-start;
        }
        
        .booking-icon {
            color: var(--secondary-color);
            margin-right: 10px;
            margin-top: 3px;
            min-width: 20px;
        }
        
        .status-badge {
            font-size: 0.8rem;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 500;
            text-transform: capitalize;
        }
        
        .status-menunggu {
            background-color: #FFF3CD;
            color: #856404;
        }
        
        .status-diterima {
            background-color: #D4EDDA;
            color: #155724;
        }
        
        .status-ditolak {
            background-color: #F8D7DA;
            color: #721C24;
        }
        
        .status-selesai {
            background-color: #D1ECF1;
            color: #0C5460;
        }
        
        .no-history {
            text-align: center;
            padding: 50px 0;
            color: var(--dark-color);
        }
        
        .no-history i {
            font-size: 3rem;
            color: var(--accent-color);
            margin-bottom: 20px;
        }
        
        /* Footer Styles */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 60px 0 30px;
        }
        
        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 1.8rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        
        .footer-logo img {
            height: 40px;
            margin-right: 10px;
        }
        
        .footer-about {
            color: #ddd;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        
        .footer-links h5 {
            color: var(--primary-color);
            margin-bottom: 20px;
            font-weight: 600;
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: #ddd;
            text-decoration: none;
            transition: color 0.3s;
        }
        
        .footer-links a:hover {
            color: var(--primary-color);
            text-decoration: underline;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            font-size: 1.2rem;
            margin-right: 10px;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            background-color: var(--primary-color);
            transform: translateY(-3px);
        }
        
        .copyright {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 20px;
            margin-top: 40px;
            text-align: center;
            color: #aaa;
        }
        
        @media (max-width: 768px) {
            .booking-history {
                padding: 50px 0;
            }
            
            .page-title {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="clientDashboard.php">
                <img src="../image/logo.png" alt="Royal Beauty Logo">
                Royal Beauty
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="clientDashboard.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clientDashboard.php#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clientDashboard.php#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="clientDashboard.php#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="riwayatBooking.php"><i class="fas fa-history me-1"></i> Riwayat</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="booking-history">
        <div class="container">
            <h1 class="page-title">Riwayat Booking</h1>
            
            <?php if (!empty($clientData)): ?>
                <p class="greeting">Halo, <strong><?= htmlspecialchars($clientData['nama_client']) ?></strong>. Berikut adalah riwayat booking Anda:</p>
            <?php endif; ?>

            <?php if (!empty($riwayatBookings)): ?>
    <div class="row">
        <?php foreach ($riwayatBookings as $booking): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card history-card">
                    <div class="card-header">
                        <strong>Layanan:</strong>
                        <ul class="mb-0 ps-3">
                            <?php foreach ($booking['layanan_list'] as $l): ?>
                                <li><?= htmlspecialchars($l['nama_layanan']) ?> - Rp <?= number_format($l['harga'], 0, ',', '.') ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="float-end mt-2">
                            <span class="badge bg-success">Total: Rp <?= number_format($booking['total_harga'], 0, ',', '.') ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="booking-detail">
                            <div class="booking-icon">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <strong>Stylist:</strong> <?= htmlspecialchars($booking['nama_stylist']) ?>
                            </div>
                        </div>

                        <div class="booking-detail">
                            <div class="booking-icon">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            <div>
                                <strong>Tanggal:</strong> <?= date('d M Y', strtotime($booking['tanggal'])) ?>
                            </div>
                        </div>

                        <div class="booking-detail">
                            <div class="booking-icon">
                                <i class="far fa-clock"></i>
                            </div>
                            <div>
                                <strong>Waktu:</strong> <?= htmlspecialchars($booking['waktu']) ?>
                                <span class="text-muted">(<?= $booking['total_durasi'] ?> menit)</span>
                            </div>
                        </div>

                        <div class="booking-detail">
                            <div class="booking-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div>
                                <strong>Status:</strong> 
                                <span class="status-badge 
                                    <?= $booking['status'] == 'menunggu' ? 'status-menunggu' : 
                                       ($booking['status'] == 'diterima' ? 'status-diterima' : 
                                       ($booking['status'] == 'selesai' ? 'status-selesai' : 'status-ditolak')) ?>">
                                    <?= ucfirst(htmlspecialchars($booking['status'])) ?>
                                </span>
                            </div>
                        </div>

                        <div class="booking-detail">
                            <div class="booking-icon">
                                <i class="fas fa-comment"></i>
                            </div>
                            <div>
                                <strong>Catatan:</strong> <?= htmlspecialchars($booking['catatan'] ?: '-') ?>
                            </div>
                        </div>

<div class="booking-detail">
    <div class="booking-icon">
        <i class="fas fa-money-check-alt"></i>
    </div>
    <div>
        <strong>Status DP:</strong>

        <?php
        $dp_status = $booking['pembayaran_dp_status'] ?? 'belum';

        if ($dp_status === 'dibayar'): ?>
            <span class="status-badge" style="background-color: #D4EDDA; color: #155724;">Sudah Dibayar</span>
            <br>
            <a href="../uploads/<?= htmlspecialchars($booking['bukti_pembayaran'] ?? '') ?>" target="_blank" class="btn btn-sm btn-outline-primary mt-2">
                <i class="fas fa-file-image me-1"></i>Lihat Bukti
            </a>

            <!-- Tampilkan tombol Bayar Lunas HANYA jika pelunasan belum dibayar -->
            <?php if (($booking['pembayaran_lunas_status'] ?? 'belum') === 'belum'): ?>
                <br>
                <a href="pembayaranLunas.php?id=<?= $booking['id_booking'] ?>" class="btn btn-sm btn-success mt-2">
                    <i class="fas fa-check-circle me-1"></i>Bayar Lunas Sekarang
                </a>
            <?php endif; ?>

        <?php elseif ($dp_status === 'pending'): ?>
            <span class="status-badge" style="background-color: #FFEFD5; color: #856404;">Menunggu Verifikasi</span>
            <br>
            <a href="../uploads/<?= htmlspecialchars($booking['bukti_pembayaran'] ?? '') ?>" target="_blank" class="btn btn-sm btn-outline-secondary mt-2">
                <i class="fas fa-clock me-1"></i>Lihat Bukti
            </a>

        <?php elseif ($dp_status === 'ditolak'): ?>
            <span class="status-badge" style="background-color: #F8D7DA; color: #721C24;">Pembayaran Ditolak</span>
            <br>
            <a href="pembayaran.php?id=<?= $booking['id_booking'] ?>" class="btn btn-sm btn-warning mt-2">
                <i class="fas fa-redo me-1"></i>Upload Ulang Bukti
            </a>

        <?php else: ?>
            <span class="status-badge" style="background-color: #FFF3CD; color: #856404;">Belum Bayar DP</span>
            <br>
            <a href="pembayaran.php?id=<?= $booking['id_booking'] ?>" class="btn btn-sm btn-primary mt-2">
                <i class="fas fa-wallet me-1"></i>Bayar DP Sekarang
            </a>
        <?php endif; ?>
    </div>
</div>
<div class="booking-detail">
    <div class="booking-icon">
        <i class="fas fa-money-bill-wave"></i>
    </div>
    <div>
        <strong>Status Pelunasan:</strong>
        
        <?php
        $lunas_status = $booking['pembayaran_lunas_status'] ?? 'belum';
        
        if ($lunas_status === 'dibayar'): ?>
            <span class="status-badge" style="background-color: #D4EDDA; color: #155724;">
                Lunas (Terverifikasi)
            </span>
            <br>
            <a href="../uploads/<?= htmlspecialchars($booking['bukti_pembayaran_lunas'] ?? '') ?>" 
               target="_blank" 
               class="btn btn-sm btn-outline-success mt-2">
                <i class="fas fa-file-invoice-dollar me-1"></i>Lihat Bukti Lunas
            </a>
            
        <?php elseif ($lunas_status === 'pending'): ?>
            <span class="status-badge" style="background-color: #FFEFD5; color: #856404;">
                Menunggu Verifikasi Pelunasan
            </span>
            <br>
            <a href="../uploads/<?= htmlspecialchars($booking['bukti_pembayaran_lunas'] ?? '') ?>" 
               target="_blank" 
               class="btn btn-sm btn-outline-warning mt-2">
                <i class="fas fa-hourglass-half me-1"></i>Lihat Bukti Lunas
            </a>
            
        <?php elseif ($lunas_status === 'ditolak'): ?>
            <span class="status-badge" style="background-color: #F8D7DA; color: #721C24;">
                Pelunasan Ditolak
            </span>
            <br>
            <a href="pembayaranLunas.php?id=<?= $booking['id_booking'] ?>" 
               class="btn btn-sm btn-danger mt-2">
                <i class="fas fa-exclamation-triangle me-1"></i>Upload Ulang Pelunasan
            </a>
            
        <?php elseif ($booking['pembayaran_dp_status'] === 'dibayar' && $lunas_status === 'belum'): ?>
            <span class="status-badge" style="background-color: #E2E3E5; color: #383D41;">
                Belum Lunas
            </span>
            <br>
            <a href="pembayaranLunas.php?id=<?= $booking['id_booking'] ?>" 
               class="btn btn-sm btn-success mt-2">
                <i class="fas fa-check-circle me-1"></i>Bayar Lunas Sekarang
            </a>
            
        <?php else: ?>
            <span class="status-badge" style="background-color: #E2E3E5; color: #383D41;">
                Belum Lunas
            </span>
        <?php endif; ?>
    </div>
</div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="no-history">
        <i class="fas fa-calendar-times"></i>
        <h3>Belum Ada Riwayat Booking</h3>
        <p>Anda belum memiliki riwayat booking. Silakan booking layanan kami terlebih dahulu.</p>
        <a href="clientDashboard.php#services" class="btn btn-primary">Lihat Layanan</a>
    </div>
<?php endif; ?>
        </div>
    </section>

    <footer id="contact">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="footer-logo">
                        <img src="../image/logo.png" alt="Royal Beauty Logo">
                        Royal Beauty
                    </div>
                    <p class="footer-about">
                        Royal Beauty Salon offers premium beauty treatments in a luxurious setting. Our mission is to make every client feel like royalty.
                    </p>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-4 mb-5 mb-lg-0">
                    <div class="footer-links">
                        <h5>Quick Links</h5>
                        <ul>
                            <li><a href="clientDashboard.php">Home</a></li>
                            <li><a href="clientDashboard.php#about">About Us</a></li>
                            <li><a href="clientDashboard.php#services">Services</a></li>
                            <li><a href="riwayatBooking.php">Booking History</a></li>
                            <li><a href="clientDashboard.php#contact">Contact</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="footer-links">
                        <h5>Contact Us</h5>
                        <ul>
                            <li><i class="fas fa-map-marker-alt me-2"></i> Jl. Kecantikan No. 123, Kota Cantik</li>
                            <li><i class="fas fa-phone me-2"></i> (0811) 12345678</li>
                            <li><i class="fas fa-envelope me-2"></i> info@royalbeauty.com</li>
                            <li><i class="fas fa-clock me-2"></i> Mon-Sat: 9AM - 8PM</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="copyright">
                <p class="mb-0">&copy; 2025 Royal Beauty Salon. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>