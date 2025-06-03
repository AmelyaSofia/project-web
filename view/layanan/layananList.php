<?php
// Pastikan $layanans sudah diisi oleh controller
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Layanan Salon - BeautyCare</title>
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
  <?php include './view/include/sidebar.php'; ?>

  <!-- Main Content -->
  <div class="flex-1">
    <?php include './view/include/navbar.php'; ?>

    <main class="p-10">
      <!-- Alert Message -->
      <?php if ($message): ?>
        <div class="mb-6 p-4 rounded shadow bg-green-500 text-white font-semibold max-w-xl mx-auto">
          <?= htmlspecialchars($message) ?>
        </div>
      <?php endif; ?>

      <!-- Daftar Layanan -->
      <section class="bg-white rounded shadow p-6 max-w-6xl mx-auto">
        <h2 class="text-2xl font-semibold mb-6 flex items-center gap-3 text-primary">
          <i class="fas fa-cut"></i> Daftar Layanan
        </h2>

        <div class="flex justify-between items-center mb-6">
          <!-- Tombol Tambah Layanan -->
          <button onclick="openModal()" class="bg-primary hover:bg-[#7a442b] text-white px-5 py-2 rounded flex items-center gap-2 transition">
            <i class="fas fa-plus"></i> Tambah Layanan
          </button>

          <!-- Pencarian -->
          <form action="index.php" method="GET" class="flex items-center gap-3">
            <input type="hidden" name="fitur" value="layanan_list" />
            <input type="search" name="search"
                   placeholder="Cari layanan..."
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>"
                   class="border border-primary rounded px-4 py-2 w-72 focus:outline-none focus:ring-2 focus:ring-primary" />
            <button type="submit" class="bg-primary hover:bg-[#7a442b] text-white px-4 py-2 rounded shadow transition">
              Cari
            </button>
          </form>
        </div>

        <!-- Tabel Layanan -->
        <div class="overflow-x-auto">
          <table class="min-w-full table-auto border-collapse border border-gray-300">
            <thead class="bg-primary text-white">
              <tr>
                <th class="border border-gray-300 px-4 py-2 text-left">Nama Layanan</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Deskripsi</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Harga</th>
                <th class="border border-gray-300 px-4 py-2 text-left">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($layanans)): ?>
                <tr>
                  <td colspan="4" class="border border-gray-300 px-4 py-6 text-center text-gray-500">
                    Data layanan tidak ditemukan.
                  </td>
                </tr>
              <?php else: ?>
                <?php foreach ($layanans as $layanan): ?>
                  <tr class="hover:bg-gray-100 cursor-pointer transition">
                    <td class="px-4 py-2"><?= htmlspecialchars($layanan['nama_layanan']) ?></td>
                    <td class="px-4 py-2"><?= htmlspecialchars($layanan['deskripsi_layanan']) ?></td>
                    <td class="px-4 py-2">Rp<?= number_format($layanan['harga_layanan'], 0, ',', '.') ?></td>
                    <td class="px-4 py-2 flex gap-2">
                      <button onclick="openEditModal(this, '<?= $layanan['id_layanan'] ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded flex items-center text-sm">
                        <i class="fas fa-pen mr-1"></i>Edit
                      </button>
                      <a href="index.php?fitur=hapus&id_layanan=<?= $layanan['id_layanan'] ?>" 
                         onclick="return confirm('Yakin ingin menghapus layanan <?= htmlspecialchars($layanan['nama_layanan']) ?>?')"
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
</div>

<!-- Modal Tambah/Edit Layanan -->
<div id="modal" class="fixed inset-0 hidden z-50 bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-secondary rounded-2xl shadow-xl w-full max-w-md p-6 relative border border-primary">
    <button onclick="closeModal()" class="absolute top-3 right-3 text-darktext hover:text-primary text-xl">&times;</button>
    <h3 id="modal-title" class="text-xl font-semibold mb-4 text-primary">Tambah Layanan Baru</h3>
    <form id="layanan-form" action="index.php?fitur=tambah" method="POST">
      <input type="hidden" id="edit-id" name="id_layanan" />

      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Nama Layanan</label>
        <input type="text" id="nama" name="nama_layanan" required
               class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Deskripsi</label>
        <textarea id="deskripsi" name="deskripsi_layanan" rows="3" required
                  class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext"></textarea>
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Harga</label>
        <input type="number" id="harga" name="harga_layanan" required
               class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext" />
      </div>

      <div class="flex justify-end gap-3 mt-6">
        <button type="button" onclick="closeModal()"
                class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
        <button type="submit"
                class="bg-primary hover:bg-[#7a442b] text-white px-4 py-2 rounded shadow">Simpan</button>
      </div>
    </form>
  </div>
</div>

<script>
  function openModal() {
    document.getElementById("layanan-form").action = "index.php?fitur=tambah";
    document.getElementById("modal-title").innerText = "Tambah Layanan Baru";
    document.getElementById("edit-id").value = "";
    document.getElementById("nama").value = "";
    document.getElementById("deskripsi").value = "";
    document.getElementById("harga").value = "";
    document.getElementById("modal").classList.remove("hidden");
  }

  function openEditModal(btn, id) {
    const row = btn.closest("tr");
    const cells = row.querySelectorAll("td");

    document.getElementById("layanan-form").action = "index.php?fitur=update&id_layanan=" + id;
    document.getElementById("modal-title").innerText = "Edit Layanan";
    document.getElementById("edit-id").value = id;

    document.getElementById("nama").value = cells[0].textContent.trim();
    document.getElementById("deskripsi").value = cells[1].textContent.trim();
    document.getElementById("harga").value = cells[2].textContent.replace(/[^\d]/g, '');
    document.getElementById("modal").classList.remove("hidden");
  }

  function closeModal() {
    document.getElementById("modal").classList.add("hidden");
  }
</script>

</body>
</html>
