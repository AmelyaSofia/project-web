<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Booking</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FFF3E4] text-[#967E76] min-h-screen flex">

    <?php include 'include/sidebar.php'; ?>

    <main class="ml-64 p-6 w-full bg-white min-h-screen">
        <h1 class="text-3xl font-bold mb-6">Manajemen Booking</h1>

        <?php if (isset($_GET['message'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-xl shadow-md mb-8">
            <h2 class="text-2xl font-semibold mb-4"><?php echo isset($booking) ? "Update Booking" : "Tambah Booking"; ?></h2>
            <form method="POST" action="index.php?modul=booking&fitur=<?php echo isset($booking) ? "update&id_booking=" . $booking['id_booking'] : "tambah"; ?>" class="grid gap-4">
                
                <div>
                    <label class="block font-medium">Client:</label>
                    <select name="id_client" class="w-full p-2 border border-gray-300 rounded-lg" required>
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
                </div>

                <div>
                    <label class="block font-medium">Stylist:</label>
                    <select name="id_stylist" class="w-full p-2 border border-gray-300 rounded-lg" required>
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
                </div>

                <div>
    <label class="block font-medium">Layanan:</label>
    <?php foreach ($layanans as $layanan): ?>
        <label class="block">
            <input type="checkbox" name="id_layanan[]" value="<?php echo $layanan['id_layanan']; ?>"
                <?php 
                    if (isset($_POST['id_layanan']) && in_array($layanan['id_layanan'], $_POST['id_layanan'])) echo 'checked';
                ?>>
            <?php echo htmlspecialchars($layanan['nama_layanan']); ?>
        </label>
    <?php endforeach; ?>
</div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium">Tanggal:</label>
                        <input type="date" name="tanggal" class="w-full p-2 border border-gray-300 rounded-lg" required 
                            value="<?php echo isset($booking) ? $booking['tanggal'] : ($_POST['tanggal'] ?? ''); ?>">
                    </div>
                    <div>
                        <label class="block font-medium">Waktu:</label>
                        <input type="time" name="waktu" class="w-full p-2 border border-gray-300 rounded-lg" required 
                            value="<?php echo isset($booking) ? $booking['waktu'] : ($_POST['waktu'] ?? ''); ?>">
                    </div>
                </div>

                <?php if (isset($booking)): ?>
                    <div>
                        <label class="block font-medium">Status:</label>
                        <select name="status" class="w-full p-2 border border-gray-300 rounded-lg" required>
                            <?php 
                            $statuses = ['menunggu', 'terjadwal', 'selesai', 'batal'];
                            foreach ($statuses as $status): ?>
                                <option value="<?php echo $status; ?>" <?php echo ($booking['status'] == $status) ? 'selected' : ''; ?>>
                                    <?php echo ucfirst($status); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="block font-medium">Catatan:</label>
                    <textarea name="catatan" class="w-full p-2 border border-gray-300 rounded-lg"><?php 
                        echo isset($booking) ? htmlspecialchars($booking['catatan']) : ($_POST['catatan'] ?? ''); 
                    ?></textarea>
                </div>

                <div class="flex gap-4 items-center">
                    <button type="submit" class="bg-[#967E76] hover:bg-[#D7C0AE] text-white px-6 py-2 rounded-lg">
                        <?php echo isset($booking) ? "Update" : "Tambah"; ?>
                    </button>
                    <?php if (isset($booking)): ?>
                        <a href="index.php?modul=booking&fitur=booking" class="text-red-600 hover:underline">Batal</a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <form method="GET" class="mb-4">
            <input type="hidden" name="modul" value="booking">
            <input type="hidden" name="fitur" value="booking">
            <input type="text" name="search" placeholder="Cari booking..." value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>" class="p-2 border border-gray-300 rounded-lg w-64">
            <button type="submit" class="ml-2 bg-[#967E76] text-white px-4 py-2 rounded-lg hover:bg-[#D7C0AE]">Cari</button>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full border text-sm text-left bg-white shadow-md rounded-xl">
                <thead class="bg-[#967E76] text-white">
                    <tr>
                        <th class="p-3">ID</th>
                        <th class="p-3">Client</th>
                        <th class="p-3">Stylist</th>
                        <th class="p-3">Layanan</th>
                        <th class="p-3">Harga</th> 
                        <th class="p-3">Tanggal</th>
                        <th class="p-3">Waktu</th>
                        <th class="p-3">Status</th>
                        <th class="p-3">Catatan</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($bookings)): ?>
                        <?php foreach ($bookings as $b): ?>
                            <tr class="border-t hover:bg-[#FFF3E4]">
                                <td class="p-3"><?php echo $b['id_booking']; ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($b['nama_client']); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($b['nama_stylist']); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($b['nama_layanan']); ?></td>
                                <td class="p-3">Rp <?php echo number_format($b['harga'], 0, ',', '.'); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($b['tanggal']); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($b['waktu']); ?></td>
                                <td class="p-3"><?php echo ucfirst(htmlspecialchars($b['status'])); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($b['catatan']); ?></td>
                                <td class="p-3">
                                    <div class="space-x-2">
                                        <a href="index.php?modul=booking&fitur=update&id_booking=<?php echo $b['id_booking']; ?>" class="text-blue-500 hover:underline">Edit</a>
                                        <a href="index.php?modul=booking&fitur=hapus&id_booking=<?php echo $b['id_booking']; ?>" onclick="return confirm('Yakin ingin hapus?')" class="text-red-500 hover:underline">Hapus</a>
                                        <?php if ($b['status'] !== 'selesai' && $b['status'] !== 'batal'): ?>
                                            <form action="index.php?modul=booking&fitur=ubah_status&id_booking=<?php echo $b['id_booking']; ?>" method="POST" class="inline">
                                                <select name="status" onchange="this.form.submit()" class="text-sm p-1 border rounded">
                                                    <option value="">Ubah Status</option>
                                                    <?php foreach (['menunggu', 'terjadwal', 'selesai', 'batal'] as $status): 
                                                        if ($status != $b['status']): ?>
                                                            <option value="<?php echo $status; ?>"><?php echo ucfirst($status); ?></option>
                                                    <?php endif; endforeach; ?>
                                                </select>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="9" class="p-4 text-center">Tidak ada data booking.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>