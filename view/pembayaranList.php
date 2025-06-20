<?php
$modul = $_GET['modul'] ?? 'pembayaran';
$fitur = $_GET['fitur'] ?? 'manajemen';
$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$status = $_GET['status'] ?? '';
?>

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

    <?php if (isset($message)): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Filter Form -->
    <div class="mb-6 bg-[#FFF3E4] p-4 rounded-lg shadow">
        <form method="get" class="flex items-end gap-4">
            <input type="hidden" name="modul" value="<?= htmlspecialchars($modul) ?>">
            <input type="hidden" name="fitur" value="<?= htmlspecialchars($fitur) ?>">
            
            <div>
                <label class="block text-sm font-medium mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="<?= htmlspecialchars($start_date) ?>" class="p-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="<?= htmlspecialchars($end_date) ?>" class="p-2 border rounded">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Status</label>
                <select name="status" class="p-2 border rounded">
                    <option value="">Semua Status</option>
                    <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="dibayar" <?= $status === 'dibayar' ? 'selected' : '' ?>>Dibayar</option>
                    <option value="ditolak" <?= $status === 'ditolak' ? 'selected' : '' ?>>Ditolak</option>
                </select>
            </div>
            <button type="submit" class="bg-[#967E76] text-white px-4 py-2 rounded hover:bg-[#75655e]">Filter</button>
            <?php if (!empty($start_date) || !empty($end_date) || !empty($status)): ?>
                <a href="?modul=<?= $modul ?>&fitur=<?= $fitur ?>" class="text-[#967E76] hover:underline ml-2">Reset Filter</a>
            <?php endif; ?>
        </form>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full border text-sm text-left bg-white shadow-md rounded-xl">
            <thead class="bg-[#967E76] text-white">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Client</th>
                    <th class="p-3">Jenis</th>
                    <th class="p-3">Jumlah</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Bukti</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pembayarans)): ?>
                    <?php foreach ($pembayarans as $p): ?>
                        <tr class="border-t hover:bg-[#FFF3E4]">
                            <td class="p-3"><?= htmlspecialchars($p['id_pembayaran']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($p['nama_client'] ?? 'Tidak diketahui') ?></td>
                            <td class="p-3"><?= ucfirst(htmlspecialchars($p['jenis'])) ?></td>
                            <td class="p-3">Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></td>
                            <td class="p-3"><?= date('d/m/Y H:i', strtotime($p['tanggal_pembayaran'])) ?></td>
                            <td class="p-3">
                                <?php if (!empty($p['bukti_pembayaran'])): ?>
                                    <a href="uploads/<?= htmlspecialchars($p['bukti_pembayaran']) ?>" target="_blank" class="text-blue-500 hover:underline">
                                        Lihat Bukti
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-400 italic">Tidak ada bukti</span>
                                <?php endif; ?>
                            </td>
                            <td class="p-3">
                                <span class="<?= 
                                    $p['status_pembayaran'] === 'dibayar' ? 'text-green-600' : 
                                    ($p['status_pembayaran'] === 'ditolak' ? 'text-red-600' : 'text-yellow-600')
                                ?>">
                                    <?= ucfirst(htmlspecialchars($p['status_pembayaran'])) ?>
                                </span>
                                <?php if ($p['status_pembayaran'] === 'ditolak' && !empty($p['alasan_penolakan'])): ?>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($p['alasan_penolakan']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="p-3 space-x-2">
                                <?php if ($p['status_pembayaran'] === 'pending'): ?>
                                    <form method="POST" action="index.php?modul=pembayaran&fitur=verifikasi&id=<?= $p['id_pembayaran'] ?>" class="inline">
                                        <input type="hidden" name="page" value="<?= $current_page ?>">
                                        <input type="hidden" name="start_date" value="<?= htmlspecialchars($start_date) ?>">
                                        <input type="hidden" name="end_date" value="<?= htmlspecialchars($end_date) ?>">
                                        <input type="hidden" name="status" value="<?= htmlspecialchars($status) ?>">
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
                        <td colspan="8" class="p-4 text-center text-gray-500 italic">Tidak ada data pembayaran.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
        <div class="mt-6 flex justify-center items-center gap-2">
            <?php if ($current_page > 1): ?>
                <a href="?<?= 
                    http_build_query([
                        'modul' => $modul,
                        'fitur' => $fitur,
                        'page' => 1,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'status' => $status
                    ]) 
                ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">&laquo; First</a>
                <a href="?<?= 
                    http_build_query([
                        'modul' => $modul,
                        'fitur' => $fitur,
                        'page' => $current_page - 1,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'status' => $status
                    ]) 
                ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Prev</a>
            <?php endif; ?>

            <?php 
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);
            
            if ($start_page > 1): ?>
                <span class="px-3 py-1">...</span>
            <?php endif;
            
            for ($i = $start_page; $i <= $end_page; $i++): ?>
                <a href="?<?= 
                    http_build_query([
                        'modul' => $modul,
                        'fitur' => $fitur,
                        'page' => $i,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'status' => $status
                    ]) 
                ?>" class="px-3 py-1 rounded <?= $i == $current_page ? 'bg-[#967E76] text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
                    <?= $i ?>
                </a>
            <?php endfor;
            
            if ($end_page < $total_pages): ?>
                <span class="px-3 py-1">...</span>
            <?php endif; ?>

            <?php if ($current_page < $total_pages): ?>
                <a href="?<?= 
                    http_build_query([
                        'modul' => $modul,
                        'fitur' => $fitur,
                        'page' => $current_page + 1,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'status' => $status
                    ]) 
                ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Next</a>
                <a href="?<?= 
                    http_build_query([
                        'modul' => $modul,
                        'fitur' => $fitur,
                        'page' => $total_pages,
                        'start_date' => $start_date,
                        'end_date' => $end_date,
                        'status' => $status
                    ]) 
                ?>" class="px-3 py-1 bg-gray-200 rounded hover:bg-gray-300">Last &raquo;</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>