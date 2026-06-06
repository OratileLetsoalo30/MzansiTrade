<?php
session_start();
require_once '../config/db.php'; 

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php"); 
    exit();
}

$product_id = isset($_GET['item_id']) ? mysqli_real_escape_string($conn, $_GET['item_id']) : '0';

// 2. Handle POST (Processing Payment)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $reference_id = 'ESC-'.strtoupper(bin2hex(random_bytes(4))); 
    $status = 'pending'; 

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, transaction_type, status, reference_id, description) VALUES (?, ?, 'payment', ?, ?, ?)");
    $desc = "Escrow secured for Product ID: " . $product_id;
    $stmt->bind_param("idsss", $user_id, $amount, $status, $reference_id, $desc);
    
    $success = $stmt->execute();
    $error = $stmt->error;
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Escrow | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css"> <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .escrow-card { background: #fff; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 30px; }
        .btn-escrow { background-color: #0b3c4d; color: #fff; font-weight: 700; border: none; }
        .btn-escrow:hover { background-color: #061920; color: #fff; }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 escrow-card">
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <?php if ($success): ?>
                    <h3 style="color: #0b3c4d; font-family: 'Syne';">Payment Secured!</h3>
                    <p>Your escrow payment is now in <strong>pending</strong> status.</p>
                    <div class="alert alert-info">Reference: <strong><?php echo $reference_id; ?></strong></div>
                    <a href="../index.php" class="btn btn-escrow w-100">Back to Marketplace</a>
                <?php else: ?>
                    <h3 class="text-danger">Transaction Issue</h3>
                    <p>Error: <?php echo $error; ?></p>
                    <a href="process_escrow.php?item_id=<?php echo $product_id; ?>" class="btn btn-secondary w-100">Try Again</a>
                <?php endif; ?>
            <?php else: ?>
                <h3 style="color: #0b3c4d; font-family: 'Syne';">Secure Escrow Checkout</h3>
                <p>Complete your purchase for Item #<?php echo $product_id; ?></p>
                <form method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <div class="mb-3">
                        <label>Enter Amount (R)</label>
                        <input type="number" name="amount" class="form-control" required placeholder="0.00">
                    </div>
                    <button type="submit" class="btn btn-escrow w-100 py-3">Confirm & Secure Payment</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>