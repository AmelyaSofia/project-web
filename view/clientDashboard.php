<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: login.php');
    exit;
}
require_once '../config/dbconnect.php';
$query = "SELECT * FROM layanan LIMIT 3"; // Only fetch 3 services initially
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
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
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
        
        /* Animated Hero Banner */
        .hero-banner {
            position: relative;
            height: 500px;
            overflow: hidden;
            margin-bottom: 60px;
        }
        
        .banner-slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .banner-slide.active {
            opacity: 1;
        }
        
        .banner-content {
            background-color: rgba(255, 255, 255, 0.85);
            padding: 40px;
            border-radius: 15px;
            max-width: 800px;
            backdrop-filter: blur(5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            transform: translateY(20px);
            transition: transform 1s ease;
        }
        
        .banner-slide.active .banner-content {
            transform: translateY(0);
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--dark-color);
            font-family: 'Playfair Display', serif;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.1);
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: var(--dark-color);
            line-height: 1.6;
            margin-bottom: 25px;
        }
        
        .btn-royal {
            background-color: var(--secondary-color);
            color: white;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 500;
            letter-spacing: 1px;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-royal:hover {
            background-color: var(--dark-color);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .banner-nav {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            padding: 0 20px;
            z-index: 10;
            transform: translateY(-50%);
        }
        
        .banner-nav-btn {
            background-color: rgba(255,255,255,0.7);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
            color: var(--dark-color);
            font-size: 1.2rem;
        }
        
        .banner-nav-btn:hover {
            background-color: white;
            transform: scale(1.1);
        }
        
        .banner-indicators {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            display: flex;
            justify-content: center;
            gap: 10px;
            z-index: 10;
        }
        
        .banner-indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .banner-indicator.active {
            background-color: white;
            transform: scale(1.2);
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: all 0.3s;
            margin-bottom: 30px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        
        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.15);
        }
        
        .card-img-top {
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s;
        }
        
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        
        .card-body {
            background-color: white;
            padding: 25px;
        }
        
        .card-title {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 15px;
            font-size: 1.3rem;
        }
        
        .card-text {
            color: #666;
            margin-bottom: 20px;
        }
        
        .price-tag {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--secondary-color);
        }
        
        .btn-book {
            background-color: var(--secondary-color);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-book:hover {
            background-color: var(--dark-color);
            transform: translateY(-2px);
        }
        
        /* Section Styles */
        .services-section {
            padding: 80px 0;
        }
        
        .section-title {
            text-align: center;
            margin-bottom: 50px;
            color: var(--dark-color);
            position: relative;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            font-size: 2.5rem;
        }
        
        .section-title:after {
            content: "";
            display: block;
            width: 100px;
            height: 4px;
            background: var(--secondary-color);
            margin: 20px auto;
            border-radius: 2px;
        }
        
        .section-subtitle {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 50px;
            color: #666;
            font-size: 1.1rem;
            line-height: 1.8;
        }
        
        /* About Section */
        .about-section {
            padding: 80px 0;
            background-color: #f9f5f0;
        }
        
        .about-img {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .about-img img {
            width: 100%;
            height: auto;
            transition: transform 0.5s;
        }
        
        .about-img:hover img {
            transform: scale(1.03);
        }
        
        .about-content h2 {
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 25px;
        }
        
        .about-content p {
            color: #666;
            line-height: 1.8;
            margin-bottom: 20px;
        }
        
        /* View More Button */
        .view-more-btn {
            display: block;
            text-align: center;
            margin: 40px auto 0;
            background-color: transparent;
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: 600;
            transition: all 0.3s;
            max-width: 200px;
        }
        
        .view-more-btn:hover {
            background-color: var(--secondary-color);
            color: white;
            transform: translateY(-3px);
        }
        
        /* Hidden Services */
        .hidden-services {
            display: none;
        }
        
        /* Footer Styles */
        footer {
            background-color: var(--dark-color);
            color: white;
            padding: 60px 0 30px;
            margin-top: 80px;
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="../image/logo.png" alt="Royal Beauty Logo">
                Royal Beauty
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#dashboard">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="riwayatBooking.php"><i class="fas fa-history me-1"></i> Riwayat Booking</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-banner" id="dashboard">
        <div class="banner-slide active" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('../image/banner.jpg');">
            <div class="banner-content">
                <h1 class="hero-title">Experience Royal Beauty</h1>
                <p class="hero-subtitle">
                    Where luxury meets transformation. Step into a world of elegance and let us unveil your most radiant self.
                </p>
                <a href="#services" class="btn btn-royal">Discover Services</a>
            </div>
        </div>
        
        <div class="banner-slide" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('../image/tritmen.jpg');">
            <div class="banner-content">
                <h1 class="hero-title">Premium Treatments</h1>
                <p class="hero-subtitle">
                    Indulge in our exclusive therapies crafted with royal-grade products and expert care.
                </p>
                <a href="#services" class="btn btn-royal">Explore Treatments</a>
            </div>
        </div>

        <div class="banner-slide" style="background-image: linear-gradient(rgba(0,0,0,0.3), rgba(0,0,0,0.3)), url('../image/layanan/spa.jpg');">
            <div class="banner-content">
                <h1 class="hero-title">Special Offers</h1>
                <p class="hero-subtitle">
                    Enjoy 20% off your first visit with code "ROYAL20". Your journey to beauty begins here.
                </p>
                <a href="#services" class="btn btn-royal">Claim Offer</a>
            </div>
        </div>

        <div class="banner-nav">
            <div class="banner-nav-btn prev-slide"><i class="fas fa-chevron-left"></i></div>
            <div class="banner-nav-btn next-slide"><i class="fas fa-chevron-right"></i></div>
        </div>
        
        <div class="banner-indicators">
            <div class="banner-indicator active" data-slide="0"></div>
            <div class="banner-indicator" data-slide="1"></div>
            <div class="banner-indicator" data-slide="2"></div>
        </div>
    </div>

    <section id="about" class="about-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="about-img">
                        <img src="../image/royal.jpg" alt="About Royal Beauty">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <h2>Our Royal Heritage</h2>
                        <p>Founded in 2025, Royal Beauty Salon has been transforming beauty routines into luxurious rituals. Our journey began with a simple vision: to create a sanctuary where every client feels like royalty.</p>
                        <p>Today, we stand as a premier destination for those who seek exceptional beauty treatments delivered with precision, care, and a touch of royal elegance.</p>
                        <p>Our team of certified professionals undergoes rigorous training to master both traditional techniques and the latest innovations in beauty care.</p>
                        <a href="#services" class="btn btn-royal">Our Services</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="services" class="services-section">
        <div class="container">
            <h2 class="section-title">Our Signature Services</h2>
            <p class="section-subtitle">Experience our curated selection of premium beauty treatments designed to enhance your natural radiance.</p>
            
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
                            <p class="text-muted"><i class="fas fa-clock me-2"></i><?php echo $layanan['durasi']; ?> minutes</p>
                            <p class="price-tag">Rp <?php echo number_format($layanan['harga'], 0, ',', '.'); ?></p>
                            <a href="booking.php?id=<?php echo $layanan['id_layanan']; ?>" class="btn btn-book">Book Now</a>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                } else {
                    echo '<div class="col-12 text-center"><p>No services available at the moment.</p></div>';
                }
                ?>
            </div>

            <div class="row hidden-services" id="moreServices">
                <?php 
                $query = "SELECT * FROM layanan LIMIT 3, 6";
                $moreLayanans = mysqli_query($conn, $query);
                
                if (mysqli_num_rows($moreLayanans) > 0) {
                    while($layanan = mysqli_fetch_assoc($moreLayanans)) {
                ?>
                <div class="col-md-4">
                    <div class="card">
                        <img src="../image/layanan/<?php echo $layanan['gambar_layanan']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($layanan['nama_layanan']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($layanan['nama_layanan']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($layanan['deskripsi']); ?></p>
                            <p class="text-muted"><i class="fas fa-clock me-2"></i><?php echo $layanan['durasi']; ?> minutes</p>
                            <p class="price-tag">Rp <?php echo number_format($layanan['harga'], 0, ',', '.'); ?></p>
                            <a href="booking.php?id=<?php echo $layanan['id_layanan']; ?>" class="btn btn-book">Book Now</a>
                        </div>
                    </div>
                </div>
                <?php 
                    }
                }
                ?>
            </div>
            
            <?php if (mysqli_num_rows($moreLayanans) > 0): ?>
            <button class="view-more-btn" id="viewMoreBtn">View More Services</button>
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
                            <li><a href="#">Home</a></li>
                            <li><a href="#about">About Us</a></li>
                            <li><a href="#services">Services</a></li>
                            <li><a href="#">Gallery</a></li>
                            <li><a href="#contact">Contact</a></li>
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
                <p class="mb-0">&copy; 2023 Royal Beauty Salon. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.banner-slide');
            const indicators = document.querySelectorAll('.banner-indicator');
            const prevBtn = document.querySelector('.prev-slide');
            const nextBtn = document.querySelector('.next-slide');
            let currentSlide = 0;
            const slideCount = slides.length;
            let slideInterval;
            
            function showSlide(index) {
                slides.forEach(slide => slide.classList.remove('active'));
                indicators.forEach(indicator => indicator.classList.remove('active'));

                slides[index].classList.add('active');
                indicators[index].classList.add('active');
                currentSlide = index;
            }
            
            function nextSlide() {
                currentSlide = (currentSlide + 1) % slideCount;
                showSlide(currentSlide);
            }
            
            function prevSlide() {
                currentSlide = (currentSlide - 1 + slideCount) % slideCount;
                showSlide(currentSlide);
            }

            function startSlideInterval() {
                slideInterval = setInterval(nextSlide, 5000);
            }
            
            startSlideInterval();

            const banner = document.querySelector('.hero-banner');
            banner.addEventListener('mouseenter', () => clearInterval(slideInterval));
            banner.addEventListener('mouseleave', startSlideInterval);

            indicators.forEach(indicator => {
                indicator.addEventListener('click', function() {
                    const slideIndex = parseInt(this.getAttribute('data-slide'));
                    showSlide(slideIndex);

                    clearInterval(slideInterval);
                    startSlideInterval();
                });
            });

            nextBtn.addEventListener('click', function() {
                nextSlide();
                clearInterval(slideInterval);
                startSlideInterval();
            });
            
            prevBtn.addEventListener('click', function() {
                prevSlide();
                clearInterval(slideInterval);
                startSlideInterval();
            });

            const viewMoreBtn = document.getElementById('viewMoreBtn');
            if (viewMoreBtn) {
                viewMoreBtn.addEventListener('click', function() {
                    const moreServices = document.getElementById('moreServices');
                    if (moreServices.style.display === 'none' || moreServices.style.display === '') {
                        moreServices.style.display = 'flex';
                        this.textContent = 'View Less';
                    } else {
                        moreServices.style.display = 'none';
                        this.textContent = 'View More Services';
                    }

                    setTimeout(() => {
                        this.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    }, 100);
                });
            }

            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const targetId = this.getAttribute('href');
                    if (targetId === '#') return;
                    
                    const targetElement = document.querySelector(targetId);
                    if (targetElement) {
                        targetElement.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>
</html>