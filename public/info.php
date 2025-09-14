<?php
echo "<h1>Bagisto Application Info - testnov10</h1>";
echo "<p>Deployment Time: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Test Marker: testnov10</p>";
echo "<p>Server: " . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";
phpinfo();
?>
