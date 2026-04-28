<?php
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$action = $_POST['action'] ?? $_GET['action'] ?? '';
if ($action === 'add' && !empty($_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    if (isset($_SESSION['cart'][$pid])) $_SESSION['cart'][$pid] += $qty; else $_SESSION['cart'][$pid] = $qty;
    header('Location: /mzansitrade/cart.php'); exit;
}
if ($action === 'remove' && !empty($_GET['product_id'])) {
    $pid = (int)$_GET['product_id'];
    unset($_SESSION['cart'][$pid]);
    header('Location: /mzansitrade/cart.php'); exit;
}

$cart_items = [];
if (!empty($_SESSION['cart'])) {
    $ids = array_map('intval', array_keys($_SESSION['cart']));
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = $pdo->prepare("SELECT id, name, price FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $rows = $stmt->fetchAll();
    foreach ($rows as $r) {
        $cid = $r['id'];
        $cart_items[] = ['product' => $r, 'qty' => $_SESSION['cart'][$cid]];
    }
}
?>
<h1>Your Cart</h1>
<?php if (empty($cart_items)): ?>
  <p>Your cart is empty.</p>
<?php else: ?>
  <table>
    <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Action</th></tr></thead>
    <tbody>
    <?php $total = 0; foreach ($cart_items as $it): $p = $it['product']; $qty = $it['qty']; $line = $p['price'] * $qty; $total += $line; ?>
      <tr>
        <td><?= esc($p['name']) ?></td>
        <td><?= $qty ?></td>
        <td>R<?= number_format($line, 2) ?></td>
        <td><a href="/mzansitrade/cart.php?action=remove&product_id=<?= esc($p['id']) ?>">Remove</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
    <tfoot><tr><td colspan="2">Total</td><td colspan="2">R<?= number_format($total, 2) ?></td></tr></tfoot>
  </table>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
