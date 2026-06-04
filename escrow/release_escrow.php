<?php
session_start();
include 'db_config.php';

if (!isset($_SESSION['user_id'])) {
    die("Please login to release funds.");
}

if (isset($_GET['hash'])) {
    $hash = mysqli_real_escape_string($conn, $_GET['hash']);
    $current_user = $_SESSION['user_id'];


    $check_query = "SELECT item_id, buyer_id FROM transactions WHERE unique_hash = '$hash' AND status = 'escrow' LIMIT 1";
    $result = mysqli_query($conn, $check_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $trade = mysqli_fetch_assoc($result);

        if ($trade['buyer_id'] == $current_user) {
            $item_id = $trade['item_id'];

            
            mysqli_query($conn, "UPDATE transactions SET status = 'completed' WHERE unique_hash = '$hash'");
            mysqli_query($conn, "UPDATE products SET status = 'sold' WHERE id = '$item_id'");

            
            echo "<script>
                    alert('Funds released successfully! The trade is complete.');
                    window.location.href = 'dashboard.php';
                  </script>";
        } else {
            die("Unauthorized: You are not the buyer of this item.");
        }
    } else {
        die("Invalid or already completed transaction.");
    }
} else {
    header("Location: dashboard.php");
}
?>