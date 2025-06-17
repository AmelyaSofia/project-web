<?php
session_start();

if (!isset($_SESSION['id_client'])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/../model/pembayaranModel.php';

$modelPembayaran = new ModelPembayaran();

$id_booking = isset($_GET['id']) ? intval($_GET['id']) : 0;
$upload_error = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['bukti']) && $_FILES['bukti']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = __DIR__ . '/../uploads/';
        $filename = time() . '_' . basename($_FILES['bukti']['name']);
        $target_file = $upload_dir . $filename;
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Buat folder jika belum ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Validasi jenis file (gambar saja)
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['bukti']['tmp_name'], $target_file)) {
                // Simpan ke database (path yang disimpan untuk akses web, bukan path asli server)
                $web_path = '../uploads/' . $filename;
                $jumlah_dp = 50000; // DP default
                $modelPembayaran->simpanPembayaran($id_booking, 'dp', $jumlah_dp, $web_path);
                $success_message = "Bukti pembayaran berhasil diunggah. Menunggu verifikasi admin.";
            } else {
                $upload_error = "Gagal memindahkan file.";
            }
        } else {
            $upload_error = "Jenis file tidak diizinkan. Hanya JPG, JPEG, PNG, GIF.";
        }
    } else {
        $upload_error = "Silakan pilih file bukti pembayaran.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembayaran DP - Royal Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Pembayaran DP</h2>
        <p>Silakan unggah bukti pembayaran DP untuk Booking ID: <strong><?= $id_booking ?></strong></p>

        <?php if ($upload_error): ?>
            <div class="alert alert-danger"><?= $upload_error ?></div>
        <?php elseif ($success_message): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
            <a href="riwayatBooking.php" class="btn btn-success mt-3">Kembali ke Riwayat Booking</a>
        <?php else: ?>
            <form method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="bukti" class="form-label">Unggah Bukti Pembayaran (JPG, JPEG, PNG, GIF)</label>
                    <input type="file" name="bukti" id="bukti" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Kirim Bukti</button>
                <a href="riwayatBooking.php" class="btn btn-secondary ms-2">Batal</a>
            </form>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
