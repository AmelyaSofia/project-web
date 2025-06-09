<?php
session_start();

if (!isset($_SESSION['id_client'])) {
    header("Location: index.php");
    exit();
}

require_once __DIR__ . '/../model/bookingModel.php';
require_once __DIR__ . '/../model/clientModel.php';
require_once __DIR__ . '/../model/stylistModel.php';
require_once __DIR__ . '/../model/layananModel.php';

$bookingModel = new ModelBooking();
$clientModel = new ModelClient();
$stylistModel = new ModelStylist();
$layananModel = new ModelLayanan();

$id_client = $_SESSION['id_client'];

$riwayatBookings = $bookingModel->getBookingByClient($id_client);

$clientData = $clientModel->getClientById($id_client);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Booking - Royal Beauty</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"  rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> 
    <style>
        body {
            background-color: #f9f9f9;
        }
        .history-card {
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .status-badge {
            font-size: 0.85rem;
            padding: 5px 10px;
            border-radius: 5px;
        }
        .status-menunggu {
            background-color: #ffc107;
            color: #000;
        }
        .status-diterima {
            background-color: #28a745;
            color: #fff;
        }
        .status-ditolak {
            background-color: #dc3545;
            color: #fff;
        }
        .btn-custom {
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#"><img src="../image/logo.png" alt="Logo" width="30"> Royal Beauty</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="clientDashboard.php"><i class="fas fa-home me-1"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#"><i class="fas fa-history me-1"></i> Riwayat Booking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt me-1"></i> Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="container py-5">
    <h2 class="mb-4">Riwayat Booking Anda</h2>
    
    <?php if (!empty($clientData)): ?>
        <p>Halo, <strong><?= htmlspecialchars($clientData['nama_client']) ?></strong>. Berikut adalah riwayat booking Anda:</p>
    <?php endif; ?>

    <?php if (!empty($riwayatBookings)): ?>
        <div class="row g-3">
            <?php foreach ($riwayatBookings as $booking): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card history-card">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($booking['nama_layanan']) ?></h5>
                            <p class="card-text">
                                <i class="fas fa-user me-2"></i><strong>Stylist:</strong> <?= htmlspecialchars($booking['nama_stylist']) ?><br>
                                <i class="far fa-calendar-alt me-2"></i><strong>Tanggal:</strong> <?= date('d M Y', strtotime($booking['tanggal'])) ?><br>
                                <i class="far fa-clock me-2"></i><strong>Waktu:</strong> <?= htmlspecialchars($booking['waktu']) ?><br>
                                <i class="fas fa-info-circle me-2"></i><strong>Status:</strong>
                                <span class="status-badge 
                                    <?= $booking['status'] == 'menunggu' ? 'status-menunggu' : 
                                       ($booking['status'] == 'diterima' ? 'status-diterima' : 'status-ditolak') ?>">
                                    <?= ucfirst(htmlspecialchars($booking['status'])) ?>
                                </span><br>
                                <i class="fas fa-comment me-2"></i><strong>Catatan:</strong> <?= htmlspecialchars($booking['catatan'] ?: '-') ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-3">Belum ada riwayat booking.</div>
    <?php endif; ?>
</div>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container text-center">
        <p class="mb-0">&copy; 2025 Royal Beauty Salon. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> 
</body>
</html>