<?php
require_once __DIR__ . '/../includes/header.php';
require_once __DIR__ . '/../config/db.php';
if (empty($_SESSION['user_id']) || empty($_SESSION['is_admin'])) { header('Location: /mzansitrade/login.php'); exit; }
$stmt = $pdo->query('SELECT id, name, price, created_at FROM products ORDER BY id DESC');
$products = $stmt->fetchAll();
?>
<h1>Products (Admin)</h1>
<p><em>CRUD operations not implemented in this example. Use these as a starting point.</em></p>
<table>
  <thead><tr><th>ID</th><th>Name</th><th>Price</th><th>Added</th></tr></thead>
  <tbody>
  <?php foreach ($products as $p): ?>
    <tr>
      <td><?= esc($p['id']) ?></td>
      <td><?= esc($p['name']) ?></td>
      <td>R<?= number_format($p['price'], 2) ?></td>
      <td><?= esc($p['created_at']) ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
