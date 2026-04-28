<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/db.php';
if (empty($_SESSION['user_id'])) {
    header('Location: /mzansitrade/login.php');
    exit;
}
?>
<h1>Your Dashboard</h1>
<p>Welcome, <?= esc($_SESSION['user_name'] ?? 'User') ?>.</p>
<p><a href="/mzansitrade/cart.php">View Cart</a></p>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
