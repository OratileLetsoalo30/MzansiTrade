<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$uid = $_SESSION['user_id'];
$message = "";

if (isset($_POST['submit_id'])) {
    $id_number = trim($_POST['id_number']);
    
    // Server-side validation
    if (!preg_match('/^[0-9]{13}$/', $id_number)) {
        $message = "<div class='alert alert-danger shadow-sm'>Error: ID must be exactly 13 digits.</div>";
    } else {
        // Update user
        $stmt = $conn->prepare("UPDATE users SET id_number = ?, is_verified = 0 WHERE user_id = ?");
        $stmt->bind_param("si", $id_number, $uid);
        
        if ($stmt->execute()) {
            header("Location: ../pages/profile.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger shadow-sm'>Database error: " . $conn->error . "</div>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Account | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background-color: #f8f9fa;">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card p-4 shadow-sm" style="border-radius: 20px; background: #ffffff; border: none;">
                    <h3 class="fw-bold mb-3" style="color: var(--primary-teal);"><i class="bi bi-shield-check pe-2"></i>ID Verification</h3>
                    <?php echo $message; ?>
                    <p class="text-muted small">Enter your South African ID number to request profile verification.</p>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">ID Number</label>
                            <input type="text" name="id_number" class="form-control py-2" required placeholder="Enter 13-digit ID" maxlength="13" style="border-radius: 10px;">
                        </div>
                        <button type="submit" name="submit_id" class="btn btn-primary w-100 py-2 fw-bold" style="border-radius: 10px;">Submit for Review</button>
                    </form>
                    <a href="../pages/profile.php" class="btn btn-link w-100 mt-2 text-decoration-none text-secondary small"><i class="bi bi-arrow-left pe-1"></i>Back to Profile</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>