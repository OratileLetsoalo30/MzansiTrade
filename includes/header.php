<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Global configuration files
include_once 'db_config.php';
?>

<nav class="navbar navbar-expand-lg border-bottom shadow-sm py-3 header-navbar">
    <div class="container-fluid header-container">
        
        <a class="navbar-brand m-0 p-0 d-flex align-items-center logo-left-corner" href="index.php">
            <img src="assets/img/logo.png" alt="MsanziTrade Logo" class="brand-logo-img" onerror="this.src='https://via.placeholder.com/45x45?text=Logo'">
        </a>

        <div class="brand-title-center">
            <span class="logo-text">MsanziTrade <span class="badge-c2c">C2C</span></span>
        </div>

        <button class="navbar-toggler border-0 shadow-none text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto align-items-center">
                
                <?php if(isset($_SESSION['user_id'])): ?>
                    <div class="nav-item dropdown ms-lg-3 mt-3 mt-lg-0">
                        <a class="nav-link dropdown-toggle d-flex align-items-center p-0" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-initials-avatar d-flex align-items-center justify-content-center border fw-bold">
                                <?php 
                                    $name = $_SESSION['username'] ?? 'User';
                                    $nameParts = explode(" ", $name);
                                    $initials = "";
                                    foreach ($nameParts as $w) { 
                                        if(!empty($w)) $initials .= $w[0]; 
                                    }
                                    echo strtoupper(substr($initials, 0, 2)); 
                                ?>
                            </div>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item py-2" href="index.php">
                                <i class="bi bi-house-door me-2"></i>Home Marketplace
                            </a></li>
                            
                            <li><a class="dropdown-item py-2" href="help.php">
                                <i class="bi bi-question-circle me-2 text-primary"></i>Help & Safety Guide
                            </a></li>

                            <li><a class="dropdown-item py-2" href="#" data-bs-toggle="modal" data-bs-target="#aboutSystemModal">
                                <i class="bi bi-info-circle me-2 text-success"></i>About Us
                            </a></li>
                            
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item py-2" href="pages/profile.php">
                                <i class="bi bi-person me-2"></i>Profile Settings
                            </a></li>

                            <li><a class="dropdown-item py-2" href="seller/dashboard.php">
                                <i class="bi bi-grid me-2"></i>My Transactions
                            </a></li>

                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'Admin'): ?>
                                <li><a class="dropdown-item py-2 text-info" href="admin_dashboard.php">
                                    <i class="bi bi-shield-lock me-2"></i>Admin Dashboard
                                </a></li>
                                <li><a class="dropdown-item py-2 text-danger small fw-bold" href="admin_tickets.php">
                                    <i class="bi bi-headset me-2"></i>Review Dispute Tickets
                                </a></li>
                            <?php endif; ?>

                            <li><a class="dropdown-item py-2" href="sell.php">
                                 <i class="bi bi-plus-circle me-2"></i>Sell Something
                            </a></li>
    
                            <li><hr class="dropdown-divider"></li>

                            <li><a class="dropdown-item py-2 text-danger" href="auth/logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i>Log out
                            </a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a class="nav-link text-dark px-3 mt-2 mt-lg-0" href="auth/login.php">Login</a>
                    <a class="btn btn-sell-nav fw-bold px-4 ms-lg-2 mt-2 mt-lg-0 text-white" href="auth/register.php">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<div class="modal fade" id="aboutSystemModal" tabindex="-1" aria-labelledby="aboutSystemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
            <div class="modal-header border-0 bg-success text-white py-3" style="border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #00663c !important;">
                <h5 class="modal-title fw-bold" id="aboutSystemModalLabel">
                    <i class="bi bi-info-circle-fill me-2"></i>About MsanziTrade
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img src="assets/img/logo.png" alt="MsanziTrade Logo" class="mb-3" style="width: 80px; height: 80px; object-fit: contain;" onerror="this.src='https://via.placeholder.com/80x80?text=Logo'">
                <h4 class="fw-bold text-dark mb-2">MsanziTrade</h4>
                <p class="text-secondary small mb-0" style="line-height: 1.6;">
                    MsanziTrade is a creative C-2-C marketplace dedicated to empowering local informal traders across Cape Town townships. Our integrated smart escrow architecture secures transaction cash safely until products are checked face-to-face, providing safe street-level commerce options completely free of scams.
                </p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <button type="button" class="btn btn-secondary px-4 rounded-1" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div id="kasi-bot-widget" class="position-fixed bottom-0 end-0 me-4 mb-4" style="z-index: 1050;">
    <button id="bot-toggle-btn" class="btn rounded-circle shadow-lg d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; background-color: #00663c; color: #fff; border:none;">
        <i class="bi bi-chat-dots-fill fs-4" id="bot-icon-state"></i>
    </button>

    <div id="bot-chat-window" class="card border-0 shadow-lg d-none position-absolute bottom-0 end-0 mb-5 pb-4" style="width: 340px; border-radius: 12px; transform: translateY(-15px); border: 1px solid #eee !important;">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-3" style="border-top-left-radius: 12px; border-top-right-radius: 12px; background-color: #333 !important;">
            <div class="d-flex align-items-center">
                <div class="bg-success rounded-circle me-2 animate-pulse" style="width: 10px; height: 10px; background-color: #1c873b !important;"></div>
                <h6 class="fw-bold m-0" style="font-size: 14px;">Msanzi Support Assistant</h6>
            </div>
            <button type="button" class="btn-close btn-close-white small" id="bot-close-window-btn"></button>
        </div>

        <div class="card-body p-3 bg-light overflow-y-auto" id="bot-message-screen" style="height: 280px; font-size: 13px;">
            <div class="mb-3 text-start">
                <div class="bg-white p-2.5 rounded-3 text-secondary shadow-sm border border-light d-inline-block" style="max-width: 85%;">
                    Molo! Welcome to MsanziTrade. How can I assist you with your informal escrow verification or listings today?
                </div>
            </div>
        </div>

        <div class="p-2 border-top bg-white position-absolute bottom-0 w-100" style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
            <form id="bot-input-form-thread" class="d-flex m-0">
                <input type="text" id="bot-user-input-field" class="form-control form-control-sm border-0 bg-light me-1" placeholder="Type a message..." autocomplete="off" required>
                <button type="submit" class="btn btn-sm px-3 text-white" style="background-color: #00663c;"><i class="bi bi-send-fill"></i></button>
            </form>
        </div>
    </div>
