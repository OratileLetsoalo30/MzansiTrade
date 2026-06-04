<?php
session_start();
// Go up to root, then into the auth folder
include '../db_config.php';
include '../auth/auth_check.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['item_id'])) {
    $item_id = intval($_POST['item_id']);
    $buyer_id = $uid; 
    $hash = bin2hex(random_bytes(16)); 

    $conn->begin_transaction();

    try {
        $product_stmt = $conn->prepare("SELECT user_id, status, item_name FROM products WHERE id = ? FOR UPDATE");
        $product_stmt->bind_param("i", $item_id);
        $product_stmt->execute();
        $product_res = $product_stmt->get_result();
        
        if (!$product_res || $product_res->num_rows === 0) {
            throw new Exception("Product listing not found in the MzansiTrade directory.");
        }
        
        $product_data = $product_res->fetch_assoc();
        $seller_id = $product_data['user_id'];
        $current_status = $product_data['status'];
        $item_name = $product_data['item_name'];
        $product_stmt->close();

        if ($current_status !== 'available') {
            throw new Exception("This item is no longer available for secure trade.");
        }

        if ($seller_id == $buyer_id) {
            throw new Exception("Security Alert: You cannot initiate an escrow transaction for your own item.");
        }
        
        $escrow_stmt = $conn->prepare("INSERT INTO transactions (item_id, buyer_id, seller_id, unique_hash, status) VALUES (?, ?, ?, ?, 'escrow')");
        $escrow_stmt->bind_param("iiis", $item_id, $buyer_id, $seller_id, $hash);
        
        if (!$escrow_stmt->execute()) {
            throw new Exception("Security Ledger failure. Please try again.");
        }
        $escrow_stmt->close();

        $update_stmt = $conn->prepare("UPDATE products SET status = 'pending' WHERE id = ?");
        $update_stmt->bind_param("i", $item_id);
        $update_stmt->execute();
        $update_stmt->close();

        $conn->commit();
        
        // Success: Redirect to the verify page in the same folder
        echo "<script>
                alert('Success! " . addslashes($item_name) . " has been secured in Escrow. Please prepare for your safe street-level meetup.'); 
                window.location='verify_qr.php?hash=" . $hash . "';
              </script>";
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        echo "<script>
                alert('Escrow Protection Fault: " . addslashes($e->getMessage()) . "'); 
                window.location='../index.php';
              </script>";
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?>