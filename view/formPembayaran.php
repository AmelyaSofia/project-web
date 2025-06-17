<!-- view/formPembayaran.php -->
<form action="../controller/pembayaranController.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_booking" value="<?= $id_booking; ?>">
    
    <label>Jenis Pembayaran:</label>
    <select name="jenis" required>
        <option value="dp">DP (50%)</option>
        <option value="pelunasan">Pelunasan</option>
    </select>

    <label>Jumlah:</label>
    <input type="number" name="jumlah" required>

    <label>Upload Bukti Pembayaran:</label>
    <input type="file" name="bukti" accept="image/*" required>

    <button type="submit" name="submit_pembayaran">Kirim Pembayaran</button>
</form>
