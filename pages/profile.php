<?php
session_start();
include '../config/db.php';

$user_id = $_SESSION['user_id'];
$user_res = mysqli_query($conn, "SELECT * FROM users WHERE user_id = '$user_id'");
$user = mysqli_fetch_assoc($user_res);
$is_verified = $user['is_verified'] ?? 0;

$my_items = mysqli_query($conn, "SELECT * FROM products WHERE seller_id = '$user_id'");

include dirname(__DIR__) . '/includes/header.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .profile-avatar { width: 80px; height: 80px; font-size: 32px; background-color: #2c3e50; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: bold; }
        .mzansi-card { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        
        /* Forces the gray background on the table header */
        .table thead tr { background-color: #f0f0f0 !important; }
        .table thead th { border-bottom: none !important; color: #555; }
        
        /* Line divider for the empty listing state */
        .empty-listings-row { border-top: 1px solid #eee; }
    </style>
</head>
<body style="background-color: #f8f9fa;">

<div class="container mt-5">
    <div class="row">
        <!-- LEFT COLUMN -->
        <div class="col-md-4">
            <div class="mzansi-card text-center">
                <div class="text-start mb-3"><a href="../index.php" class="text-secondary text-decoration-none">← Back to Market</a></div>
                
                <div class="profile-avatar"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></div>
                <div class="fw-bold fs-4"><?php echo htmlspecialchars($user['username']); ?></div>
                <div class="text-muted small">User</div>
                
                <hr class="my-3">

                <?php if ($is_verified == 1): ?>
                    <div class="text-success my-3 fw-bold"><i class="bi bi-shield-check"></i> Identity Verified</div>
                    <a href="generate_certificate.php" class="btn btn-primary w-100">Generate Certificate</a>
                <?php else: ?>
                    <div class="text-danger my-3 fw-bold"><i class="bi bi-shield-x"></i> Unverified Account</div>
                    <!-- Icon restored in the button -->
                    <a href="../seller/verify_self.php" class="btn btn-primary w-100">
                        <i class="bi bi-person-badge"></i> Verify My Profile Now
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="col-md-8">
            <h4 class="fw-bold mb-3">My Listings</h4>
            <div class="mzansi-card p-0 overflow-hidden">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Item</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if($my_items && mysqli_num_rows($my_items) > 0): ?>
                            <?php while($item = mysqli_fetch_assoc($my_items)): ?>
                                <tr>
                                    <td class="ps-4"><?php echo htmlspecialchars($item['item_name']); ?></td>
                                    <td>R <?php echo number_format($item['price'], 2); ?></td>
                                    <td><a href="../seller/edit.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-secondary">Edit</a></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <!-- Line added back to separate header from empty message -->
                            <tr class="empty-listings-row"><td colspan="3" class="text-muted text-center py-4">You haven't listed any items yet.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>