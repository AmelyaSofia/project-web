<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Tambah Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #333;
        }
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
        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        .btn-submit {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #28a745;
            border: none;
            color: white;
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

<h1>Tambah Admin</h1>

<form method="POST" action="index.php?fitur=tambah">
    <label for="nama_admin">Nama Admin</label>
    <input type="text" name="nama_admin" id="nama_admin" required />

    <label for="email_admin">Email Admin</label>
    <input type="email" name="email_admin" id="email_admin" required />

    <label for="password_admin">Password</label>
    <input type="password" name="password_admin" id="password_admin" required />

    <label for="foto_admin">URL Foto (Opsional)</label>
    <input type="text" name="foto_admin" id="foto_admin" />

    <button type="submit" class="btn-submit">Simpan</button>
</form>

<a href="index.php?fitur=admin" class="btn-back">← Kembali ke daftar</a>

</body>
</html>
