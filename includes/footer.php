<?php
/* includes/footer.php */
?>
<footer class="footer mt-auto py-5" style="background-color: var(--primary-teal); border-top: 3px solid var(--accent-orange);">
    <div class="container text-center">
        <div class="footer-logo mb-3">
            <h4 class="fw-bold mb-0 text-white" style="letter-spacing: -0.5px;">
                Mzansi<span>Trade</span>
            </h4>
            <style>
                .footer-logo h4 span { color: var(--accent-orange); }
            </style>
        </div>
        
        <div class="footer-links mb-4">
            <a href="/mzansitrade/index.php" class="text-white-50 text-decoration-none mx-2 hover-white">Home</a>
            <a href="/mzansitrade/pages/how-it-works.php" class="text-white-50 text-decoration-none mx-2 hover-white">How it Works</a>
            <a href="/mzansitrade/escrow/escrow_safety.php" class="text-white-50 text-decoration-none mx-2 hover-white">Escrow Safety</a>
            <a href="/mzansitrade/pages/support.php" class="text-white-50 text-decoration-none mx-2 hover-white">Support</a>
        </div>
        
        <p class="mb-0 text-white-50" style="font-size: 13px; letter-spacing: 0.3px;">
            &copy; <?php echo date('Y'); ?> <span style="color: var(--accent-yellow); font-weight: 500;">MzansiTrade</span> &middot; Secure Consumer-to-Consumer Local Marketplace &middot; Cape Town, South Africa.
        </p>
    </div>
</footer>

<style>
/* Quick local interactions for footer state dynamics */
.hover-white:hover {
    color: var(--white) !important;
    text-decoration: underline !important;
    transition: color 0.2s ease;
}
</style>