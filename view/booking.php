<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"  rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Booking Layanan</h2>
    <form action="../index.php?modul=booking&fitur=tambah" method="POST">
        <!-- Hidden input -->
        <input type="hidden" name="id_client" value="<?= $_SESSION['id_client'] ?? '' ?>">
        <input type="hidden" name="id_layanan" value="<?= $_GET['id'] ?>">

        <div class="mb-3">
            <label for="id_stylist" class="form-label">Pilih Stylist</label>
            <select name="id_stylist" class="form-control" required>
                <?php
                include '../model/stylistModel.php';
                $stylistModel = new ModelStylist();
                $stylists = $stylistModel->getStylists();
                foreach ($stylists as $stylist) {
                    echo "<option value='{$stylist['id_stylist']}'>{$stylist['nama_stylist']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal Booking</label>
            <input type="date" name="tanggal" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="waktu" class="form-label">Waktu Booking</label>
            <input type="time" name="waktu" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="catatan" class="form-label">Catatan Tambahan</label>
            <textarea name="catatan" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Simpan Booking</button>
    </form>
</div>
</body>
</html>