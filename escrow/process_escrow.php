<?php
session_start();
// This path is correct because process_escrow is in the /escrow folder
require_once '../config/db.php'; 

// 1. Security Check
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php"); 
    exit();
}

// 2. Handle POST (Processing Payment)
$success = false;
$error = "";
$reference_id = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $amount = filter_var($_POST['amount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $product_id = intval($_POST['product_id']);
    
    // Generate unique reference
    $reference_id = 'ESC-'.strtoupper(bin2hex(random_bytes(4))); 
    $status = 'pending'; 

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, transaction_type, status, reference_id, description) VALUES (?, ?, 'payment', ?, ?, ?)");
    $desc = "Escrow secured for Product ID: " . $product_id;
    $stmt->bind_param("idsss", $user_id, $amount, $status, $reference_id, $desc);
    
    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = $stmt->error;
    }
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
    <link rel="stylesheet" href="../assets/css/style.css"> 
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .escrow-card { background: #fff; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 30px; }
        .btn-escrow { background-color: #163f4c; color: #ffffff; font-weight: 700; border: none; transition: 0.3s; }
        .btn-escrow:hover { background-color: #f4a41c; color: #163f4c; }
        h3 { color: #163f4c; font-family: 'Syne', sans-serif; }
    </style>
</head>
<body>

<?php include '../includes/header.php'; ?>

<div class="container py-5 mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6 escrow-card">
            <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
                <?php if ($success): ?>
                    <h3>Payment Secured!</h3>
                    <p>Your escrow payment is now in <strong>pending</strong> status.</p>
                    <div class="alert" style="background-color: #f2f7f9; border-left: 5px solid #163f4c; color: #163f4c;">
                        Reference: <strong><?php echo $reference_id; ?></strong>
                    </div>
                    <a href="../index.php" class="btn btn-escrow w-100">Back to Marketplace</a>
                <?php else: ?>
                    <h3 class="text-danger">Transaction Issue</h3>
                    <p>Error: <?php echo htmlspecialchars($error); ?></p>
                    <a href="../checkout.php?item_id=<?php echo isset($_POST['product_id']) ? intval($_POST['product_id']) : 0; ?>" class="btn btn-secondary w-100">Try Again</a>
                <?php endif; ?>
            <?php else: ?>
                <h3>Direct Access Restricted</h3>
                <p>Please complete the checkout process from the product page.</p>
                <a href="../index.php" class="btn btn-escrow w-100">Back to Marketplace</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
</body>
</html>