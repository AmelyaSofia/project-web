<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Client</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#f8f9fa] flex">
  

  <div class="flex-1 p-6">
    <!-- Header + Stats -->
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-3xl font-bold text-[#5F5B5B]">Client Management</h1>
      <div>
        <button class="bg-white p-2 rounded-full shadow-md hover:bg-gray-100 mr-2">
          <!-- Toggle layout icon -->
          📅
        </button>
        <button class="bg-white p-2 rounded-full shadow-md hover:bg-gray-100">🌐</button>
      </div>
    </div>
    <div class="grid grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-lg shadow p-4">
        <div class="font-semibold text-sm text-gray-500">Total Client</div>
        <div class="text-2xl font-bold text-[#5F5B5B]"><?php echo count($clients); ?></div>
      </div>
      <div class="bg-white rounded-lg shadow p-4">
        <div class="font-semibold text-sm text-gray-500">Active Clients</div>
        <div class="text-2xl font-bold text-[#5F5B5B]">
          <?php /* hitung status Active */ ?>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow p-4">
        <div class="font-semibold text-sm text-gray-500">Pending Approvals</div>
        <div class="text-2xl font-bold text-[#5F5B5B]">
          <?php /* hitung pending */ ?>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow p-4">
        <div class="font-semibold text-sm text-gray-500">Completed Clients</div>
        <div class="text-2xl font-bold text-[#5F5B5B]">
          <?php /* selesai */ ?>
        </div>
      </div>
    </div>

    <!-- Tabs filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
      <nav class="flex space-x-4">
        <button class="px-4 py-2 bg-[#967E76] text-white rounded-full">All</button>
        <button class="px-4 py-2">Pending</button>
        <button class="px-4 py-2">Active</button>
        <button class="px-4 py-2">Completed</button>
      </nav>
    </div>

    <!-- Tabel Bookings -->
    <div class="bg-white shadow rounded-lg overflow-x-auto mb-6">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-[#EEE3CB] to-[#D7C0AE]">
          <tr>
            <th class="px-6 py-4 text-xs font-medium uppercase text-gray-600 text-left">Client ID</th>
            <th class="px-6 py-4 text-xs font-medium uppercase text-gray-600 text-left">Nama</th>
            <th class="px-6 py-4 text-xs font-medium uppercase text-gray-600 text-left">Email</th>
            <th class="px-6 py-4 text-xs font-medium uppercase text-gray-600 text-left">No HP</th>
            <th class="px-6 py-4 text-xs font-medium uppercase text-gray-600 text-left">Username</th>
            <th class="px-6 py-4 text-xs font-medium uppercase text-gray-600 text-left">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          <?php foreach ($clients as $i => $c): ?>
          <tr class="hover:bg-[#EEE3CB]">
            <td class="px-6 py-4 text-sm text-[#5F5B5B]"><?php echo $i+1; ?></td>
            <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($c['nama_client']); ?></td>
            <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($c['email']); ?></td>
            <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($c['no_hp']); ?></td>
            <td class="px-6 py-4 text-sm"><?php echo htmlspecialchars($c['username']); ?></td>
            <td class="px-6 py-4 text-sm space-x-2">
              <a href="index.php?modul=client&fitur=update&id_client=<?php echo $c['id_client']; ?>" class="text-[#967E76] hover:underline">Edit</a>
              <a href="index.php?modul=client&fitur=hapus&id_client=<?php echo $c['id_client']; ?>" class="text-red-500 hover:underline" onclick="return confirm('Yakin?')">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <!-- Pagination -->
      <div class="flex justify-end p-4">
        <button class="px-3 py-1 rounded-l bg-gray-200">1</button><button class="px-3 py-1 bg-gray-100">2</button><span class="px-3 py-1">…</span><button class="px-3 py-1 bg-gray-100">15</button><button class="px-3 py-1 rounded-r bg-gray-200">›</button>
      </div>
    </div>

    <!-- Slide-in Form Panel -->
    <div id="panel" class="fixed inset-y-0 right-0 w-96 bg-white shadow-lg transform translate-x-full transition-transform">
      <div class="p-6 flex justify-between items-center border-b">
        <h2 class="text-xl font-semibold text-[#5F5B5B]"><?php echo (isset($client)?'Update':'Tambah'); ?> Client</h2>
        <button onclick="togglePanel()">✕</button>
      </div>
      <div class="p-6 overflow-auto h-full">
        <form method="POST" action="index.php?modul=client&fitur=<?php echo isset($client)?'update&id_client='.$client['id_client']:'tambah'; ?>" class="space-y-4">
          <label>Nama</label><input type="text" name="nama_client" required class="border p-2 rounded w-full" value="<?php echo $client['nama_client']??'';?>">
          <label>Email</label><input type="email" name="email" required class="border p-2 rounded w-full" value="<?php echo $client['email']??'';?>">
          <label>No HP</label><input type="text" name="no_hp" required class="border p-2 rounded w-full" value="<?php echo $client['no_hp']??'';?>">
          <label>Username</label><input type="text" name="username" required class="border p-2 rounded w-full" value="<?php echo $client['username']??'';?>">
          <label>Alamat</label><textarea name="alamat" required class="border p-2 rounded w-full"><?php echo $client['alamat']??'';?></textarea>
          <label>Password</label><input type="text" name="password" required class="border p-2 rounded w-full" value="<?php echo $client['password']??'';?>">
          <button type="submit" class="mt-4 w-full bg-[#967E76] text-white py-2 rounded">Simpan</button>
        </form>
      </div>
    </div>
  </div>

  <script>
    const panel = document.getElementById('panel');
    function togglePanel() {
      panel.classList.toggle('translate-x-full');
    }
    // auto-open panel saat update
    <?php if (isset($_GET['fitur']) && $_GET['fitur']=='update'): ?>
      panel.classList.remove('translate-x-full');
    <?php endif; ?>
  </script>
</body>
</html>
