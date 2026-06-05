<?php
session_start();
require_once '../db_config.php';

$error_msg = "";

if (isset($_POST['login_btn'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['username']);
    $pass  = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$uname'";
    $run   = mysqli_query($conn, $query);
    
    if ($run && mysqli_num_rows($run) > 0) {
        $user = mysqli_fetch_assoc($run);
        
        if (password_verify($pass, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: index.php");
            exit();
        } else {
            $error_msg = "Incorrect Password";
        }
    } else {
        $error_msg = "User not found";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $words['login_title'] ?? 'Login'; ?> | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    
    <style>
        /* This background class ensures the gradient covers the full page */
        body { 
            background: linear-gradient(180deg, #0b3c4d 0%, #061920 100%) !important;
            background-attachment: fixed;
            min-height: 100vh;
        }
        /* Specific override to hide chat widgets as requested */
        .chatbot-bubble-widget, [class*="chat-bubble"], [id*="chat"] {
            display: none !important;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?> 
    
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4">
                    <div class="card-body">
                        <?php if (!empty($error_msg)): ?>
                            <div class="alert alert-danger"><?php echo $error_msg; ?></div>
                        <?php endif; ?>

                        <h3 class="text-center mb-4 fw-bold"><?php echo $words['login_title'] ?? 'Login'; ?></h3>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary"><?php echo $words['username'] ?? 'Username'; ?></label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-secondary"><?php echo $words['password'] ?? 'Password'; ?></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="login_btn" class="btn btn-primary w-100 py-2">
                                <?php echo $words['signin_btn'] ?? 'Sign In'; ?>
                            </button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="register.php" class="text-decoration-none text-primary small">
                                <?php echo $words['need_account'] ?? 'Create an account? Register here'; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>