<?php
session_start();
include '../db_config.php'; // Adjust this path if necessary

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['hash'])) {
    die("Error: Missing transaction hash.");
}

$hash = mysqli_real_escape_string($conn, $_GET['hash']);
$user_id = $_SESSION['user_id'];

$query = "SELECT t.*, p.item_name, p.price 
          FROM transactions t 
          JOIN products p ON t.item_id = p.id 
          WHERE t.unique_hash = '$hash' AND t.seller_id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    die("Error: Unauthorized or invalid transaction.");
}

$trade = mysqli_fetch_assoc($result);

// MzansiTrade Security Logic
$time_window = floor(time() / 60); 
$secret_salt = "MzansiSecureSystem2026"; 
$security_token = hash_hmac('sha256', $hash . $time_window, $secret_salt);
$qr_data = $hash . "|" . $security_token; 
$qr_chart_url = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" . urlencode($qr_data);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Handshake | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root { --primary-teal: #0b3c4d; --accent-orange: #f28e2b; }
        body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
        .handshake-card { border-radius: 20px; border-top: 6px solid var(--accent-orange); }
        .qr-box { border: 2px dashed var(--primary-teal); border-radius: 12px; }
        .mzansi-btn { background-color: var(--primary-teal); color: white; }
    </style>
    <meta http-equiv="refresh" content="30">
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="container mt-5" style="max-width: 450px;">
        <div class="card handshake-card shadow-lg border-0 p-4 text-center">
            
            <h2 class="fw-bold" style="color: var(--primary-teal);">Secure Handshake</h2>
            <p class="text-muted small">Verify your meetup with the buyer</p>
            
            <div class="py-3">
                <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($trade['item_name']); ?></h5>
                <div class="fs-4 fw-bold mb-3" style="color: var(--accent-orange);">
                    R <?php echo number_format($trade['price'], 2); ?>
                </div>
            </div>
            
            <div class="bg-white p-3 d-inline-block mx-auto qr-box shadow-sm mb-4">
                <img src="<?php echo $qr_chart_url; ?>" alt="Transaction QR Code" class="img-fluid">
            </div>

            <div class="alert alert-warning border-0 small" style="background-color: rgba(242, 142, 43, 0.1); color: #856404;">
                <i class="bi bi-shield-check"></i> <strong>Important:</strong> Keep this screen open. Your unique code refreshes every 30 seconds for your safety.
            </div>
            
            <a href="../index.php" class="btn btn-outline-secondary btn-sm mt-2 border-0">Cancel Transaction</a>
        </div>
    </div>
</body>
</html>