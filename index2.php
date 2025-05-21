<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Royal Beauty - Dashboard Pengguna</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
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
    }

    .logo-title img {
      height: auto;
      max-height: 120px; /* Perbesar logo tapi batasi agar header tetap stabil */
    }


    .logo-title {
      display: flex;
      align-items: center;
      gap: 15px;
    }


    header h1 {
      font-family: 'Playfair Display', serif;
      font-size: 28px;
      color: #c88f8f;
      margin: 0;
      letter-spacing: 1px;
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

    .container {
      max-width: 1200px;
      margin: 30px auto;
      padding: 0 20px;
    }

    .welcome-banner {
      background: linear-gradient(135deg, rgba(200, 143, 143, 0.1) 0%, rgba(255, 255, 255, 0.8) 100%);
      border-radius: 10px;
      padding: 25px 30px;
      margin-bottom: 30px;
      border: 1px solid #e0d5ca;
    }

    .welcome-banner h2 {
      font-family: 'Playfair Display', serif;
      color: #c88f8f;
      margin-top: 0;
      font-size: 24px;
    }

    .section-title {
      font-family: 'Playfair Display', serif;
      color: #c88f8f;
      font-size: 22px;
      margin: 30px 0 20px;
      position: relative;
      padding-bottom: 10px;
    }

    .section-title::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      width: 50px;
      height: 2px;
      background-color: #c88f8f;
    }

    .card-container {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 30px;
      margin-bottom: 40px;
    }

    .card {
      background-color: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
      transition: transform 0.4s ease, box-shadow 0.4s ease;
      overflow: hidden;
    }

    .card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .card-img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }

    .card-content {
      padding: 20px;
    }

    .card h3 {
      color: #c88f8f;
      font-size: 18px;
      margin: 0 0 10px;
    }

    .card p {
      font-size: 14px;
      color: #6a4f4f;
      margin: 0 0 15px;
      line-height: 1.5;
    }

    .card-footer {
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding-top: 10px;
      border-top: 1px dashed #e0d5ca;
    }

    .btn-book {
      background-color: #c88f8f;
      color: white;
      padding: 8px 16px;
      border-radius: 20px;
      border: none;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
      font-size: 14px;
    }

    .btn-book:hover {
      background-color: #b57d7d;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(200, 143, 143, 0.3);
    }

    footer {
      background-color: #fff;
      text-align: center;
      padding: 20px 10px;
      font-size: 14px;
      color: #8c6c5c;
      border-top: 1px solid #e0d5ca;
      margin-top: 50px;
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

    @media (max-width: 992px) {
      .card-container {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 600px) {
      .card-container {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>

  <header>
    <div class="logo-title">
      <img src="image/logo.png" alt="Logo Royal Beauty" />
    </div>
    <div class="user-nav">
      <a href="tentang.php">Tentang Kami</a>
      <a href="profile.php">Profil</a>
      <a href="pesanan.php">Pesanan</a>
      <a href="logout.php">Logout</a>

      <form action="search.php" method="GET" style="display: flex; align-items: center;">
        <input type="text" name="query" placeholder="Cari layanan..." style="padding: 5px 10px; border-radius: 20px; border: 1px solid #e0d5ca; font-size: 14px; margin-left: 15px;">
        <button type="submit" style="background-color: #c88f8f; color: white; border: none; border-radius: 20px; padding: 5px 15px; font-size: 14px; cursor: pointer;">Cari</button>
      </form>
    </div>
  </header>

  <div class="container">
    <div class="welcome-banner">
      <h2>Selamat Datang di Royal Beauty</h2>
      <p>Temukan layanan kecantikan premium kami untuk membuat Anda tampil lebih percaya diri dan memukau.</p>
    </div>

    <h2 class="section-title">Layanan Tersedia</h2>

    <div class="card-container">
      <div class="card">
        <img src="image/layanan1.jpg" class="card-img" alt="Potong Rambut" />
        <div class="card-content">
          <h3>Potong Rambut</h3>
          <p>Potong rambut sesuai tren terbaru dengan stylist profesional kami untuk penampilan yang lebih segar.</p>
          <div class="card-footer">
            <button class="btn-book" onclick="window.location.href='booking.php?service=potong_rambut'">Booking</button>
          </div>
        </div>
      </div>

      <div class="card">
        <img src="image/layanan2.jpg" class="card-img" alt="Manicure & Pedicure" />
        <div class="card-content">
          <h3>Manicure & Pedicure</h3>
          <p>Perawatan lengkap untuk kuku tangan dan kaki menggunakan produk premium dan teknik terbaik.</p>
          <div class="card-footer">
            <button class="btn-book" onclick="window.location.href='booking.php?service=manicure_pedicure'">Booking</button>
          </div>
        </div>
      </div>

      <div class="card">
        <img src="image/layanan3.jpg" class="card-img" alt="Facial Treatment" />
        <div class="card-content">
          <h3>Facial Treatment</h3>
          <p>Perawatan wajah menyeluruh untuk kulit yang lebih sehat, cerah, dan bercahaya alami.</p>
          <div class="card-footer">
            <button class="btn-book" onclick="window.location.href='booking.php?service=facial_treatment'">Booking</button>
          </div>
        </div>
      </div>

      <div class="card">
        <img src="image/layanan4.jpg" class="card-img" alt="Hair Coloring" />
        <div class="card-content">
          <h3>Hair Coloring</h3>
          <p>Transformasi warna rambut dengan produk berkualitas tinggi dan teknik pewarnaan profesional.</p>
          <div class="card-footer">
            <button class="btn-book" onclick="window.location.href='booking.php?service=hair_color'">Booking</button>
          </div>
        </div>
      </div>

      <div class="card">
        <img src="image/layanan6.jpg" class="card-img" alt="Makeup Artist" />
        <div class="card-content">
          <h3>Makeup Artist</h3>
          <p>Layanan makeup profesional untuk berbagai acara spesial oleh makeup artist berpengalaman.</p>
          <div class="card-footer">
            <button class="btn-book" onclick="window.location.href='booking.php?service=makeup_service'">Booking</button>
          </div>
        </div>
      </div>

      <div class="card">
        <img src="image/layanan5.jpg" class="card-img" alt="Spa & Relaksasi" />
        <div class="card-content">
          <h3>Spa & Relaksasi</h3>
          <p>Pengalaman spa lengkap untuk merilekskan tubuh dan pikiran dengan aromaterapi pilihan.</p>
          <div class="card-footer">
            <button class="btn-book" onclick="window.location.href='booking.php?service=spa_relaksasi'">Booking</button>
          </div>
        </div>
      </div>
    </div>
  </div>

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
