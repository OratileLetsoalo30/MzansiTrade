<?php
include '../config/db.php';
include '../auth_check.php'; 

if (isset($_POST['post_item_btn'])) {
    if (!$is_verified) {
        die("Security Exception: Account verification required to write data records.");
    }

    $name = $_POST['item_name'];
    $price = $_POST['price'];
    $loc = $_POST['location'];
    $desc = $_POST['description'];

    $filename = $_FILES["item_image"]["name"];
    $tempname = $_FILES["item_image"]["tmp_name"];
    $folder = "uploads/" . time() . "_" . $filename; 

    if (!is_dir('uploads')) {
        mkdir('uploads', 0777, true);
    }

    if (move_uploaded_file($tempname, $folder)) {
        $user_phone = $_SESSION['phone'] ?? ''; 
        
        $insert_stmt = $conn->prepare("INSERT INTO products (user_id, item_name, price, location, description, image_path, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("isdssss", $uid, $name, $price, $loc, $desc, $folder, $user_phone);
        
        if ($insert_stmt->execute()) {
            $insert_stmt->close();
            echo "<script>alert('Awesome! Your item is now live on MzansiTrade.'); window.location='index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Database error: Failed to record listing.');</script>";
            $insert_stmt->close();
        }
    } else {
        echo "<script>alert('Failed to upload image. Please try again.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post an Ad | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    
    <style>
        /* Creative custom touches specifically for the Sell form */
        .sell-input-icon {
            color: var(--accent-orange);
            font-size: 1.1rem;
        }
        .custom-file-upload {
            border: 2px dashed var(--primary-teal);
            background-color: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        .custom-file-upload:hover {
            background-color: #e9ecef;
            border-color: var(--accent-orange);
        }
        .locked-state-box {
            background: linear-gradient(135deg, rgba(242, 142, 43, 0.08) 0%, rgba(11, 60, 77, 0.05) 100%);
            border: 1px solid rgba(242, 142, 43, 0.3);
            border-radius: 16px;
        }
    </style>
</head>
<body class="mzansi-login-body">
    
    <?php include 'header.php'; ?> 
    
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6 mzansi-auth-card position-relative overflow-hidden">
                
                <div class="position-absolute top-0 end-0 p-3" style="opacity: 0.05; font-size: 120px; right: -20px !important; top: -40px !important; color: var(--primary-teal); pointer-events: none;">
                    <i class="bi bi-tag-fill"></i>
                </div>

                <div class="text-center mb-4 pb-2 border-bottom">
                    <h3 class="fw-bold mb-1" style="font-family: 'Syne', sans-serif; color: var(--primary-teal);">
                        List on <span style="color: var(--accent-orange);">MzansiTrade</span>
                    </h3>
                    <p class="text-muted small">Turn your items into cash instantly</p>
                </div>
                
                <?php if (!$is_verified): ?>
                    <div class="locked-state-box text-center py-5 px-4 my-3">
                        <div class="mb-3">
                            <span class="bg-white rounded-circle d-inline-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                                <i class="bi bi-shield-lock-fill" style="font-size: 40px; color: var(--accent-orange);"></i>
                            </span>
                        </div>
                        <h4 class="fw-bold text-dark" style="font-family: 'Syne', sans-serif;">Verification Required</h4>
                        <p class="text-muted small px-2 mb-4">
                            To maintain a premium and safe marketplace, we require all sellers to verify their identity before posting ads. It only takes a minute!
                        </p>
                        <a href="/profile.php" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm">
                            <i class="bi bi-person-badge pe-2"></i>Verify My Profile Now
                        </a>
                    </div>
                <?php else: ?>
                    <form method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        
                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold text-dark small">
                                <i class="bi bi-box-seam sell-input-icon me-1"></i> What are you selling?
                            </label>
                            <input type="text" name="item_name" class="form-control form-control-lg bg-light" placeholder="e.g. Samsung Galaxy S21" required minlength="3">
                            <div class="invalid-feedback">Please enter a descriptive name (minimum 3 characters).</div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4 text-start">
                                <label class="form-label fw-bold text-dark small">
                                    <i class="bi bi-cash-coin sell-input-icon me-1"></i> Price
                                </label>
                                <div class="input-group input-group-lg">
                                    <span class="input-group-text bg-light fw-bold" style="color: var(--primary-teal); border-right: none;">R</span>
                                    <input type="number" step="0.01" min="1" name="price" class="form-control bg-light" style="border-left: none;" placeholder="0.00" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-4 text-start">
                                <label class="form-label fw-bold text-dark small">
                                    <i class="bi bi-geo-alt-fill sell-input-icon me-1"></i> Meetup Location
                                </label>
                                <input type="text" name="location" class="form-control form-control-lg bg-light" placeholder="e.g. Khayelitsha" required>
                            </div>
                        </div>

                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold text-dark small">
                                <i class="bi bi-card-text sell-input-icon me-1"></i> Description
                            </label>
                            <textarea name="description" class="form-control bg-light" rows="4" placeholder="Mention the condition, features, and your reason for selling..." required></textarea>
                        </div>

                        <div class="mb-4 text-start">
                            <label class="form-label fw-bold text-dark small">
                                <i class="bi bi-camera-fill sell-input-icon me-1"></i> Product Photo
                            </label>
                            <div class="custom-file-upload text-center position-relative">
                                <i class="bi bi-cloud-arrow-up text-muted mb-2" style="font-size: 2rem;"></i>
                                <input type="file" name="item_image" class="form-control bg-white" accept="image/*" required>
                                <div class="form-text mt-2 small">Upload a clear, well-lit photo of the item.</div>
                            </div>
                        </div>

                        <button type="submit" name="post_item_btn" class="btn btn-primary w-100 py-3 fw-bold rounded-3 shadow-sm" style="font-size: 1.1rem; letter-spacing: 0.5px;">
                            <i class="bi bi-rocket-takeoff-fill me-2"></i>Post My Listing
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        (function () {
            'use strict'
            var forms = document.querySelectorAll('.needs-validation')
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>