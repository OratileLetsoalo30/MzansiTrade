<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Loading | MzansiTrade</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        #splash-screen {
            position: fixed; 
            top: 0; left: 0; width: 100%; height: 100%; 
            display: flex; flex-direction: column; 
            align-items: center; justify-content: center; 
            z-index: 9999; 
            /* Same Gradient Background as your site */
            background: linear-gradient(180deg, #0b3c4d 0%, #061920 100%) !important;
            transition: opacity 0.6s ease-in-out;
        }
    </style>
</head>
<body>

    <div id="splash-screen">
        <img src="assets/img/logo.jpeg" alt="Logo" style="max-width: 250px; margin-bottom: 30px;">
        <div class="spinner-border" style="color: #ffc107; width: 3rem; height: 3rem;" role="status"></div>
    </div>

    <script>
        // Force redirect after 5 seconds regardless of page state
        setTimeout(function() {
            document.getElementById('splash-screen').style.opacity = '0';
            
            setTimeout(function() {
                window.location.href = 'index.php';
            }, 600);
        }, 5000);
    </script>
</body>
</html>