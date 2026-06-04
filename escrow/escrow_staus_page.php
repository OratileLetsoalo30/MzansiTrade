<?php
session_start();
include '../db_config.php';
include '../auth/auth_check.php';
include 'escrow_config.php';

if (!isset($_GET['hash'])) {
    header("Location: ../index.php");
    exit();
}

$hash = mysqli_real_escape_string($conn, $_GET['hash']);

// Get transaction details via escrow_status.php API
// This is cleaner than duplicating the query logic
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Status | MzansiTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-teal: #0B3C4D;
            --accent-orange: #FFA500;
            --success: #52B788;
            --danger: #D62828;
        }
        
        body {
            background: #f8f9fa;
            font-family: 'Segoe UI', sans-serif;
        }
        
        .status-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
        }
        
        .status-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            overflow: hidden;
            margin-bottom: 20px;
        }
        
        .status-header {
            background: linear-gradient(135deg, var(--primary-teal), #1a5f73);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            background: rgba(255,255,255,0.2);
            font-size: 14px;
            font-weight: 600;
            margin-top: 10px;
            color: white;
        }
        
        .status-body {
            padding: 30px;
        }
        
        .item-info {
            background: #f8f9fa;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border-left: 4px solid var(--accent-orange);
        }
        
        .item-info h5 {
            color: var(--primary-teal);
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .item-price {
            font-size: 20px;
            font-weight: 700;
            color: var(--accent-orange);
        }
        
        .timeline {
            position: relative;
            padding: 0;
            margin: 30px 0;
        }
        
        .timeline-item {
            position: relative;
            padding-left: 40px;
            margin-bottom: 30px;
        }
        
        .timeline-marker {
            position: absolute;
            left: 0;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #e0e0e0;
            border: 3px solid white;
            top: 0;
        }
        
        .timeline-item.completed .timeline-marker {
            background: var(--success);
        }
        
        .timeline-item.active .timeline-marker {
            background: var(--accent-orange);
            box-shadow: 0 0 0 4px rgba(242, 142, 43, 0.2);
        }
        
        .timeline-item.pending .timeline-marker {
            background: #ddd;
        }
        
        .timeline-item:not(:last-child)::before {
            content: '';
            position: absolute;
            left: 9px;
            top: 20px;
            width: 2px;
            height: 30px;
            background: #e0e0e0;
        }
        
        .timeline-item.completed:not(:last-child)::before {
            background: var(--success);
        }
        
        .timeline-item.active:not(:last-child)::before {
            background: var(--accent-orange);
        }
        
        .timeline-title {
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 4px;
        }
        
        .timeline-description {
            font-size: 13px;
            color: #666;
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }
        
        .action-btn {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .btn-primary-action {
            background: linear-gradient(135deg, var(--accent-orange), #ff9f3d);
            color: white;
        }
        
        .btn-primary-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(242, 142, 43, 0.3);
        }
        
        .btn-secondary-action {
            background: #f0f0f0;
            color: #666;
        }
        
        .btn-secondary-action:hover {
            background: #e0e0e0;
        }
        
        .info-box {
            background: rgba(11, 60, 77, 0.1);
            border-left: 4px solid var(--primary-teal);
            padding: 15px;
            border-radius: 8px;
            font-size: 13px;
            color: var(--primary-teal);
            margin-bottom: 20px;
        }
        
        .parties-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 20px 0;
        }
        
        .party-card {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            border-top: 3px solid var(--primary-teal);
        }
        
        .party-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            margin-bottom: 8px;
        }
        
        .party-name {
            font-weight: 600;
            color: var(--primary-teal);
            margin-bottom: 4px;
        }
        
        .verification-badge {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
        }
        
        .verified {
            color: var(--success);
        }
        
        .pending {
            color: var(--accent-orange);
        }
        
        .loading {
            text-align: center;
            padding: 40px;
        }
        
        .spinner-border {
            color: var(--primary-teal);
        }
    </style>
</head>
<body>
    <?php include '../includes/header.php'; ?>

    <div class="status-container">
        <div class="status-card" id="mainCard">
            <div class="loading">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-3 text-muted">Loading transaction details...</p>
            </div>
        </div>
    </div>

    <script>
        const hash = "<?php echo htmlspecialchars($hash); ?>";

        async function loadStatus() {
            try {
                const response = await fetch(`escrow_status.php?hash=${encodeURIComponent(hash)}`);
                const data = await response.json();

                if (data.success) {
                    renderStatus(data);
                } else {
                    showError(data.error);
                }
            } catch (error) {
                showError('Failed to load transaction: ' + error.message);
            }
        }

        function renderStatus(data) {
            const t = data.transaction;
            const item = data.item;
            const parties = data.parties;
            const timeline = data.timeline;
            const verification = data.verification;

            let html = `
                <div class="status-header">
                    <div>
                        <i class="bi bi-lock-fill" style="font-size: 32px;"></i>
                        <h1 class="mt-2">Transaction Status</h1>
                        <div class="status-badge">${t.status_label}</div>
                    </div>
                </div>

                <div class="status-body">
                    <div class="info-box">
                        <i class="bi bi-info-circle"></i> 
                        <strong>Transaction ID:</strong> ${t.id}<br>
                        <strong>Flow Type:</strong> ${t.flow_label}
                    </div>

                    <div class="item-info">
                        <h5>${item.name}</h5>
                        <div class="item-price">R ${item.price.toFixed(2)}</div>
                    </div>

                    <h6 style="color: var(--primary-teal); margin-top: 25px; margin-bottom: 15px;">
                        <i class="bi bi-people"></i> Parties
                    </h6>
                    <div class="parties-info">
                        <div class="party-card">
                            <div class="party-label">Buyer</div>
                            <div class="party-name">${parties.buyer.username}</div>
                            <div class="verification-badge ${verification.qr_verified_by_buyer ? 'verified' : 'pending'}">
                                <i class="bi bi-${verification.qr_verified_by_buyer ? 'check-circle-fill' : 'circle'}"></i>
                                ${verification.qr_verified_by_buyer ? 'Verified' : 'Pending'}
                            </div>
                        </div>
                        <div class="party-card">
                            <div class="party-label">Seller</div>
                            <div class="party-name">${parties.seller.username}</div>
                            <div class="verification-badge ${verification.confirmed_by_seller ? 'verified' : 'pending'}">
                                <i class="bi bi-${verification.confirmed_by_seller ? 'check-circle-fill' : 'circle'}"></i>
                                ${verification.confirmed_by_seller ? 'Confirmed' : 'Pending'}
                            </div>
                        </div>
                    </div>

                    <h6 style="color: var(--primary-teal); margin-top: 30px; margin-bottom: 20px;">
                        <i class="bi bi-clock-history"></i> Transaction Timeline
                    </h6>
                    <div class="timeline">
                        ${timeline.map((event, i) => `
                            <div class="timeline-item ${event.status}">
                                <div class="timeline-title">${event.event}</div>
                                <div class="timeline-description">${event.description}</div>
                                ${event.time ? `<small style="color: #999;">${new Date(event.time).toLocaleString()}</small>` : ''}
                            </div>
                        `).join('')}
                    </div>

                    <div class="action-buttons">
                        <button class="action-btn btn-secondary-action" onclick="location.reload()">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                        <button class="action-btn btn-secondary-action" onclick="history.back()">
                            <i class="bi bi-arrow-left"></i> Back
                        </button>
                    </div>
                </div>
            `;

            document.getElementById('mainCard').innerHTML = html;

            // Auto-refresh every 5 seconds while transaction is pending
            if (!['completed', 'refunded', 'cancelled'].includes(t.status)) {
                setTimeout(loadStatus, 5000);
            }
        }

        function showError(error) {
            document.getElementById('mainCard').innerHTML = `
                <div class="status-header" style="background: linear-gradient(135deg, #d62828, #c41e3a);">
                    <i class="bi bi-exclamation-triangle" style="font-size: 32px;"></i>
                    <h1 class="mt-2">Transaction Error</h1>
                </div>
                <div class="status-body">
                    <div class="alert alert-danger">${error}</div>
                    <button class="btn btn-primary w-100" onclick="history.back()">Go Back</button>
                </div>
            `;
        }

        // Load on page load
        loadStatus();
    </script>
</body>
</html>