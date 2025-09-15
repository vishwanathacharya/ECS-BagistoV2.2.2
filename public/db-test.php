<?php
echo "<h2>Database Connection Test</h2>";

// Get environment variables
$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST') ?? 'localhost';
$port = $_ENV['DB_PORT'] ?? getenv('DB_PORT') ?? '3306';
$database = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE') ?? 'bagisto';
$username = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME') ?? 'admin';
$password = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD') ?? '';

echo "Database Configuration:<br>";
echo "Host: " . $host . "<br>";
echo "Port: " . $port . "<br>";
echo "Database: " . $database . "<br>";
echo "Username: " . $username . "<br>";
echo "Password: " . (empty($password) ? '❌ Empty' : '✅ Set (' . strlen($password) . ' chars)') . "<br><br>";

// Test connection
try {
    $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_TIMEOUT => 5
    ]);
    
    echo "✅ Database Connection: SUCCESS<br>";
    
    // Test query
    $stmt = $pdo->query("SELECT VERSION() as version, NOW() as current_time");
    $result = $stmt->fetch();
    
    echo "MySQL Version: " . $result['version'] . "<br>";
    echo "Current Time: " . $result['current_time'] . "<br>";
    
    // Test tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<br>Tables Count: " . count($tables) . "<br>";
    if (count($tables) > 0) {
        echo "Sample Tables: " . implode(', ', array_slice($tables, 0, 5)) . "<br>";
    } else {
        echo "❌ No tables found - Database might not be migrated<br>";
    }
    
} catch (PDOException $e) {
    echo "❌ Database Connection: FAILED<br>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "Error Code: " . $e->getCode() . "<br>";
}

// Test Laravel environment
echo "<br><h3>Laravel Environment Check:</h3>";
$env_file = __DIR__ . '/../.env';
if (file_exists($env_file)) {
    echo "✅ .env file exists<br>";
    $env_content = file_get_contents($env_file);
    echo "APP_KEY: " . (strpos($env_content, 'APP_KEY=') !== false ? '✅ Set' : '❌ Missing') . "<br>";
    echo "DB_HOST: " . (strpos($env_content, 'DB_HOST=') !== false ? '✅ Set' : '❌ Missing') . "<br>";
} else {
    echo "❌ .env file missing<br>";
}

// Test storage permissions
echo "<br><h3>Storage Permissions:</h3>";
$storage_path = __DIR__ . '/../storage';
$bootstrap_cache = __DIR__ . '/../bootstrap/cache';

echo "Storage writable: " . (is_writable($storage_path) ? '✅ Yes' : '❌ No') . "<br>";
echo "Bootstrap cache writable: " . (is_writable($bootstrap_cache) ? '✅ Yes' : '❌ No') . "<br>";

echo "<br>Timestamp: " . date('Y-m-d H:i:s');
?>
