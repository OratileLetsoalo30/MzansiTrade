<?php
// Prevent blank pages by forcing errors to display if something breaks deep in the includes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Root-relative server path targeting the master layout
include dirname(__DIR__) . '/includes/header.php'; 
?>

<div class="container py-5 my-4">
    <div class="mb-4">
        <a href="/mzansitrade/index.php" class="text-decoration-none fw-bold d-inline-flex align-items-center" style="color: #163f4c !important;">
            <span class="me-2">&larr;</span> Back to Marketplace Home
        </a>
    </div>

    <div class="text-center mb-5">
        <h1 class="fw-bold" style="color: #163f4c; font-family: 'Syne', sans-serif;">We've Got Your Back</h1>
        <p class="text-muted lead">Encountered an issue with your identity processing status, an active transaction, or a shipping courier?</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-sm overflow-hidden rounded-4">
                <div class="row g-0">
                    <div class="col-md-6 p-5 bg-light">
                        <h4 class="fw-bold mb-4" style="color: #163f4c;">Immediate Help Channels</h4>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase" style="color: #f4a41c; font-size: 0.8rem; letter-spacing: 1px;">Identity Processing Support</h6>
                            <p class="text-muted small mb-2">If your ID verification upload failed or is taking longer than 24 hours, contact our validation compliance crew.</p>
                            <a href="#" class="fw-bold text-decoration-none" style="color: #163f4c;">Check Verification Status →</a>
                        </div>
                        
                        <div class="mb-4">
                            <h6 class="fw-bold text-uppercase" style="color: #f4a41c; font-size: 0.8rem; letter-spacing: 1px;">Active Escrow Holds</h6>
                            <p class="text-muted small mb-2">For issues regarding payment release prompts, balance configurations, or transaction delays.</p>
                            <a href="#" class="fw-bold text-decoration-none" style="color: #163f4c;">Open Escrow Ticket →</a>
                        </div>
                    </div>

                    <div class="col-md-6 p-5 text-white" style="background-color: #163f4c;">
                        <h4 class="fw-bold mb-4" style="color: #f4a41c;">Courier Operations FAQ</h4>
                        
                        <div class="mb-3">
                            <h6 class="fw-bold mb-1" style="color: #f4a41c;">Pudo Shipments</h6>
                            <p class="small text-white-50">Sellers must provide the digital locker code receipt pin directly inside your transaction chat window.</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="fw-bold mb-1" style="color: #f4a41c;">Paxi Shipments</h6>
                            <p class="small text-white-50">Ensure your waybill slip references the exact Pep store code selected by the buyer during pickup alignment.</p>
                        </div>

                        <div class="mt-4 pt-3 border-top border-secondary">
                            <p class="small mb-0 text-white-50">Support Availability: <strong>Mon-Sat 08:00 - 18:00</strong></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>