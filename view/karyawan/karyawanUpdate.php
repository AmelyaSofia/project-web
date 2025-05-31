<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Update Karyawan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f9f9f9; }
        h1 { color: #333; }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 400px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input[type="text"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        .btn-submit {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #ffc107;
            border: none;
            color: black;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-back {
            margin-top: 15px;
            display: inline-block;
            text-decoration: none;
            color: #007BFF;
        }
    </style>
</head>
<body>

<h1>Update Karyawan</h1>

<form method="POST" action="index.php?fitur=update&id_karyawan=<?= $karyawan['id_karyawan'] ?>">
    <label for="nama_karyawan">Nama Karyawan</label>
    <input type="text" name="nama_karyawan" id="nama_karyawan" value="<?= htmlspecialchars($karyawan['nama_karyawan']) ?>" required />

    <label for="peran_karyawan">Peran Karyawan</label>
    <input type="text" name="peran_karyawan" id="peran_karyawan" value="<?= htmlspecialchars($karyawan['peran_karyawan']) ?>" required />

    <label for="foto_karyawan">URL Foto</label>
    <input type="text" name="foto_karyawan" id="foto_karyawan" value="<?= htmlspecialchars($karyawan['foto_karyawan']) ?>" />

    <button type="submit" class="btn-submit">Update</button>
</form>

<a href="index.php?fitur=karyawan" class="btn-back">← Kembali ke daftar</a>

</body>
</html>
