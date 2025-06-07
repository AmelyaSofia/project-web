<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Layanan</title>
    <style>
        table, th, td {
            border: 1px solid #ccc;
            border-collapse: collapse;
            padding: 8px;
        }
        table {
            width: 100%;
            margin-top: 20px;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-container, .search-container {
            margin: 20px 0;
        }
        img.thumbnail {
            width: 80px;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>

    <h1>Data Layanan</h1>

    <?php if (isset($_GET['message'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <div class="search-container">
        <form method="GET" action="index.php">
            <input type="hidden" name="fitur" value="layanan">
            <input type="text" name="search" placeholder="Cari layanan..." value="<?php echo $_GET['search'] ?? ''; ?>">
            <button type="submit">Cari</button>
        </form>
    </div>
    <div class="form-container">
    <?php if (isset($_GET['fitur']) && $_GET['fitur'] == 'update' && isset($layanan)): ?>
        <h2>Update Layanan</h2>
        <form method="POST" action="index.php?fitur=update&id_layanan=<?php echo $layanan['id_layanan']; ?>" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama Layanan:</label>
                <input type="text" class="form-control" name="nama_layanan" value="<?php echo htmlspecialchars($layanan['nama_layanan']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi:</label>
                <textarea class="form-control" name="deskripsi" rows="3" required><?php echo htmlspecialchars($layanan['deskripsi']); ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga (Rp):</label>
                    <input type="number" class="form-control" name="harga" value="<?php echo $layanan['harga']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Durasi (menit):</label>
                    <input type="number" class="form-control" name="durasi" value="<?php echo $layanan['durasi']; ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Layanan:</label>
                <input type="file" class="form-control" name="gambar_layanan" accept="image/*">
                <?php if (!empty($layanan['gambar_layanan'])): ?>
                    <div class="mt-2">
                        <img src="./image/layanan/<?php echo htmlspecialchars($layanan['gambar_layanan']); ?>" class="img-thumbnail" style="max-width: 200px;">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" name="hapus_gambar" id="hapusGambar">
                            <label class="form-check-label" for="hapusGambar">Hapus gambar saat update</label>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="index.php?fitur=layanan" class="btn btn-secondary">Batal</a>
            </div>
        </form>
    <?php else: ?>
        <h2>Tambah Layanan</h2>
        <form method="POST" action="index.php?fitur=tambah" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nama Layanan:</label>
                <input type="text" class="form-control" name="nama_layanan" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Deskripsi:</label>
                <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga (Rp):</label>
                    <input type="number" class="form-control" name="harga" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Durasi (menit):</label>
                    <input type="number" class="form-control" name="durasi" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Gambar Layanan:</label>
                <input type="file" class="form-control" name="gambar_layanan" accept="image/*" required>
                <small class="text-muted">Format: JPG, PNG, JPEG (Maks. 10MB)</small>
            </div>

            <button type="submit" class="btn btn-primary">Tambah</button>
        </form>
    <?php endif; ?>
</div>
    <h2>Daftar Layanan</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Durasi</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($layanans)): ?>
                <?php foreach ($layanans as $l): ?>
                    <tr>
                        <td><?php echo $l['id_layanan']; ?></td>
                        <td><?php echo htmlspecialchars($l['nama_layanan']); ?></td>
                        <td><?php echo htmlspecialchars($l['deskripsi']); ?></td>
                        <td><?php echo 'Rp ' . number_format($l['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo $l['durasi']; ?> menit</td>
                        <td>
                            <?php if (!empty($l['gambar_layanan'])): ?>
                                <img src="./image/layanan/<?php echo htmlspecialchars($l['gambar_layanan']); ?>" class="thumbnail">
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="index.php?fitur=update&id_layanan=<?php echo $l['id_layanan']; ?>">Edit</a> |
                            <a href="index.php?fitur=hapus&id_layanan=<?php echo $l['id_layanan']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus layanan ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Tidak ada data layanan.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
