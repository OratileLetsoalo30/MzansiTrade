<?php
session_start();
require_once 'db.php'; 

// 1. Security Check: Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php"); 
    exit();
}

// 2. Process the transaction
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $amount = mysqli_real_escape_string($conn, $_POST['amount']);
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    
    $transaction_type = 'payment'; 
    $status = 'pending'; 
    $reference_id = 'ESC-'.strtoupper(bin2hex(random_bytes(4))); 
    $description = "Escrow secured for Product ID: " . $product_id; 

    $stmt = $conn->prepare("INSERT INTO transactions (user_id, amount, transaction_type, status, reference_id, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("idssss", $user_id, $amount, $transaction_type, $status, $reference_id, $description);

    // 3. Render Output using your MzansiTrade Design System classes
    echo "
    <!DOCTYPE html>
    <html lang='en'>
    <head>
        <link rel='stylesheet' href='style.css'>
        <title>Payment Secured | MzansiTrade</title>
    </head>
    <body class='mzansi-login-body'>
        <div class='container d-flex justify-content-center align-items-center' style='min-height: 100vh;'>
            <div class='escrow-success-card'>
    ";

    if ($stmt->execute()) {
        echo "
                <h3 style='color: var(--primary-teal); font-family: Syne;'>Payment Secured!</h3>
                <p style='color: var(--text-muted);'>Your escrow payment has been safely placed in pending status.</p>
                
                <div class='escrow-ref-box'>
                    <p class='mb-0'>Reference: <strong>$reference_id</strong></p>
                </div>
                
                <a href='index.php' class='btn w-100 escrow-btn'>Back to Marketplace</a>
        ";
    } else {
        echo "
                <h3 style='color: var(--accent-orange);'>Transaction Issue</h3>
                <p>We encountered an error: " . $stmt->error . "</p>
                <a href='index.php' class='btn btn-secondary w-100'>Return Home</a>
        ";
    }

    echo "
            </div>
        </div>
    </body>
    </html>";

    $stmt->close();
    $conn->close();
}
?>