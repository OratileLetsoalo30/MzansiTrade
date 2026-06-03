<?php
// search.php
if (isset($_GET['q'])) {
    $query = strtolower(trim($_GET['q']));

    // Define category mappings
    $map = [
        // Hair products
        'hair' => 'category.php?cat=hair',
        'wig' => 'category.php?cat=wigs',
        'weave' => 'category.php?cat=weaves',
        'bundle' => 'category.php?cat=bundles',
        'closure' => 'category.php?cat=closures',
        'buss down' => 'category.php?cat=bussdown',
        '13x4' => 'category.php?cat=13x4',
        'straight' => 'category.php?style=straight',
        'curls' => 'category.php?style=curls',
        'water' => 'category.php?style=water_wave',
        'jerry' => 'category.php?style=jerry_curl',
        
        // Shoes
        'sneakers' => 'category.php?cat=sneakers',
        'nike' => 'brand.php?brand=nike',
        'heels' => 'category.php?cat=heels',
        'shoes' => 'category.php?cat=shoes',
        'steve madden' => 'brand.php?brand=steve_madden',
        'adidas' => 'brand.php?brand=adidas',
        
        // Devices
        'devices' => 'category.php?cat=devices',
        'iphone' => 'brand.php?brand=iphone',
        'pro max' => 'search.php?q=pro+max', // Generic search
        'jbl' => 'brand.php?brand=jbl',
        
        // Locations
        'khayelitsha' => 'index.php?location=khayelitsha',
        'ottery' => 'index.php?location=ottery',
        'blouberg' => 'index.php?location=blouberg',
        'greenpoint' => 'index.php?location=greenpoint',
        'nyanga' => 'index.php?location=nyanga',
        'wynberg' => 'index.php?location=wynberg',
        'rondebosch' => 'index.php?location=rondebosch',
        'claremont' => 'index.php?location=claremont',
        'delft' => 'index.php?location=delft',
        'gugulethu' => 'index.php?location=gugulethu'
    ];

    // Check if the query matches a key
    foreach ($map as $keyword => $url) {
        if (strpos($query, $keyword) !== false) {
            header("Location: " . $url);
            exit;
        }
    }

    // Default fallback: If no keyword matched, go to general results page
    header("Location: search_results.php?q=" . urlencode($query));
    exit;
}
?>