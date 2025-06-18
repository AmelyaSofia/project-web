<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Stylist - Royal Beauty</title>
    <script src="https://cdn.tailwindcss.com"></script> 
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600&family=Inter&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#967E76', 
                        'secondary': '#D7C0AE',
                        'accent': '#EEE3CB'
                    },
                    fontFamily: {
                        'playfair': ['Playfair Display', 'serif'],
                        'inter': ['Inter', 'sans-serif']
                    }
                }
            }
        }
    </script>

    <style>
        .thumbnail {
            width: 80px;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen font-inter">

<div class="flex min-h-screen">

    <?php include 'include/sidebar.php'; ?>

    <main class="ml-64 p-6 w-full bg-white min-h-screen">
        <h1 class="text-3xl font-bold mb-4 text-gray-800">Data Stylist</h1>

        <?php if (isset($_GET['message'])): ?>
            <div class="p-3 mb-4 bg-green-100 text-green-700 rounded"><?php echo htmlspecialchars($_GET['message']); ?></div>
        <?php endif; ?>

        <div class="mb-6">
            <form method="GET" action="index.php" class="flex gap-2">
                <input type="hidden" name="modul" value="stylist">
                <input type="hidden" name="fitur" value="list">
                <input type="text" name="search" placeholder="Cari stylist..." 
                       value="<?php echo $_GET['search'] ?? ''; ?>" 
                       class="px-4 py-2 border rounded flex-1">
                <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90">Cari</button>
            </form>
        </div>

        <div class="mb-8 bg-gray-50 p-6 rounded-lg shadow-sm">
            <?php if (isset($_GET['fitur']) && $_GET['fitur'] == 'update' && isset($stylist)): ?>
                <h2 class="text-xl font-semibold mb-4">Update Stylist</h2>
                <form method="POST" action="index.php?modul=stylist&fitur=update&id_stylist=<?php echo $stylist['id_stylist']; ?>">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Nama Stylist:</label>
                        <input type="text" name="nama_stylist" value="<?= htmlspecialchars($stylist['nama_stylist']) ?>" required class="w-full px-4 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Keahlian:</label>
                        <input type="text" name="keahlian" value="<?= htmlspecialchars($stylist['keahlian']) ?>" required class="w-full px-4 py-2 border rounded-md">
                    </div>
                    <div class="flex justify-between mt-4">
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90">Update</button>
                        <a href="index.php?modul=stylist&fitur=list" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</a>
                    </div>
                </form>
            <?php else: ?>
                <h2 class="text-xl font-semibold mb-4">Tambah Stylist Baru</h2>
                <form method="POST" action="index.php?modul=stylist&fitur=tambah">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Nama Stylist:</label>
                        <input type="text" name="nama_stylist" required class="w-full px-4 py-2 border rounded-md">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium mb-2">Keahlian:</label>
                        <input type="text" name="keahlian" required class="w-full px-4 py-2 border rounded-md">
                    </div>
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-opacity-90">Tambah</button>
                </form>
            <?php endif; ?>
        </div>

        <h2 class="text-xl font-semibold mb-4">Daftar Stylist</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border-b px-4 py-2 text-left">ID</th>
                        <th class="border-b px-4 py-2 text-left">Nama Stylist</th>
                        <th class="border-b px-4 py-2 text-left">Keahlian</th>
                        <th class="border-b px-4 py-2 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($stylists)): ?>
                        <?php foreach ($stylists as $s): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="border-b px-4 py-2"><?= $s['id_stylist'] ?></td>
                                <td class="border-b px-4 py-2"><?= htmlspecialchars($s['nama_stylist']) ?></td>
                                <td class="border-b px-4 py-2"><?= htmlspecialchars($s['keahlian']) ?></td>
                                <td class="border-b px-4 py-2">
                                    <a href="index.php?modul=stylist&fitur=update&id_stylist=<?= $s['id_stylist'] ?>" class="text-blue-600 hover:underline">Edit</a> |
                                    <a href="index.php?modul=stylist&fitur=hapus&id_stylist=<?= $s['id_stylist'] ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus stylist ini?')" class="text-red-600 hover:underline">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-4 text-gray-500">Tidak ada data stylist.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</div>

</body>
</html>