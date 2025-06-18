<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once '../config/dbconnect.php'; 

$query_total = "SELECT COUNT(*) as total FROM booking";
$result_total = mysqli_query($conn, $query_total);
$total_booking = mysqli_fetch_assoc($result_total)['total'];

$today = date('Y-m-d');
$query_new = "SELECT COUNT(*) as new FROM booking WHERE tanggal = '$today'";
$result_new = mysqli_query($conn, $query_new);
$new_booking = mysqli_fetch_assoc($result_new)['new'];

$query_stylist = "SELECT COUNT(*) as total FROM stylist";
$result_stylist = mysqli_query($conn, $query_stylist);
$total_stylist = mysqli_fetch_assoc($result_stylist)['total'];


$query_recent = "SELECT b.*, c.nama_client, s.nama_stylist, 
    GROUP_CONCAT(l.nama_layanan SEPARATOR ', ') AS nama_layanan
    FROM booking b
    JOIN client c ON b.id_client = c.id_client
    JOIN stylist s ON b.id_stylist = s.id_stylist
    JOIN booking_layanan bl ON b.id_booking = bl.id_booking
    JOIN layanan l ON bl.id_layanan = l.id_layanan
    GROUP BY b.id_booking
    ORDER BY b.tanggal DESC, b.waktu DESC
    LIMIT 5
";

$result_recent = mysqli_query($conn, $query_recent);
$recent_bookings = mysqli_fetch_all($result_recent, MYSQLI_ASSOC);
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Admin | Royal Salon</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    :root {
      --primary-light: #EEE3CB;
      --primary-medium: #D7C0AE;
      --primary-dark: #967E76;
      --secondary-dark: #5F5B5B;
      --accent-cool: #B7C4CF;
    }
    body {
      background-color: var(--primary-light);
      transition: all 0.3s ease;
    }
    .card-hover {
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .card-hover:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    .smooth-border {
      border-color: rgba(150, 126, 118, 0.3);
    }
    .status-confirmed {
      background-color: #D1FAE5;
      color: #065F46;
    }
    .status-pending {
      background-color: #FEF3C7;
      color: #92400E;
    }
    .status-completed {
      background-color: #DBEAFE;
      color: #1E40AF;
    }
    .status-cancelled {
      background-color: #FEE2E2;
      color: #991B1B;
    }
  </style>
</head>
<body class="min-h-screen font-sans text-[var(--secondary-dark)]">

  <?php include 'include/sidebar.php'; ?>
  <?php include 'include/navbar.php'; ?>

  <main class="ml-64 p-6 pt-20 transition-all duration-300">
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <div class="bg-white rounded-xl shadow-sm smooth-border border-l-4 p-6 card-hover">
        <h3 class="text-xl font-semibold mb-2">Selamat datang Admin, <?= htmlspecialchars($_SESSION['username']) ?>!</h3>
        <p class="text-[var(--primary-dark)] mb-4">Kelola data stylist, layanan, dan booking secara mudah melalui menu di samping.</p>
        <a href="logout.php" class="inline-block px-4 py-2 bg-[var(--primary-dark)] text-white rounded-lg hover:bg-[var(--secondary-dark)] transition-colors duration-300">
          Logout
        </a>
      </div>

      <div class="bg-gradient-to-br from-[var(--primary-light)] to-white rounded-xl shadow-sm smooth-border border-l-4 p-6 card-hover">
        <h3 class="text-xl font-semibold mb-2">Statistik Hari Ini</h3>
        <div class="flex space-x-4 mt-4">
          <div class="bg-[var(--primary-medium)] bg-opacity-30 p-3 rounded-lg text-center flex-1">
            <p class="text-2xl font-bold text-[var(--secondary-dark)]"><?= $total_booking ?></p>
            <p class="text-sm text-[var(--primary-dark)]">Total Booking</p>
          </div>
          <div class="bg-[var(--accent-cool)] bg-opacity-30 p-3 rounded-lg text-center flex-1">
            <p class="text-2xl font-bold text-[var(--secondary-dark)]"><?= $new_booking ?></p>
            <p class="text-sm text-[var(--primary-dark)]">Baru Hari Ini</p>
          </div>
          <div class="bg-[var(--primary-medium)] bg-opacity-30 p-3 rounded-lg text-center flex-1">
            <p class="text-2xl font-bold text-[var(--secondary-dark)]"><?= $total_stylist ?></p>
            <p class="text-sm text-[var(--primary-dark)]">Stylist</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-xl shadow-sm p-6 col-span-1 md:col-span-2 card-hover">
        <h3 class="text-xl font-semibold mb-4 pb-2 border-b border-[var(--primary-medium)]">Booking Terbaru</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-[var(--primary-medium)] divide-opacity-30">
            <thead class="bg-[var(--primary-light)] bg-opacity-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Layanan</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Stylist</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Waktu</th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-[var(--primary-medium)] divide-opacity-20">
              <?php foreach ($recent_bookings as $booking): ?>
              <tr class="hover:bg-[var(--primary-light)] hover:bg-opacity-30 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($booking['nama_client']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($booking['nama_layanan']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($booking['nama_stylist']) ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?= date('d M Y', strtotime($booking['tanggal'])) ?></td>
                <td class="px-6 py-4 whitespace-nowrap"><?= date('H:i', strtotime($booking['waktu'])) ?></td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <?php 
                  $status_class = '';
                  switch(strtolower($booking['status'])) {
                    case 'terjadwal':
                    case 'confirmed':
                      $status_class = 'status-confirmed';
                      break;
                    case 'menunggu':
                    case 'pending':
                      $status_class = 'status-pending';
                      break;
                    case 'selesai':
                    case 'completed':
                      $status_class = 'status-completed';
                      break;
                    case 'batal':
                    case 'cancelled':
                      $status_class = 'status-cancelled';
                      break;
                    default:
                      $status_class = 'bg-gray-100 text-gray-800';
                  }
                  ?>
                  <span class="px-2 py-1 text-xs rounded-full <?= $status_class ?>">
                    <?= ucfirst(htmlspecialchars($booking['status'])) ?>
                  </span>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>

</body>
</html>