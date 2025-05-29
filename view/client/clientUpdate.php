<!-- ./view/client/clientUpdate.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Edit Client</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        form { max-width: 500px; margin: auto; }
        label { display: block; margin-top: 15px; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; }
        button { margin-top: 20px; padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; }
        button:hover { background-color: #0056b3; }
        a { text-decoration: none; display: inline-block; margin-top: 20px; color: #007bff; }
    </style>
</head>
<body>
    <h2>Edit Client</h2>
    <form method="POST" action="index.php?fitur=update&id_client=<?= $client['id_client'] ?>">
        <label for="nama_client">Nama Client:</label>
        <input type="text" name="nama_client" value="<?= htmlspecialchars($client['nama_client']) ?>" required />

        <label for="email_client">Email:</label>
        <input type="email" name="email_client" value="<?= htmlspecialchars($client['email_client']) ?>" required />

        <label for="notelp_client">No. Telepon:</label>
        <input type="text" name="notelp_client" value="<?= htmlspecialchars($client['notelp_client']) ?>" required />

        <label for="alamat_client">Alamat:</label>
        <textarea name="alamat_client" rows="3" required><?= htmlspecialchars($client['alamat_client']) ?></textarea>

        <label for="tanggal_daftar">Tanggal Daftar:</label>
        <input type="date" name="tanggal_daftar" value="<?= htmlspecialchars($client['tanggal_daftar']) ?>" required />

        <button type="submit">Update</button>
    </form>

    <a href="index.php?fitur=list">← Kembali ke Daftar Client</a>
</body>
</html>
