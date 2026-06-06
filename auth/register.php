<?php
session_start();

//FIXED: Hardcoded to point directly actual path
require_once '../config/db.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_btn'])) {
    $uname = mysqli_real_escape_string($conn, trim($_POST['username']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // FIXED: Ensured execution points directly to password_hash table schema layout values
    $query = "INSERT INTO users (username, email, password_hash) VALUES ('$uname', '$email', '$pass')";
    
    if (mysqli_query($conn, $query)) {
        // Triggers success pop up block and transfers user control straight back into the login dashboard
        echo "<script>alert('Registration Successful!'); window.location='login.php';</script>";
        exit();
    } else {
        $error_msg = "Registration Error: " . mysqli_error($conn);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/style.css">
    
    <style>
        body.mzansi-login-body {
            background: linear-gradient(180deg, #0b3c4d 0%, #061920 100%) !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
        }
        .btn-mzansi-primary {
            background-color: var(--accent-yellow, #ffc107) !important;
            color: var(--primary-teal, #0b3c4d) !important;
            font-weight: 700 !important;
            border: none !important;
        }
        .btn-mzansi-primary:hover {
            background-color: #e0a800 !important;
        }
        .mzansi-auth-card {
            background: #ffffff;
            border-radius: 20px !important;
            padding: 40px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
        }
    </style>
</head>
<body class="mzansi-login-body">


    <div class="container mt-5 pt-4">
        <div class="row justify-content-center">
            <div class="col-md-4 mzansi-auth-card">
                
                <?php if (!empty($error_msg)): ?>
                    <div class="alert alert-danger py-2 small"><?php echo $error_msg; ?></div>
                <?php endif; ?>

                <h3 class="text-center fw-bold" style="font-family: 'Syne', sans-serif; color: #0b3c4d;">Register</h3>
                <hr>
                <form method="POST" action="register.php">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Username</label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" name="register_btn" class="btn btn-mzansi-primary w-100 py-2">Register</button>
                </form>
                
                <div class="text-center mt-3">
                    <a href="login.php" class="text-decoration-none small fw-bold" style="color: #0b3c4d;">Already have an account? Log In</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>