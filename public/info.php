<?php
echo "<h1>Bagisto PHP Configuration</h1>";
echo "<p>Last Updated: " . date('Y-m-d H:i:s') . " UTC</p>";
echo "<p>Environment: " . ($_ENV['APP_ENV'] ?? 'production') . "</p>";
echo "<hr>";
phpinfo();
?>
