<?php
// index.php — MzansiTrade Homepage
session_start();

$page_title = 'Buy & Sell Locally in Cape Town';

// ── Sample listings data (replace with DB query later) ──────
// Example: $listings = fetch_listings_from_db(limit: 8);
$featured_listings = [
  [
    'id'       => 1,
    'title'    => 'Brazilian Weave Bundle',
    'price'    => 320,
    'area'     => 'Wynberg',
    'category' => 'hair',
    'emoji'    => '💇',
    'verified' => true,
  ],
  [
    'id'       => 2,
    'title'    => 'Nike Air Force 1 — Size 9',
    'price'    => 850,
    'area'     => 'Claremont',
    'category' => 'shoes',
    'emoji'    => '👟',
    'verified' => true,
  ],
  [
    'id'       => 3,
    'title'    => 'Samsung A54 — Good Condition',
    'price'    => 3200,
    'area'     => 'Rondebosch',
    'category' => 'devices',
    'emoji'    => '📱',
    'verified' => false,
  ],
  [
    'id'       => 4,
    'title'    => 'Zara Cargo Pants — Size M',
    'price'    => 280,
    'area'     => 'Blouberg',
    'category' => 'clothing',
    'emoji'    => '👗',
    'verified' => true,
  ],
  [
    'id'       => 5,
    'title'    => 'Adidas Superstar — Size 8',
    'price'    => 650,
    'area'     => 'Green Point',
    'category' => 'shoes',
    'emoji'    => '👟',
    'verified' => true,
  ],
  [
    'id'       => 6,
    'title'    => 'Portable Speaker — JBL Flip 6',
    'price'    => 1100,
    'area'     => 'Mowbray',
    'category' => 'devices',
    'emoji'    => '🔊',
    'verified' => false,
  ],
  [
    'id'       => 7,
    'title'    => 'Human Hair Closure 4x4',
    'price'    => 480,
    'area'     => 'Wynberg',
    'category' => 'hair',
    'emoji'    => '💇',
    'verified' => true,
  ],
  [
    'id'       => 8,
    'title'    => 'Leather Crossbody Bag',
    'price'    => 390,
    'area'     => 'Observatory',
    'category' => 'bags',
    'emoji'    => '👜',
    'verified' => true,
  ],
];

$categories = [
  ['slug' => 'hair',     'emoji' => '💇', 'name' => 'Hair & Beauty',      'count' => 340],
  ['slug' => 'shoes',    'emoji' => '👟', 'name' => 'Shoes & Sneakers',   'count' => 512],
  ['slug' => 'devices',  'emoji' => '📱', 'name' => 'Devices & Tech',     'count' => 278],
  ['slug' => 'clothing', 'emoji' => '👗', 'name' => 'Clothing',           'count' => 640],
  ['slug' => 'home',     'emoji' => '🏠', 'name' => 'Home & Living',      'count' => 190],
  ['slug' => 'bags',     'emoji' => '🎒', 'name' => 'Bags & Accessories', 'count' => 225],
];

$areas = [
  'Wynberg', 'Claremont', 'Rondebosch', 'Mowbray',
  'Blouberg', 'Green Point', 'Observatory', 'Bellville',
  'Khayelitsha', 'Mitchells Plain', 'Athlone', 'Parow',
];

include 'includes/header.php';
?>

<!-- ════════════════════════════════════════════════════════
     HERO SECTION
════════════════════════════════════════════════════════ -->
<section class="hero">
  <div class="hero-bg-grid"   aria-hidden="true"></div>
  <div class="hero-glow-teal" aria-hidden="true"></div>
  <div class="hero-glow-orange" aria-hidden="true"></div>

  <div class="container">
    <div class="hero-content">

      <!-- Live badge -->
      <div class="hero-badge">
        <span class="badge-dot" aria-hidden="true"></span>
        Cape Town's local marketplace
      </div>

      <!-- Headline -->
      <h1>
        Buy &amp; sell locally.<br>
        <span class="accent-teal">Securely.</span>
        <span class="accent-orange">Seamlessly.</span>
      </h1>

      <!-- Sub-headline -->
      <p class="hero-sub">
        MzansiTrade connects Cape Town traders — from Wynberg to Blouberg — with
        a secure escrow system, verified sellers, and local pickup points (PUDO/PAXI).
      </p>

      <!-- CTA buttons -->
      <div class="hero-actions">
        <a href="listings.php" class="btn btn-teal btn-lg">
          <i class="ti ti-search" aria-hidden="true"></i> Browse listings
        </a>
        <a href="register.php" class="btn btn-ghost btn-lg">
          Become a seller <i class="ti ti-arrow-right" aria-hidden="true"></i>
        </a>
      </div>

      <!-- Stats -->
      <div class="hero-stats">
        <div class="stat-item">
          <div class="stat-num">2.4<em>k+</em></div>
          <div class="stat-label">Active listings</div>
        </div>
        <div class="stat-item">
          <div class="stat-num">800<em>+</em></div>
          <div class="stat-label">Verified sellers</div>
        </div>
        <div class="stat-item">
          <div class="stat-num">12<em>+</em></div>
          <div class="stat-label">CT neighbourhoods</div>
        </div>
      </div>

    </div><!-- /.hero-content -->
  </div><!-- /.container -->
</section>

<div class="divider"></div>

<!-- ════════════════════════════════════════════════════════
     BROWSE BY AREA
════════════════════════════════════════════════════════ -->
<section class="section">
  <div class="container">
    <div class="section-header">
      <p class="section-label">Shop near you</p>
      <h2>Browse by area</h2>
    </div>

    <div class="areas-grid">
      <?php foreach ($areas as $area): ?>
        <a href="listings.php?area=<?= urlencode($area) ?>" class="area-chip">
          <i class="ti ti-map-pin" aria-hidden="true"></i>
          <?= htmlspecialchars($area) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════
     SHOP BY CATEGORY
