<?php
// HARD-CODED CONNECTION TO TEST
$conn = mysqli_connect("localhost", "root", "", "mzansitrade");

if (!$conn) {
    die("<h1>Connection Failed:</h1> " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_btn'])) {
    $uname = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $pass  = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // SQL INSERT
    $query = "INSERT INTO users (username, email, password_hash) VALUES ('$uname', '$email', '$pass')";
    
    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Registration Successful!'); window.location='login.php';</script>";
        exit();
    } else {
        die("<h1>SQL Error:</h1> " . mysqli_error($conn));
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
        /* MzansiTrade Branding Integration */
        body.mzansi-login-body {
            background: linear-gradient(180deg, #0b3c4d 0%, #061920 100%) !important;
            background-attachment: fixed !important;
            min-height: 100vh !important;
        }
        .btn-mzansi-primary {
            background-color: var(--accent-yellow) !important;
            color: var(--primary-teal) !important;
            font-weight: 700 !important;
            border: none !important;
        }
        .btn-mzansi-primary:hover {
            background-color: #e0a800 !important;
        }
        .mzansi-auth-card {
            background: var(--white);
            border-radius: 20px !important;
            padding: 40px !important;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3) !important;
        }
    </style>
</head>
<body class="mzansi-login-body">
    <?php include 'header.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4 mzansi-auth-card">
                <h3 class="text-center" style="font-family: 'Syne', sans-serif; color: var(--primary-teal);">Register</h3>
                <hr>
                <form method="POST">
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
            </div>
        </div>
    </div>
</body>
</html>
