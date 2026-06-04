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

if (!isset($_POST['hash'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing transaction hash']);
    exit();
}

$hash = mysqli_real_escape_string($conn, $_POST['hash']);
$user_id = $uid; // From auth_check.php

try {
    // ===== GET TRANSACTION =====
    $trans_stmt = $conn->prepare("
        SELECT t.*, p.price, p.item_name, u.email as seller_email, ub.email as buyer_email
        FROM transactions t
        JOIN products p ON t.item_id = p.id
        JOIN users u ON t.seller_id = u.id
        JOIN users ub ON t.buyer_id = ub.id
        WHERE t.unique_hash = ?
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
    $seller_id = $transaction['seller_id'];
    $buyer_id = $transaction['buyer_id'];
    $amount = floatval($transaction['price']);

    // ===== AUTHORIZATION CHECK =====
    // Only seller can release (after buyer confirms payment received)
    // Or system admin can release if disputed
    if ($user_id != $seller_id) {
        throw new Exception("Only the seller can release funds.");
    }

    // ===== STATUS VALIDATION =====
    // Can only release if awaiting_confirmation (payment_first flow)
    // or payment_completed (qr_first flow)
    
    $valid_statuses = [
        ESCROW_STATUS_AWAITING_CONFIRMATION,
        ESCROW_STATUS_PAYMENT_COMPLETED,
        ESCROW_STATUS_HANDSHAKE_VERIFIED
    ];

    if (!in_array($transaction['status'], $valid_statuses)) {
        throw new Exception("Transaction cannot be released at this stage. Status: " . $transaction['status']);
    }

    // ===== VERIFY BOTH PARTIES HAVE VERIFIED =====
    if (!$transaction['qr_verified'] || !$transaction['seller_confirmed']) {
        throw new Exception("Both parties must verify QR code before releasing funds.");
    }

    $conn->begin_transaction();

    // ===== CALCULATE FEES =====
    $platform_fee = calculateEscrowFee($amount);
    $seller_receives = $amount - $platform_fee;

    // ===== UPDATE TRANSACTION =====
    $release_stmt = $conn->prepare("
        UPDATE transactions 
        SET status = ?,
            completed_at = NOW(),
            updated_at = NOW(),
            notes = CONCAT(COALESCE(notes, ''), 'Funds released. Platform fee: R', ?, ' | Seller receives: R', ?, '\n')
        WHERE id = ?
    ");
    
    $fee_str = number_format($platform_fee, 2);
    $receives_str = number_format($seller_receives, 2);
    $completed_status = ESCROW_STATUS_COMPLETED;
    
    $release_stmt->bind_param(
        "sssi",
        $completed_status,
        $fee_str,
        $receives_str,
        $transaction_id
    );
    $release_stmt->execute();
    $release_stmt->close();

    // ===== UPDATE PRODUCT STATUS =====
    $product_stmt = $conn->prepare("
        UPDATE products 
        SET status = 'sold'
        WHERE id = ?
    ");
    $product_stmt->bind_param("i", $transaction['item_id']);
    $product_stmt->execute();
    $product_stmt->close();

    // ===== RECORD FINANCIAL TRANSACTION =====
    // In production, transfer funds to seller's wallet or bank account
    // For now, just log it
    
    $wallet_note = "Escrow release for transaction #" . $transaction_id . " - " . $transaction['item_name'];

    // If you have a wallet/ledger system, insert here:
    // INSERT INTO seller_wallet (seller_id, amount, type, reference_id) 
    // VALUES (?, ?, 'escrow_release', ?)

    // ===== LOG ACTIONS =====
    logEscrowAction($conn, $transaction_id, 'released', $seller_id, [
        'amount' => $amount,
        'platform_fee' => $platform_fee,
        'seller_receives' => $seller_receives
    ]);

    $conn->commit();

    // ===== SEND NOTIFICATIONS =====
    // TODO: Send email to seller confirming payment received
    // TODO: Send email to buyer confirming transaction complete
    // TODO: Send notification to both parties

    echo json_encode([
        'success' => true,
        'message' => 'Escrow funds released successfully',
        'transaction_id' => $transaction_id,
        'item_name' => $transaction['item_name'],
        'amount' => $amount,
        'platform_fee' => $platform_fee,
        'seller_receives' => $seller_receives,
        'status' => ESCROW_STATUS_COMPLETED,
        'completed_at' => date('Y-m-d H:i:s'),
        'seller_email' => $transaction['seller_email'],
        'buyer_email' => $transaction['buyer_email']
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