<?php
/**
 * MzansiTrade Escrow System Configuration
 * Centralized settings for all escrow operations
 */

if (!defined('ESCROW_CONFIG_LOADED')) {
    define('ESCROW_CONFIG_LOADED', true);

    // ===== SECURITY SETTINGS =====
    // MzansiTrade Color Scheme
    define('MZANSI_PRIMARY', '#0B3C4D');      // Dark Teal
    define('MZANSI_ACCENT', '#FFA500');       // Bright Orange
    define('MZANSI_SUCCESS', '#52B788');      // Green
    define('MZANSI_DANGER', '#D62828');       // Red
    
    define('ESCROW_SECRET_SALT', 'MzansiSecureEscrow2026_' . substr(hash('sha256', $_SERVER['HTTP_HOST']), 0, 16));
    define('ESCROW_QR_REFRESH_INTERVAL', 60); // Seconds - QR token refreshes every X seconds
    define('ESCROW_QR_TIME_TOLERANCE', 120); // Accept QR codes within 2 minutes of generation
    define('ESCROW_TOKEN_LENGTH', 32);

    // ===== FLOW SETTINGS =====
    define('ESCROW_FLOW_PAYMENT_FIRST', 'payment_first'); // Buyer pays → QR handshake → Release
    define('ESCROW_FLOW_QR_FIRST', 'qr_first');           // QR handshake → Buyer pays → Release

    // ===== PAYMENT SETTINGS =====
    define('ESCROW_PAYMENT_MOCK', 'mock');
    define('ESCROW_PAYMENT_STRIPE', 'stripe');
    define('ESCROW_PAYMENT_PAYPAL', 'paypal');
    define('ESCROW_PAYMENT_BANK', 'bank_transfer');

    // Current payment provider (for mock payment)
    define('ESCROW_CURRENT_PAYMENT_PROVIDER', 'mock');

    // ===== STATUS CONSTANTS =====
    define('ESCROW_STATUS_INITIATED', 'escrow_initiated');
    define('ESCROW_STATUS_PAYMENT_PENDING', 'escrow_payment_pending');
    define('ESCROW_STATUS_PAYMENT_COMPLETED', 'escrow_payment_completed');
    define('ESCROW_STATUS_AWAITING_HANDSHAKE', 'escrow_awaiting_handshake');
    define('ESCROW_STATUS_HANDSHAKE_VERIFIED', 'escrow_handshake_verified');
    define('ESCROW_STATUS_AWAITING_CONFIRMATION', 'escrow_awaiting_confirmation');
    define('ESCROW_STATUS_COMPLETED', 'escrow_completed');
    define('ESCROW_STATUS_CANCELLED', 'escrow_cancelled');
    define('ESCROW_STATUS_REFUNDED', 'escrow_refunded');
    define('ESCROW_STATUS_DISPUTED', 'escrow_disputed');

    // ===== TIMEOUT SETTINGS =====
    define('ESCROW_PAYMENT_TIMEOUT', 3600); // 1 hour to complete payment
    define('ESCROW_HANDSHAKE_TIMEOUT', 86400); // 24 hours to complete handshake
    define('ESCROW_CONFIRMATION_TIMEOUT', 3600); // 1 hour for seller to confirm
    define('ESCROW_DISPUTE_WINDOW', 7 * 86400); // 7 days to dispute transaction

    // ===== TRANSACTION FEES =====
    define('ESCROW_FEE_PERCENTAGE', 2.5); // 2.5% platform fee
    define('ESCROW_FEE_MINIMUM', 1.50); // Minimum R1.50 fee

    // ===== QR CODE SETTINGS =====
    define('ESCROW_QR_SIZE', '300x300');
    define('ESCROW_QR_PROVIDER', 'https://api.qrserver.com/v1/create-qr-code/');

    // ===== LOGGING =====
    define('ESCROW_LOG_EVENTS', true); // Log all escrow actions
    define('ESCROW_LOG_LEVEL', 'INFO'); // DEBUG, INFO, WARNING, ERROR

    /**
     * Log escrow action to audit trail
     */
    function logEscrowAction($conn, $transaction_id, $action, $user_id = null, $details = null) {
        if (!ESCROW_LOG_EVENTS) return;

        $user_id = $user_id ?? ($_SESSION['user_id'] ?? null);
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $details_json = $details ? json_encode($details) : null;

        $stmt = $conn->prepare("
            INSERT INTO escrow_logs (transaction_id, action, user_id, ip_address, details)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("isiss", $transaction_id, $action, $user_id, $ip, $details_json);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Generate HMAC security token for QR code
     */
    function generateEscrowToken($data, $timestamp = null) {
        $timestamp = $timestamp ?? floor(time() / ESCROW_QR_REFRESH_INTERVAL);
        return hash_hmac('sha256', $data . '|' . $timestamp, ESCROW_SECRET_SALT);
    }

    /**
     * Verify HMAC security token
     */
    function verifyEscrowToken($data, $token, $allowedTimestamps = 2) {
        $currentTime = floor(time() / ESCROW_QR_REFRESH_INTERVAL);
        
        for ($i = 0; $i <= $allowedTimestamps; $i++) {
            $timestamp = $currentTime - $i;
            $expectedToken = hash_hmac('sha256', $data . '|' . $timestamp, ESCROW_SECRET_SALT);
            
            if (hash_equals($token, $expectedToken)) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Calculate escrow fee
     */
    function calculateEscrowFee($amount) {
        $fee = ($amount * ESCROW_FEE_PERCENTAGE) / 100;
        return max($fee, ESCROW_FEE_MINIMUM);
    }

    /**
     * Get transaction status with human-readable label
     */
    function getEscrowStatusLabel($status) {
        $labels = [
            ESCROW_STATUS_INITIATED => 'Escrow Initiated',
            ESCROW_STATUS_PAYMENT_PENDING => 'Awaiting Payment',
            ESCROW_STATUS_PAYMENT_COMPLETED => 'Payment Completed',
            ESCROW_STATUS_AWAITING_HANDSHAKE => 'Awaiting Handshake',
            ESCROW_STATUS_HANDSHAKE_VERIFIED => 'Handshake Verified',
            ESCROW_STATUS_AWAITING_CONFIRMATION => 'Awaiting Confirmation',
            ESCROW_STATUS_COMPLETED => 'Completed',
            ESCROW_STATUS_CANCELLED => 'Cancelled',
            ESCROW_STATUS_REFUNDED => 'Refunded',
            ESCROW_STATUS_DISPUTED => 'Disputed',
        ];
        
        return $labels[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Get escrow status color (for UI) - MzansiTrade colors
     */
    function getEscrowStatusColor($status) {
        $colors = [
            ESCROW_STATUS_INITIATED => MZANSI_PRIMARY,
            ESCROW_STATUS_PAYMENT_PENDING => MZANSI_ACCENT,
            ESCROW_STATUS_PAYMENT_COMPLETED => MZANSI_SUCCESS,
            ESCROW_STATUS_AWAITING_HANDSHAKE => MZANSI_ACCENT,
            ESCROW_STATUS_HANDSHAKE_VERIFIED => MZANSI_SUCCESS,
            ESCROW_STATUS_AWAITING_CONFIRMATION => MZANSI_ACCENT,
            ESCROW_STATUS_COMPLETED => MZANSI_SUCCESS,
            ESCROW_STATUS_CANCELLED => MZANSI_DANGER,
            ESCROW_STATUS_REFUNDED => MZANSI_DANGER,
            ESCROW_STATUS_DISPUTED => MZANSI_ACCENT,
        ];
        
        return $colors[$status] ?? MZANSI_PRIMARY;
    }
}
?>