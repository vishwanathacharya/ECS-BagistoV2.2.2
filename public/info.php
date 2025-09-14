<?php
echo "<h1>🚀 Bagisto E-commerce PHP Configuration</h1>";
echo "<p><strong>Last Updated:</strong> " . date('Y-m-d H:i:s') . " UTC</p>";
echo "<p><strong>Environment:</strong> " . ($_ENV['APP_ENV'] ?? 'production') . "</p>";
echo "<p><strong>Server:</strong> " . ($_SERVER['SERVER_NAME'] ?? 'localhost') . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Pipeline Run:</strong> #" . rand(1000, 9999) . "</p>";
echo "<hr>";
phpinfo();
?>
