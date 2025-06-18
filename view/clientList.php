<?php if (!isset($clients)) $clients = []; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Client Management</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8f9fa] flex">

 <?php include 'include/sidebar.php'; ?>

  <main class="ml-64 p-6 w-full bg-white min-h-screen">
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-bold text-[#5F5B5B]">Client Management</h1>
      <button onclick="toggleModal()" class="bg-[#967E76] text-white px-4 py-2 rounded shadow hover:bg-[#7e665f]">
        + Tambah Client
      </button>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-lg shadow p-4">
        <div class="font-semibold text-sm text-gray-500">Total Client</div>
        <div class="text-2xl font-bold text-[#5F5B5B]"><?php echo count($clients); ?></div>
      </div>
    </div>

    <div class="bg-white shadow rounded-lg overflow-x-auto mb-6">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-[#EEE3CB] to-[#D7C0AE]">
          <tr>
            <th class="px-6 py-4 text-xs font-medium text-gray-600 text-left">#</th>
            <th class="px-6 py-4 text-xs font-medium text-gray-600 text-left">Nama</th>
            <th class="px-6 py-4 text-xs font-medium text-gray-600 text-left">Email</th>
            <th class="px-6 py-4 text-xs font-medium text-gray-600 text-left">No HP</th>
            <th class="px-6 py-4 text-xs font-medium text-gray-600 text-left">Username</th>
            <th class="px-6 py-4 text-xs font-medium text-gray-600 text-left">Alamat</th>
            <th class="px-6 py-4 text-xs font-medium text-gray-600 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white text-sm">
          <?php if (!empty($clients)): ?>
            <?php foreach ($clients as $i => $c): ?>
              <tr class="hover:bg-[#EEE3CB]">
                <td class="px-6 py-4"><?php echo $i + 1; ?></td>
                <td class="px-6 py-4"><?php echo htmlspecialchars($c['nama_client']); ?></td>
                <td class="px-6 py-4"><?php echo htmlspecialchars($c['email']); ?></td>
                <td class="px-6 py-4"><?php echo htmlspecialchars($c['no_hp']); ?></td>
                <td class="px-6 py-4"><?php echo htmlspecialchars($c['username']); ?></td>
                <td class="px-6 py-4"><?php echo htmlspecialchars($c['alamat']); ?></td>
                <td class="px-6 py-4 space-x-2">
                  <a href="index.php?modul=client&fitur=update&id_client=<?php echo $c['id_client']; ?>" class="text-[#967E76] hover:underline">Edit</a>
                  <a href="index.php?modul=client&fitur=hapus&id_client=<?php echo $c['id_client']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td colspan="7" class="text-center text-gray-500 py-4">Belum ada data client.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <div id="clientModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-xl p-6 relative">
      <button onclick="toggleModal()" class="absolute top-2 right-2 text-gray-500 text-xl">&times;</button>
      <h2 class="text-xl font-semibold mb-4 text-[#5F5B5B]"><?php echo (isset($client) ? 'Update' : 'Tambah'); ?> Client</h2>
      <form method="POST" action="index.php?modul=client&fitur=<?php echo isset($client) ? 'update&id_client=' . $client['id_client'] : 'tambah'; ?>" class="space-y-4">
        <div>
          <label>Nama</label>
          <input type="text" name="nama_client" required class="border p-2 rounded w-full" value="<?php echo $client['nama_client'] ?? ''; ?>">
        </div>
        <div>
          <label>Email</label>
          <input type="email" name="email" required class="border p-2 rounded w-full" value="<?php echo $client['email'] ?? ''; ?>">
        </div>
        <div>
          <label>No HP</label>
          <input type="text" name="no_hp" required class="border p-2 rounded w-full" value="<?php echo $client['no_hp'] ?? ''; ?>">
        </div>
        <div>
          <label>Username</label>
          <input type="text" name="username" required class="border p-2 rounded w-full" value="<?php echo $client['username'] ?? ''; ?>">
        </div>
        <div>
          <label>Alamat</label>
          <textarea name="alamat" required class="border p-2 rounded w-full"><?php echo $client['alamat'] ?? ''; ?></textarea>
        </div>
        <div>
          <label>Password</label>
          <input type="text" name="password" required class="border p-2 rounded w-full" value="<?php echo $client['password'] ?? ''; ?>">
        </div>
        <button type="submit" class="w-full bg-[#967E76] text-white py-2 rounded">Simpan</button>
      </form>
    </div>
  </div>

  <script>
    const modal = document.getElementById('clientModal');
    function toggleModal() {
      modal.classList.toggle('hidden');
    }

    <?php if (isset($_GET['fitur']) && $_GET['fitur'] == 'update'): ?>
      window.addEventListener('DOMContentLoaded', () => {
        toggleModal();
      });
    <?php endif; ?>
  </script>

</body>
</html>
