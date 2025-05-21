<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Royal Beauty Salon</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background-color: #fdf6f0;
      color: #5c4033;
      line-height: 1.6;
    }

    header {
      background-color: #fff;
      padding: 15px 40px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-bottom: 1px solid #e0d5ca;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      height: 60px;
      z-index: 10;
      position: relative;
    }

    .logo-title {
      display: flex;
      align-items: center;
      gap: 15px;
    }

    .logo-title img {
      height: auto;
      max-height: 120px;
    }

    .user-nav {
      display: flex;
      gap: 15px;
      align-items: center;
    }

    .user-nav a {
      text-decoration: none;
      color: #5c4033;
      font-size: 14px;
      font-weight: 500;
      padding: 8px 16px;
      background-color: #fff;
      border-radius: 20px;
      border: 1px solid #e0d5ca;
      transition: all 0.3s ease;
      display: inline-block;
    }

    .user-nav a:hover {
      background-color: #c88f8f;
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(200, 143, 143, 0.2);
    }

    .login-icon {
      display: flex;
      align-items: center;
      gap: 8px;
      text-decoration: none;
      font-size: 14px;
      color: #5c4033;
      font-weight: 500;
    }

    .login-icon i {
      color: #5c4033;
      font-size: 16px;
    }

    .hero {
      position: relative;
      width: 100%;
      height: 100vh;
      overflow: hidden;
    }

    .hero::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('image/gambarweb1.jpg') no-repeat center center / cover;
      z-index: -2;
    }

    .hero::after {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(255, 245, 235, 0.6);
      z-index: -1;
    }

    .hero-content {
      position: relative;
      z-index: 1;
      max-width: 700px;
      padding: 20px;
      text-align: center;
      margin: 0 auto;
      top: 30%;
    }

    .hero h2 {
      font-size: 44px;
      color: #b57d7d;
      font-family: 'Playfair Display', serif;
      margin-bottom: 10px;
    }

    .hero p {
      font-size: 18px;
      color: #6a4f4f;
    }

    footer {
      background-color: #fff;
      text-align: center;
      padding: 20px 10px;
      font-size: 14px;
      color: #8c6c5c;
      border-top: 1px solid #e0d5ca;
    }

    .contact-info {
      max-width: 600px;
      margin: 0 auto 10px;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 15px;
    }

    .social-links {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 15px;
    }

    .social-links a {
      color: #8c6c5c;
      font-size: 18px;
      text-decoration: none;
    }

    .social-links a:hover {
      color: #c88f8f;
    }
  </style>
</head>
<body>

<?php require_once 'config/dbconnect.php'; ?>

<header>
  <div class="logo-title">
    <img src="image/logo.png" alt="Logo Royal Beauty" />
  </div>
  <div class="user-nav">
    <a href="index.php">Home</a>
    <a href="layanan.php">Service</a>
    <a href="login.php" class="login-icon">
      <i class="fas fa-user"></i> Login
    </a>
  </div>
</header>

<section class="hero">
  <div class="hero-content">
    <h2>Transform Your Look with Royal Beauty</h2>
    <p>Layanan salon premium untuk kamu yang ingin tampil menawan setiap saat ada habib</p>
  </div>
</section>

<footer>
  <div class="contact-info">
    <p>📍 Jl. Mawar No. 123, Jakarta</p>
    <p>📞 0812-3456-7890</p>
    <p>📧 royalbeauty@example.com</p>
  </div>
  <p>&copy; 2025 Royal Beauty Salon. All Rights Reserved.</p>
</footer>

</body>
</html>
