<?php
// Display error messages for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Trigger verification
if (isset($_POST['trigger_verify_btn'])) {
    mysqli_query($conn, "UPDATE users SET is_verified = 1 WHERE user_id = '$user_id'");
    header("Location: profile.php?verified=success");
    exit();
}

$user_res = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($user_res);
$is_verified = $user['is_verified'] ?? 0;

$my_items = mysqli_query($conn, "SELECT * FROM products WHERE seller_id = '$user_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .profile-avatar {
            width: 80px; height: 80px; font-size: 30px;
            background-color: var(--primary-teal);
            color: var(--white);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 15px;
        }
        /* Using your unified Design System */
        .mzansi-profile-card {
            background: var(--white);
            border-radius: 20px !important;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08) !important;
            padding: 30px !important;
        }
    </style>
</head>
<body style="background-color: #f8f9fa;">

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="mzansi-profile-card text-center">
                <div class="text-start mb-3">
                    <a href="../index.php" class="text-decoration-none small text-secondary">
                        <i class="bi bi-arrow-left pe-1"></i>Back to Market
                    </a>
                </div>

                <div class="profile-avatar">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </div>
                <h3 class="fw-bold" style="color: var(--primary-teal);"><?php echo $user['username']; ?></h3>
                <p class="text-muted mb-2"><?php echo $user['phone'] ?? ''; ?></p>
                
                <div class="mb-3">
                    <span class="badge rounded-pill bg-light text-dark px-3 border"><?php echo ucfirst($user['role'] ?? 'User'); ?></span>
                </div>
                
                <hr class="text-muted">

                <div class="mt-3">
                    <?php if ($is_verified): ?>
                        <div class="text-success small fw-bold mb-3">
                            <i class="bi bi-patch-check-fill pe-1"></i>Identity Verified
                        </div>
                        <a href="generate_certificate.php" class="btn btn-primary w-100 py-2 fw-bold" target="_blank">
                            <i class="bi bi-file-earmark-medical pe-1"></i> Generate Certificate
                        </a>
                    <?php else: ?>
                        <div class="text-danger small mb-3">
                            <i class="bi bi-shield-x pe-1"></i>Unverified Account
                        </div>
                        <form method="POST">
                            <button type="submit" name="trigger_verify_btn" class="btn btn-primary w-100 py-2 fw-bold">
                                <i class="bi bi-whatsapp pe-1"></i>Verify via WhatsApp
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <h4 class="fw-bold mb-3" style="color: var(--primary-teal);">My Listings</h4>
            <div class="mzansi-profile-card p-0 overflow-hidden">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Item</th>
                            <th>Price</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($my_items && mysqli_num_rows($my_items) > 0): ?>
                            <?php while($item = mysqli_fetch_assoc($my_items)): ?>
                            <tr>
                                <td class="fw-bold ps-4"><?php echo htmlspecialchars($item['item_name']); ?></td>
                                <td>R <?php echo number_format($item['price'], 2); ?></td>
                                <td class="text-end pe-4">
                                    <a href="../seller/edit_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-secondary me-2">Edit</a>
                                    <a href="../seller/delete_item.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center py-4 text-muted">You haven't listed any items yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>