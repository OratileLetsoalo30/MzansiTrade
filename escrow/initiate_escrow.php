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

if (!isset($_POST['item_id']) || !isset($_POST['flow_type'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing required parameters']);
    exit();
}

$item_id = intval($_POST['item_id']);
$flow_type = in_array($_POST['flow_type'], [ESCROW_FLOW_PAYMENT_FIRST, ESCROW_FLOW_QR_FIRST]) 
    ? $_POST['flow_type'] 
    : ESCROW_FLOW_PAYMENT_FIRST;

$buyer_id = $uid; // From auth_check.php
$hash = bin2hex(random_bytes(16));

try {
    $conn->begin_transaction();

    // ===== SECURITY CHECKS =====
    // 1. Verify product exists and is available
    $product_stmt = $conn->prepare("
        SELECT id, user_id, status, item_name, price 
        FROM products 
        WHERE id = ? 
        FOR UPDATE
    ");
    $product_stmt->bind_param("i", $item_id);
    $product_stmt->execute();
    $product_res = $product_stmt->get_result();

    if ($product_res->num_rows === 0) {
        throw new Exception("Product listing not found.");
    }

    $product = $product_res->fetch_assoc();
    $seller_id = $product['user_id'];
    $item_name = $product['item_name'];
    $price = $product['price'];
    $product_stmt->close();

    // 2. Check product status
    if ($product['status'] !== 'available') {
        throw new Exception("This item is no longer available.");
    }

    // 3. Prevent self-purchase
    if ($seller_id == $buyer_id) {
        throw new Exception("You cannot purchase your own item.");
    }

    // 4. Check if buyer/seller has active disputes
    $dispute_check = $conn->prepare("
        SELECT id FROM transactions 
        WHERE status = ? 
        AND (buyer_id = ? OR seller_id = ?) 
        LIMIT 1
    ");
    $dispute_status = ESCROW_STATUS_DISPUTED;
    $dispute_check->bind_param("sii", $dispute_status, $buyer_id, $buyer_id);
    $dispute_check->execute();
    
    if ($dispute_check->get_result()->num_rows > 0) {
        throw new Exception("You have an active dispute and cannot initiate transactions.");
    }
    $dispute_check->close();

    // ===== CREATE TRANSACTION =====
    $escrow_amount = floatval($price);
    $initial_status = ($flow_type === ESCROW_FLOW_PAYMENT_FIRST) 
        ? ESCROW_STATUS_PAYMENT_PENDING 
        : ESCROW_STATUS_AWAITING_HANDSHAKE;

    $transaction_stmt = $conn->prepare("
        INSERT INTO transactions (
            item_id, 
            buyer_id, 
            seller_id, 
            unique_hash, 
            status, 
            escrow_flow, 
            escrow_amount,
            payment_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");
    
    $transaction_stmt->bind_param(
        "iiisssd",
        $item_id,
        $buyer_id,
        $seller_id,
        $hash,
        $initial_status,
        $flow_type,
        $escrow_amount
    );

    if (!$transaction_stmt->execute()) {
        throw new Exception("Failed to create escrow transaction.");
    }

    $transaction_id = $conn->insert_id;
    $transaction_stmt->close();

    // ===== UPDATE PRODUCT STATUS =====
    $product_update = $conn->prepare("
        UPDATE products 
        SET status = 'pending' 
        WHERE id = ?
    ");
    $product_update->bind_param("i", $item_id);
    $product_update->execute();
    $product_update->close();

    // ===== LOG ACTION =====
    logEscrowAction($conn, $transaction_id, 'initiated', $buyer_id, [
        'flow_type' => $flow_type,
        'item_id' => $item_id,
        'amount' => $escrow_amount
    ]);

    $conn->commit();

    // ===== DETERMINE NEXT STEP =====
    $next_url = ($flow_type === ESCROW_FLOW_PAYMENT_FIRST)
        ? "../escrow/payment.php?hash=" . $hash
        : "../escrow/handshake.php?hash=" . $hash;

    echo json_encode([
        'success' => true,
        'message' => 'Escrow initiated successfully',
        'transaction_id' => $transaction_id,
        'hash' => $hash,
        'flow_type' => $flow_type,
        'item_name' => $item_name,
        'amount' => $escrow_amount,
        'redirect_url' => $next_url
    ]);

} catch (Exception $e) {
    $conn->rollback();
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>