</div>

<style>
    @keyframes pulseGlow {
        0% { opacity: 0.4; }
        50% { opacity: 1; }
        100% { opacity: 0.4; }
    }
    .animate-pulse { animation: pulseGlow 2s infinite ease-in-out; }
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.getElementById('bot-toggle-btn');
    const chatWindow = document.getElementById('bot-chat-window');
    const closeBtn = document.getElementById('bot-close-window-btn');
    const iconState = document.getElementById('bot-icon-state');
    const inputForm = document.getElementById('bot-input-form-thread');
    const inputField = document.getElementById('bot-user-input-field');
    const messageScreen = document.getElementById('bot-message-screen');

    let misunderstandingCounter = 0;

    const txtUnknown = "I'm sorry, I didn't quite catch that. You can ask me about 'escrow', 'meetup verification', or how to report a 'scam'.";
    const txtFallback = "Connecting your message feed to MsanziTrade dispute administration...";

    const knowledgeBase = [
        { keywords: ['escrow', 'hold', 'money', 'safe', 'payment', 'imali'], answer: "MsanziTrade Escrow safely holds buyer money in deposit. Cash is only released to the township trader after the buyer verifies the product matches their expectations face-to-face." },
        { keywords: ['meet', 'location', 'place', 'meetup', 'handover', 'wynberg', 'khayelitsha'], answer: "Always make trades in busy public exchange areas like local transport interchanges, active service hubs, or close to safe collection infrastructure partners." },
        { keywords: ['fake', 'wrong', 'broken', 'damaged', 'scam'], answer: "If an item does not match its listing description, click 'Reject Escrow' immediately in your profile hub. Do not hand over verification tokens." },
        { keywords: ['verify', 'identity', 'id', 'merchant'], answer: "Sellers receive a 'Verified Seller' badge after confirming their profile identity credentials with South African ID verification checks." }
    ];

    toggleBtn.addEventListener('click', function() {
        if(chatWindow.classList.contains('d-none')) {
            chatWindow.classList.remove('d-none');
            iconState.className = "bi bi-x-lg fs-4";
            inputField.focus();
        } else {
            closeWidget();
        }
    });

    closeBtn.addEventListener('click', closeWidget);

    function closeWidget() {
        chatWindow.classList.add('d-none');
        iconState.className = "bi bi-chat-dots-fill fs-4";
    }

    function appendMessage(text, isUser = false, isHtml = false) {
        const wrapper = document.createElement('div');
        wrapper.className = `mb-3 ${isUser ? 'text-end' : 'text-start'}`;
        
        const bubble = document.createElement('div');
        bubble.className = `p-2.5 rounded-3 shadow-sm border border-light d-inline-block ${isUser ? 'text-white' : 'bg-white text-secondary'}`;
        bubble.style.maxWidth = "85%";
        if (isUser) {
            bubble.style.backgroundColor = "#00663c";
        }
        
        if(isHtml) {
            bubble.innerHTML = text;
        } else {
            bubble.textContent = text;
        }
        
        wrapper.appendChild(bubble);
        messageScreen.appendChild(wrapper);
        messageScreen.scrollTop = messageScreen.scrollHeight;
    }

    inputForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const userQuery = inputField.value.trim().toLowerCase();
        if(!userQuery) return;

        appendMessage(inputField.value, true);
        inputField.value = "";

        setTimeout(() => {
            let matchFound = false;

            for (let entry of knowledgeBase) {
                if (entry.keywords.some(keyword => userQuery.includes(keyword))) {
                    appendMessage(entry.answer);
                    matchFound = true;
                    misunderstandingCounter = 0;
                    break;
                }
            }

            if (!matchFound) {
                misunderstandingCounter++;
                
                if (misunderstandingCounter >= 2) {
                    let htmlTicketForm = `
                        <p class="mb-2 fw-semibold text-danger"><i class="bi bi-headset me-1"></i> ${txtFallback}</p>
                        <div id="inline-ticket-box">
                            <textarea id="ticket-text-input" class="form-control form-control-sm mb-2" rows="2" placeholder="Explain your trade dispute or platform problem here..." required></textarea>
                            <button type="button" id="submit-ticket-btn" class="btn btn-sm btn-danger w-100 fw-bold rounded-1">
                                <i class="bi bi-shield-fill-exclamation me-1"></i> File Emergency Support Ticket
                            </button>
                        </div>
                    `;
                    appendMessage(htmlTicketForm, false, true);
                    misunderstandingCounter = 0;

                    document.getElementById('submit-ticket-btn').addEventListener('click', handleTicketEscalation);
                } else {
                    appendMessage(txtUnknown);
                }
            }
        }, 400);
    });

    function handleTicketEscalation() {
        const messageText = document.getElementById('ticket-text-input').value.trim();
        const ticketBox = document.getElementById('inline-ticket-box');

        if (!messageText) return;

        ticketBox.innerHTML = `<div class="spinner-border spinner-border-sm text-danger" role="status"></div> Sending to security dashboard...`;

        const formData = new FormData();
        formData.append('ticket_message', messageText);

        fetch('create_ticket.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                ticketBox.innerHTML = `
                    <div class="alert alert-success border-0 small m-0 py-2 fw-bold text-center text-success bg-success-subtle">
                        <i class="bi bi-check2-circle me-1"></i> Escalated! Admin team notified.
                    </div>`;
            } else {
                ticketBox.innerHTML = `<div class="text-danger small">Error saving data.</div>`;
            }
        })
        .catch(err => {
            ticketBox.innerHTML = `<div class="text-danger small">Connection error. Try again.</div>`;
        });
    }
});
</script>