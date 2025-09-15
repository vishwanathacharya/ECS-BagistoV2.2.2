<?php
echo "<h2>Laravel Debug - " . date('Y-m-d H:i:s') . "</h2>";

// Check if Laravel bootstrap exists
$bootstrap = __DIR__ . '/../bootstrap/app.php';
echo "Bootstrap file: " . (file_exists($bootstrap) ? '✅ Exists' : '❌ Missing') . "<br>";

// Check vendor autoload
$vendor = __DIR__ . '/../vendor/autoload.php';
echo "Vendor autoload: " . (file_exists($vendor) ? '✅ Exists' : '❌ Missing') . "<br>";

// Check .env file
$env = __DIR__ . '/../.env';
echo ".env file: " . (file_exists($env) ? '✅ Exists' : '❌ Missing') . "<br>";

// Check storage permissions
$storage = __DIR__ . '/../storage';
$bootstrap_cache = __DIR__ . '/../bootstrap/cache';
echo "Storage writable: " . (is_writable($storage) ? '✅ Yes' : '❌ No') . "<br>";
echo "Bootstrap cache writable: " . (is_writable($bootstrap_cache) ? '✅ Yes' : '❌ No') . "<br>";

// Try to load Laravel
try {
    echo "<br><h3>Loading Laravel...</h3>";
    
    if (file_exists($vendor)) {
        require_once $vendor;
    }
    
    if (file_exists($bootstrap)) {
        $app = require_once $bootstrap;
        echo "✅ Laravel app loaded successfully<br>";
        
        // Try to boot the application
        $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
        echo "✅ HTTP Kernel created<br>";
        
        // Check database connection through Laravel
        $app->boot();
        echo "✅ Application booted<br>";
        
    } else {
        echo "❌ Bootstrap file missing<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
}

// Check key Laravel directories
echo "<br><h3>Directory Structure:</h3>";
$dirs = ['app', 'config', 'database', 'resources', 'routes', 'storage', 'vendor'];
foreach ($dirs as $dir) {
    $path = __DIR__ . '/../' . $dir;
    echo "$dir: " . (is_dir($path) ? '✅ Exists' : '❌ Missing') . "<br>";
}

// Check environment variables
echo "<br><h3>Environment Variables:</h3>";
$env_vars = ['APP_ENV', 'APP_DEBUG', 'APP_KEY', 'DB_HOST', 'DB_DATABASE'];
foreach ($env_vars as $var) {
    $value = getenv($var) ?: $_ENV[$var] ?? 'Not Set';
    if ($var === 'APP_KEY' && $value !== 'Not Set') {
        $value = substr($value, 0, 10) . '...';
    }
    echo "$var: $value<br>";
}
?>
