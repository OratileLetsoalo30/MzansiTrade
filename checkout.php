<?php
session_start();
include 'db_config.php';

// 1. Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, send them to login before buying
    header("Location: auth/login.php"); 
    exit();
}

// 2. Catch the Item ID from the button click
if (!isset($_GET['item_id']) || empty($_GET['item_id'])) {
    die("
    <div class='container mt-5'>
        <div class='alert alert-danger text-center shadow-sm p-4 rounded-4' style='border-left: 5px solid #d9534f;'>
            <h5 class='fw-bold mb-1'>No Item Selected</h5>
            <p class='mb-0 small'>Please go back to the marketplace and select an item to purchase.</p>
            <a href='index.php' class='btn btn-dark mt-3 px-4'>Back to Home</a>
        </div>
    </div>");
}

$item_id = intval($_GET['item_id']);

// 3. Fetch the item details from the database to show the buyer
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("
    <div class='container mt-5'>
        <div class='alert alert-danger text-center shadow-sm p-4 rounded-4' style='border-left: 5px solid #d9534f;'>
            <h5 class='fw-bold mb-1'>Item Unavailable</h5>
            <p class='mb-0 small'>This item could not be found or has already been sold.</p>
            <a href='index.php' class='btn btn-dark mt-3 px-4'>Back to Home</a>
        </div>
    </div>");
}

$item = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Escrow Checkout | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        /* Custom UI for the checkout selection */
        .payment-option {
            border: 2px solid var(--border-subtle, #dee2e6);
            border-radius: 12px;
            padding: 16px 20px;
            transition: all 0.3s ease;
            background-color: #f8f9fa;
            cursor: pointer;
        }
        .payment-option:hover {
            border-color: #f28e2b;
            background-color: #fffaf6;
        }
        /* Highlight selected radio button container */
        .payment-option input[type="radio"]:checked + label {
            color: #0b3c4d;
        }
        .payment-option:has(input[type="radio"]:checked) {
            border-color: #0b3c4d;
            background-color: #f2f7f9;
            box-shadow: 0 4px 12px rgba(11, 60, 77, 0.08);
        }
        .escrow-banner {
            background: linear-gradient(135deg, rgba(242, 142, 43, 0.1) 0%, rgba(242, 142, 43, 0.02) 100%);
            border: 1px solid rgba(242, 142, 43, 0.3);
            border-radius: 12px;
        }
    </style>
</head>
<body class="mzansi-login-body" style="background-color: #f4f7f6;">

    <?php include 'header.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            
            <div class="col-md-8 col-lg-6 mzansi-auth-card bg-white p-4 p-md-5 position-relative overflow-hidden" style="border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08);">
                
                <div class="position-absolute top-0 end-0 p-3" style="opacity: 0.03; font-size: 100px; right: -20px !important; top: -30px !important; color: #0b3c4d; pointer-events: none;">
                    <i class="bi bi-shield-check"></i>
                </div>

                <div class="text-center mb-4 border-bottom pb-3">
                    <h3 class="fw-bold mb-1" style="font-family: 'Syne', sans-serif; color: #0b3c4d;">Secure Checkout</h3>
                    <p class="text-muted small">Lock in your purchase safely</p>
                </div>
                
                <div class="escrow-banner p-3 mb-4 d-flex align-items-center">
                    <i class="bi bi-shield-lock-fill fs-1 me-3" style="color: #f28e2b;"></i>
                    <div>
                        <h6 class="fw-bold mb-1" style="color: #0b3c4d;">MzansiTrade Smart Escrow</h6>
                        <p class="mb-0 small text-dark opacity-75" style="line-height: 1.4;">
                            Your money is held safely by us. It is only released to the seller after you meet and inspect the item face-to-face.
                        </p>
                    </div>
                </div>

                <div class="d-flex align-items-center mb-4 pb-4 border-bottom">
                    <div class="position-relative">
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" alt="Item" class="rounded-3 shadow-sm" style="width: 90px; height: 90px; object-fit: cover; border: 2px solid #e9ecef;">
                    </div>
                    
                    <div class="ms-3 w-100">
                        <h5 class="fw-bold mb-1" style="color: #0b3c4d;"><?php echo htmlspecialchars($item['item_name']); ?></h5>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-geo-alt-fill me-1" style="color: #f28e2b;"></i> Meetup: <?php echo htmlspecialchars($item['location']); ?>
                        </p>
                        <h4 class="fw-bold mb-0" style="color: #f28e2b;">
                            <span class="fs-6 text-muted me-1">Total:</span>R <?php echo number_format($item['price'], 2); ?>
                        </h4>
                    </div>
                </div>

                <form action="process_escrow.php" method="POST">
                    <input type="hidden" name="item_id" value="<?php echo $item['id']; ?>">
                    <input type="hidden" name="seller_id" value="<?php echo $item['user_id']; ?>">
                    <input type="hidden" name="amount" value="<?php echo $item['price']; ?>">
                    
                    <h6 class="fw-bold mb-3" style="color: #0b3c4d;">Select Payment Method</h6>
                    
                    <div class="payment-option mb-3 d-flex align-items-center">
                        <input class="form-check-input me-3 ms-1 shadow-none" type="radio" name="payment_method" id="eft" value="EFT" checked style="transform: scale(1.2); accent-color: #0b3c4d;">
                        <label class="form-check-label fw-bold w-100 d-flex justify-content-between align-items-center m-0" for="eft">
                            <span class="fs-6">Manual EFT (Bank Transfer)</span>
                            <i class="bi bi-bank2 fs-4" style="color: #0b3c4d;"></i>
                        </label>
                    </div>

                    <div class="payment-option mb-4 d-flex align-items-center">
                        <input class="form-check-input me-3 ms-1 shadow-none" type="radio" name="payment_method" id="qr" value="QR Code" style="transform: scale(1.2); accent-color: #0b3c4d;">
                        <label class="form-check-label fw-bold w-100 d-flex justify-content-between align-items-center m-0" for="qr">
                            <span class="fs-6">Scan to Pay (QR Code)</span>
                            <i class="bi bi-qr-code-scan fs-4" style="color: #f28e2b;"></i>
                        </label>
                    </div>

                    <button type="submit" class="btn w-100 py-3 fw-bold fs-5 text-white shadow-sm" style="background-color: #f28e2b; border-radius: 12px; transition: all 0.3s ease;" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
                        <i class="bi bi-lock-fill me-2"></i> Lock R <?php echo number_format($item['price'], 2); ?> in Escrow
                    </button>
                    
                    <p class="text-center text-muted small mt-3 mb-0">
                        <i class="bi bi-shield-check me-1 text-success"></i> Transaction secured by RSA banking standards.
                    </p>
                </form>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>