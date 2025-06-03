<?php
$message = $_GET['message'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Karyawan - Royal Beauty</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>

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
  <?php include './view/include/sidebar.php'; ?>
  <?php include './view/include/navbar.php'; ?>

  <main class="flex-1 p-8">
    <?php if ($message): ?>
      <div class="mb-6 p-4 rounded shadow bg-green-500 text-white font-semibold max-w-xl mx-auto">
        <?= htmlspecialchars($message) ?>
      </div>
    <?php endif; ?>

    <section class="bg-white rounded shadow p-6 max-w-6xl mx-auto">
      <h2 class="text-2xl font-semibold mb-6 flex items-center gap-3 text-primary">
        <i class="fas fa-users"></i> Daftar Karyawan
      </h2>

      <div class="flex justify-between items-center mb-6">
        <button onclick="openModal()" class="bg-primary hover:bg-[#7a442b] text-white px-5 py-2 rounded flex items-center gap-2 transition">
          <i class="fas fa-plus"></i> Tambah Karyawan
        </button>
      </div>

      <div class="overflow-x-auto">
        <table class="min-w-full table-auto border-collapse border border-gray-300">
          <thead class="bg-primary text-white">
            <tr>
              <th class="border px-4 py-2 text-left">Nama</th>
              <th class="border px-4 py-2 text-left">Peran</th>
              <th class="border px-4 py-2 text-left">Foto</th>
              <th class="border px-4 py-2 text-left">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($karyawans)): ?>
              <tr>
                <td colspan="4" class="px-4 py-6 text-center text-gray-500">Tidak ada data karyawan.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($karyawans as $k): ?>
              <tr class="hover:bg-gray-100 transition">
                <td class="px-4 py-2"><?= htmlspecialchars($k['nama_karyawan']) ?></td>
                <td class="px-4 py-2"><?= htmlspecialchars($k['peran_karyawan']) ?></td>
                <td class="px-4 py-2">
                  <?php if (!empty($k['foto_karyawan'])): ?>
                    <img src="<?= htmlspecialchars($k['foto_karyawan']) ?>" alt="Foto" class="w-12 h-12 object-cover rounded-full border" />
                  <?php else: ?>
                    <span class="text-gray-400 italic">Tidak ada</span>
                  <?php endif; ?>
                </td>
                <td class="px-4 py-2 flex gap-2">
                  <button onclick="openEditModal(this, '<?= $k['id_karyawan'] ?>')" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm">
                    <i class="fas fa-pen mr-1"></i>Edit
                  </button>
                  <a href="index.php?fitur=hapus&id_karyawan=<?= $k['id_karyawan'] ?>" 
                     onclick="return confirm('Hapus karyawan <?= htmlspecialchars($k['nama_karyawan']) ?>?')"
                     class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
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

<!-- Modal Form Tambah/Edit Karyawan -->
<div id="modal" class="fixed inset-0 hidden z-50 bg-black bg-opacity-50 flex items-center justify-center">
  <div class="bg-secondary rounded-2xl shadow-xl w-full max-w-md p-6 relative border border-primary">
    <button onclick="closeModal()" class="absolute top-3 right-3 text-darktext hover:text-primary text-xl">&times;</button>
    <h3 id="modal-title" class="text-xl font-semibold mb-4 text-primary">Tambah Karyawan</h3>
    <form id="karyawan-form" action="index.php?fitur=tambah" method="POST">
      <input type="hidden" id="edit-id" name="id_karyawan" />

      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
        <input type="text" id="nama" name="nama_karyawan" required
               class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">Peran</label>
        <input type="text" id="peran" name="peran_karyawan" required
               class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext" />
      </div>

      <div class="mb-4">
        <label class="block text-sm font-medium mb-2">URL Foto</label>
        <input type="text" id="foto" name="foto_karyawan"
               class="w-full border border-primary bg-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary text-darktext" />
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
    document.getElementById("karyawan-form").action = "index.php?fitur=tambah";
    document.getElementById("modal-title").innerText = "Tambah Karyawan";
    document.getElementById("edit-id").value = "";
    document.getElementById("nama").value = "";
    document.getElementById("peran").value = "";
    document.getElementById("foto").value = "";
    document.getElementById("modal").classList.remove("hidden");
  }

  function openEditModal(button, id) {
    const row = button.closest("tr");
    const cells = row.querySelectorAll("td");

    document.getElementById("karyawan-form").action = "index.php?fitur=update&id_karyawan=" + id;
    document.getElementById("modal-title").innerText = "Edit Karyawan";
    document.getElementById("edit-id").value = id;
    document.getElementById("nama").value = cells[0].textContent.trim();
    document.getElementById("peran").value = cells[1].textContent.trim();
    document.getElementById("foto").value = ""; // Kosongin input URL Foto
    document.getElementById("modal").classList.remove("hidden");
  }

  function closeModal() {
    document.getElementById("modal").classList.add("hidden");
  }
</script>


</body>
</html>
