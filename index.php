<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/db.php';
// simple product list
$stmt = $pdo->query('SELECT id, name, price FROM products ORDER BY id DESC LIMIT 12');
$products = $stmt->fetchAll();
?>
<h1>Welcome to MzansiTrade</h1>
<?php if ($products): ?>
  <ul class="products">
  <?php foreach ($products as $p): ?>
    <li>
      <a href="/mzansitrade/product.php?id=<?= esc($p['id']) ?>"><?= esc($p['name']) ?></a>
      — R<?= number_format($p['price'], 2) ?>
    </li>
  <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>No products found.</p>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
