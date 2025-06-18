<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Royal Beauty - Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Roboto&display=swap" rel="stylesheet">
  <style>
    body {
      background: #fdf6f0;
      font-family: 'Roboto', sans-serif;
    }
    .sidebar {
      background: white;
      border-right: 1px solid #5c4033;
    }
    .sidebar .nav-link.active {
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
    .sidebar .nav-link.active:hover {
      background-color: #e0d5ca;
      color: #5c4033;
    }
    .dashboard-header {
      background: #f3e5dc;
      font-family: 'Playfair Display', serif;
      border-radius: 15px;
      color: #c88f8f;
      padding: 20px;
    }
    .dashboard-header p {
      font-family: 'Roboto', sans-serif;
      color: #5c4033;
    }
    .logo-title {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .profile-img {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      object-fit: cover;
    }
    .project-card {
      border-radius: 10px;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      padding: 15px;
    }
    .project-card h6,
    .project-card p {
      font-family: 'Roboto', sans-serif;
      color: #5c4033;
    }
    .col-md-6,
    .col-md-3,
    .col-md-9 {
      margin-bottom: 20px;
    }
    h6,
    .bg-white h6,
    .bg-white p {
      color: #5c4033 !important;
    }
    .team-member-name,
    .team-member-role {
      color: #5c4033;
    }
  </style>
</head>
<body>
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 sidebar d-flex flex-column p-3">
        <div class="logo-title mb-4">
          <img src="image/logo.png" alt="Logo Royal Beauty" style="max-height: 120px;">
        </div>
        <a href="#" class="nav-link active mb-2"><i class="fa fa-chart-line me-2"></i> Dashboard</a>
        <a href="#" class="nav-link active mb-2"><i class="fa fa-calendar-check me-2"></i> Janji Temu</a>
        <a href="#" class="nav-link active mb-2"><i class="fa fa-cut me-2"></i> Layanan</a>
        <a href="#" class="nav-link active mb-2"><i class="fa fa-user-friends me-2"></i> Klien</a>
        <a href="#" class="nav-link active mb-2"><i class="fa fa-file-invoice-dollar me-2"></i> Laporan Keuangan</a>
        <a href="#" class="nav-link active mb-2"><i class="fa fa-cog me-2"></i> Pengaturan</a>
        <a href="#" class="nav-link active mt-auto"><i class="fa fa-question-circle me-2"></i> Bantuan</a>
      </div>

      <div class="col-md-10 p-4">
        <div class="dashboard-header mb-4 d-flex justify-content-between align-items-center">
          <div>
            <h5>Selamat Datang, Admin Royal</h5>
            <p>Anda dapat mengelola janji temu, layanan, klien, dan laporan keuangan salon di sini.</p>
          </div>
          <div class="text-end">
            <p class="mb-1">Login sebagai: <strong>admin@royalbeauty.com</strong></p>
            <img src="image\yoona.jpg" alt="Admin Profile" class="profile-img">
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-md-9">
            <div class="bg-white p-3 rounded shadow-sm">
              <h6>Aktivitas Mingguan</h6>
              <canvas id="activityChart"></canvas>
            </div>
          </div>
          <div class="col-md-3">
            <div class="bg-white p-3 rounded mb-3">
              <h6>Total Klien</h6>
              <p id="totalKlien">132</p>
            </div>
            <div class="bg-white p-3 rounded">
              <h6>Pendapatan Bulan Ini</h6>
              <p id="pendapatan">Rp12.350.000</p>
            </div>
          </div>
        </div>

        <div class="row">
          <h6>Layanan Populer</h6>
          <div class="col-md-4">
            <div class="project-card">
              <h6>Facial Glow</h6>
              <p>Perawatan wajah untuk kulit cerah dan sehat.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="project-card">
              <h6>Hair Spa</h6>
              <p>Perawatan rambut untuk menjaga kesehatan rambut Anda.</p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="project-card">
              <h6>Manicure & Pedicure</h6>
              <p>Perawatan kuku tangan dan kaki yang menyegarkan.</p>
            </div>
          </div>
        </div>

        <div class="row mt-4">
          <h6>Tim Salon</h6>
          <div class="col-md-6">
            <div class="d-flex align-items-center bg-white p-2 rounded">
              <img src="image\yoona.jpg" class="profile-img me-3">
              <div>
                <strong class="team-member-name">Zahra</strong><br>
                <small class="team-member-role">Beautician</small>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="d-flex align-items-center bg-white p-2 rounded">
              <img src="image\yoona.jpg" class="profile-img me-3">
              <div>
                <strong class="team-member-name">Riva</strong><br>
                <small class="team-member-role">Hair Stylist</small>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const ctx = document.getElementById('activityChart');
    new Chart(ctx, {
      type: 'line',
      data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
          label: 'Janji Temu',
          data: [5, 8, 6, 9, 7, 10, 12],
          borderColor: '#a78bfa',
          backgroundColor: 'rgba(167, 139, 250, 0.1)',
          fill: true,
          tension: 0.4
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false }
        }
      }
    });
  </script>
</body>
</html>
