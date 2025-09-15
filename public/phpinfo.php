<?php
echo "<h2>PHP Extensions Check</h2>";

// Check GD extension
if (extension_loaded('gd')) {
    echo "✅ GD Extension: ENABLED<br>";
    $gd_info = gd_info();
    echo "GD Version: " . $gd_info['GD Version'] . "<br>";
    echo "JPEG Support: " . ($gd_info['JPEG Support'] ? '✅ Yes' : '❌ No') . "<br>";
    echo "PNG Support: " . ($gd_info['PNG Support'] ? '✅ Yes' : '❌ No') . "<br>";
    echo "WebP Support: " . ($gd_info['WebP Support'] ? '✅ Yes' : '❌ No') . "<br>";
    echo "FreeType Support: " . ($gd_info['FreeType Support'] ? '✅ Yes' : '❌ No') . "<br>";
} else {
    echo "❌ GD Extension: NOT ENABLED<br>";
}

echo "<br><h3>All Loaded Extensions:</h3>";
$extensions = get_loaded_extensions();
sort($extensions);
foreach ($extensions as $ext) {
    echo "• " . $ext . "<br>";
}

echo "<br><h3>PHP Version:</h3>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server: " . $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' . "<br>";
echo "Timestamp: " . date('Y-m-d H:i:s') . "<br>";
?>
