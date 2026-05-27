<?php
// includes/header.php
session_start();

$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="MzansiTrade — Cape Town's secure C2C marketplace for local traders." />
  <title><?= isset($page_title) ? htmlspecialchars($page_title) . ' | MzansiTrade' : 'MzansiTrade — Buy & Sell Locally in Cape Town' ?></title>

  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css" />

  <!-- Main stylesheet -->
  <link rel="stylesheet" href="/mzansitrade/css/style.css"  />
</head>

<body>

<!-- ── NAVBAR ─────────────────────────────────────────── -->
<header>
  <nav class="navbar">
    <div class="container navbar-inner">

      <!-- Logo -->
      <a href="index.php" class="logo">
        <div class="logo-icon">
          <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 12L12 3l9 9v9a1 1 0 01-1 1H4a1 1 0 01-1-1v-9z"/>
          </svg>
        </div>
        <span class="logo-wordmark">Mzansi<span>Trade</span></span>
      </a>

      <!-- Desktop nav links -->
      <ul class="nav-links">
        <li><a href="listings.php" class="<?= $current_page === 'listings' ? 'active' : '' ?>">Browse</a></li>
        <li><a href="sell.php" class="<?= $current_page === 'sell' ? 'active' : '' ?>">Sell</a></li>
        <li><a href="how.php" class="<?= $current_page === 'how' ? 'active' : '' ?>">How it works</a></li>
        <li><a href="areas.php" class="<?= $current_page === 'areas' ? 'active' : '' ?>">Areas</a></li>
      </ul>

      <!-- 🔥 UPDATED SEARCH (NOW FUNCTIONAL) -->
      <form class="nav-search" role="search" action="search.php" method="GET">

        <i class="ti ti-search" aria-hidden="true"></i>

        <!-- keyword search -->
        <input 
          type="text" 
          name="query"
          placeholder="Search sneakers, wigs, iPhones..." 
          aria-label="Search listings"
          required
        />

        <!-- optional category -->
        <select name="category" class="nav-select">
          <option value="">All</option>
          <option value="sneakers">Sneakers</option>
          <option value="hair">Wigs</option>
          <option value="devices">Devices</option>
        </select>

        <!-- optional location -->
        <select name="location" class="nav-select">
          <option value="">Location</option>
          <option value="cape_town">Cape Town</option>
          <option value="johannesburg">Johannesburg</option>
          <option value="durban">Durban</option>
          <option value="pretoria">Pretoria</option>
        </select>

        <button type="submit" class="nav-search-btn">Go</button>

      </form>

      <!-- Auth buttons -->
      <div class="nav-actions">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="dashboard.php" class="btn btn-ghost btn-sm">
            <i class="ti ti-user"></i> Account
          </a>
          <a href="logout.php" class="btn btn-teal btn-sm">Log out</a>
        <?php else: ?>
          <a href="login.php" class="btn btn-ghost btn-sm">Log in</a>
          <a href="register.php" class="btn btn-teal btn-sm">Start selling</a>
        <?php endif; ?>
      </div>

      <!-- Mobile menu button -->
      <button class="hamburger" id="hamburger" aria-label="Open menu">
        <i class="ti ti-menu-2"></i>
      </button>

    </div>
  </nav>

  <!-- MOBILE MENU -->
  <nav class="mobile-menu" id="mobile-menu">
    <a href="listings.php">Browse</a>
    <a href="sell.php">Sell</a>
    <a href="how.php">How it works</a>
    <a href="areas.php">Areas</a>

    <?php if (isset($_SESSION['user_id'])): ?>
      <a href="dashboard.php">My account</a>
      <a href="logout.php">Log out</a>
    <?php else: ?>
      <a href="login.php">Log in</a>
      <a href="register.php">Create account</a>
    <?php endif; ?>
  </nav>
</header>