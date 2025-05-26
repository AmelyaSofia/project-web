<!-- ./view/client/listClient.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Daftar Client</title>
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
    <h2>Daftar Client</h2>

    <!-- Pesan sukses/gagal -->
    <?php if (isset($_GET['message'])): ?>
        <div class="message"><?= htmlspecialchars($_GET['message']) ?></div>
    <?php endif; ?>

    <!-- Form pencarian -->
    <form method="GET" action="index.php" class="search-box">
        <input type="hidden" name="fitur" value="list" />
        <input type="text" name="search" placeholder="Cari client..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
        <button type="submit">Cari</button>
        <a href="index.php?fitur=list" class="btn" style="background-color:#6c757d;">Reset</a>
    </form>

    <!-- Tombol tambah client -->
    <a href="index.php?fitur=tambah" class="btn">Tambah Client</a>

    <!-- Tabel daftar client -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Client</th>
                <th>Email</th>
                <th>No. Telp</th>
                <th>Alamat</th>
                <th>Tanggal Daftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clients)): ?>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['id_client']) ?></td>
                        <td><?= htmlspecialchars($client['nama_client']) ?></td>
                        <td><?= htmlspecialchars($client['email_client']) ?></td>
                        <td><?= htmlspecialchars($client['notelp_client']) ?></td>
                        <td><?= htmlspecialchars($client['alamat_client']) ?></td>
                        <td><?= htmlspecialchars($client['tanggal_daftar']) ?></td>
                        <td>
                            <a href="index.php?fitur=update&id_client=<?= $client['id_client'] ?>" class="btn">Edit</a>
                            <a href="index.php?fitur=hapus&id_client=<?= $client['id_client'] ?>" class="btn btn-danger" 
                               onclick="return confirm('Yakin ingin menghapus client ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" style="text-align:center;">Data client tidak ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
