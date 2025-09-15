<?php
// Error-safe Laravel index with debugging

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

try {
    // Check maintenance mode
    if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
        require $maintenance;
    }

    // Load autoloader
    if (!file_exists(__DIR__.'/../vendor/autoload.php')) {
        die('Vendor autoload not found. Run composer install.');
    }
    require __DIR__.'/../vendor/autoload.php';

    // Load Laravel app
    if (!file_exists(__DIR__.'/../bootstrap/app.php')) {
        die('Laravel bootstrap not found.');
    }
    $app = require_once __DIR__.'/../bootstrap/app.php';

    // Check critical environment variables
    if (!getenv('APP_KEY') && !isset($_ENV['APP_KEY'])) {
        die('APP_KEY not set. Laravel cannot function without encryption key.');
    }

    // Create kernel and handle request
    $kernel = $app->make(Kernel::class);
    
    $response = $kernel->handle(
        $request = Request::capture(),
    );
    
    // Send response
    $response->send();
    
    // Terminate
    $kernel->terminate($request, $response);
    
} catch (Exception $e) {
    // Fallback error page
    http_response_code(500);
    echo "<!DOCTYPE html><html><head><title>Application Error</title></head><body>";
    echo "<h1>Application Error</h1>";
    echo "<p>The application encountered an error and could not complete your request.</p>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<p><a href='/health.php'>Health Check</a> | <a href='/laravel-debug.php'>Debug Info</a></p>";
    echo "</body></html>";
} catch (Error $e) {
    // PHP Fatal errors
    http_response_code(500);
    echo "<!DOCTYPE html><html><head><title>Fatal Error</title></head><body>";
    echo "<h1>Fatal Error</h1>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
    echo "<p><a href='/health.php'>Health Check</a> | <a href='/laravel-debug.php'>Debug Info</a></p>";
    echo "</body></html>";
}
?>
