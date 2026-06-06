<?php
// Prevent blank pages by forcing errors to display if something breaks deep in the includes
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Root-relative server path targeting the master layout
include dirname(__DIR__) . '/includes/header.php'; 
?>

<div class="container py-5 my-4">
    <div class="text-center mb-5">
        <h1 class="fw-bold" style="color: #163f4c; font-family: 'Syne', sans-serif;">How MzansiTrade Works</h1>
        <p class="text-muted lead">A secure ecosystem where both buyers and sellers are 100% protected.</p>
    </div>

    <div class="card border-0 p-4 mb-5 text-center shadow-sm" style="background-color: rgba(244, 164, 28, 0.08); border-left: 5px solid #f4a41c !important;">
        <h4 class="fw-bold mb-2" style="color: #f4a41c;">🛡️ Mandatory ID Verification</h4>
        <p class="mb-0 text-muted mx-auto" style="max-width: 700px;">
            To ensure complete accountability and eliminate anonymous scammers, <strong>every user must upload their South African ID or Passport</strong>. Your profile will go through our secure verification processing system before you can trade.
        </p>
    </div>

    <div class="row g-5">
        <div class="col-md-6">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100" style="border-top: 4px solid #163f4c !important;">
                <h3 class="fw-bold mb-4" style="color: #163f4c;">🛍️ For Buyers</h3>
                
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">1. Browse & Connect</h5>
                    <p class="text-muted small">Explore listings across Cape Town and chat with the verified seller via WhatsApp to finalize transaction details.</p>
                </div>
                
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">2. Secure Funds via Escrow</h5>
                    <p class="text-muted small">Pay securely through our process escrow module. Your money is locked safely in our platform vault—the seller can see the funds are secured, but cannot touch them yet.</p>
                </div>
                
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">3. Track & Inspect</h5>
                    <p class="text-muted small">The seller dispatches your items via <strong>Pudo</strong> or <strong>Paxi</strong>. Once the package arrives at your locker or store, inspect the gear to ensure it matches the description.</p>
                </div>
                
                <div>
                    <h5 class="fw-bold mb-1">4. Release Funds</h5>
                    <p class="text-muted small">Happy with your purchase? Click "Release Funds" to instantly complete the transaction. Your trade is fully finalized.</p>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="p-4 rounded-4 shadow-sm bg-white h-100" style="border-top: 4px solid #f4a41c !important;">
                <h3 class="fw-bold mb-4" style="color: #163f4c;">💰 For Sellers</h3>
                
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">1. List Your Items</h5>
                    <p class="text-muted small">Upload clean photos of your tech, sneakers, or hair products, set a fair price, and link your account.</p>
                </div>
                
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">2. Guaranteed Payment Confirmation</h5>
                    <p class="text-muted small">Never ship goods on "promises" or fake SMS proofs. MzansiTrade will formally notify you when the buyer's money is safely secured in escrow.</p>
                </div>
                
                <div class="mb-4">
                    <h5 class="fw-bold mb-1">3. Ship with Peace of Mind</h5>
                    <p class="text-muted small">Drop off the package at your closest <strong>Pudo locker</strong> or <strong>Paxi point</strong>. Share the tracking or waybill details with the buyer via chat.</p>
                </div>
                
                <div>
                    <h5 class="fw-bold mb-1">4. Get Paid Swiftly</h5>
                    <p class="text-muted small">Once the buyer receives and confirms their item, the escrow release triggers automatically, transferring your earnings straight to your balance.</p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-5 pt-3">
        <a href="/mzansitrade/index.php" class="btn btn-lg fw-bold px-5 py-3 shadow-sm text-center" style="background-color: #163f4c !important; color: #ffffff !important; border-radius: 8px; text-decoration: none; display: inline-block;">Back to Marketplace</a>
    </div>
</div>

<?php include dirname(__DIR__) . '/includes/footer.php'; ?>