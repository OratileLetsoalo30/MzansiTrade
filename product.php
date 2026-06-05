<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'config/db.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$product_id = intval($_GET['id']);

// Query matching your exact column names: seller_id, product_id, product_name
$query = "
    SELECT p.*, u.username AS seller_name, u.is_verified AS seller_verified 
    FROM products p 
    LEFT JOIN users u ON p.seller_id = u.id 
    WHERE p.product_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='container mt-5 alert alert-danger'>Product listing not found.</div>";
    exit();
}

$product = $result->fetch_assoc();

if (isset($_POST['buy_escrow_btn'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: auth/login.php");
        exit();
    }

    $buyer_id = $_SESSION['user_id'];
    $seller_id = $product['seller_id']; 
    $price = $product['price'];

    if ($buyer_id == $seller_id) {
        echo "<script>alert('Security: You cannot buy your own product.');</script>";
    } else {
        $escrow_stmt = $conn->prepare("INSERT INTO transactions (product_id, buyer_id, seller_id, amount, status) VALUES (?, ?, ?, ?, 'held_in_escrow')");
        $escrow_stmt->bind_param("iiid", $product_id, $buyer_id, $seller_id, $price);
        
        if ($escrow_stmt->execute()) {
            echo "<script>alert('Cash secured in Escrow!'); window.location='pages/profile.php';</script>";
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['product_name']); ?> | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body style="background-color: #f8f9fa;">

    <?php include 'includes/header.php'; ?>

    <div class="container mt-5 mb-5">
        <div class="mb-4">
            <a href="index.php" class="text-decoration-none small text-secondary">
                <i class="bi bi-arrow-left pe-1"></i> Back to Marketplace
            </a>
        </div>

        <div class="row g-5">
            <div class="col-md-6">
                <div class="p-2 bg-white shadow-sm rounded-4">
                    <img src="<?php echo htmlspecialchars($product['image_path']); ?>" class="img-fluid rounded" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                </div>
            </div>

            <div class="col-md-6">
                <div class="p-4 bg-white rounded-4 shadow-sm text-start">
                    <h1 class="fw-bold mb-3"><?php echo htmlspecialchars($product['product_name']); ?></h1>
                    <div class="mb-4">
                        <span class="fs-2 fw-bold" style="color: #f28e2b;">R <?php echo number_format($product['price'], 2); ?></span>
                    </div>
                    <hr>
                    <h5 class="fw-bold mb-2">Description</h5>
                    <p class="text-secondary mb-4"><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                    
                    <form method="POST">
                        <button type="submit" name="buy_escrow_btn" class="btn w-100 py-3 fw-bold text-white shadow-sm rounded-3" style="background-color: #0b3c4d; font-size: 1.1rem;">
                            <i class="bi bi-lock-fill me-2"></i> Buy with Escrow Protection
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>