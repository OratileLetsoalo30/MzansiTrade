<?php
session_start();

//FIXED: Hardcoded to point directly actual path
require_once '../config/db.php';

// Redirect to root index if user session is active
if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit();
}

// Show Splash Screen on initial login file open load
$showSplash = !isset($_SESSION['login_splash_shown']);
$_SESSION['login_splash_shown'] = true;

$error_msg = "";

if (isset($_POST['login_btn'])) {
    $uname = mysqli_real_escape_string($conn, trim($_POST['username']));
    $pass  = $_POST['password'];

    $query = "SELECT * FROM users WHERE username='$uname' LIMIT 1";
    $run   = mysqli_query($conn, $query);
    
    if ($run && mysqli_num_rows($run) > 0) {
        $user = mysqli_fetch_assoc($run);
        
        // Verifies against your password_hash layout column
        if (password_verify($pass, $user['password_hash'])) {
            $_SESSION['user_id']  = $user['id'] ?? $user['user_id'];
            $_SESSION['username'] = $user['username'];
            
            // FIXED: Relative path stepped up out of subfolder to route to global index
            header("Location: ../index.php");
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
    
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        body { 
            background: linear-gradient(180deg, #0b3c4d 0%, #061920 100%) !important;
            background-attachment: fixed;
            min-height: 100vh;
        }
        .chatbot-bubble-widget, [class*="chat-bubble"], [id*="chat"] {
            display: none !important;
        }
    </style>
</head>
<body>
    
    <?php if ($showSplash): ?>
    <div id="splash-screen" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center; justify-content: center; z-index: 9999; background: linear-gradient(180deg, #0b3c4d 0%, #061920 100%); transition: opacity 0.6s ease-in-out;">
        <img src="../assets/img/logo.png" alt="MzansiTrade Logo" style="max-width: 250px; height: auto; margin-bottom: 30px;" onerror="this.style.display='none';">
        <div class="spinner-border" style="color: #ffc107; width: 3rem; height: 3rem;" role="status"></div>
    </div>
    <script>
        window.addEventListener('load', function() {
            const splash = document.getElementById('splash-screen');
            setTimeout(() => {
                splash.style.opacity = '0';
                setTimeout(() => { splash.style.display = 'none'; }, 600);
            }, 3000); // Displays for 3 seconds before disappearing
        });
    </script>
    <?php endif; ?>

    <?php if (file_exists('../includes/header.php')) { include '../includes/header.php'; } ?> 
    
    <div class="container mt-5 pt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card p-4 shadow border-0">
                    <div class="card-body">
                        <?php if (!empty($error_msg)): ?>
                            <div class="alert alert-danger py-2 small"><?php echo $error_msg; ?></div>
                        <?php endif; ?>

                        <h3 class="text-center mb-4 fw-bold" style="color: #0b3c4d;"><?php echo $words['login_title'] ?? 'Login'; ?></h3>
                        
                        <form method="POST" action="login.php">
                            <div class="mb-3">
                                <label class="form-label fw-bold small text-secondary"><?php echo $words['username'] ?? 'Username'; ?></label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold small text-secondary"><?php echo $words['password'] ?? 'Password'; ?></label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="login_btn" class="btn btn-primary w-100 py-2" style="background-color: #ffc107; border: none; color: #0b3c4d; font-weight:700;">
                                <?php echo $words['signin_btn'] ?? 'Sign In'; ?>
                            </button>
                        </form>
                        
                        <div class="text-center mt-3">
                            <a href="register.php" class="text-decoration-none small fw-bold" style="color: #0b3c4d;">
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