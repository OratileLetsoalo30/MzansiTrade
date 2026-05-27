<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/db.php';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    // show list fallback
    $stmt = $pdo->query('SELECT id, name, price FROM products ORDER BY id DESC LIMIT 20');
    $products = $stmt->fetchAll();
    ?>
    <h1>Products</h1>
    <ul class="products">
    <?php foreach ($products as $p): ?>
      <li><a href="/mzansitrade/product.php?id=<?= esc($p['id']) ?>"><?= esc($p['name']) ?></a> — R<?= number_format($p['price'], 2) ?></li>
    <?php endforeach; ?>
    </ul>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
$stmt = $pdo->prepare('SELECT id, name, description, price FROM products WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) {
    echo '<p>Product not found.</p>';
    require_once __DIR__ . '/includes/footer.php';
    exit;
}
?>
<h1><?= esc($product['name']) ?></h1>
<p><?= nl2br(esc($product['description'] ?? '')) ?></p>
<p>Price: R<?= number_format($product['price'], 2) ?></p>
<form method="post" action="/mzansitrade/cart.php">
  <input type="hidden" name="action" value="add">
  <input type="hidden" name="product_id" value="<?= esc($product['id']) ?>">
  <label>Quantity <input type="number" name="qty" value="1" min="1"></label>
  <button type="submit">Add to cart</button>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
