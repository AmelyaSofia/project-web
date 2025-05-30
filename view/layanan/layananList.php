<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Daftar Layanan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .message { padding: 10px; background-color: #e0ffe0; border: 1px solid #00aa00; margin-bottom: 15px; }
        .btn { text-decoration: none; padding: 6px 12px; background-color: #007bff; color: white; border-radius: 4px; }
        .btn:hover { background-color: #0056b3; }
        .btn-danger { background-color: #dc3545; }
        .btn-danger:hover { background-color: #a71d2a; }
        .search-box { margin-bottom: 15px; }
    </style>
</head>
<body>
    <h2>Daftar Layanan</h2>

    <?php if (isset($_GET['message'])): ?>
        <div class="message"><?= htmlspecialchars($_GET['message']) ?></div>
    <?php endif; ?>

    <form method="GET" action="index.php" class="search-box">
        <input type="hidden" name="fitur" value="list" />
        <input type="text" name="search" placeholder="Cari nama layanan..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
        <button type="submit">Cari</button>
        <a href="index.php?fitur=list" class="btn" style="background-color:#6c757d;">Reset</a>
    </form>

    <a href="index.php?fitur=tambah" class="btn">Tambah Layanan</a>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Layanan</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($layanans)): ?>
                <?php foreach ($layanans as $layanan): ?>
                    <tr>
                        <td><?= htmlspecialchars($layanan['id_layanan']) ?></td>
                        <td><?= htmlspecialchars($layanan['nama_layanan']) ?></td>
                        <td><?= htmlspecialchars($layanan['deskripsi_layanan']) ?></td>
                        <td>Rp<?= number_format($layanan['harga_layanan'], 0, ',', '.') ?></td>
                        <td>
                            <a href="index.php?fitur=update&id_layanan=<?= $layanan['id_layanan'] ?>" class="btn">Edit</a>
                            <a href="index.php?fitur=hapus&id_layanan=<?= $layanan['id_layanan'] ?>" class="btn btn-danger"
                               onclick="return confirm('Yakin ingin menghapus layanan ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5" style="text-align:center;">Data layanan tidak ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
