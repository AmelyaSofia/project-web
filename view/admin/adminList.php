<?php
// Pastikan $admins sudah diisi oleh controller
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Admin Salon - BeautyCare</title>
  <!-- Tailwind CDN -->
  <script src="https://cdn.tailwindcss.com"></script> 
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/> 

  <!-- Custom Theme Colors -->
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#93552f',
            secondary: '#fce4c2',
            darktext: '#3a3a3a',
            lightbg: '#f8f8f8'
          }
        }
      }
    }
  </script>
</head>
<body class="bg-lightbg text-darktext font-sans">

<div class="flex min-h-screen">
  <!-- Sidebar -->
  <?php
    include './view/include/sidebar.php'; 
  ?>

  <!-- Main Content -->
  <?php
    include './view/include/navbar.php'; 
  ?>

    <!-- Alert Message -->
    <?php if ($message): ?>
      <div class="mb-6 p-4 rounded shadow bg-green-500 text-white font-semibold max-w-xl mx-auto">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <!-- Admin List Table -->
    <section class="bg-white rounded shadow p-6 max-w-6xl mx-auto">
      <h2 class="text-2xl font-semibold mb-6 flex items-center gap-3 text-primary">
        <i class="fas fa-user-shield"></i> Daftar Admin
      </h2>

      <div class="flex justify-between items-center mb-6">
        <!-- Tombol Tambah Admin -->
        <button onclick="openModal()" class="bg-primary hover:bg-[#7a442b] text-white px-5 py-2 rounded flex items-center gap-2 transition">
          <i class="fas fa-plus"></i> Tambah Admin
        </button>

        <!-- Pencarian -->
        <form action="index.php" method="GET" class="flex items-center gap-3">
          <input type="hidden" name="fitur" value="list" />
          <input type="search" name="search"
                 placeholder="Cari admin..."
                 value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                 class="border border-primary rounded px-4 py-2 w-72 focus:outline-none focus:ring-2 focus:ring-primary" />
          <button type="submit" class="bg-primary hover:bg-[#7a442b] text-white px-4 py-2 rounded shadow transition">
            Cari
          </button>
        </form>
      </div>

      <!-- Tabel Admin -->
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse border border-gray-300">
          <thead class="bg-primary text-white">
            <tr>
              <th class="border border-gray-300 px-4 py-2 text-left">Nama</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Email</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($admins)): ?>
              <tr>
                <td colspan="5" class="border border-gray-300 px-4 py-6 text-center text-gray-500">
                  Data admin tidak ditemukan.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($admins as $admin): ?>
                <tr class="hover:bg-gray-100 cursor-pointer transition">
                  <td class="px-4 py-2"><?= htmlspecialchars($admin['nama_admin']) ?></td>
                  <td class="px-4 py-2"><?= htmlspecialchars($admin['email_admin']) ?></td>
                  <td class="px-4 py-2">
                    <?php if (($admin['status_admin'] ?? 'aktif') === 'aktif'): ?>
                      <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-[#fdecea] text-primary">
                        <i class="fas fa-check-circle mr-1"></i>Aktif
                      </span>
                    <?php else: ?>
                      <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-600">
                        Nonaktif
                      </span>
                    <?php endif; ?>
                  </td>
                  <td class="px-4 py-2 flex gap-2">
                    <button onclick="openEditModal(this, '<?= $admin['id_admin'] ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded flex items-center text-sm">
                      <i class="fas fa-pen mr-1"></i>Edit
                    </button>
                    <a href="index.php?fitur=hapus&id_admin=<?= $admin['id_admin'] ?>" 
                       onclick="return confirm('Yakin ingin menghapus admin <?= htmlspecialchars($admin['nama_admin']) ?>?')"
                       class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded flex items-center text-sm">
                      <i class="fas fa-trash mr-1"></i>Hapus
                    </a>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>
  </main>
</div>

<!-- Modal Form Tambah/Edit Admin -->
<div id="modal" class="fixed inset-0 hidden z-50 bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-secondary rounded-2xl shadow-xl w-full max-w-md p-6 relative border border-primary">
    <button onclick="closeModal()" class="absolute top-3 right-3 text-darktext hover:text-primary text-xl">&times;</button>
    <h3 id="modal-title" class="text-xl font-semibold mb-4 text-primary">Tambah Admin Baru</h3>
    <form id="admin-form" action="index.php?fitur=tambah" method="POST">
      <input type="hidden" id="edit-id" name="id_admin" />
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
        <input type="text" id="nama" name="nama_admin" required class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext" />
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Email</label>
        <input type="email" id="email" name="email_admin" required class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext" />
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Password</label>
        <input type="password" id="password" name="password_admin" class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext" />
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Foto URL</label>
        <input type="text" id="foto" name="foto_admin" class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext" />
      </div>
      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Status</label>
        <select id="status" name="status_admin" class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext">
          <option value="aktif">Aktif</option>
          <option value="nonaktif">Nonaktif</option>
        </select>
      </div>
      <div class="flex justify-end gap-3 mt-6">
        <button type="button" onclick="closeModal()" class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
        <button type="submit" class="bg-primary hover:bg-[#7a442b] text-white px-4 py-2 rounded shadow">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModal() {
    document.getElementById("admin-form").action = "index.php?fitur=tambah";
    document.getElementById("modal-title").innerText = "Tambah Admin Baru";
    document.getElementById("edit-id").value = "";
    document.getElementById("nama").value = "";
    document.getElementById("email").value = "";
    document.getElementById("password").value = "";
    document.getElementById("foto").value = "";
    document.getElementById("status").value = "aktif";
    document.getElementById("modal").classList.remove("hidden");
  }

  function openEditModal(btn, id) {
    const row = btn.closest("tr");
    const cells = row.querySelectorAll("td");

    document.getElementById("admin-form").action = "index.php?fitur=update&id_admin=" + id;
    document.getElementById("modal-title").innerText = "Edit Admin";
    document.getElementById("edit-id").value = id;

    document.getElementById("nama").value = cells[0].textContent;
    document.getElementById("email").value = cells[1].textContent;
    document.getElementById("password").value = "******"; 
    document.getElementById("foto").value = ""; 
    document.getElementById("status").value = cells[2].textContent.includes("Aktif") ? "aktif" : "nonaktif";

    document.getElementById("modal").classList.remove("hidden");
  }

  function closeModal() {
    document.getElementById("modal").classList.add("hidden");
  }
</script>

</body>
</html>