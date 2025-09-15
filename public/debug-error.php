<?php
echo "<h2>Laravel Error Debug - " . date('Y-m-d H:i:s') . "</h2>";

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    echo "1. Loading autoloader...<br>";
    require_once __DIR__ . '/../vendor/autoload.php';
    echo "✅ Autoloader loaded<br>";
    
    echo "2. Loading Laravel app...<br>";
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "✅ Laravel app loaded<br>";
    
    echo "3. Creating HTTP kernel...<br>";
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ HTTP kernel created<br>";
    
    echo "4. Creating request for '/'...<br>";
    $request = Illuminate\Http\Request::create('/', 'GET');
    echo "✅ Request created<br>";
    
    echo "5. Handling request...<br>";
    ob_start();
    $response = $kernel->handle($request);
    $output = ob_get_clean();
    
    echo "✅ Request handled successfully<br>";
    echo "Status Code: " . $response->getStatusCode() . "<br>";
    echo "Content Length: " . strlen($response->getContent()) . " bytes<br>";
    
    if ($output) {
        echo "Output captured: " . htmlspecialchars($output) . "<br>";
    }
    
    // Check for Laravel errors in logs
    echo "<br><h3>Recent Laravel Logs:</h3>";
    $log_file = __DIR__ . '/../storage/logs/laravel.log';
    if (file_exists($log_file)) {
        $logs = file_get_contents($log_file);
        $recent_logs = substr($logs, -2000); // Last 2000 chars
        echo "<pre>" . htmlspecialchars($recent_logs) . "</pre>";
    } else {
        echo "No log file found at: $log_file<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Error occurred:<br>";
    echo "Message: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . "<br>";
    echo "Line: " . $e->getLine() . "<br>";
    echo "<br><strong>Stack Trace:</strong><br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Check environment variables
echo "<br><h3>Critical Environment Variables:</h3>";
$critical_vars = ['APP_KEY', 'APP_ENV', 'DB_HOST', 'DB_DATABASE', 'DB_USERNAME'];
foreach ($critical_vars as $var) {
    $value = getenv($var) ?: $_ENV[$var] ?? 'NOT SET';
    if ($var === 'APP_KEY' && $value !== 'NOT SET') {
        $value = substr($value, 0, 15) . '...';
    }
    if ($var === 'DB_USERNAME' && $value !== 'NOT SET') {
        $value = substr($value, 0, 5) . '...';
    }
    echo "$var: $value<br>";
}
?>
