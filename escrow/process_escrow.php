<?php
session_start();
include 'db_config.php';

// 1. Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit();
}

// 2. Process form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $buyer_id = $_SESSION['user_id'];
    $seller_id = intval($_POST['seller_id']);
    $product_id = intval($_POST['item_id']);
    $amount = floatval($_POST['amount']);
    $payment_method = $_POST['payment_method'];

    // Save the transaction to our escrow table
    $stmt = $conn->prepare("INSERT INTO escrow_transactions (buyer_id, seller_id, product_id, amount, payment_method) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiids", $buyer_id, $seller_id, $product_id, $amount, $payment_method);
    
    if ($stmt->execute()) {
        $transaction_id = $stmt->insert_id;
        $success = true;
    } else {
        $success = false;
        $error = "Database error. Could not initiate escrow process securely.";
    }
    $stmt->close();
} else {
    // If someone tries to access this page directly without submitting the form, redirect home
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complete Escrow Securely | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .reference-box {
            background-color: #fffaf6;
            border: 2px dashed #f28e2b !important;
            border-radius: 12px;
        }
        .bank-detail-item {
            border-bottom: 1px solid #edf2f4;
            padding: 10px 0;
        }
        .bank-detail-item:last-child {
            border-bottom: none;
        }
        .qr-wrapper {
            border: 3px solid #0b3c4d;
            border-radius: 16px;
            padding: 15px;
            display: inline-block;
            background: #ffffff;
            box-shadow: 0 8px 20px rgba(11, 60, 77, 0.06);
        }
    </style>
</head>
<body style="background-color: #f4f7f6;">

    <?php include 'header.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-5">
                
                <?php if (isset($success) && $success): ?>
                    
                    <div class="card border-0 shadow-sm p-4 p-md-5 bg-white position-relative overflow-hidden" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
                        
                        <div class="position-absolute top-0 start-0 w-100" style="height: 6px; background-color: #f28e2b;"></div>

                        <div class="text-center mb-4">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px; background-color: rgba(11, 60, 77, 0.06);">
                                <i class="bi bi-shield-fill-check" style="font-size: 35px; color: #0b3c4d;"></i>
                            </div>
                            <h3 class="fw-bold mb-1" style="color: #0b3c4d; font-family: 'Syne', sans-serif;">Escrow Secured</h3>
                            <p class="text-muted small">Transaction Order: <span class="fw-bold" style="color: #0b3c4d;">#MZ-<?php echo 1000 + $transaction_id; ?></span></p>
                        </div>
                        
                        <div class="p-3 text-center mb-4 rounded-3" style="background-color: #0b3c4d; color: #ffffff;">
                            <span class="small text-white-50 text-uppercase tracking-wider">Amount to Deposit</span>
                            <h