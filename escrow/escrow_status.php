<?php
session_start();
include '../db_config.php';
include '../auth/auth_check.php';
include 'escrow_config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit();
}

// Support both GET and POST for flexibility
$hash = $_REQUEST['hash'] ?? null;

if (!$hash) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Missing transaction hash']);
    exit();
}

$hash = mysqli_real_escape_string($conn, $hash);
$user_id = $uid; // From auth_check.php

try {
    // ===== GET TRANSACTION =====
    $trans_stmt = $conn->prepare("
        SELECT 
            t.id,
            t.unique_hash,
            t.status,
            t.escrow_flow,
            t.escrow_amount,
            t.buyer_id,
            t.seller_id,
            t.qr_verified,
            t.seller_confirmed,
            t.payment_status,
            t.item_id,
            t.created_at,
            t.updated_at,
            t.completed_at,
            p.item_name,
            p.price,
            ub.email as buyer_email,
            ub.username as buyer_username,
            us.email as seller_email,
            us.username as seller_username
        FROM transactions t
        JOIN products p ON t.item_id = p.id
        JOIN users ub ON t.buyer_id = ub.id
        JOIN users us ON t.seller_id = us.id
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

    // ===== AUTHORIZATION =====
    // Users can only see their own transactions
    if ($user_id != $transaction['buyer_id'] && $user_id != $transaction['seller_id']) {
        throw new Exception("You are not authorized to view this transaction.");
    }

    // ===== DETERMINE CURRENT STATUS FOR UI =====
    $status_label = getEscrowStatusLabel($transaction['status']);
    $status_color = getEscrowStatusColor($transaction['status']);

    // ===== BUILD STATUS TIMELINE =====
    $timeline = buildEscrowTimeline($transaction);

    // ===== CALCULATE TIME REMAINING =====
    $time_info = calculateEscrowTimeouts($transaction);

    // ===== GET PAYMENT DETAILS (if exists) =====
    $payment_details = null;
    if ($transaction['payment_status'] === 'completed') {
        $payment_stmt = $conn->prepare("
            SELECT 
                payment_method,
                payment_reference,
                mock_payment_id,
                completed_at
            FROM escrow_payments
            WHERE transaction_id = ?
            LIMIT 1
        ");
        $payment_stmt->bind_param("i", $transaction['id']);
        $payment_stmt->execute();
        $payment_result = $payment_stmt->get_result();
        
        if ($payment_result->num_rows > 0) {
            $payment_details = $payment_result->fetch_assoc();
        }
        $payment_stmt->close();
    }

    // ===== DETERMINE USER ROLE =====
    $user_role = ($user_id == $transaction['buyer_id']) ? 'buyer' : 'seller';

    echo json_encode([
        'success' => true,
        'transaction' => [
            'id' => $transaction['id'],
            'hash' => $transaction['unique_hash'],
            'status' => $transaction['status'],
            'status_label' => $status_label,
            'status_color' => $status_color,
            'flow_type' => $transaction['escrow_flow'],
            'flow_label' => $transaction['escrow_flow'] === ESCROW_FLOW_PAYMENT_FIRST ? 'Pay First' : 'QR First',
        ],
        'item' => [
            'id' => $transaction['item_id'],
            'name' => $transaction['item_name'],
            'price' => $transaction['price']
        ],
        'parties' => [
            'buyer' => [
                'id' => $transaction['buyer_id'],
                'username' => $transaction['buyer_username'],
                'email' => $transaction['buyer_email'],
                'verified' => (bool)$transaction['qr_verified']
            ],
            'seller' => [
                'id' => $transaction['seller_id'],
                'username' => $transaction['seller_username'],
                'email' => $transaction['seller_email'],
                'confirmed' => (bool)$transaction['seller_confirmed']
            ]
        ],
        'verification' => [
            'qr_verified_by_buyer' => (bool)$transaction['qr_verified'],
            'confirmed_by_seller' => (bool)$transaction['seller_confirmed'],
            'both_verified' => $transaction['qr_verified'] && $transaction['seller_confirmed']
        ],
        'payment' => [
            'status' => $transaction['payment_status'],
            'details' => $payment_details
        ],
        'timeline' => $timeline,
        'time_remaining' => $time_info,
        'user_role' => $user_role,
        'created_at' => $transaction['created_at'],
        'updated_at' => $transaction['updated_at'],
        'completed_at' => $transaction['completed_at']
    ]);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}

/**
 * Build a timeline of events for the transaction
 */
function buildEscrowTimeline($transaction) {
    $timeline = [];

    // Initiated
    $timeline[] = [
        'event' => 'Transaction Initiated',
        'status' => 'completed',
        'time' => $transaction['created_at'],
        'description' => 'Escrow transaction created'
    ];

    if ($transaction['escrow_flow'] === ESCROW_FLOW_PAYMENT_FIRST) {
        // Payment step
        if ($transaction['payment_status'] === 'completed') {
            $timeline[] = [
                'event' => 'Payment Completed',
                'status' => 'completed',
                'time' => $transaction['updated_at'],
                'description' => 'Buyer has completed payment'
            ];
        } else {
            $timeline[] = [
                'event' => 'Awaiting Payment',
                'status' => in_array($transaction['status'], [
                    ESCROW_STATUS_PAYMENT_PENDING,
                    ESCROW_STATUS_AWAITING_HANDSHAKE
                ]) ? 'active' : 'pending',
                'description' => 'Waiting for buyer to complete payment'
            ];
        }

        // Handshake step
        if ($transaction['qr_verified'] && $transaction['seller_confirmed']) {
            $timeline[] = [
                'event' => 'Handshake Verified',
                'status' => 'completed',
                'time' => $transaction['updated_at'],
                'description' => 'Both parties confirmed meeting'
            ];
        } else {
            $timeline[] = [
                'event' => 'Handshake Verification',
                'status' => ($transaction['payment_status'] === 'completed') ? 'active' : 'pending',
                'description' => $transaction['qr_verified'] ? 'Buyer verified, waiting for seller' : 'Awaiting QR code verification'
            ];
        }
    } else {
        // QR First flow
        if ($transaction['qr_verified'] && $transaction['seller_confirmed']) {
            $timeline[] = [
                'event' => 'Handshake Verified',
                'status' => 'completed',
                'time' => $transaction['updated_at'],
                'description' => 'Both parties confirmed meeting'
            ];
        } else {
            $timeline[] = [
                'event' => 'Handshake Verification',
                'status' => 'active',
                'description' => 'Awaiting QR code verification'
            ];
        }

        if ($transaction['payment_status'] === 'completed') {
            $timeline[] = [
                'event' => 'Payment Completed',
                'status' => 'completed',
                'time' => $transaction['updated_at'],
                'description' => 'Payment has been processed'
            ];
        } else {
            $timeline[] = [
                'event' => 'Payment Processing',
                'status' => (in_array($transaction['status'], [
                    ESCROW_STATUS_PAYMENT_PENDING,
                    ESCROW_STATUS_HANDSHAKE_VERIFIED
                ])) ? 'active' : 'pending',
                'description' => 'Waiting for payment processing'
            ];
        }
    }

    // Completion
    if ($transaction['status'] === ESCROW_STATUS_COMPLETED) {
        $timeline[] = [
            'event' => 'Transaction Completed',
            'status' => 'completed',
            'time' => $transaction['completed_at'],
            'description' => 'Funds released to seller'
        ];
    } else {
        $timeline[] = [
            'event' => 'Transaction Completion',
            'status' => 'pending',
            'description' => 'Final step pending'
        ];
    }

    return $timeline;
}

/**
 * Calculate time remaining for various escrow stages
 */
function calculateEscrowTimeouts($transaction) {
    $created = strtotime($transaction['created_at']);
    $now = time();
    $elapsed = $now - $created;

    return [
        'payment_timeout_seconds' => max(0, ESCROW_PAYMENT_TIMEOUT - $elapsed),
        'handshake_timeout_seconds' => max(0, ESCROW_HANDSHAKE_TIMEOUT - $elapsed),
        'created_ago_seconds' => $elapsed,
        'created_ago_formatted' => formatTimeAgo($created)
    ];
}

/**
 * Format time ago in human-readable format
 */
function formatTimeAgo($timestamp) {
    $seconds = time() - $timestamp;
    
    if ($seconds < 60) return $seconds . 's ago';
    if ($seconds < 3600) return floor($seconds / 60) . 'm ago';
    if ($seconds < 86400) return floor($seconds / 3600) . 'h ago';
    return floor($seconds / 86400) . 'd ago';
}
?>