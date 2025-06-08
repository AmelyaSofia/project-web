<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Client</title>
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
    <h1>Data Client</h1>

    <?php if (isset($_GET['message'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <div class="search-container">
        <form method="GET" action="index.php">
            <input type="hidden" name="fitur" value="client">
            <input type="text" name="search" placeholder="Cari client..." value="<?php echo $_GET['search'] ?? ''; ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

    <div class="form-container">
        <?php if (isset($_GET['fitur']) && $_GET['fitur'] == 'update' && isset($client)): ?>
            <h2>Update Client</h2>
            <form method="POST" action="index.php?modul=client&fitur=update&id_client=<?php echo $client['id_client']; ?>">
                <label>Nama:</label><br>
                <input type="text" name="nama_client" value="<?php echo htmlspecialchars($client['nama_client']); ?>" required><br>

                <label>Email:</label><br>
                <input type="email" name="email" value="<?php echo htmlspecialchars($client['email']); ?>" required><br>

                <label>No HP:</label><br>
                <input type="text" name="no_hp" value="<?php echo htmlspecialchars($client['no_hp']); ?>" required><br>

                <label>Alamat:</label><br>
                <textarea name="alamat" required><?php echo htmlspecialchars($client['alamat']); ?></textarea><br>

                <label>Username:</label><br>
                <input type="text" name="username" value="<?php echo htmlspecialchars($client['username']); ?>" required><br>

                <label>Password:</label><br>
                <input type="text" name="password" value="<?php echo htmlspecialchars($client['password']); ?>" required><br><br>

                <button type="submit">Update</button>
                <a href="index.php?fitur=client">Batal</a>
            </form>
        <?php else: ?>
            <h2>Tambah Client</h2>
            <form method="POST" action="index.php?modul=client&fitur=tambah">
                <label>Nama:</label><br>
                <input type="text" name="nama_client" required><br>

                <label>Email:</label><br>
                <input type="email" name="email" required><br>

                <label>No HP:</label><br>
                <input type="text" name="no_hp" required><br>

                <label>Alamat:</label><br>
                <textarea name="alamat" required></textarea><br>

                <label>Username:</label><br>
                <input type="text" name="username" required><br>

                <label>Password:</label><br>
                <input type="text" name="password" required><br><br>

                <button type="submit">Tambah</button>
            </form>
        <?php endif; ?>
    </div>

    <h2>Daftar Client</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No HP</th>
                <th>Alamat</th>
                <th>Username</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clients)): ?>
                <?php foreach ($clients as $c): ?>
                    <tr>
                        <td><?php echo $c['id_client']; ?></td>
                        <td><?php echo htmlspecialchars($c['nama_client']); ?></td>
                        <td><?php echo htmlspecialchars($c['email']); ?></td>
                        <td><?php echo htmlspecialchars($c['no_hp']); ?></td>
                        <td><?php echo htmlspecialchars($c['alamat']); ?></td>
                        <td><?php echo htmlspecialchars($c['username']); ?></td>
                        <td>
                            <a href="index.php?modul=client&fitur=update&id_client=<?php echo $c['id_client']; ?>">Edit</a> |
                            <a href="index.php?modul=client&fitur=hapus&id_client=<?php echo $c['id_client']; ?>" onclick="return confirm('Yakin ingin hapus client ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="7">Tidak ada data client.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
