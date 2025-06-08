<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
echo "Selamat datang Admin, " . $_SESSION['username'];
?>
<a href="logout.php">Logout</a>
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
    body {
      background-color: #fdf7f0; /* cream soft */
    }
  </style>
</head>
<body class="min-h-screen text-[#4e342e] font-sans">

  <?php include 'include/sidebar.php'; ?>
  <?php include 'include/navbar.php'; ?>

  <main class="ml-64 p-6 pt-20">
    <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Welcome Card -->
      <div class="bg-white rounded-xl shadow-md border-l-4 border-[#a1887f] p-6 transition-transform hover:-translate-y-1">
        <h3 class="text-xl font-semibold text-[#4e342e] mb-2">Selamat datang Admin, <?= htmlspecialchars($_SESSION['username']) ?>!</h3>
        <p class="text-[#6d4c41] mb-4">Kelola data stylist, layanan, dan booking secara mudah melalui menu di samping.</p>
        <a href="logout.php" class="inline-block px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">Logout</a>
      </div>

      <!-- Statistik -->
      <div class="bg-white rounded-xl shadow-md border-l-4 border-[#a1887f] p-6 transition-transform hover:-translate-y-1">
        <h3 class="text-xl font-semibold text-[#4e342e] mb-2">Statistik Hari Ini</h3>
        <p class="text-[#6d4c41]">3 Booking Terjadwal | 1 Booking Baru | 5 Stylist Aktif</p>
      </div>
    </section>
  </main>

</body>
</html>