<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit;
}
require_once '../config/dbconnect.php';
$query = "SELECT * FROM layanan";
$layanans = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royal Beauty - Client Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #D7C0AE;
            --secondary-color: #967E76;
            --dark-color: #5F5B5B;
            --light-color: #EEE3CB;
            --accent-color: #B7C4CF;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }
        
        /* Navbar Style */
        .navbar {
            background-color: white !important;
            color: var(--dark-color) !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 15px 0;
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
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--secondary-color) !important;
        }
        
        /* Hero Banner */
        .hero-banner {
            background-image: url('../image/header.jpg');
            background-size: cover;       /* Biar gambar menutupi seluruh area */
            background-position: center;  /* Posisi gambar di tengah */
            background-repeat: no-repeat; /* Jangan diulang */
            padding: 125px 0;
            text-align: center;
            margin-bottom: 60px;
        }
        
        .hero-title {
            font-size: 2.5rem;
            font-weight: 300;
            margin-bottom: 20px;
            color: var(--dark-color);
        }
        
        .hero-subtitle {
            font-size: 1.2rem;
            max-width: 700px;
            margin: 0 auto;
            color: var(--dark-color);
            line-height: 1.6;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .card-img-top {
            height: 180px;
            object-fit: cover;
        }
        
        .card-body {
            background-color: var(--light-color);
        }
        
        .btn-primary {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--dark-color);
            border-color: var(--dark-color);
        }
        
        /* Section Styles */
        .services-section {
            padding: 60px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 40px;
            color: var(--dark-color);
            position: relative;
        }
        
        .section-title:after {
            content: "";
            display: block;
            width: 80px;
            height: 3px;
            background: var(--secondary-color);
            margin: 15px auto;
        }
        
        /* Footer Styles */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 40px 0 20px;
            margin-top: 60px;
        }
        
        .footer-links a {
            color: var(--primary-color);
            text-decoration: none;
            margin: 0 10px;
        }
        
        .footer-links a:hover {
            text-decoration: underline;
        }
        
        .social-icons a {
            color: white;
            font-size: 1.5rem;
            margin: 0 10px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Testimonials</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                    <li class="nav-item ms-3">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Banner -->
    <div class="hero-banner">
        <div class="container">
            <h1 class="hero-title">ROYAL BEAUTY SALON</h1>
            <p class="hero-subtitle">
                Selamat datang, <?php echo $_SESSION['username']; ?>! Kami menyediakan perawatan kecantikan premium 
                dengan standar kerajaan. Temukan layanan terbaik kami untuk membuat Anda merasa seperti ratu.
            </p>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container services-section">
        <h2 class="section-title">Our Premium Services</h2>
        
        <div class="row">
            <?php 
            if (mysqli_num_rows($layanans) > 0) {
                while($layanan = mysqli_fetch_assoc($layanans)) {
            ?>
            <div class="col-md-4">
                <div class="card">
                    <img src="../image/layanan/<?php echo $layanan['gambar_layanan']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($layanan['nama_layanan']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($layanan['nama_layanan']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($layanan['deskripsi']); ?></p>
                        <p class="text-muted"><i class="fas fa-clock me-2"></i><?php echo $layanan['durasi']; ?> menit</p>
                        <p class="h5">Rp <?php echo number_format($layanan['harga'], 0, ',', '.'); ?></p>
                        <a href="booking.php?id=<?php echo $layanan['id_layanan']; ?>" class="btn btn-primary">Book Now</a>
                    </div>
                </div>
            </div>
            <?php 
                }
            } else {
                echo '<div class="col-12 text-center"><p>Tidak ada layanan tersedia saat ini.</p></div>';
            }
            ?>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <h5><i class="fas fa-crown me-2"></i>Royal Beauty Salon</h5>
                    <p>Jl. Kecantikan No. 123, Kota Cantik<br>
                    Phone: (021) 12345678<br>
                    Email: info@royalbeauty.com</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <div class="footer-links mb-3">
                        <a href="#">About Us</a>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms & Conditions</a>
                        <a href="#">Contact</a>
                    </div>
                    <div class="social-icons">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <hr style="border-color: var(--primary-color);">
            <div class="text-center">
                <p class="mb-0">&copy; 2023 Royal Beauty Salon. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>