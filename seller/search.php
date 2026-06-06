<?php
// ../seller/search.php

if (isset($_GET['q']) || isset($_GET['location'])) {
    $query = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
    $location = isset($_GET['location']) ? strtolower(trim($_GET['location'])) : '';

    // If this file is inside a folder (like auth/ or seller/), use "../index.php"
    // If this file is in the main folder with index.php, change this to "index.php"
    $redirectUrl = "../index.php"; 

    // ==========================================
    // 1. LOCATION LOGIC
    // ==========================================
    $locationsList = [
        'khayelitsha', 'ottery', 'blouberg', 'greenpoint', 'nyanga', 
        'wynberg', 'rondebosch', 'claremont', 'delft', 'gugulethu'
    ];

    // If location wasn't selected from the dropdown, check if they typed it in the search bar
    if (empty($location)) {
        foreach ($locationsList as $loc) {
            if (strpos($query, $loc) !== false) {
                $location = $loc;
                break;
            }
        }
    }

    // Append location parameter to the URL if one was found
    if (!empty($location)) {
        // Starts with ? for the first URL parameter
        $redirectUrl .= "?location=" . urlencode($location); 
    }

    // ==========================================
    // 2. CATEGORY & SECTION MAPPING (SMOOTH SCROLL)
    // ==========================================
    $anchor = '';

    // Hair products mapping
    $hair_keywords = ['hair', 'wig', 'weave', 'bundle', 'closure', 'buss down', '13x4', 'straight', 'curls', 'water', 'jerry', 'body wave', 'deep wave', 'loose wave', 'kinky', 'yaki', 'hd lace', 'chocolate layered'];
    // Shoes mapping
    $shoe_keywords = ['sneakers', 'nike', 'heels', 'shoes', 'steve madden', 'adidas', 'puma', 'reebok', 'clogs'];
    // Devices mapping
    $device_keywords = ['devices', 'iphone', 'pro max', 'jbl', 'samsung', 'galaxy', 'macbook', 'laptop', 'tablet', 'ipad', 'airpods'];

    // Check which category the query belongs to
    foreach ($hair_keywords as $kw) {
        if (strpos($query, $kw) !== false) {
            $anchor = '#hair-section';
            break;
        }
    }
    
    if (empty($anchor)) {
        foreach ($shoe_keywords as $kw) {
            if (strpos($query, $kw) !== false) {
                $anchor = '#shoes-section';
                break;
            }
        }
    }
    
    if (empty($anchor)) {
        foreach ($device_keywords as $kw) {
            if (strpos($query, $kw) !== false) {
                $anchor = '#devices-section';
                break;
            }
        }
    }

    // Append the anchor ID so the page smoothly glides to the correct section
    $redirectUrl .= $anchor;

    // ==========================================
    // 3. EXECUTE REDIRECT
    // ==========================================
    header("Location: " . $redirectUrl);
    exit();
} else {
    // Fallback if someone opens search.php with no search typed
    header("Location: ../index.php");
    exit();
}
?>