<?php
session_start();
// Include your DB connection if needed:
// include '../config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
include dirname(__DIR__) . '/includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My History | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Plus Jakarta Sans', sans-serif; }
        .history-card { background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .table-header { background-color: #0b3c4d; color: #ffffff; }
        .btn-back { border: 2px solid #0b3c4d; color: #0b3c4d; font-weight: 700; border-radius: 12px; }
        .btn-back:hover { background-color: #0b3c4d; color: #ffffff; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="history-card">
                    <h3 class="mb-4" style="color: #0b3c4d; font-family: 'Syne', sans-serif;">My Transaction History</h3>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-header">
                                <tr>
                                    <th class="ps-3">Item Name</th>
                                    <th>Date</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="ps-3 fw-bold">Black Steve Madden Heels</td>
                                    <td>05 June 2026</td>
                                    <td>R 1 000</td>
                                    <td><span class="badge bg-warning text-dark">In Escrow</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <a href="../index.php" class="btn btn-back mt-3 px-4">← Back to Marketplace</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>