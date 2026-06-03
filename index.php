<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MsanziTrade | Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/admission/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'header.php'; ?>

    <div class="bg-white border-bottom py-4 mb-4 shadow-sm">
        <div class="container">
            <form action="search.php" method="GET" class="mx-auto main-search-bar">
                <div class="input-group">
                    <input type="text" class="form-control form-control-lg border-secondary-subtle" placeholder="Search hair, wigs, shoes, sneaker, Nike, closure, iPhone, speaker..." style="font-size: 1rem;">
                    
                    <select class="form-select form-select-lg border-secondary-subtle bg-light text-dark" style="max-width: 180px; font-size: 0.95rem;">
                        <option value="">Locations</option>
                        <option value="gugulethu">Gugulethu</option>
                        <option value="khayelitsha">Khayelitsha</option>
                        <option value="nyanga">Nyanga</option>
                        <option value="delft">Delft</option>
                        <option value="ottery">Ottery</option>
                        <option value="wynberg">Wynberg</option>
                        <option value="claremont">Claremont</option>
                        <option value="rondebosch">Rondebosch</option>  
                        <option value="mowbray">Mowbray</option>
                        <option value="blouberg">Blouberg</option>
                        <option value="greenpoint">Greenpoint</option>
                    </select>
                    
                    <button class="btn btn-msanzi-accent px-4" type="submit">
                        <i class="bi bi-search fs-5"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="container my-5">
        <h3 class="fw-bold mb-4 text-dark d-flex align-items-center">
            <i class="bi bi-fire text-danger me-2"></i> Trending Listings in Cape Town Townships
        </h3>

        <div class="row row-cols-2 row-cols-md-2 row-cols-lg-3 g-3 g-md-4">
            
            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/heel.png" alt="Black Steve Madden Heels" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Heels'">
                    </div>
                    <div class="card-body d-flex flex-column text-start p-2 p-md-3">
                        <div class="d-flex flex-column mb-2">
                            <span class="badge-verified mb-1 align-self-start"><i class="bi bi-patch-check-fill"></i> Verified</span>
                            <h6 class="fw-bold card-title mb-0 text-dark text-truncate">Black Steve Madden heels</h6>
                        </div>
                        <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> Khayelitsha</p>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12 col-md-6">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%20Black%20Steve%20Madden%20Heels" target="_blank" class="btn whatsapp-btn btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="checkout.php?item=1" class="btn btn-msanzi-primary btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-shield-lock-fill"></i> Buy Escrow
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/sneaker.png" alt="Nike Retro J4 Sneaker" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Sneaker'">
                    </div>
                    <div class="card-body d-flex flex-column text-start p-2 p-md-3">
                        <div class="d-flex flex-column mb-2">
                            <span class="badge-verified mb-1 align-self-start"><i class="bi bi-patch-check-fill"></i> Verified</span>
                            <h6 class="fw-bold card-title mb-0 text-dark text-truncate">Nike Retro J4 Sneaker</h6>
                        </div>
                        <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> Gugulethu</p>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12 col-md-6">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%20Nike%20Sneaker" target="_blank" class="btn whatsapp-btn btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="checkout.php?item=2" class="btn btn-msanzi-primary btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-shield-lock-fill"></i> Buy Escrow
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/speaker.png" alt="Bluetooth Speaker" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Speaker'">
                    </div>
                    <div class="card-body d-flex flex-column text-start p-2 p-md-3">
                        <div class="d-flex flex-column mb-2">
                            <span class="badge-unverified mb-1 align-self-start"><i class="bi bi-exclamation-triangle-fill"></i> Unverified</span>
                            <h6 class="fw-bold card-title mb-0 text-dark text-truncate">JBL Bluetooth Speaker</h6>
                        </div>
                        <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> Nyanga</p>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12 col-md-6">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%20Speaker" target="_blank" class="btn whatsapp-btn btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="checkout.php?item=3" class="btn btn-msanzi-primary btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-shield-lock-fill"></i> Buy Escrow
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/iphone.png" alt="iPhone" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=iPhone'">
                    </div>
                    <div class="card-body d-flex flex-column text-start p-2 p-md-3">
                        <div class="d-flex flex-column mb-2">
                            <span class="badge-verified mb-1 align-self-start"><i class="bi bi-patch-check-fill"></i> Verified</span>
                            <h6 class="fw-bold card-title mb-0 text-dark text-truncate">Blue iPhone 17 Pro Max Used</h6>
                        </div>
                        <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> Delft</p>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12 col-md-6">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%20iPhone" target="_blank" class="btn whatsapp-btn btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="checkout.php?item=4" class="btn btn-msanzi-primary btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-shield-lock-fill"></i> Buy Escrow
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/wig.png" alt="Wig" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=13x4+Wig'">
                    </div>
                    <div class="card-body d-flex flex-column text-start p-2 p-md-3">
                        <div class="d-flex flex-column mb-2">
                            <span class="badge-verified mb-1 align-self-start"><i class="bi bi-patch-check-fill"></i> Verified</span>
                            <h6 class="fw-bold card-title mb-0 text-dark text-truncate">28" 13x4 Lace Front Wig</h6>
                        </div>
                        <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> Ottery</p>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12 col-md-6">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%2013x4%20Wig" target="_blank" class="btn whatsapp-btn btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="checkout.php?item=5" class="btn btn-msanzi-primary btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-shield-lock-fill"></i> Buy Escrow
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 product-card shadow-sm border-0">
                    <div class="product-image-holder">
                        <img src="assets/img/closure.png" alt="Closure" class="product-img" onerror="this.src='https://via.placeholder.com/300x300?text=Closure'">
                    </div>
                    <div class="card-body d-flex flex-column text-start p-2 p-md-3">
                        <div class="d-flex flex-column mb-2">
                            <span class="badge-unverified mb-1 align-self-start"><i class="bi bi-exclamation-triangle-fill"></i> Unverified</span>
                            <h6 class="fw-bold card-title mb-0 text-dark text-truncate">Closure + 3 bundles 24"</h6>
                        </div>
                        <p class="text-muted small mb-3"><i class="bi bi-geo-alt-fill text-danger"></i> Khayelitsha</p>
                        
                        <div class="mt-auto row g-2">
                            <div class="col-12 col-md-6">
                                <a href="https://wa.me/27820000000?text=Hi,%20I'm%20interested%20in%20the%20Closure" target="_blank" class="btn whatsapp-btn btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-whatsapp"></i> WhatsApp
                                </a>
                            </div>
                            <div class="col-12">
                                <a href="checkout.php?item=6" class="btn btn-msanzi-primary btn-xs-custom w-100 py-2 rounded-2">
                                    <i class="bi bi-shield-lock-fill"></i> Buy Escrow
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