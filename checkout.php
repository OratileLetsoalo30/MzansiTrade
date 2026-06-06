<?php
session_start();
include 'config/db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php"); 
    exit();
}

$item_id = isset($_GET['item_id']) ? intval($_GET['item_id']) : 0;

$stmt = $conn->prepare("SELECT product_id, product_name, price, stock_quantity FROM products WHERE product_id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$item = $stmt->get_result()->fetch_assoc();

if (!$item) {
    die("<div class='container mt-5 text-center'><h3>Product not found.</h3><a href='index.php' class='btn btn-primary'>Return to Marketplace</a></div>");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Checkout | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f4f7f6; font-family: sans-serif; }
        .checkout-box { background: #fff; padding: 40px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); }
        .btn-escrow { background-color: #f4a41c; color: white; font-weight: 800; border-radius: 12px; }
        .price-text { color: #f4a41c; font-size: 1.5rem; font-weight: 800; }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 checkout-box">
                <h3 style="color: #0b3c4d;">Secure Checkout</h3>
                <hr>
                <h5><?php echo htmlspecialchars($item['product_name']); ?></h5>
                <p class="price-text">R <?php echo number_format($item['price'], 2); ?></p>
                
                <form action="escrow/process_escrow.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $item['product_id']; ?>">
                    <input type="hidden" name="amount" value="<?php echo $item['price']; ?>">
                    
                    <div class="mb-3">
                        <label class="fw-bold">Quantity</label>
                        <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?php echo $item['stock_quantity']; ?>">
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold">Payment Method</label>
                        <select name="payment_method" class="form-select" required>
                            <option value="EFT">Manual EFT</option>
                            <option value="QR">Scan-to-Pay (QR)</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-escrow w-100 py-3">Pay with Escrow</button>
                    <a href="index.php" class="btn btn-back w-100 py-2 text-decoration-none">← Back to Marketplace</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>