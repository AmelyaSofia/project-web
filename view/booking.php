<?php
session_start();
require_once __DIR__.'/../model/clientModel.php';
require_once __DIR__.'/../model/stylistModel.php';
require_once __DIR__.'/../model/layananModel.php';
require_once __DIR__.'/../controller/bookingController.php';

$controller = new ControllerBooking();

$id_layanan = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_client = $_SESSION['id_client'] ?? null;
    $id_stylist = $_POST['id_stylist'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $catatan = $_POST['catatan'] ?? '';

    $status = 'menunggu';

    $success = $controller->modelBooking->addBooking($id_client, $id_stylist, $id_layanan, $tanggal, $waktu, $catatan, $status);

    if ($success) {
        header("Location: riwayatBooking.php?message=Booking+berhasil+dibuat");
        exit;
    } else {
        header("Location: booking.php?id=$id_layanan&message=Gagal+membuat+booking");
        exit;
    }
}

$stylists = $controller->modelStylist->getStylists();
$layanan = $controller->modelLayanan->getLayananById($id_layanan);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Layanan - Royal Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            color: var(--dark-color);
        }
        
        /* Navbar Style */
        .navbar {
            background-color: white !important;
            color: var(--dark-color) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 10px 0;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            font-family: 'Poppins', sans-serif;
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
        
        /* Booking Form */
        .booking-container {
            padding: 80px 0;
            flex: 1;
        }
        
        .booking-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
            background-color: white;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .booking-header {
            background-color: var(--primary-color);
            color: var(--dark-color);
            font-weight: 600;
            padding: 20px;
            border-bottom: none;
            text-align: center;
            font-family: 'Poppins', sans-serif;
        }
        
        .booking-body {
            padding: 30px;
        }
        
        .form-label {
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-control, .form-select {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 20px;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.25rem rgba(150, 126, 118, 0.25);
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        
        .btn-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: var(--dark-color);
        }
        
        .alert {
            border-radius: 8px;
        }
        
        .service-info {
            background-color: var(--light-color);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .service-info h5 {
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        /* Footer Styles */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 60px 0 20px;
            font-family: 'Poppins', sans-serif;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            font-weight: 700;
            font-size: 1.5rem;
            margin-bottom: 20px;
            color: white;
        }
        
        .footer-logo img {
            height: 40px;
            margin-right: 10px;
        }
        
        .footer-about {
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .social-icons {
            margin-bottom: 20px;
        }
        
        .social-icons a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background-color: rgba(255,255,255,0.1);
            border-radius: 50%;
            color: white;
            margin-right: 10px;
            transition: all 0.3s;
        }
        
        .social-icons a:hover {
            background-color: var(--primary-color);
            color: var(--dark-color);
            transform: translateY(-3px);
        }
        
        .footer-links h5 {
            font-weight: 600;
            margin-bottom: 20px;
            position: relative;
            padding-bottom: 10px;
        }
        
        .footer-links h5:after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 50px;
            height: 2px;
            background-color: var(--primary-color);
        }
        
        .footer-links ul {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 10px;
        }
        
        .footer-links a {
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .footer-links a:hover {
            color: var(--primary-color);
            padding-left: 5px;
        }
        
        .footer-links i {
            width: 20px;
            margin-right: 10px;
            color: var(--primary-color);
        }
        
        .copyright {
            text-align: center;
            padding-top: 30px;
            margin-top: 30px;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.9rem;
            color: rgba(255,255,255,0.6);
        }
        
        @media (max-width: 768px) {
            .booking-container {
                padding: 50px 0;
            }
            
            .booking-body {
                padding: 20px;
            }
            
            footer {
                text-align: center;
            }
            
            .footer-links h5:after {
                left: 50%;
                transform: translateX(-50%);
            }
            
            .social-icons {
                justify-content: center;
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
                        <a class="nav-link" href="riwayatBooking.php"><i class="fas fa-history me-1"></i> Riwayat</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="booking-container">
        <div class="container">
            <div class="booking-card">
                <div class="booking-header">
                    <h2>Booking Layanan</h2>
                </div>
                <div class="booking-body">
                    <?php if (isset($_GET['message'])): ?>
                        <div class="alert alert-<?= strpos($_GET['message'], 'berhasil') !== false ? 'success' : 'danger' ?>">
                            <?= htmlspecialchars($_GET['message']) ?>
                        </div>
                    <?php endif; ?>

                    <!-- <div class="service-info">
                        <h5>Layanan yang dipilih:</h5>
                        <p class="mb-0"><?= htmlspecialchars($layanan['nama_layanan'] ?? 'Tidak dikenali') ?></p>
                    </div> -->
                    <div class="service-info">
                        <h5>Layanan yang dipilih:</h5>
                        <p class="mb-1"><strong><?= htmlspecialchars($layanan['nama_layanan'] ?? 'Tidak ditemukan') ?></strong></p>
                        <p class="mb-0">Harga: Rp <?= number_format($layanan['harga'] ?? 0, 0, ',', '.') ?></p>
                    </div>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="id_stylist" class="form-label">Pilih Stylist</label>
                            <select name="id_stylist" class="form-select" required>
                                <option value="">-- Pilih Stylist --</option>
                                <?php foreach ($stylists as $s): ?>
                                    <option value="<?= $s['id_stylist'] ?>">
                                        <?= htmlspecialchars($s['nama_stylist']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tanggal" class="form-label">Tanggal</label>
                                <input type="date" name="tanggal" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="waktu" class="form-label">Waktu</label>
                                <input type="time" name="waktu" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan Tambahan</label>
                            <textarea name="catatan" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="clientDashboard.php#services" class="btn btn-outline-secondary me-md-2">Kembali</a>
                            <button type="submit" class="btn btn-primary">Submit Booking</button>
                        </div>
                    </form>
                </div>
            </div>
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
                            <li><i class="fas fa-map-marker-alt"></i> Jl. Kecantikan No. 123, Kota Cantik</li>
                            <li><i class="fas fa-phone"></i> (0811) 12345678</li>
                            <li><i class="fas fa-envelope"></i> info@royalbeauty.com</li>
                            <li><i class="fas fa-clock"></i> Mon-Sat: 9AM - 8PM</li>
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
    <script>
        // Set minimum date to today
        document.querySelector('input[name="tanggal"]').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>