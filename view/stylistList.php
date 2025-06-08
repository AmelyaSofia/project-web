<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Stylist</title>
    <style>
        table, th, td {
            border: 1px solid #ccc;
            border-collapse: collapse;
            padding: 8px;
        }
        table {
            width: 100%;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-container, .search-container {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Data Stylist</h1>

    <?php if (isset($_GET['message'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <div class="search-container">
        <form method="GET" action="index.php">
            <input type="hidden" name="fitur" value="stylist">
            <input type="text" name="search" placeholder="Cari stylist..." value="<?php echo $_GET['search'] ?? ''; ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

    <div class="form-container">
        <?php if (isset($_GET['fitur']) && $_GET['fitur'] == 'update' && isset($stylist)): ?>
            <h2>Update Stylist</h2>
            <form method="POST" action="index.php?modul=stylist&fitur=update&id_stylist=<?php echo $stylist['id_stylist']; ?>">
                <label>Nama Stylist:</label><br>
                <input type="text" name="nama_stylist" value="<?php echo htmlspecialchars($stylist['nama_stylist']); ?>" required><br>

                <label>Keahlian:</label><br>
                <input type="text" name="keahlian" value="<?php echo htmlspecialchars($stylist['keahlian']); ?>" required><br><br>

                <button type="submit">Update</button>
                <a href="index.php?fitur=stylist">Batal</a>
            </form>
        <?php else: ?>
            <h2>Tambah Stylist</h2>
            <form method="POST" action="index.php?modul=stylist&fitur=tambah" enctype="multipart/form-data">
                <label>Nama Stylist:</label><br>
                <input type="text" name="nama_stylist" required><br>

                <label>Keahlian:</label><br>
                <input type="text" name="keahlian" required><br><br>

                <button type="submit">Tambah</button>
            </form>
        <?php endif; ?>
    </div>

    <h2>Daftar Stylist</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama Stylist</th>
                <th>Keahlian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($stylists)): ?>
                <?php foreach ($stylists as $s): ?>
                    <tr>
                        <td><?php echo $s['id_stylist']; ?></td>
                        <td><?php echo htmlspecialchars($s['nama_stylist']); ?></td>
                        <td><?php echo htmlspecialchars($s['keahlian']); ?></td>
                        <td>
                            <a href="index.php?modul=stylist&fitur=update&id_stylist=<?php echo $s['id_stylist']; ?>">Edit</a> |
                            <a href="index.php?modul=stylist&fitur=hapus&id_stylist=<?php echo $s['id_stylist']; ?>" onclick="return confirm('Yakin ingin hapus stylist ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">Tidak ada data stylist.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
