<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MsanziTrade | Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'includes/header.php'; ?>

    <div class="search-bar-wrapper py-3 mb-4">
        <div class="container-fluid px-4">
            <form action="search.php" method="GET" class="mx-auto" style="max-width: 700px;">
                <div class="input-group search-input-group">
                    <input type="text" class="form-control" placeholder="Search for products, brands, or categories..." name="query" required>
                    
                    <select class="form-select">
                        <option value="">All Locations</option>
                        <option value="wynberg">Wynberg</option>
                        <option value="claremont">Claremont</option>
                        <option value="rondebosch">Rondebosch</option>  
                        <option value="mowbray">Mowbray</option>
                        <option value="blouberg">Blouberg</option>
                        <option value="greenpoint">Greenpoint</option>
                    </select>
                    
                    <button class="btn btn-search-submit" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="main-grid-container">
        <h4 class="grid-heading mb-4">
            Trending Listings in Cape Town Townships
        </h4>

        <div class="row row-cols-2 g-3 g-md-4">
            
            <div class="col">
                <div class="card h-100 product-card border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/heel.png" alt="Black Steve Madden Heels" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Heels'">
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-md-3">
                        <span class="badge-verified mb-1"><i class="bi bi-patch-check-fill"></i> Verified Seller</span>
                        <h6 class="product-title text-truncate mb-1">Black Steve Madden heels</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Khayelitsha</p>
                        <h5 class="product-price mb-3">R1 000</h5>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12">
                                <a href="checkout.php?item=1" class="btn escrow-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-shield-lock-fill"></i> Buy with Escrow
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%20Black%20Steve%20Madden%20Heels%20for%20R1000" target="_blank" class="btn contact-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-whatsapp"></i> Chat WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 product-card border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/sneaker.png" alt="Nike Retro J4 Sneaker" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Sneaker'">
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-md-3">
                        <span class="badge-verified mb-1"><i class="bi bi-patch-check-fill"></i> Verified Seller</span>
                        <h6 class="product-title text-truncate mb-1">Nike Retro J4 Sneaker</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Gugulethu</p>
                        <h5 class="product-price mb-3">R2 400</h5>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12">
                                <a href="checkout.php?item=2" class="btn escrow-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-shield-lock-fill"></i> Buy with Escrow
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%20Nike%20Retro%20J4%20Sneaker%20for%20R2400" target="_blank" class="btn contact-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-whatsapp"></i> Chat WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 product-card border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/speaker.png" alt="Bluetooth Speaker" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Speaker'">
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-md-3">
                        <span class="badge-unverified mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Unverified</span>
                        <h6 class="product-title text-truncate mb-1">JBL Bluetooth Speaker</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Nyanga</p>
                        <h5 class="product-price mb-3">R700</h5>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12">
                                <a href="checkout.php?item=3" class="btn escrow-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-shield-lock-fill"></i> Buy with Escrow
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%20JBL%20Bluetooth%20Speaker%20for%20R700" target="_blank" class="btn contact-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-whatsapp"></i> Chat WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 product-card border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/iphone.png" alt="iPhone" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=iPhone'">
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-md-3">
                        <span class="badge-verified mb-1"><i class="bi bi-patch-check-fill"></i> Verified Seller</span>
                        <h6 class="product-title text-truncate mb-1">Blue iPhone 17 Pro Max Used</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Delft</p>
                        <h5 class="product-price mb-3">R18 500</h5>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12">
                                <a href="checkout.php?item=4" class="btn escrow-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-shield-lock-fill"></i> Buy with Escrow
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%20Blue%20iPhone" target="_blank" class="btn contact-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-whatsapp"></i> Chat WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 product-card border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/wig.png" alt="Wig" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=13x4+Wig'">
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-md-3">
                        <span class="badge-verified mb-1"><i class="bi bi-patch-check-fill"></i> Verified Seller</span>
                        <h6 class="product-title text-truncate mb-1">28" 13x4 Lace Front Wig</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Ottery</p>
                        <h5 class="product-price mb-3">R3 200</h5>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12">
                                <a href="checkout.php?item=5" class="btn escrow-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-shield-lock-fill"></i> Buy with Escrow
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%2028%22%2013x4%20Lace%20Front%20Wig" target="_blank" class="btn contact-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-whatsapp"></i> Chat WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 product-card border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/closure.png" alt="Closure" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Closure'">
                    </div>
                    <div class="card-body d-flex flex-column p-2 p-md-3">
                        <span class="badge-unverified mb-1"><i class="bi bi-exclamation-triangle-fill"></i> Unverified</span>
                        <h6 class="product-title text-truncate mb-1">Closure + 3 bundles 24"</h6>
                        <p class="product-location mb-2"><i class="bi bi-geo-alt"></i> Khayelitsha</p>
                        <h5 class="product-price mb-3">R1 850</h5>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12">
                                <a href="checkout.php?item=6" class="btn escrow-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-shield-lock-fill"></i> Buy with Escrow
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%20Closure%20%2B%203%20bundles%2024%22" target="_blank" class="btn contact-btn w-100 py-2 rounded-1">
                                    <i class="bi bi-whatsapp"></i> Chat WhatsApp
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>