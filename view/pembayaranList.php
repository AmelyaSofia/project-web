<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pembayaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FFF3E4] text-[#967E76] min-h-screen flex">

<?php include 'include/sidebar.php'; ?>

<main class="ml-64 p-6 w-full bg-white min-h-screen">
    <h1 class="text-3xl font-bold mb-6">Manajemen Pembayaran</h1>

    <?php if (isset($_GET['message'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($_GET['message']); ?>
        </div>
    <?php endif; ?>

    <!-- Tabel Pembayaran -->
    <div class="overflow-x-auto">
        <table class="w-full border text-sm text-left bg-white shadow-md rounded-xl">
            <thead class="bg-[#967E76] text-white">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Client</th>
                    <th class="p-3">Jenis</th>
                    <th class="p-3">Bukti</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pembayarans)): ?>
                    <?php foreach ($pembayarans as $p): ?>
                        <tr class="border-t hover:bg-[#FFF3E4]">
                            <td class="p-3"><?= htmlspecialchars($p['id_pembayaran']); ?></td>
                            <td class="p-3"><?= htmlspecialchars($p['nama_client'] ?? 'Tidak diketahui'); ?></td>
                            <td class="p-3"><?= ucfirst(htmlspecialchars($p['jenis'])); ?></td>
                            <td class="p-3">
                                <?php if (!empty($p['bukti_pembayaran'])): ?>
                                    <a href="uploads/<?= urlencode($p['bukti_pembayaran']); ?>" target="_blank" class="text-blue-500 hover:underline">
                                        Lihat Bukti
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">Tidak ada bukti</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3"><?= ucfirst(htmlspecialchars($p['status_pembayaran'])); ?></td>
                            <td class="p-3 space-x-2">
                                <?php if ($p['status_pembayaran'] === 'pending'): ?>
                                    <form method="POST" action="index.php?modul=pembayaran&fitur=verifikasi&id=<?= $p['id_pembayaran']; ?>" class="inline">
                                        <button name="aksi" value="terima" class="bg-green-500 text-white px-3 py-1 rounded hover:bg-green-600">Terima</button>
                                        <button name="aksi" value="tolak" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">Tolak</button>
                                    </form>
                                <?php elseif ($p['status_pembayaran'] === 'ditolak'): ?>
                                    <span class="text-red-600">Ditolak</span>
                                <?php else: ?>
                                    <span class="text-green-600">Dibayar</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500 italic">Tidak ada data pembayaran.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
