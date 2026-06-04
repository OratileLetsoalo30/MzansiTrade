<?php
session_start();
$showSplash = !isset($_SESSION['splash_shown']);
$_SESSION['splash_shown'] = true;
?>
<?php if ($showSplash): ?>
    <?php include 'splash.php'; exit; ?>
<?php endif; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MzansiTrade | Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css?v=<?php echo time(); ?>">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8f9fa;
        }

        /* Search Utility Styling */
        .search-bar-wrapper {
            background-color: #f1f3f5;
            border-bottom: 1px solid #e9ecef;
        }
        .search-input-group {
            border: 2px solid #0b3c4d;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(11, 60, 77, 0.08);
        }
        .btn-search-submit {
            background-color: #ffc107 !important;
            color: #0b3c4d !important;
            border: none;
            padding: 0 25px;
            font-weight: 700;
        }

        /* Hero Banner Overrides */
        .mzansi-premium-hero {
            background: linear-gradient(180deg, #0b3c4d 0%, #061920 100%) !important;
            padding: 80px 20px !important;
            color: #ffffff !important;
            border-bottom: 4px solid #f28e2b;
        }
        .hero-geo-badge {
            background: rgba(242, 142, 43, 0.15) !important;
            border: 1px solid rgba(242, 142, 43, 0.4) !important;
            color: #f28e2b !important;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 6px 16px;
            border-radius: 50px;
            display: inline-flex;
        }
        .hero-headline-title {
            font-family: 'Syne', sans-serif;
            font-size: 3.8rem;
            font-weight: 800;
            line-height: 1.1;
            letter-spacing: -0.02em;
            color: #ffffff !important;
        }
        .gradient-accent-text {
            background: linear-gradient(45deg, #f28e2b 0%, #ffc107 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
        }
        .hero-body-summary {
            color: #ced4da !important;
            font-size: 17px;
            line-height: 1.6;
            font-weight: 400;
        }

        /* Statistics */
        .stat-giant-number {
            font-family: 'Syne', sans-serif;
            color: #ffc107 !important;
            font-size: 2.4rem;
            font-weight: 800;
        }
        .stat-sub-label {
            color: #a0afb7 !important;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <?php if ($showSplash): ?>
<div id="splash-screen" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; background: linear-gradient(180deg, #0b3c4d 0%, #061920 100%); transition: opacity 0.6s ease-in-out;">
    <img src="assets/img/logo.png" alt="MzansiTrade Logo" style="max-width: 250px; height: auto; margin-bottom: 30px;">
    <div class="spinner-border" style="color: #ffc107; width: 3rem; height: 3rem;" role="status"></div>
</div>
<script>
    
    window.addEventListener('load', function() {
        const splash = document.getElementById('splash-screen');
        setTimeout(() => {
            splash.style.opacity = '0';
            setTimeout(() => { splash.style.display = 'none'; }, 600);
        }, 5000); // Stays for 5 seconds
    });
</script>
<?php endif; ?>
    <?php include 'includes/header.php'; ?>

    <div class="search-bar-wrapper py-3">
        <div class="container-fluid px-4">
            <form action="search_handler.php" method="GET" class="mx-auto" style="max-width: 750px;">
                <div class="input-group search-input-group">
                    <span class="input-group-text bg-white border-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" class="form-control border-0" placeholder="What are you looking for today?" name="q" required style="box-shadow:none;">
                    
                    <select class="form-select border-0 border-start" name="location" style="max-width: 160px; background-color: #fafafa; box-shadow:none;">
                        <option value="">All Areas</option>
                        <option value="khayelitsha">Khayelitsha</option>
                        <option value="gugulethu">Gugulethu</option>
                        <option value="nyanga">Nyanga</option>
                        <option value="delft">Delft</option>
                        <option value="ottery">Ottery</option>
                        <option value="wynberg">Wynberg</option>
                        <option value="blouberg">Blouberg</option>  
                        <option value="claremont">Claremont</option>
                        <option value="langa">Langa</option>
                        <option value="rondebosch">Rondebosch</option>
                    </select>
                    
                    <button class="btn btn-search-submit" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="mzansi-premium-hero text-center">
        <div class="container">
            <div class="hero-geo-badge mb-3">
                <i class="bi bi-patch-check-fill me-2"></i> Cape Town's Trusted Local C2C Network
            </div>
            <h1 class="hero-headline-title mb-3">
                Buy & sell locally.<br>
                <span class="gradient-accent-text">Securely. Seamlessly.</span>
            </h1>
            <p class="hero-body-summary mx-auto mb-5" style="max-width: 650px;">
                MzansiTrade connects Cape Town traders — all local townships — with a secure escrow system, verified sellers, and local pickup points.
            </p>
            
            <div class="row justify-content-center g-4">
                <div class="col-4 col-md-2">
                    <div class="stat-giant-number">2.4K+</div>
                    <div class="stat-sub-label">Active Listings</div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="stat-giant-number">800+</div>
                    <div class="stat-sub-label">Verified Sellers</div>
                </div>
                <div class="col-4 col-md-2">
                    <div class="stat-giant-number">12+</div>
                    <div class="stat-sub-label">Neighbourhoods</div>
                </div>
            </div>
        </div>
    </div>

    <div class="main-grid-container container py-5">
        <h4 class="fw-bold mb-4" style="color: #0b3c4d; font-family: 'Syne', sans-serif;">Trending Listings in Cape Town Township Areas</h4>
        
        <h5 class="category-divider-title mb-4"><i class="bi bi-tag-fill me-2" style="color: #f28e2b;"></i>Trending in Shoes</h5>
        <div class="row justify-content-start g-4 mb-5">
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card border-0 shadow-sm">
                    <div class="product-image-holder">
                        <img src="assets/img/heel.png" alt="Black Heels" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Heels'">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge-verified mb-1"><i class="bi bi-patch-check-fill"></i> Verified Seller</span>
                        <h6 class="product-title text-truncate mb-1" style="font-weight: 600; color: #1a2a30;">Black Steve Madden heels</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Khayelitsha</p>
                        <h5 class="product-price mb-3" style="color: #0b3c4d; font-weight: 700;">R1 000</h5>
                        <div class="mt-auto row g-2">
                            <div class="col-12"><a href="checkout.php?item=1" class="btn escrow-btn w-100 py-2"><i class="bi bi-shield-lock-fill"></i> Buy with Escrow</a></div>
                            <div class="col-12"><a href="#" class="btn contact-btn w-100 py-2"><i class="bi bi-whatsapp"></i> Chat WhatsApp</a></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card border-0 shadow-sm">
                    <div class="product-image-holder">
                        <img src="assets/img/sneaker.png" alt="Sneaker" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Sneaker'">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge-verified mb-1"><i class="bi bi-patch-check-fill"></i> Verified Seller</span>
                        <h6 class="product-title text-truncate mb-1" style="font-weight: 600; color: #1a2a30;">Nike Retro J4 Sneaker</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Gugulethu</p>
                        <h5 class="product-price mb-3" style="color: #0b3c4d; font-weight: 700;">R2 400</h5>
                        <div class="mt-auto row g-2">
                            <div class="col-12"><a href="checkout.php?item=2" class="btn escrow-btn w-100 py-2"><i class="bi bi-shield-lock-fill"></i> Buy with Escrow</a></div>
                            <div class="col-12"><a href="#" class="btn contact-btn w-100 py-2"><i class="bi bi-whatsapp"></i> Chat WhatsApp</a></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <h5 class="category-divider-title mb-4"><i class="bi bi-laptop-fill me-2" style="color: #f28e2b;"></i>Trending in Devices</h5>
        <div class="row justify-content-start g-4 mb-5">
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card border-0 shadow-sm">
                    <div class="product-image-holder">
                        <img src="assets/img/speaker.png" alt="JBL Speaker" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Speaker'">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge-verified mb-1" style="color: #dfa510;"><i class="bi bi-exclamation-triangle-fill"></i> Standard Seller</span>
                        <h6 class="product-title text-truncate mb-1" style="font-weight: 600; color: #1a2a30;">JBL Bluetooth Speaker</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Nyanga</p>
                        <h5 class="product-price mb-3" style="color: #0b3c4d; font-weight: 700;">R700</h5>
                        <div class="mt-auto row g-2">
                            <div class="col-12"><a href="checkout.php?item=3" class="btn escrow-btn w-100 py-2"><i class="bi bi-shield-lock-fill"></i> Buy with Escrow</a></div>
                            <div class="col-12"><a href="#" class="btn contact-btn w-100 py-2"><i class="bi bi-whatsapp"></i> Chat WhatsApp</a></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card border-0 shadow-sm">
                    <div class="product-image-holder">
                        <img src="assets/img/iphone.png" alt="iPhone" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=iPhone'">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge-verified mb-1"><i class="bi bi-patch-check-fill"></i> Verified Seller</span>
                        <h6 class="product-title text-truncate mb-1" style="font-weight: 600; color: #1a2a30;">iPhone 13 Pro Max 256GB</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Langa</p>
                        <h5 class="product-price mb-3" style="color: #0b3c4d; font-weight: 700;">R11 500</h5>
                        <div class="mt-auto row g-2">
                            <div class="col-12"><a href="checkout.php?item=5" class="btn escrow-btn w-100 py-2"><i class="bi bi-shield-lock-fill"></i> Buy with Escrow</a></div>
                            <div class="col-12"><a href="#" class="btn contact-btn w-100 py-2"><i class="bi bi-whatsapp"></i> Chat WhatsApp</a></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <h5 class="category-divider-title mb-4"><i class="bi bi-scissors me-2" style="color: #f28e2b;"></i>Trending in Hair Products</h5>
        <div class="row justify-content-start g-4 mb-5">
            
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card border-0 shadow-sm">
                    <div class="product-image-holder">
                        <img src="assets/img/wig.png" alt="Lace Wig" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Wig'">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge-verified mb-1"><i class="bi bi-patch-check-fill"></i> Verified Seller</span>
                        <h6 class="product-title text-truncate mb-1" style="font-weight: 600; color: #1a2a30;">28" 13x4 Lace Front Wig</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Ottery</p>
                        <h5 class="product-price mb-3" style="color: #0b3c4d; font-weight: 700;">R3 200</h5>
                        <div class="mt-auto row g-2">
                            <div class="col-12"><a href="checkout.php?item=4" class="btn escrow-btn w-100 py-2"><i class="bi bi-shield-lock-fill"></i> Buy with Escrow</a></div>
                            <div class="col-12"><a href="#" class="btn contact-btn w-100 py-2"><i class="bi bi-whatsapp"></i> Chat WhatsApp</a></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                <div class="card h-100 product-card border-0 shadow-sm">
                    <div class="product-image-holder">
                        <img src="assets/img/closure.png" alt="Hair Closure" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Closure'">
                    </div>
                    <div class="card-body d-flex flex-column p-3">
                        <span class="badge-verified mb-1"><i class="bi bi-patch-check-fill"></i> Verified Seller</span>
                        <h6 class="product-title text-truncate mb-1" style="font-weight: 600; color: #1a2a30;">3x 24" Brazilian Hair Bundles + Closure </h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Delft</p>
                        <h5 class="product-price mb-3" style="color: #0b3c4d; font-weight: 700;">R1 800</h5>
                        <div class="mt-auto row g-2">
                            <div class="col-12"><a href="checkout.php?item=6" class="btn escrow-btn w-100 py-2"><i class="bi bi-shield-lock-fill"></i> Buy with Escrow</a></div>
                            <div class="col-12"><a href="#" class="btn contact-btn w-100 py-2"><i class="bi bi-whatsapp"></i> Chat WhatsApp</a></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php include 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>