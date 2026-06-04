<?php
session_start();
include '../db_config.php';
include '../auth/auth_check.php';
include 'escrow_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

if (!isset($_POST['hash']) || !isset($_POST['qr_token'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing hash or QR token']);
    exit();
}

$hash = mysqli_real_escape_string($conn, $_POST['hash']);
$qr_token = mysqli_real_escape_string($conn, $_POST['qr_token']);
$user_id = $uid; // From auth_check.php
$user_role = null; // Will determine if buyer or seller

try {
    // ===== GET TRANSACTION =====
    $trans_stmt = $conn->prepare("
        SELECT id, buyer_id, seller_id, status, escrow_flow, unique_hash, escrow_amount, item_id
        FROM transactions 
        WHERE unique_hash = ?
        LIMIT 1
    ");
    $trans_stmt->bind_param("s", $hash);
    $trans_stmt->execute();
    $trans_result = $trans_stmt->get_result();

    if ($trans_result->num_rows === 0) {
        throw new Exception("Transaction not found.");
    }

    $transaction = $trans_result->fetch_assoc();
    $trans_stmt->close();

    $transaction_id = $transaction['id'];

    // ===== DETERMINE USER ROLE =====
    if ($user_id == $transaction['buyer_id']) {
        $user_role = 'buyer';
    } elseif ($user_id == $transaction['seller_id']) {
        $user_role = 'seller';
    } else {
        throw new Exception("You are not authorized for this transaction.");
    }

    // ===== VERIFY QR TOKEN =====
    // Token should be: hash|security_token
    if (strpos($qr_token, '|') === false) {
        throw new Exception("Invalid QR format.");
    }

    list($token_hash, $token_signature) = explode('|', $qr_token, 2);

    if ($token_hash !== $hash) {
        throw new Exception("QR code hash mismatch.");
    }

    if (!verifyEscrowToken($hash, $token_signature, 2)) {
        throw new Exception("QR code has expired or is invalid. Please request a new QR code.");
    }

    // ===== CHECK TRANSACTION STATUS =====
    // Allow verification if:
    // - flow is payment_first and status is awaiting_handshake
    // - flow is qr_first and status is awaiting_handshake
    
    if (!in_array($transaction['status'], [
        ESCROW_STATUS_AWAITING_HANDSHAKE,
        ESCROW_STATUS_HANDSHAKE_VERIFIED,
        ESCROW_STATUS_AWAITING_CONFIRMATION
    ])) {
        throw new Exception("Transaction status does not allow QR verification.");
    }

    // ===== UPDATE VERIFICATION STATUS =====
    $conn->begin_transaction();

    if ($user_role === 'buyer') {
        // Buyer verified QR
        $verify_stmt = $conn->prepare("
            UPDATE transactions 
            SET qr_verified = 1,
                updated_at = NOW()
            WHERE id = ?
        ");
        $verify_stmt->bind_param("i", $transaction_id);
        $verify_stmt->execute();
        $verify_stmt->close();

        $action = 'buyer_qr_verified';
        
    } else {
        // Seller confirmed QR
        $confirm_stmt = $conn->prepare("
            UPDATE transactions 
            SET seller_confirmed = 1,
                updated_at = NOW()
            WHERE id = ?
        ");
        $confirm_stmt->bind_param("i", $transaction_id);
        $confirm_stmt->execute();
        $confirm_stmt->close();

        $action = 'seller_qr_confirmed';
    }

    // ===== CHECK IF BOTH HAVE VERIFIED =====
    $check_stmt = $conn->prepare("
        SELECT qr_verified, seller_confirmed 
        FROM transactions 
        WHERE id = ?
    ");
    $check_stmt->bind_param("i", $transaction_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $verification_status = $check_result->fetch_assoc();
    $check_stmt->close();

    $both_verified = $verification_status['qr_verified'] && $verification_status['seller_confirmed'];

    // ===== UPDATE STATUS IF BOTH VERIFIED =====
    if ($both_verified) {
        // For payment_first: move to awaiting_confirmation
        // For qr_first: payment needs to be processed now
        
        if ($transaction['escrow_flow'] === ESCROW_FLOW_PAYMENT_FIRST) {
            $new_status = ESCROW_STATUS_AWAITING_CONFIRMATION;
        } else {
            $new_status = ESCROW_STATUS_PAYMENT_PENDING;
        }

        $status_stmt = $conn->prepare("
            UPDATE transactions 
            SET status = ?
            WHERE id = ?
        ");
        $status_stmt->bind_param("si", $new_status, $transaction_id);
        $status_stmt->execute();
        $status_stmt->close();
    } else {
        $new_status = ESCROW_STATUS_HANDSHAKE_VERIFIED;
    }

    // ===== LOG ACTION =====
    logEscrowAction($conn, $transaction_id, $action, $user_id, [
        'user_role' => $user_role,
        'both_verified' => $both_verified
    ]);

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'QR verification successful',
        'transaction_id' => $transaction_id,
        'user_role' => $user_role,
        'qr_verified' => $verification_status['qr_verified'],
        'seller_confirmed' => $verification_status['seller_confirmed'],
        'both_verified' => $both_verified,
        'next_status' => $new_status,
        'flow_type' => $transaction['escrow_flow'],
        'next_action' => $both_verified 
            ? ($transaction['escrow_flow'] === ESCROW_FLOW_PAYMENT_FIRST ? 'release_funds' : 'process_payment')
            : 'waiting_for_other_party'
    ]);

} catch (Exception $e) {
    if ($conn->inTransaction()) {
        $conn->rollback();
    }
    
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>