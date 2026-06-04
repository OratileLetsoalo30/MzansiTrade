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
$buyer_id = $uid; // From auth_check.php
$payment_method = $_POST['payment_method'] ?? 'mock';

try {
    // ===== GET TRANSACTION =====
    $trans_stmt = $conn->prepare("
        SELECT t.*, p.price, u.email as seller_email
        FROM transactions t
        JOIN products p ON t.item_id = p.id
        JOIN users u ON t.seller_id = u.id
        WHERE t.unique_hash = ? AND t.buyer_id = ?
    ");
    $trans_stmt->bind_param("si", $hash, $buyer_id);
    $trans_stmt->execute();
    $trans_result = $trans_stmt->get_result();

    if ($trans_result->num_rows === 0) {
        throw new Exception("Transaction not found or unauthorized.");
    }

    $transaction = $trans_result->fetch_assoc();
    $trans_stmt->close();

    $transaction_id = $transaction['id'];
    $amount = floatval($transaction['price']);

    // ===== PROCESS MOCK PAYMENT =====
    // In production, this would call Stripe/PayPal API
    // For now, we simulate successful payment

    $conn->begin_transaction();

    // Generate mock payment reference
    $mock_payment_id = 'MOCK_' . strtoupper(bin2hex(random_bytes(8)));
    $payment_reference = $mock_payment_id;

    // Create payment record
    $payment_stmt = $conn->prepare("
        INSERT INTO escrow_payments (
            transaction_id,
            buyer_id,
            amount,
            payment_method,
            payment_reference,
            payment_status,
            mock_payment_id,
            completed_at
        ) VALUES (?, ?, ?, ?, ?, 'completed', ?, NOW())
    ");
    
    $payment_stmt->bind_param(
        "iidssss",
        $transaction_id,
        $buyer_id,
        $amount,
        $payment_method,
        $payment_reference,
        $mock_payment_id
    );

    if (!$payment_stmt->execute()) {
        throw new Exception("Failed to record payment.");
    }
    $payment_stmt->close();

    // ===== UPDATE TRANSACTION STATUS =====
    // If flow is payment_first, move to awaiting_handshake
    // If flow is qr_first, payment stays pending (shouldn't happen in qr_first)
    
    $new_status = ($transaction['escrow_flow'] === ESCROW_FLOW_PAYMENT_FIRST)
        ? ESCROW_STATUS_AWAITING_HANDSHAKE
        : ESCROW_STATUS_PAYMENT_COMPLETED;

    $update_stmt = $conn->prepare("
        UPDATE transactions 
        SET status = ?, 
            payment_status = 'completed',
            updated_at = NOW()
        WHERE id = ?
    ");
    $update_stmt->bind_param("si", $new_status, $transaction_id);
    $update_stmt->execute();
    $update_stmt->close();

    // ===== LOG ACTION =====
    logEscrowAction($conn, $transaction_id, 'payment_processed', $buyer_id, [
        'payment_method' => $payment_method,
        'amount' => $amount,
        'reference' => $payment_reference,
        'mock_payment_id' => $mock_payment_id
    ]);

    $conn->commit();

    // ===== DETERMINE NEXT URL =====
    $next_url = ($transaction['escrow_flow'] === ESCROW_FLOW_PAYMENT_FIRST)
        ? "handshake.php?hash=" . $hash
        : "payment_confirmation.php?hash=" . $hash;

    echo json_encode([
        'success' => true,
        'message' => 'Payment processed successfully',
        'transaction_id' => $transaction_id,
        'payment_id' => $mock_payment_id,
        'amount' => $amount,
        'status' => $new_status,
        'next_url' => $next_url,
        'note' => 'This is a mock payment. In production, integrate with Stripe/PayPal.'
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