<?php
session_start();
require_once __DIR__.'/../model/clientModel.php';
require_once __DIR__.'/../model/stylistModel.php';
require_once __DIR__.'/../model/layananModel.php';
require_once __DIR__.'/../controller/bookingController.php';

$controller = new ControllerBooking();

$id_layanan = $_GET['id'] ?? null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_client = $_SESSION['id_client'] ?? null;
    $id_stylist = $_POST['id_stylist'];
    $tanggal = $_POST['tanggal'];
    $waktu = $_POST['waktu'];
    $catatan = $_POST['catatan'] ?? '';

    $status = 'menunggu';

    $success = $controller->modelBooking->addBooking($id_client, $id_stylist, $id_layanan, $tanggal, $waktu, $catatan);

    if ($success) {
        header("Location: booking.php?id=$id_layanan&message=Booking+berhasil+dibuat");
        exit;
    } else {
        header("Location: booking.php?id=$id_layanan&message=Gagal+membuat+booking");
        exit;
    }
}

$stylists = $controller->modelStylist->getStylists();
$layanan = $controller->modelLayanan->getLayananById($id_layanan);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Layanan</title>
</head>
<body>
    <h2>Booking Layanan: <?php echo htmlspecialchars($layanan['nama_layanan'] ?? 'Tidak dikenali'); ?></h2>

    <?php if (isset($_GET['message'])): ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="id_stylist">Pilih Stylist:</label>
        <select name="id_stylist" required>
            <option value="">-- Pilih Stylist --</option>
            <?php foreach ($stylists as $s): ?>
                <option value="<?php echo $s['id_stylist']; ?>">
                    <?php echo htmlspecialchars($s['nama_stylist']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="tanggal">Tanggal:</label>
        <input type="date" name="tanggal" required>
        <br><br>

        <label for="waktu">Waktu:</label>
        <input type="time" name="waktu" required>
        <br><br>

        <label for="catatan">Catatan Tambahan:</label>
        <textarea name="catatan"></textarea>
        <br><br>

        <button type="submit">Submit Booking</button>
    </form>
</body>
</html>