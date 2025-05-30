<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Update Layanan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 600px; }
        label { display: block; margin-top: 10px; }
        input, textarea {
            width: 100%; padding: 8px; margin-top: 5px;
            border: 1px solid #ccc; border-radius: 4px;
        }
        button {
            margin-top: 15px; padding: 8px 16px;
            background-color: #007bff; color: white;
            border: none; border-radius: 4px;
            cursor: pointer;
        }
        button:hover { background-color: #0056b3; }
        a { display: inline-block; margin-top: 15px; color: #007bff; }
    </style>
</head>
<body>
    <h2>Update Layanan</h2>

    <form method="POST" action="index.php?fitur=update&id_layanan=<?= $layanan['id_layanan'] ?>">
        <label for="nama_layanan">Nama Layanan</label>
        <input type="text" id="nama_layanan" name="nama_layanan" required
               value="<?= htmlspecialchars($layanan['nama_layanan']) ?>">

        <label for="deskripsi_layanan">Deskripsi Layanan</label>
        <textarea id="deskripsi_layanan" name="deskripsi_layanan" required><?= htmlspecialchars($layanan['deskripsi_layanan']) ?></textarea>

        <label for="harga_layanan">Harga Layanan</label>
        <input type="number" id="harga_layanan" name="harga_layanan" required
               value="<?= htmlspecialchars($layanan['harga_layanan']) ?>">

        <button type="submit">Update</button>
    </form>

    <a href="index.php?fitur=list">← Kembali ke daftar</a>
</body>
</html>
