<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once '../config/db.php'; 

// ==========================================
// THE FIX: Define your base project path here
// If your URL is localhost/mzansitrade/index.php, keep it as '/mzansitrade'
// If your URL is just localhost/index.php, change it to '' (blank)
// ==========================================
$base_url = '/mzansitrade'; 
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?php echo $base_url; ?>/mzansitrade/assets/css/style.css">

<nav class="navbar navbar-expand-lg navbar-dark shadow-sm py-3 msanzi-custom-nav" style="background-color: #0b3c4d;">
    <div class="container-fluid position-relative msanzi-nav-container" style="width: 100%;">
        
        <a class="navbar-brand d-flex align-items-center position-absolute msanzi-logo-left" href="<?php echo $base_url; ?>/index.php" style="left: 15px; top: 50%; transform: translateY(-50%); margin: 0; padding: 0; z-index: 10;">
            <img src="<?php echo $base_url; ?>/assets/img/logo.jpeg" alt="Mzansi Trade Logo" class="logo-blend" style="height: 50px; width: auto; mix-blend-mode: multiply;">
        </a>

        <div class="brand-title-center-override position-absolute" style="left: 50%; top: 50%; transform: translate(-50%, -50%); margin: 0; white-space: nowrap; z-index: 5;">
            <span class="logo-text fw-bold text-white fs-4">MzansiTrade <span class="small fw-normal ms-1" style="color: #f28e2b; font-size: 13px;">C2C</span></span>
        </div>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse msanzi-collapse-target" id="navbarNav">
            <div class="navbar-nav ms-auto align-items-center" style="margin-left: auto !important;">
            
                <div class="nav-item dropdown ms-3 text-end d-flex flex-column align-items-end">
                    <a class="nav-link dropdown-toggle d-flex align-items-center p-0 text-white" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold border border-2" style="width: 42px; height: 42px; font-size: 18px; background-color: #f28e2b; border-color: #ffffff !important; color: #ffffff;">
                            <?php 
                                $name = $_SESSION['username'] ?? 'User';
                                $nameParts = explode(" ", $name);
                                $initials = "";
                                foreach ($nameParts as $w) { 
                                    if(!empty($w)) $initials .= $w[0]; 
                                }
                                echo strtoupper(substr($initials, 0, 1)); 
                            ?>
                        </div>
                    </a>
                    
                    <span class="small fw-bold mt-1" style="font-size: 11px; color: #f28e2b;">
                        <?php echo htmlspecialchars($_SESSION['username'] ?? 'Mzansi User'); ?>
                    </span>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-0 rounded-0" aria-labelledby="userDropdown" style="width: 240px; border-top: 3px solid #f28e2b !important;">
    
    <li>
        <a class="dropdown-item py-2.5 d-flex align-items-center gap-3" href="<?php echo $base_url; ?>../pages/profile.php" style="color: #0b3c4d; font-size: 14px;">
            <i class="bi bi-person-gear fs-5" style="color: #0b3c4d;"></i>Profile Settings
        </a>
    </li> 

    <li>
        <a class="dropdown-item py-2.5 d-flex align-items-center gap-3" href="<?php echo $base_url; ?>/index.php" style="color: #0b3c4d; font-size: 14px;">
            <i class="bi bi-house-door fs-5" style="color: #0b3c4d;"></i>Home
        </a>
    </li>

    <li>
        <a class="dropdown-item py-2.5 d-flex align-items-center gap-3" href="#" data-bs-toggle="modal" data-bs-target="#aboutSystemModal" style="color: #0b3c4d; font-size: 14px;">
            <i class="bi bi-info-circle fs-5" style="color: #0b3c4d;"></i>About MzansiTrade
        </a>
    </li>
    
    <li><hr class="dropdown-divider my-0" style="border-top: 1px solid #e0e0e0; opacity: 1;"></li>

    <li>
        <a class="dropdown-item py-2.5 d-flex align-items-center gap-3" href="<?php echo $base_url; ?>/seller/sell_item.php" style="color: #0b3c4d; font-size: 14px;">
             <i class="bi bi-plus-circle fs-5" style="color: #f28e2b;"></i>Sell Item
        </a>
</li>

    <li>
        <a class="dropdown-item py-2.5 d-flex align-items-center gap-3" href="<?php echo $base_url; ?>/auth/register.php" style="color: #0b3c4d; font-size: 14px;">
            <i class="bi bi-person-plus fs-5" style="color: #0b3c4d;"></i>Register Account
        </a>
    </li>

    <li>
        <a class="dropdown-item py-2.5 d-flex align-items-center gap-3" href="<?php echo $base_url; ?>/auth/logout.php" style="color: #d93f3f; font-size: 14px; background-color: #fff5f5;">
            <i class="bi bi-box-arrow-right fs-5" style="color: #d93f3f;"></i>Log Out
        </a>
    </li>
</ul>
                </div>

            </div>
        </div>
    </div>
</nav>

<div class="modal fade" id="aboutSystemModal" tabindex="-1" aria-labelledby="aboutSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 bg-success text-white py-3" style="border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #0b3c4d !important;">
                <h5 class="modal-title fw-bold" id="aboutSystemModalLabel">
                    <i class="bi bi-info-circle-fill me-2"></i>About MzansiTrade
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img src="<?php echo $base_url; ?>/assets/img/logo.jpeg" alt="MzansiTrade Logo" class="mb-3" style="width: 80px; height: 80px; object-fit: contain; mix-blend-mode: multiply;" onerror="this.src='https://via.placeholder.com/80x80?text=Logo'">
                <h4 class="fw-bold mb-2" style="color: #0b3c4d;">MzansiTrade</h4>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    MzansiTrade is a creative C-2-C marketplace dedicated to empowering local informal traders across Cape Town townships. Our integrated smart escrow architecture secures transaction cash safely until products are checked face-to-face, providing safe street-level commerce options completely free of scams.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-secondary px-4 rounded-1" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>