════════════════════════════════════════════════════════ -->
<section class="section" style="padding-top: 0;">
  <div class="container">
    <div class="section-header">
      <p class="section-label">What's trending</p>
      <h2>Shop by category</h2>
    </div>

    <div class="cats-grid">
      <?php foreach ($categories as $cat): ?>
        <a href="listings.php?cat=<?= urlencode($cat['slug']) ?>" class="cat-card">
          <div class="cat-emoji"><?= $cat['emoji'] ?></div>
          <div class="cat-name"><?= htmlspecialchars($cat['name']) ?></div>
          <div class="cat-count"><?= number_format($cat['count']) ?> listings</div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════
     FEATURED LISTINGS
════════════════════════════════════════════════════════ -->
<section class="section" style="padding-top: 0;">
  <div class="container">
    <div class="section-header" style="display:flex; align-items:flex-end; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
      <div>
        <p class="section-label">Just listed</p>
        <h2>Fresh near you</h2>
      </div>
      <a href="listings.php" class="btn btn-ghost btn-sm">
        View all <i class="ti ti-arrow-right" aria-hidden="true"></i>
      </a>
    </div>

    <div class="listings-grid">
      <?php foreach ($featured_listings as $item): ?>
        <a href="listing.php?id=<?= (int)$item['id'] ?>" class="listing-card">
          <div class="listing-img"><?= $item['emoji'] ?></div>
          <div class="listing-body">
            <div class="listing-title">
              <?= htmlspecialchars($item['title']) ?>
              <?php if ($item['verified']): ?>
                <span class="badge-verified">Verified</span>
              <?php endif; ?>
            </div>
            <div class="listing-location">
              <i class="ti ti-map-pin" aria-hidden="true"></i>
              <?= htmlspecialchars($item['area']) ?>
            </div>
            <div class="listing-price">R <?= number_format($item['price']) ?></div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════
     TRUST STRIP
════════════════════════════════════════════════════════ -->
<div class="trust-strip">
  <div class="container">
    <div class="trust-grid">

      <div class="trust-item">
        <div class="trust-icon"><i class="ti ti-lock" aria-hidden="true"></i></div>
        <div>
          <p class="trust-title">Escrow protection</p>
          <p class="trust-desc">Funds are only released once you confirm receipt. Zero fraud risk for buyers and sellers.</p>
        </div>
      </div>

      <div class="trust-item">
        <div class="trust-icon"><i class="ti ti-shield-check" aria-hidden="true"></i></div>
        <div>
          <p class="trust-title">Verified sellers</p>
          <p class="trust-desc">All sellers verify their SA ID. Shop with confidence knowing who you're trading with.</p>
        </div>
      </div>

      <div class="trust-item">
        <div class="trust-icon"><i class="ti ti-truck-delivery" aria-hidden="true"></i></div>
        <div>
          <p class="trust-title">Local pickup points</p>
          <p class="trust-desc">PUDO &amp; PAXI integration keeps delivery costs low across Cape Town.</p>
        </div>
      </div>

      <div class="trust-item">
        <div class="trust-icon"><i class="ti ti-wifi-off" aria-hidden="true"></i></div>
        <div>
          <p class="trust-title">Low-data friendly</p>
          <p class="trust-desc">Built lite for South African network conditions. Accessible for all traders.</p>
        </div>
      </div>

    </div><!-- /.trust-grid -->
  </div>
</div>

<!-- ════════════════════════════════════════════════════════
     HOW IT WORKS
════════════════════════════════════════════════════════ -->
<section class="section">
  <div class="container">
    <div class="section-header" style="text-align:center; max-width:500px; margin: 0 auto 2.5rem;">
      <p class="section-label">Simple process</p>
      <h2>How MzansiTrade works</h2>
    </div>

    <div class="steps-grid">
      <div class="step-card">
        <div class="step-num">01</div>
        <h3 class="step-title">Create your account</h3>
        <p class="step-desc">Sign up and verify your SA ID in minutes. Both buyers and sellers are protected from the start.</p>
      </div>
      <div class="step-card">
        <div class="step-num">02</div>
        <h3 class="step-title">List or browse</h3>
        <p class="step-desc">Post your items with photos and a price, or search listings across your Cape Town neighbourhood.</p>
      </div>
      <div class="step-card">
        <div class="step-num">03</div>
        <h3 class="step-title">Pay securely</h3>
        <p class="step-desc">Funds are held in escrow and released only once you confirm your item has arrived safely.</p>
      </div>
      <div class="step-card">
        <div class="step-num">04</div>
        <h3 class="step-title">Collect locally</h3>
        <p class="step-desc">Pick up from your nearest PUDO or PAXI point, or arrange a direct meetup with the seller.</p>
      </div>
    </div>

  </div>
</section>

<!-- ════════════════════════════════════════════════════════
     CTA BANNER
════════════════════════════════════════════════════════ -->
<section class="section" style="padding-top:0;">
  <div class="container">
    <div class="cta-banner">
      <h2>Ready to start trading?</h2>
      <p>Join hundreds of Cape Town traders already on MzansiTrade — it's free.</p>
      <div class="cta-actions">
        <a href="register.php" class="btn btn-teal btn-lg">
          <i class="ti ti-user-plus" aria-hidden="true"></i> Create free account
        </a>
        <a href="sell.php" class="btn btn-ghost btn-lg">
          List your first item <i class="ti ti-arrow-right" aria-hidden="true"></i>
        </a>
      </div>
    </div>
  </div>
</section>

<?php include 'includes/footer.php'; ?>