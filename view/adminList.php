<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Admin</title>
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
    <h1>Data Admin</h1>

    <?php if (isset($_GET['message'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <div class="search-container">
        <form method="GET" action="index.php">
            <input type="hidden" name="fitur" value="admin">
            <input type="text" name="search" placeholder="Cari admin..." value="<?php echo $_GET['search'] ?? ''; ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

    <div class="form-container">
        <?php if (isset($_GET['fitur']) && $_GET['fitur'] == 'update' && isset($admin)): ?>
            <h2>Update Admin</h2>
            <form method="POST" action="index.php?fitur=update&id_admin=<?php echo $admin['id_admin']; ?>">
                <label>Username:</label><br>
                <input type="text" name="username" value="<?php echo htmlspecialchars($admin['username']); ?>" required><br>
                <label>Password:</label><br>
                <input type="password" name="password" value="<?php echo htmlspecialchars($admin['password']); ?>" required><br>
                <label>Nama Admin:</label><br>
                <input type="text" name="nama_admin" value="<?php echo htmlspecialchars($admin['nama_admin']); ?>" required><br><br>
                <button type="submit">Update</button>
                <a href="index.php?fitur=admin">Batal</a>
            </form>
        <?php else: ?>
            <h2>Tambah Admin</h2>
            <form method="POST" action="index.php?fitur=tambah">
                <label>Username:</label><br>
                <input type="text" name="username" required><br>
                <label>Password:</label><br>
                <input type="password" name="password" required><br>
                <label>Nama Admin:</label><br>
                <input type="text" name="nama_admin" required><br><br>
                <button type="submit">Tambah</button>
            </form>
        <?php endif; ?>
    </div>

    <h2>Daftar Admin</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Nama Admin</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($admins)): ?>
                <?php foreach ($admins as $a): ?>
                    <tr>
                        <td><?php echo $a['id_admin']; ?></td>
                        <td><?php echo htmlspecialchars($a['username']); ?></td>
                        <td><?php echo htmlspecialchars($a['nama_admin']); ?></td>
                        <td>
                            <a href="index.php?fitur=update&id_admin=<?php echo $a['id_admin']; ?>">Edit</a> |
                            <a href="index.php?fitur=hapus&id_admin=<?php echo $a['id_admin']; ?>" onclick="return confirm('Yakin ingin hapus admin ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="4">Tidak ada data admin.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
