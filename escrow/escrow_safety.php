<?php
// Prevent blank pages by forcing errors to display if something breaks deep in the includes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Root-relative server path targeting the master layout
include dirname(__DIR__) . '/includes/header.php'; 
?>

<div class="container py-5 my-4">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <h1 class="fw-bold" style="color: #163f4c; font-family: 'Syne', sans-serif;">Bulletproof Escrow Safety</h1>
            <h4 class="mb-4" style="color: #f4a41c;">Ironclad Protection for Buyers and Sellers</h4>
            
            <p class="text-muted" style="line-height: 1.8;">
                Marketplace fraud happens when one party has to trust another party blindly. MzansiTrade completely eliminates this vulnerability. Our custom escrow framework holds funds independently so no one can "take the money and run."
            </p>

            <div class="mt-4">
                <div class="d-flex mb-3">
                    <div class="me-3"><h4 style="color: #163f4c;">🛡️</h4></div>
                    <div>
                        <h6 class="fw-bold mb-1">Anti-Scam Architecture</h6>
                        <p class="text-muted small">Sellers cannot accept payment and disappear, because the money does not get deposited into their account until delivery validation occurs.</p>
                    </div>
                </div>

                <div class="d-flex mb-3">
                    <div class="me-3"><h4 style="color: #f4a41c;">📦</h4></div>
                    <div>
                        <h6 class="fw-bold mb-1">Delivery Accountability</h6>
                        <p class="text-muted small">By integrating clear tracking references through Pudo and Paxi, the transit cycle of every package is traceable, protecting sellers from false "not received" claims.</p>
                    </div>
                </div>

                <div class="d-flex">
                    <div class="me-3"><h4 style="color: #f4a41c;">⚖️</h4></div>
                    <div>
                        <h6 class="fw-bold mb-1">Dispute Resolution Platform</h6>
                        <p class="text-muted small">If an item arrives broken, counterfeit, or completely incorrect, our internal dispute unit locks the escrow context until proof is evaluated and a fair refund decision is executed.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow p-5 rounded-4" style="background-color: #163f4c; color: white;">
                <h3 class="fw-bold mb-4" style="color: #f4a41c; font-family: 'Syne', sans-serif;">The Shield Policy</h3>
                <p class="text-white-50" style="font-size: 0.95rem; line-height: 1.7;">
                    Our mandatory ID verification processing protocol matches bank-grade vetting standards. Because all accounts track to an explicit national identity profile, malicious actors cannot operate within MzansiTrade. 
                </p>
                <hr style="border-color: rgba(255,255,255,0.2);">
                <p class="small mb-4 text-white-50">
                    Whether you are handling transactional updates or shipping custom inventory, your data and assets stay safely locked behind our custom escrow systems.
                </p>
                <a href="/mzansitrade/index.php" class="btn fw-bold w-100 py-3 shadow-sm text-center" style="background-color: #f4a41c !important; color: #163f4c !important; border-radius: 8px; text-decoration: none; display: inline-block;">Explore Marketplace</a>
            </div>
        </div>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>