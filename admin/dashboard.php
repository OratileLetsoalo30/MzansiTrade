<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/db.php';
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: /mzansitrade/login.php'); exit;
}
?>
<h1>Admin Dashboard</h1>
<p>Welcome, <?= esc($_SESSION['user_name'] ?? 'Admin') ?>.</p>
<ul>
  <li><a href="/mzansitrade/admin/users.php">Manage Users</a></li>
  <li><a href="/mzansitrade/admin/products.php">Manage Products</a></li>
</ul>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
