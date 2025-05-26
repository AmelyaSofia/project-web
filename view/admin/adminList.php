<!-- ./view/admin/listAdmin.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Daftar Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background-color: #fff;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #007BFF;
            color: white;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            padding: 6px 12px;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            margin-right: 5px;
        }
        .btn-add {
            background-color: #28a745;
            color: white;
        }
        .btn-edit {
            background-color: #ffc107;
            color: black;
        }
        .btn-delete {
            background-color: #dc3545;
            color: white;
        }
        .search-form {
            margin-bottom: 15px;
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
        }
    </style>
</head>
<body>

<h1>Daftar Admin</h1>

<?php if (!empty($_GET['message'])): ?>
    <div class="message"><?= htmlspecialchars($_GET['message']) ?></div>
<?php endif; ?>

<form class="search-form" method="GET" action="index.php">
    <input type="hidden" name="fitur" value="admin" />
    <input type="text" name="search" placeholder="Cari nama atau email admin..." value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>" />
    <button type="submit">Cari</button>
    <a href="index.php?fitur=admin" style="margin-left:10px;">Reset</a>
</form>

<a href="index.php?fitur=tambah" class="btn btn-add">+ Tambah Admin</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Admin</th>
            <th>Email Admin</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($admins)): ?>
            <?php foreach ($admins as $admin): ?>
                <tr>
                    <td><?= htmlspecialchars($admin['id_admin']) ?></td>
                    <td><?= htmlspecialchars($admin['nama_admin']) ?></td>
                    <td><?= htmlspecialchars($admin['email_admin']) ?></td>
                    <td>
                        <?php if (!empty($admin['foto_admin'])): ?>
                            <img src="<?= htmlspecialchars($admin['foto_admin']) ?>" alt="Foto Admin" style="width:50px; height:50px; object-fit:cover; border-radius:50%;" />
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="index.php?fitur=update&id_admin=<?= $admin['id_admin'] ?>" class="btn btn-edit">Edit</a>
                        <a href="index.php?fitur=hapus&id_admin=<?= $admin['id_admin'] ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus admin ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">Data admin tidak ditemukan.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
