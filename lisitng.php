<?php
session_start();
include 'config/db.php'; 
include 'includes/header.php';

// Fetch all items from products, using your correct database column names
$query = "SELECT * FROM products ORDER BY product_id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>MzansiTrade | Marketplace</title>
</head>
<body style="background-color: #f8f9fa;">

<div class="container mt-5">
    <h2 class="fw-bold mb-4" style="color: #0b3c4d;">Latest Listings</h2>
    <div class="row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($item = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <!-- Points to your uploads/ folder -->
                        <img src="<?php echo htmlspecialchars($item['image_path']); ?>" class="card-img-top" alt="Product" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title fw-bold" style="color: #0b3c4d;"><?php echo htmlspecialchars($item['product_name']); ?></h5>
                            <p class="card-text fw-bold" style="color: #f28e2b;">R <?php echo number_format($item['price'], 2); ?></p>
                            <a href="product.php?id=<?php echo $item['product_id']; ?>" class="btn w-100" style="background-color: #0b3c4d; color: white;">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <p class="text-muted">No items are currently listed.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>