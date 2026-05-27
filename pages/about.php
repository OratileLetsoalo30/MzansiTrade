<?php
session_start();
$page_title = "About MzansiTrade";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="mzansitrade/assets/css/style.css">
</head>

<body>

<!-- HEADER -->
<header class="header">
    <h1>MzansiTrade</h1>
    <p>Buy & Sell Locally in South Africa 🇿🇦</p>
</header>

<!-- ABOUT SECTION -->
<section class="about">
    <h2>About Us</h2>

    <p>
        MzansiTrade is a local online marketplace built for South Africans to buy and sell products easily.
        We connect everyday sellers and buyers in categories like Shoes, Devices, and Hair products.
    </p>

    <p>
        Our goal is to empower local trade, support small businesses, and make buying and selling simple,
        fast, and accessible to everyone in Mzansi.
    </p>

    <div class="about-boxes">

        <div class="box">
            <h3>💼 Our Mission</h3>
            <p>To create a trusted community marketplace for all South Africans.</p>
        </div>

        <div class="box">
            <h3>🚀 Our Vision</h3>
            <p>To become the leading C2C platform in Africa.</p>
        </div>

        <div class="box">
            <h3>🤝 What We Sell</h3>
            <p>Shoes, Devices, Hair products, and more coming soon.</p>
        </div>

    </div>
</section>

<!-- FOOTER -->
<footer class="footer">
    <p>&copy; <?php echo date("Y"); ?> MzansiTrade. All rights reserved.</p>
</footer>

</body>
</html>