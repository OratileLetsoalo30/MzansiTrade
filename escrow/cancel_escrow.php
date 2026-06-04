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
$reason = mysqli_real_escape_string($conn, $_POST['reason'] ?? 'No reason provided');
$user_id = $uid; // From auth_check.php

try {
    // ===== GET TRANSACTION =====
    $trans_stmt = $conn->prepare("
        SELECT t.*, p.item_name, u.email as seller_email, ub.email as buyer_email
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

    // ===== AUTHORIZATION =====
    // Both buyer and seller can cancel, but at different stages
    if ($user_id != $transaction['buyer_id'] && $user_id != $transaction['seller_id']) {
        throw new Exception("You are not authorized to cancel this transaction.");
    }

    // ===== CHECK CANCELLATION RULES =====
    $user_role = ($user_id == $transaction['buyer_id']) ? 'buyer' : 'seller';
    $can_cancel = false;
    $cancellation_reason = '';

    // Buyer can cancel if:
    // - Not yet paid (payment_pending)
    // - Seller hasn't confirmed (handshake stage)
    if ($user_role === 'buyer') {
        if (in_array($transaction['status'], [
            ESCROW_STATUS_PAYMENT_PENDING,
            ESCROW_STATUS_AWAITING_HANDSHAKE,
            ESCROW_STATUS_HANDSHAKE_VERIFIED
        ])) {
            $can_cancel = true;
            $cancellation_reason = 'Buyer cancelled transaction';
        }
    }

    // Seller can cancel if:
    // - Buyer hasn't verified QR (waiting for handshake)
    // - No payment received yet (qr_first flow)
    if ($user_role === 'seller') {
        if (in_array($transaction['status'], [
            ESCROW_STATUS_AWAITING_HANDSHAKE,
            ESCROW_STATUS_HANDSHAKE_VERIFIED,
            ESCROW_STATUS_PAYMENT_PENDING
        ]) && !$transaction['qr_verified']) {
            $can_cancel = true;
            $cancellation_reason = 'Seller cancelled transaction';
        }
    }

    if (!$can_cancel) {
        throw new Exception("Cannot cancel transaction at this stage. Status: " . $transaction['status']);
    }

    $conn->begin_transaction();

    // ===== DETERMINE REFUND LOGIC =====
    $refund_amount = null;
    $new_status = ESCROW_STATUS_CANCELLED;

    if ($transaction['payment_status'] === 'completed') {
        // Payment was made, need to refund
        $refund_amount = floatval($transaction['escrow_amount']);
        $new_status = ESCROW_STATUS_REFUNDED;

        // Create refund record
        $refund_stmt = $conn->prepare("
            UPDATE escrow_payments 
            SET payment_status = 'refunded'
            WHERE transaction_id = ?
        ");
        $refund_stmt->bind_param("i", $transaction_id);
        $refund_stmt->execute();
        $refund_stmt->close();
    }

    // ===== UPDATE TRANSACTION =====
    $cancel_stmt = $conn->prepare("
        UPDATE transactions 
        SET status = ?,
            payment_status = CASE 
                WHEN payment_status = 'completed' THEN 'refunded'
                ELSE 'cancelled'
            END,
            updated_at = NOW(),
            notes = CONCAT(COALESCE(notes, ''), ?, ' | Cancelled by: ', ?, ' | Reason: ', ?, '\n')
        WHERE id = ?
    ");
    
    $cancel_reason = $cancellation_reason . ' (' . $user_role . ')';
    
    $cancel_stmt->bind_param(
        "sssssi",
        $new_status,
        $cancel_reason,
        $user_role,
        $reason,
        $transaction_id
    );
    $cancel_stmt->execute();
    $cancel_stmt->close();

    // ===== RESTORE PRODUCT STATUS =====
    $product_stmt = $conn->prepare("
        UPDATE products 
        SET status = 'available'
        WHERE id = ?
    ");
    $product_stmt->bind_param("i", $transaction['item_id']);
    $product_stmt->execute();
    $product_stmt->close();

    // ===== LOG ACTION =====
    logEscrowAction($conn, $transaction_id, 'cancelled', $user_id, [
        'cancelled_by' => $user_role,
        'reason' => $reason,
        'refund_amount' => $refund_amount,
        'status' => $new_status
    ]);

    $conn->commit();

    echo json_encode([
        'success' => true,
        'message' => 'Transaction cancelled successfully',
        'transaction_id' => $transaction_id,
        'cancelled_by' => $user_role,
        'status' => $new_status,
        'refund_amount' => $refund_amount,
        'refund_processed' => $refund_amount ? true : false,
        'notes' => 'If payment was made, refund will be processed within 3-5 business days.',
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