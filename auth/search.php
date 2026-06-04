<?php
// search.php

// 1. Capture and clean the user inputs
$query = isset($_GET['q']) ? strtolower(trim($_GET['q'])) : '';
$location = isset($_GET['location']) ? strtolower(trim($_GET['location'])) : '';

// 2. Base URL structure for redirection rules
$redirect_url = '';

// 3. Rule Mapping Matrix for Keywords
$mappings = [
    // --- Hair Categories & Attributes ---
    'hair'       => 'category.php?cat=hair',
    'wig'        => 'category.php?cat=wigs',
    'weave'      => 'category.php?cat=weaves',
    'bundle'     => 'category.php?cat=bundles',
    'closure'    => 'category.php?cat=closures',
    'buss down'  => 'category.php?cat=bussdown',
    '13x4'       => 'category.php?cat=13x4',
    'straight'   => 'category.php?style=straight',
    'curls'      => 'category.php?style=curls',
    'water'      => 'category.php?style=water_wave',
    'jerry'      => 'category.php?style=jerry_curl',
    
    // --- Footwear & Brands ---
    'sneakers'   => 'category.php?cat=sneakers',
    'nike'       => 'brand.php?brand=nike',
    'heels'      => 'category.php?cat=heels',
    'shoes'      => 'category.php?cat=shoes',
    'steve madden' => 'brand.php?brand=steve_madden',
    'adidas'     => 'brand.php?brand=adidas',
    
    // --- Devices & Tech ---
    'devices'    => 'category.php?cat=devices',
    'iphone'     => 'brand.php?brand=iphone',
    'pro max'    => 'category.php?sub=pro_max',
    'jbl'        => 'brand.php?brand=jbl',

    // --- Locations (If typed instead of selected) ---
    'khayelitsha' => 'index.php?location=khayelitsha',
    'ottery'      => 'index.php?location=ottery',
    'blouberg'    => 'index.php?location=blouberg',
    'greenpoint'  => 'index.php?location=greenpoint',
    'nyanga'      => 'index.php?location=nyanga',
    'wynberg'     => 'index.php?location=wynberg',
    'rondebosch'  => 'index.php?location=rondebosch',
    'claremont'   => 'index.php?location=claremont',
    'delft'       => 'index.php?location=delft',
    'gugulethu'   => 'index.php?location=gugulethu'
];

// 4. Evaluate text query matches first
foreach ($mappings as $keyword => $target_path) {
    if (!empty($query) && strpos($query, $keyword) !== false) {
        $redirect_url = $target_path;
        break; // Stop looking once a match is successfully identified
    }
}

// 5. Build dynamic processing logic if no target path was locked
if (empty($redirect_url)) {
    if (!empty($location) && empty($query)) {
        // Dropdown selection only: filter home grid by location
        header("Location: index.php?location=" . urlencode($location));
        exit;
    } elseif (!empty($query)) {
        // Fallback fallback: Unmatched keyword goes to structural search page
        $redirect_url = "search.php?query=" . urlencode($query);
    } else {
        // Empty submission recovery path
        header("Location: index.php");
        exit;
    }
}

// 6. Append structural location data tracking if a dropdown selection was present
if (!empty($location) && strpos($redirect_url, 'index.php') === false) {
    $separator = (strpos($redirect_url, '?') !== false) ? '&' : '?';
    $redirect_url .= $separator . "location=" . urlencode($location);
}

// Execute routing destination link transfer
header("Location: " . $redirect_url);
exit;
?>