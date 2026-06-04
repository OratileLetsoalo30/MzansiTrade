<?php
session_start();
include '../db_config.php';
include '../auth/auth_check.php';
include 'escrow_config.php';

if (!isset($_GET['hash'])) {
    header("Location: ../index.php");
    exit();
}

$hash = mysqli_real_escape_string($conn, $_GET['hash']);

// Get transaction details
$trans_stmt = $conn->prepare("
    SELECT t.*, p.item_name, p.price, u.username as seller_username
    FROM transactions t
    JOIN products p ON t.item_id = p.id
    JOIN users u ON t.seller_id = u.id
    WHERE t.unique_hash = ? AND t.buyer_id = ? AND t.status = ?
");

$status = ESCROW_STATUS_PAYMENT_PENDING;
$trans_stmt->bind_param("sis", $hash, $uid, $status);
$trans_stmt->execute();
$result = $trans_stmt->get_result();

if ($result->num_rows === 0) {
    die("Invalid transaction or already paid.");
}

$transaction = $result->fetch_assoc();
$trans_stmt->close();

$amount = floatval($transaction['price']);
$fee = calculateEscrowFee($amount);
$total = $amount + $fee;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-teal: #0B3C4D;
            --accent-orange: #FFA500;
            --success: #52B788;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-teal) 0%, #1a5f73 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .payment-container {
            max-width: 500px;
            margin: 40px auto;
        }
        
        .payment-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            overflow: hidden;
            border: none;
        }
        
        .payment-header {
            background: linear-gradient(135deg, var(--primary-teal), #1a5f73);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .payment-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin: 0;
        }
        
        .payment-body {
            padding: 30px;
        }
        
        .item-details {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--accent-orange);
        }
        
        .item-details p {
            margin: 8px 0;
            color: #666;
            font-size: 14px;
        }
        
        .item-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 10px;
        }
        
        .seller-info {
            font-size: 13px;
            color: #999;
        }
        
        .price-breakdown {
            margin-bottom: 25px;
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
        }
        
        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 15px;
        }
        
        .price-label {
            color: #666;
            font-weight: 500;
        }
        
        .price-value {
            color: var(--primary-teal);
            font-weight: 600;
        }
        
        .total-row {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid var(--accent-orange);
            font-size: 18px;
            font-weight: 700;
            color: var(--primary-teal);
        }
        
        .fee-info {
            background: rgba(255, 165, 0, 0.1);
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .payment-button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 12px;
        }
        
        .btn-pay {
            background: linear-gradient(135deg, var(--accent-orange), #ffb81c);
            color: white;
            box-shadow: 0 4px 15px rgba(255, 165, 0, 0.2);
        }
        
        .btn-pay:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 165, 0, 0.4);
        }
        
        .btn-pay:active {
            transform: translateY(0);
        }
        
        .btn-cancel {
            background: #f0f0f0;
            color: #666;
        }
        
        .btn-cancel:hover {
            background: #e0e0e0;
        }
        
        .loading-spinner {
            display: none;
            margin-right: 8px;
        }
        
        .security-badge {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: var(--success);
            font-size: 13px;
            margin-top: 15px;
            font-weight: 500;
        }
        
        .alert-info {
            background: rgba(11, 60, 77, 0.1);
            border-left: 4px solid var(--primary-teal);
            color: var(--primary-teal);
            border: none;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="payment-container">
        <div class="payment-card">
            <div class="payment-header">
                <h1><i class="bi bi-lock-fill"></i> Secure Payment</h1>
                <p class="mb-0 mt-2" style="opacity: 0.9;">MzansiTrade Escrow Protection</p>
            </div>

            <div class="payment-body">
                <!-- Item Details -->
                <div class="item-details">
                    <div class="item-name"><?php echo htmlspecialchars($transaction['item_name']); ?></div>
                    <p class="seller-info"><i class="bi bi-shop"></i> Seller: <?php echo htmlspecialchars($transaction['seller_username']); ?></p>
                </div>

                <!-- Fee Info -->
                <div class="fee-info">
                    <i class="bi bi-info-circle"></i>
                    <span>MzansiTrade charges a small platform fee for escrow protection (2.5% minimum R1.50)</span>
                </div>

                <!-- Price Breakdown -->
                <div class="price-breakdown">
                    <div class="price-row">
                        <span class="price-label">Item Price</span>
                        <span class="price-value">R <?php echo number_format($amount, 2); ?></span>
                    </div>
                    <div class="price-row">
                        <span class="price-label">Platform Fee</span>
                        <span class="price-value">R <?php echo number_format($fee, 2); ?></span>
                    </div>
                    <div class="total-row">
                        <span>Total Amount</span>
                        <span>R <?php echo number_format($total, 2); ?></span>
                    </div>
                </div>

                <!-- Payment Method (Mock) -->
                <div class="alert alert-info small mb-3">
                    <i class="bi bi-info-circle"></i> <strong>Mock Payment Mode:</strong> 
                    This is a demonstration. In production, you'll see Stripe, PayPal, or other payment options.
                </div>

                <!-- Payment Buttons -->
                <button class="payment-button btn-pay" onclick="processPayment()" id="payBtn">
                    <span class="loading-spinner" id="spinner">
                        <span class="spinner-border spinner-border-sm"></span>
                    </span>
                    <span id="btnText"><i class="bi bi-check-circle"></i> Complete Mock Payment</span>
                </button>

                <a href="escrow_status.php?hash=<?php echo $hash; ?>" class="payment-button btn-cancel">
                    View Transaction Status
                </a>

                <!-- Security Badge -->
                <div class="security-badge">
                    <i class="bi bi-shield-check" style="color: var(--success); font-size: 16px;"></i>
                    Secured by MzansiTrade Escrow
                </div>

                <!-- Important Notice -->
                <div class="alert alert-warning mt-4 small border-0" style="background-color: rgba(242, 142, 43, 0.1);">
                    <i class="bi bi-exclamation-triangle"></i> <strong>Important:</strong>
                    <ul class="mb-0 mt-2" style="padding-left: 20px;">
                        <li>Your funds are held securely in escrow</li>
                        <li>Only released after QR code handshake confirmation</li>
                        <li>You can cancel up to 24 hours before meeting</li>
                        <li>Meet the seller in a safe public location</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        const hash = "<?php echo $hash; ?>";

        async function processPayment() {
            const btn = document.getElementById('payBtn');
            const spinner = document.getElementById('spinner');
            const btnText = document.getElementById('btnText');

            // Disable button and show spinner
            btn.disabled = true;
            spinner.style.display = 'inline-block';
            btnText.textContent = ' Processing payment...';

            try {
                const response = await fetch('process_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `hash=${encodeURIComponent(hash)}&payment_method=mock`
                });

                const data = await response.json();

                if (data.success) {
                    alert('Payment processed! Redirecting to QR handshake...');
                    window.location.href = data.next_url;
                } else {
                    alert('Payment failed: ' + data.error);
                    btn.disabled = false;
                    spinner.style.display = 'none';
                    btnText.textContent = '<i class="bi bi-check-circle"></i> Complete Mock Payment';
                }
            } catch (error) {
                alert('Error: ' + error.message);
                btn.disabled = false;
                spinner.style.display = 'none';
                btnText.textContent = '<i class="bi bi-check-circle"></i> Complete Mock Payment';
            }
        }
    </script>
</body>
</html>