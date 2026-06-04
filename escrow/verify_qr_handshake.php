<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    die("Unauthorized access.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['qr_hash'])) {
    $raw_payload = $_POST['qr_hash'];
    $buyer_id = $_SESSION['user_id'];
    
    
    $payload_parts = explode('|', $raw_payload);
    if (count($payload_parts) !== 2) {
        die("Verification Failed: Corrupted or invalid QR payload structure.");
    }
    
    $scanned_hash = mysqli_real_escape_string($conn, $payload_parts[0]);
    $scanned_token = $payload_parts[1];
    
    
    $secret_salt = "MKasiSecureSystem2026";
    $current_window = floor(time() / 60);
    
    $valid_token_current = hash_hmac('sha256', $scanned_hash . $current_window, $secret_salt);
    $valid_token_previous = hash_hmac('sha256', $scanned_hash . ($current_window - 1), $secret_salt);
    
    if ($scanned_token !== $valid_token_current && $scanned_token !== $valid_token_previous) {
        die("Verification Failed: The scanned QR code has expired. Please ask the seller to present a fresh screen.");
    }


    $check_query = "SELECT * FROM transactions 
                    WHERE unique_hash = '$scanned_hash' 
                    AND buyer_id = '$buyer_id' 
                    AND status = 'escrow' 
                    LIMIT 1";
    $result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($result) === 1) {
        $transaction = mysqli_fetch_assoc($result);
        $item_id = $transaction['item_id'];

        
        mysqli_begin_transaction($conn);

        try {
            $lock_check = mysqli_query($conn, "SELECT status FROM transactions WHERE id = '{$transaction['id']}' FOR UPDATE");
            $current_state = mysqli_fetch_assoc($lock_check);
            
            if ($current_state['status'] !== 'escrow') {
                throw new Exception("Transaction has already been processed or closed.");
            }

            
            $update_tx = "UPDATE transactions SET status = 'completed' WHERE id = '{$transaction['id']}'";
            mysqli_query($conn, $update_tx);

            
            $update_product = "UPDATE products SET status = 'sold' WHERE id = '$item_id'";
            mysqli_query($conn, $update_product);

            
            mysqli_commit($conn);

            header("Location: dashboard.php?status=success");
            exit();

        } catch (Exception $e) {
            mysqli_rollback($conn);
            die("Cryptographic Handshake Interrupted: " . $e->getMessage());
        }

    } else {
        die("Verification Failed: You are not authorized to release funds for this product.");
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>