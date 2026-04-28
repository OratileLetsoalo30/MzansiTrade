<?php
if (session_status() === PHP_SESSION_NONE) session_start();
function esc($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
$logged = !empty($_SESSION['user_id']);
$is_admin = !empty($_SESSION['is_admin']);
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>MzansiTrade</title>
  <link rel="stylesheet" href="/mzansitrade/assets/css/style.css">
  <meta name="robots" content="noindex,nofollow">
</head>
<body>
<header>
  <nav>
    <a href="/mzansitrade/index.php">Home</a> |
    <a href="/mzansitrade/product.php">Products</a> |
    <a href="/mzansitrade/cart.php">Cart</a>
    <?php if ($logged): ?>
      | <a href="/mzansitrade/dashboard.php">Dashboard</a>
      | <a href="/mzansitrade/logout.php">Logout</a>
    <?php else: ?>
      | <a href="/mzansitrade/login.php">Login</a>
      | <a href="/mzansitrade/register.php">Register</a>
    <?php endif; ?>
    <?php if ($is_admin): ?> | <a href="/mzansitrade/admin/dashboard.php">Admin</a><?php endif; ?>
  </nav>
</header>
<main>
