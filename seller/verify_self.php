<?php
session_start();
include '../config/db.php';

if (!isset($_SESSION['user_id'])) { header("Location: ../auth/login.php"); exit(); }

if (isset($_POST['submit_id'])) {
    $uid = $_SESSION['user_id'];
    $id_num = mysqli_real_escape_string($conn, $_POST['id_number']);
    
    // Update user with their ID and set status to 'pending'
    $update = $conn->prepare("UPDATE users SET id_number = ?, verification_status = 'pending' WHERE user_id = ?");
    $update->bind_param("si", $id_num, $uid);
    
    if ($update->execute()) {
        echo "<script>alert('ID submitted! We are reviewing your account.'); window.location='profile.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Verify Account | MzansiTrade</title>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card p-4 shadow-sm" style="max-width: 500px; margin: auto;">
            <h3 class="text-center" style="color: #0b3c4d;">Verify Your Identity</h3>
            <p class="text-center text-muted">Submit your ID number for manual verification.</p>
            <form method="POST">
                <input type="text" name="id_number" class="form-control mb-3" placeholder="Enter South African ID Number" required>
                <button type="submit" name="submit_id" class="btn btn-primary w-100" style="background-color: #0b3c4d;">Submit for Review</button>
            </form>
        </div>
    </div>
</body>
</html>