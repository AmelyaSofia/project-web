<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Booking</title>
    <style>
        table, th, td {
            border: 1px solid #ccc;
            border-collapse: collapse;
            padding: 8px;
            text-align: left;
        }
        table {
            width: 100%;
            margin-bottom: 20px;
        }
        th {
            background-color: #f2f2f2;
        }
        .form-container, .search-container {
            margin: 20px 0;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, select, textarea {
            width: 100%;
            padding: 6px;
            box-sizing: border-box;
        }
        button {
            margin-top: 15px;
            padding: 8px 16px;
        }
        .message {
            color: green;
        }
    </style>
</head>
<body>
    <h1>Manajemen Booking</h1>

    <?php if (isset($_GET['message'])): ?>
        <p class="message"><?php echo htmlspecialchars($_GET['message']); ?></p>
    <?php endif; ?>

    <div class="search-container">
        <form method="GET" action="index.php">
            <input type="hidden" name="modul" value="booking">
            <input type="hidden" name="fitur" value="booking">
            <input type="text" name="search" placeholder="Cari booking..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
            <button type="submit">Cari</button>
        </form>
    </div>

    <h2><?php echo isset($booking) ? "Update Booking" : "Tambah Booking"; ?></h2>
    <div class="form-container">
        <form method="POST" action="index.php?modul=booking&fitur=<?php echo isset($booking) ? "update&id_booking=" . $booking['id_booking'] : "tambah"; ?>">
            <label>Client:</label>
            <select name="id_client" required>
                <option value="">-- Pilih Client --</option>
                <?php foreach ($clients as $client): ?>
                    <option value="<?php echo $client['id_client']; ?>" 
                        <?php 
                            if (isset($booking) && $booking['id_client'] == $client['id_client']) echo 'selected'; 
                            elseif (!isset($booking) && isset($_POST['id_client']) && $_POST['id_client'] == $client['id_client']) echo 'selected';
                        ?>>
                        <?php echo htmlspecialchars($client['nama_client']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Stylist:</label>
            <select name="id_stylist" required>
                <option value="">-- Pilih Stylist --</option>
                <?php foreach ($stylists as $stylist): ?>
                    <option value="<?php echo $stylist['id_stylist']; ?>" 
                        <?php 
                            if (isset($booking) && $booking['id_stylist'] == $stylist['id_stylist']) echo 'selected'; 
                            elseif (!isset($booking) && isset($_POST['id_stylist']) && $_POST['id_stylist'] == $stylist['id_stylist']) echo 'selected';
                        ?>>
                        <?php echo htmlspecialchars($stylist['nama_stylist']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Layanan:</label>
            <select name="id_layanan" required>
                <option value="">-- Pilih Layanan --</option>
                <?php foreach ($layanans as $layanan): ?>
                    <option value="<?php echo $layanan['id_layanan']; ?>" 
                        <?php 
                            if (isset($booking) && $booking['id_layanan'] == $layanan['id_layanan']) echo 'selected'; 
                            elseif (!isset($booking) && isset($_POST['id_layanan']) && $_POST['id_layanan'] == $layanan['id_layanan']) echo 'selected';
                        ?>>
                        <?php echo htmlspecialchars($layanan['nama_layanan']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Tanggal:</label>
            <input type="date" name="tanggal" required 
                value="<?php echo isset($booking) ? $booking['tanggal'] : ($_POST['tanggal'] ?? ''); ?>">

            <label>Waktu:</label>
            <input type="time" name="waktu" required 
                value="<?php echo isset($booking) ? $booking['waktu'] : ($_POST['waktu'] ?? ''); ?>">

            <?php if (isset($booking)): ?>
                <label>Status:</label>
                <select name="status" required>
                    <?php 
                    $statuses = ['menunggu', 'terjadwal', 'selesai', 'batal'];
                    foreach ($statuses as $status): ?>
                        <option value="<?php echo $status; ?>" <?php echo ($booking['status'] == $status) ? 'selected' : ''; ?>>
                            <?php echo ucfirst($status); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

            <label>Catatan:</label>
            <textarea name="catatan"><?php 
                echo isset($booking) ? htmlspecialchars($booking['catatan']) : ($_POST['catatan'] ?? ''); 
            ?></textarea>

            <button type="submit"><?php echo isset($booking) ? "Update" : "Tambah"; ?></button>
            <?php if (isset($booking)): ?>
                <a href="index.php?modul=booking&fitur=booking">Batal</a>
            <?php endif; ?>
        </form>
    </div>

    <h2>Daftar Booking</h2>
    <table>
        <thead>
            <tr>
                <th>ID Booking</th>
                <th>Client</th>
                <th>Stylist</th>
                <th>Layanan</th>
                <th>Tanggal</th>
                <th>Waktu</th>
                <th>Status</th>
                <th>Catatan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($bookings)): ?>
                <?php foreach ($bookings as $b): ?>
                    <tr>
                        <td><?php echo $b['id_booking']; ?></td>
                        <td><?php echo htmlspecialchars($b['nama_client']); ?></td>
                        <td><?php echo htmlspecialchars($b['nama_stylist']); ?></td>
                        <td><?php echo htmlspecialchars($b['nama_layanan']); ?></td>
                        <td><?php echo htmlspecialchars($b['tanggal']); ?></td>
                        <td><?php echo htmlspecialchars($b['waktu']); ?></td>
                        <td><?php echo ucfirst(htmlspecialchars($b['status'])); ?></td>
                        <td><?php echo htmlspecialchars($b['catatan']); ?></td>
                        <td>
                            <a href="index.php?modul=booking&fitur=update&id_booking=<?php echo $b['id_booking']; ?>">Edit</a> | 
                            <a href="index.php?modul=booking&fitur=hapus&id_booking=<?php echo $b['id_booking']; ?>" onclick="return confirm('Yakin ingin hapus booking ini?')">Hapus</a> |
                            <?php if ($b['status'] !== 'selesai' && $b['status'] !== 'batal'): ?>
                                <form action="index.php?modul=booking&fitur=ubah_status&id_booking=<?php echo $b['id_booking']; ?>" method="POST" style="display:inline;">
                                    <select name="status" onchange="this.form.submit()">
                                        <option value="">Ubah Status</option>
                                        <?php 
                                        foreach (['menunggu', 'terjadwal', 'selesai', 'batal'] as $status): 
                                            if ($status != $b['status']):
                                        ?>
                                            <option value="<?php echo $status; ?>"><?php echo ucfirst($status); ?></option>
                                        <?php 
                                            endif;
                                        endforeach; 
                                        ?>
                                    </select>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="9">Tidak ada data booking.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
