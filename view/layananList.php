<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Layanan - Royal Beauty</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script> 
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f5f5f5;
        }
        .brand-font {
            font-family: 'Playfair Display', serif;
        }
        .content {
            padding: 2rem;
            margin-left: 16rem; /* Lebar sidebar */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1.5rem;
        }
        th, td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #334155;
        }
        tr:hover {
            background-color: #f8fafc;
        }
        .btn-primary {
            background-color: #967E76;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #7a6861;
        }
        .btn-secondary {
            background-color: #e2e8f0;
            color: #334155;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s;
        }
        .btn-secondary:hover {
            background-color: #cbd5e1;
        }
        .form-input {
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            width: 100%;
            margin-bottom: 1rem;
        }
        .form-input:focus {
            outline: none;
            border-color: #967E76;
            box-shadow: 0 0 0 3px rgba(150, 126, 118, 0.2);
        }
        .thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 0.5rem;
        }
        .action-link {
            color: #3b82f6;
            margin-right: 0.5rem;
        }
        .action-link:hover {
            text-decoration: underline;
        }
        .action-link.delete {
            color: #ef4444;
        }

        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

<!-- Include Sidebar -->
<?php include 'include/sidebar.php'; ?>

<!-- Main Content -->
<div class="content">
    <h1 class="text-3xl font-bold text-[#967E76] mb-6 brand-font">Data Layanan</h1>

    <?php if (isset($_GET['message'])): ?>
        <div class="p-4 mb-6 bg-green-100 text-green-700 rounded-lg">
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Search Form -->
    <div class="bg-white p-4 rounded-lg shadow mb-6">
        <form method="GET" action="index.php" class="flex items-center gap-4">
            <input type="hidden" name="fitur" value="layanan">
            <input type="text" name="search" placeholder="Cari layanan..." 
                   value="<?php echo $_GET['search'] ?? ''; ?>"
                   class="form-input flex-grow">
            <button type="submit" class="btn-primary">Cari</button>
        </form>
    </div>

    <!-- Form Tambah / Update -->
    <div class="bg-white p-6 rounded-lg shadow mb-8">
        <?php if (isset($_GET['fitur']) && $_GET['fitur'] === 'update' && isset($layanan)): ?>
            <h2 class="text-2xl font-semibold text-[#967E76] mb-4">Update Layanan</h2>
            <form method="POST" action="index.php?modul=layanan&fitur=update&id_layanan=<?php echo $layanan['id_layanan']; ?>" enctype="multipart/form-data">
                <label class="block mb-2">Nama Layanan:</label>
                <input type="text" class="form-input" name="nama_layanan" value="<?php echo htmlspecialchars($layanan['nama_layanan']); ?>" required>
                <label class="block mb-2">Deskripsi:</label>
                <textarea class="form-input" name="deskripsi" required><?php echo htmlspecialchars($layanan['deskripsi']); ?></textarea>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2">Harga (Rp):</label>
                        <input type="number" class="form-input" name="harga" value="<?php echo $layanan['harga']; ?>" required>
                    </div>
                    <div>
                        <label class="block mb-2">Durasi (menit):</label>
                        <input type="number" class="form-input" name="durasi" value="<?php echo $layanan['durasi']; ?>" required>
                    </div>
                </div>
                <label class="block mt-4 mb-2">Gambar:</label>
                <input type="file" class="form-input" name="gambar_layanan" accept="image/*">
                <?php if (!empty($layanan['gambar_layanan'])): ?>
                    <img src="./image/layanan/<?php echo htmlspecialchars($layanan['gambar_layanan']); ?>" class="thumbnail mt-2 mb-2">
                    <div>
                        <label><input type="checkbox" name="hapus_gambar" class="mr-2">Hapus gambar saat update</label>
                    </div>
                <?php endif; ?>
                <div class="flex justify-between mt-6">
                    <button type="submit" class="btn-primary">Update</button>
                    <a href="index.php?fitur=layanan" class="btn-secondary">Batal</a>
                </div>
            </form>
        <?php else: ?>
            <h2 class="text-2xl font-semibold text-[#967E76] mb-4">Tambah Layanan</h2>
            <form method="POST" action="index.php?modul=layanan&fitur=tambah" enctype="multipart/form-data">
                <label class="block mb-2">Nama Layanan:</label>
                <input type="text" class="form-input" name="nama_layanan" required>
                <label class="block mb-2">Deskripsi:</label>
                <textarea class="form-input" name="deskripsi" required></textarea>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block mb-2">Harga (Rp):</label>
                        <input type="number" class="form-input" name="harga" required>
                    </div>
                    <div>
                        <label class="block mb-2">Durasi (menit):</label>
                        <input type="number" class="form-input" name="durasi" required>
                    </div>
                </div>
                <label class="block mt-4 mb-2">Gambar:</label>
                <input type="file" class="form-input" name="gambar_layanan" accept="image/*" required>
                <button type="submit" class="btn-primary mt-4">Tambah</button>
            </form>
        <?php endif; ?>
    </div>

    <!-- Table of Services -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h2 class="text-2xl font-semibold text-[#967E76] mb-4">Daftar Layanan</h2>
        <div class="overflow-x-auto">
            <table class="w-full table-auto">
                <thead>
                    <tr>
                        <th class="text-left p-2">ID</th>
                        <th class="text-left p-2">Nama</th>
                        <th class="text-left p-2">Deskripsi</th>
                        <th class="text-left p-2">Harga</th>
                        <th class="text-left p-2">Durasi</th>
                        <th class="text-left p-2">Gambar</th>
                        <th class="text-left p-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($layanans)): ?>
                        <?php foreach ($layanans as $l): ?>
                            <tr class="border-t">
                                <td class="p-2"><?php echo $l['id_layanan']; ?></td>
                                <td class="p-2"><?php echo htmlspecialchars($l['nama_layanan']); ?></td>
                                <td class="p-2"><?php echo htmlspecialchars($l['deskripsi']); ?></td>
                                <td class="p-2">Rp <?php echo number_format($l['harga'], 0, ',', '.'); ?></td>
                                <td class="p-2"><?php echo $l['durasi']; ?> menit</td>
                                <td class="p-2">
                                    <?php if (!empty($l['gambar_layanan'])): ?>
                                        <img src="./image/layanan/<?php echo htmlspecialchars($l['gambar_layanan']); ?>" class="thumbnail">
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-2">
                                    <a href="index.php?modul=layanan&fitur=update&id_layanan=<?php echo $l['id_layanan']; ?>" class="action-link">Edit</a>
                                    <a href="index.php?modul=layanan&fitur=hapus&id_layanan=<?php echo $l['id_layanan']; ?>" class="action-link delete" onclick="return confirm('Yakin ingin menghapus layanan ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-gray-500 p-4">Tidak ada data layanan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>