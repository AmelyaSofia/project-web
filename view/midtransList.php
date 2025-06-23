<?php
require_once '../config/dbconnect.php';

$start_date = $_GET['start_date'] ?? '';
$end_date = $_GET['end_date'] ?? '';
$status = $_GET['status'] ?? '';
$page = $_GET['page'] ?? 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$query = "SELECT p.*, c.nama_client FROM pembayaran p 
          JOIN booking b ON p.id_booking = b.id_booking 
          JOIN client c ON b.id_client = c.id_client 
          WHERE p.bukti_pembayaran IS NULL";

$params = [];
$types = '';
if ($start_date) {
    $query .= " AND DATE(p.tanggal_pembayaran) >= ?";
    $params[] = $start_date;
    $types .= 's';
}
if ($end_date) {
    $query .= " AND DATE(p.tanggal_pembayaran) <= ?";
    $params[] = $end_date;
    $types .= 's';
}
if ($status) {
    $query .= " AND p.status_pembayaran = ?";
    $params[] = $status;
    $types .= 's';
}

$count_query = "SELECT COUNT(*) FROM ($query) AS total";
$stmt = $conn->prepare($count_query);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$stmt->bind_result($total_rows);
$stmt->fetch();
$stmt->close();

$total_pages = ceil($total_rows / $limit);
$query .= " ORDER BY p.tanggal_pembayaran DESC LIMIT $limit OFFSET $offset";
$stmt = $conn->prepare($query);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$pembayarans = [];
while ($row = $result->fetch_assoc()) {
    $pembayarans[] = $row;
}

$current_page = $page;
$modul = 'midtrans';
$fitur = 'list';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Pembayaran Midtrans</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FFF3E4] text-[#967E76] min-h-screen flex">

<?php include 'include/sidebar.php'; ?>

<main class="ml-64 p-6 w-full bg-white min-h-screen">
    <h1 class="text-3xl font-bold mb-6">Manajemen Pembayaran Midtrans</h1>

    <!-- Filter -->
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
                <a href="?modul=<?= $modul ?>&fitur=<?= $fitur ?>" class="text-[#967E76] hover:underline ml-2">Reset</a>
            <?php endif; ?>
        </form>
    </div>

    <!-- Tabel -->
    <div class="overflow-x-auto">
        <table class="w-full border text-sm text-left bg-white shadow-md rounded-xl">
            <thead class="bg-[#967E76] text-white">
                <tr>
                    <th class="p-3">ID</th>
                    <th class="p-3">Client</th>
                    <th class="p-3">Jenis</th>
                    <th class="p-3">Jumlah</th>
                    <th class="p-3">Tanggal</th>
                    <th class="p-3">Metode</th>
                    <th class="p-3">Status</th>
                    <th class="p-3">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($pembayarans)): ?>
                    <?php foreach ($pembayarans as $p): ?>
                        <tr class="border-t hover:bg-[#FFF3E4]">
                            <td class="p-3"><?= htmlspecialchars($p['id_pembayaran']) ?></td>
                            <td class="p-3"><?= htmlspecialchars($p['nama_client'] ?? '-') ?></td>
                            <td class="p-3"><?= strtoupper(htmlspecialchars($p['jenis'])) ?></td>
                            <td class="p-3">Rp <?= number_format($p['jumlah'], 0, ',', '.') ?></td>
                            <td class="p-3"><?= date('d/m/Y H:i', strtotime($p['tanggal_pembayaran'])) ?></td>
                            <td class="p-3"><?= htmlspecialchars($p['metode_pembayaran'] ?? '-') ?></td>
                            <td class="p-3">
                                <span class="<?= 
                                    $p['status_pembayaran'] === 'dibayar' ? 'text-green-600' : 
                                    ($p['status_pembayaran'] === 'ditolak' ? 'text-red-600' : 'text-yellow-600')
                                ?>">
                                    <?= ucfirst(htmlspecialchars($p['status_pembayaran'])) ?>
                                </span>
                            </td>
                            <td class="p-3 italic text-gray-500">Midtrans</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="p-4 text-center text-gray-500 italic">Tidak ada pembayaran Midtrans.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if ($total_pages > 1): ?>
        <div class="mt-6 flex justify-center items-center gap-2">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?<?= http_build_query([
                    'modul' => $modul,
                    'fitur' => $fitur,
                    'page' => $i,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'status' => $status
                ]) ?>" class="px-3 py-1 rounded <?= $i == $current_page ? 'bg-[#967E76] text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>
</main>
</body>
</html>
