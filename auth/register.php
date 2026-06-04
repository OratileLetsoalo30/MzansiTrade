<?php
session_start();   
include 'db_config.php'; 

if (isset($_POST['register_btn'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['username']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $key = isset($_POST['admin_key']) ? $_POST['admin_key'] : '';
    $role = ($key === 'Mzansi_Secret_777') ? 'Admin' : 'Standard';
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $query = "INSERT INTO users (username, password, role, phone) VALUES ('$uname', '$pass', '$role', '$phone')";
    $run   = mysqli_query($conn, $query);
    
    if ($run) {
        echo "<script>alert('" . ($words['success_msg'] ?? 'Registration successful!') . "'); window.location='login.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
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
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    /* This overrides everything to force the background */
    body.mzansi-login-body {
        background: linear-gradient(180deg, #0b3c4d 0%, #061920 100%) !important;
        background-attachment: fixed !important;
        min-height: 100vh !important;
    }
</style>
</head>
<body class="mzansi-login-body">
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4 mzansi-auth-card">
                <h3 class="text-center"><?php echo $words['nav_register'] ?? 'Register'; ?></h3>
                <hr>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary"><?php echo $words['username'] ?? 'Username'; ?></label>
                        <input type="text" name="username" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary"><?php echo $words['password'] ?? 'Password'; ?></label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary"><?php echo $words['phone_label'] ?? 'Phone'; ?></label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g. 27712345678" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary"><?php echo $words['admin_label'] ?? 'Admin Key'; ?></label>
                        <input type="text" name="admin_key" class="form-control" placeholder="Leave blank for standard">
                        <small class="text-muted"><?php echo $words['admin_note'] ?? 'Optional'; ?></small>
                    </div>
                    <button type="submit" name="register_btn" class="btn btn-primary w-100 py-2"><?php echo $words['nav_register'] ?? 'Register'; ?></button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>