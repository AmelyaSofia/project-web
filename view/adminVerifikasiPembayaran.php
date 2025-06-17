<?php
require_once __DIR__ . '/../model/pembayaranModel.php';
$pembayaranModel = new ModelPembayaran();
$pembayaran = $pembayaranModel->getAllPembayaran();
?>

<h2>Verifikasi Pembayaran</h2>

<table border="1" cellpadding="10" cellspacing="0">
    <tr>
        <th>Nama Client</th>
        <th>ID Booking</th>
        <th>Jenis</th>
        <th>Jumlah</th>
        <th>Bukti</th>
        <th>Status</th>
        <th>Aksi</th>
    </tr>
    <?php foreach ($pembayaran as $row): ?>
    <tr>
        <td><?= htmlspecialchars($row['nama_client']) ?></td>
        <td><?= $row['id_booking'] ?></td>
        <td><?= ucfirst($row['jenis']) ?></td>
        <td>Rp<?= number_format($row['jumlah'], 0, ',', '.') ?></td>
        <td>
            <?php if (!empty($row['bukti_pembayaran'])): ?>
                <a href="../uploads/<?= htmlspecialchars($row['bukti_pembayaran']) ?>" target="_blank">Lihat</a>
            <?php else: ?>
                Tidak ada
            <?php endif; ?>
        </td>
        <td><?= ucfirst($row['status']) ?></td>
        <td>
            <?php if ($row['status'] == 'pending'): ?>
                <form action="../index.php?modul=pembayaran&fitur=verifikasi&id=<?= $row['id_pembayaran'] ?>" method="POST">
                    <select name="aksi" required>
                        <option value="">Pilih</option>
                        <option value="terima">Terima</option>
                        <option value="tolak">Tolak</option>
                    </select><br>
                    <input type="text" name="alasan" placeholder="Alasan (jika ditolak)">
                    <button type="submit">Verifikasi</button>
                </form>
            <?php else: ?>
                <em>Terverifikasi</em>
            <?php endif; ?>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
