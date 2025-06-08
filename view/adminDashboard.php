<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
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
  </style>
</head>
<body class="min-h-screen font-sans text-[var(--secondary-dark)]">

  <?php include 'include/sidebar.php'; ?>
  <?php include 'include/navbar.php'; ?>

  <main class="ml-64 p-6 pt-20 transition-all duration-300">
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Welcome Card -->
      <div class="bg-white rounded-xl shadow-sm smooth-border border-l-4 p-6 card-hover">
        <h3 class="text-xl font-semibold mb-2">Selamat datang Admin, <?= htmlspecialchars($_SESSION['username']) ?>!</h3>
        <p class="text-[var(--primary-dark)] mb-4">Kelola data stylist, layanan, dan booking secara mudah melalui menu di samping.</p>
        <a href="logout.php" class="inline-block px-4 py-2 bg-[var(--primary-dark)] text-white rounded-lg hover:bg-[var(--secondary-dark)] transition-colors duration-300">
          Logout
        </a>
      </div>

      <!-- Statistik -->
      <div class="bg-gradient-to-br from-[var(--primary-light)] to-white rounded-xl shadow-sm smooth-border border-l-4 p-6 card-hover">
        <h3 class="text-xl font-semibold mb-2">Statistik Hari Ini</h3>
        <div class="flex space-x-4 mt-4">
          <div class="bg-[var(--primary-medium)] bg-opacity-30 p-3 rounded-lg text-center flex-1">
            <p class="text-2xl font-bold text-[var(--secondary-dark)]">3</p>
            <p class="text-sm text-[var(--primary-dark)]">Booking</p>
          </div>
          <div class="bg-[var(--accent-cool)] bg-opacity-30 p-3 rounded-lg text-center flex-1">
            <p class="text-2xl font-bold text-[var(--secondary-dark)]">1</p>
            <p class="text-sm text-[var(--primary-dark)]">Baru</p>
          </div>
          <div class="bg-[var(--primary-medium)] bg-opacity-30 p-3 rounded-lg text-center flex-1">
            <p class="text-2xl font-bold text-[var(--secondary-dark)]">5</p>
            <p class="text-sm text-[var(--primary-dark)]">Stylist</p>
          </div>
        </div>
      </div>

      <!-- Recent Bookings -->
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
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-[var(--primary-medium)] divide-opacity-20">
              <tr class="hover:bg-[var(--primary-light)] hover:bg-opacity-30 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap">Andi Wijaya</td>
                <td class="px-6 py-4 whitespace-nowrap">Potong Rambut</td>
                <td class="px-6 py-4 whitespace-nowrap">Rina</td>
                <td class="px-6 py-4 whitespace-nowrap">12 Juni 2023</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Confirmed</span>
                </td>
              </tr>
              <tr class="hover:bg-[var(--primary-light)] hover:bg-opacity-30 transition-colors duration-200">
                <td class="px-6 py-4 whitespace-nowrap">Budi Santoso</td>
                <td class="px-6 py-4 whitespace-nowrap">Coloring</td>
                <td class="px-6 py-4 whitespace-nowrap">Dewi</td>
                <td class="px-6 py-4 whitespace-nowrap">11 Juni 2023</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>

</body>
</html>