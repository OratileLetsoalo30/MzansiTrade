<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$uid = $_SESSION['user_id'];

// 2. Fetch user data (Assuming $conn is already included via db_config.php)
if (isset($conn)) {
    $stmt = $conn->prepare("SELECT is_verified, phone FROM users WHERE id = ? LIMIT 1");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $user_res = $stmt->get_result();
    
    if ($user_res && $user_data = $user_res->fetch_assoc()) {
        $is_verified = $user_data['is_verified'] ?? 0;
        
        // Store phone in session if it exists
        if (!empty($user_data['phone'])) {
            $_SESSION['phone'] = $user_data['phone'];
        }
    } else {
        $is_verified = 0;
    }
    $stmt->close();
} else {
    die("Database connection missing.");
}
?>