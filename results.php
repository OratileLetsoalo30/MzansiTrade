<?php
include 'config/db.php';
$query = mysqli_real_escape_string($conn, $_GET['q'] ?? '');
$sql = "SELECT * FROM products WHERE item_name LIKE '%$query%'";
$results = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container">
        <h3>Results for: <?php echo htmlspecialchars($query); ?></h3>
        <a href="index.php" class="btn btn-secondary mb-3">← Back to Market</a>
        <div class="row">
            <?php if(mysqli_num_rows($results) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($results)): ?>
                    <div class="col-md-4 mb-3"><div class="card p-3"><?php echo $row['item_name']; ?></div></div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No items found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>