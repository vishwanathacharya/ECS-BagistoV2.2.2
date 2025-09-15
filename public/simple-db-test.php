<?php
echo "Database Test - " . date('Y-m-d H:i:s') . "<br><br>";

// Try to connect to database using environment variables
$host = getenv('DB_HOST') ?: 'localhost';
$port = getenv('DB_PORT') ?: '3306';
$db = getenv('DB_DATABASE') ?: 'bagisto';
$user = getenv('DB_USERNAME') ?: 'admin';
$pass = getenv('DB_PASSWORD') ?: '';

echo "Host: $host<br>";
echo "Port: $port<br>";
echo "Database: $db<br>";
echo "Username: $user<br>";
echo "Password: " . (empty($pass) ? 'EMPTY' : 'SET') . "<br><br>";

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $pass);
    echo "✅ CONNECTION SUCCESS<br>";
    
    $result = $pdo->query("SELECT VERSION()")->fetchColumn();
    echo "MySQL Version: $result<br>";
    
} catch (Exception $e) {
    echo "❌ CONNECTION FAILED<br>";
    echo "Error: " . $e->getMessage() . "<br>";
}
?>
