<?php
require_once __DIR__ . '/../model/bookingModel.php';
$bookingModel = new ModelBooking();

// Ambil ID booking dari URL
$id_booking = $_GET['id_booking'] ?? null;
if (!$id_booking) {
    echo "ID booking tidak ditemukan.";
    exit;
}

$booking = $bookingModel->getBookingById($id_booking);
$harga_total = $booking['harga_layanan'] ?? 0; // asumsi kolom ini ada
$dp = $harga_total * 0.5;
$pelunasan = $harga_total - $dp;
?>

<h2>Pembayaran</h2>
<p>Layanan: <?= $booking['nama_layanan'] ?></p>
<p>Harga Total: Rp<?= number_format($harga_total) ?></p>

<form action="../controller/pembayaranController.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_booking" value="<?= $id_booking; ?>">

    <label>Jenis Pembayaran:</label>
    <select name="jenis" id="jenisPembayaran" onchange="updateJumlah()" required>
        <option value="dp">DP (50%)</option>
        <option value="pelunasan">Pelunasan</option>
    </select><br><br>

    <label>Jumlah:</label>
    <input type="text" id="jumlahDisplay" value="Rp<?= number_format($dp) ?>" disabled>
    <input type="hidden" name="jumlah" id="jumlah" value="<?= $dp ?>"><br><br>

    <label>Metode Pembayaran:</label>
    <select name="metode_pembayaran" required>
        <option value="">-- Pilih Metode --</option>
                    <option value="Transfer BCA">Transfer BANK</option>
                    <option value="QRIS">QRIS</option>
                    <option value="Dana">Dana</option>
    </select><br><br>

    <label>Upload Bukti Pembayaran:</label>
    <input type="file" name="bukti_pembayaran" accept="image/*" required><br><br>

    <button type="submit" name="submit_pembayaran">Kirim Pembayaran</button>
</form>

<script>
function updateJumlah() {
    const jenis = document.getElementById("jenisPembayaran").value;
    const jumlahInput = document.getElementById("jumlah");
    const jumlahDisplay = document.getElementById("jumlahDisplay");
    
    const dp = <?= $dp ?>;
    const pelunasan = <?= $pelunasan ?>;

    if (jenis === "dp") {
        jumlahInput.value = dp;
        jumlahDisplay.value = "Rp" + dp.toLocaleString('id-ID');
    } else {
        jumlahInput.value = pelunasan;
        jumlahDisplay.value = "Rp" + pelunasan.toLocaleString('id-ID');
    }
}
</script>
