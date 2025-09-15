<?php
echo "<h2>Route Test - " . date('Y-m-d H:i:s') . "</h2>";

// Test if we can load Laravel and access routes
try {
    // Load Laravel
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    
    echo "✅ Laravel loaded<br>";
    
    // Boot the application
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    echo "✅ Kernel created<br>";
    
    // Test route resolution
    $request = Illuminate\Http\Request::create('/', 'GET');
    echo "✅ Request created for '/'<br>";
    
    // Try to handle the request
    try {
        $response = $kernel->handle($request);
        echo "✅ Route handled successfully<br>";
        echo "Status Code: " . $response->getStatusCode() . "<br>";
        echo "Content Length: " . strlen($response->getContent()) . " bytes<br>";
        
        // Show first 200 chars of response
        $content = $response->getContent();
        if (strlen($content) > 200) {
            echo "Content Preview: " . htmlspecialchars(substr($content, 0, 200)) . "...<br>";
        } else {
            echo "Full Content: " . htmlspecialchars($content) . "<br>";
        }
        
    } catch (Exception $e) {
        echo "❌ Route handling failed<br>";
        echo "Error: " . $e->getMessage() . "<br>";
        echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
        
        // Show stack trace
        echo "<br><strong>Stack Trace:</strong><br>";
        echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
    }
    
} catch (Exception $e) {
    echo "❌ Laravel loading failed<br>";
    echo "Error: " . $e->getMessage() . "<br>";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "<br>";
}

// Test direct route file
echo "<br><h3>Route Files Check:</h3>";
$web_routes = __DIR__ . '/../routes/web.php';
if (file_exists($web_routes)) {
    echo "✅ web.php exists<br>";
    $content = file_get_contents($web_routes);
    echo "Routes file size: " . strlen($content) . " bytes<br>";
    
    // Show first few lines
    $lines = explode("\n", $content);
    echo "First 5 lines:<br>";
    for ($i = 0; $i < min(5, count($lines)); $i++) {
        echo ($i + 1) . ": " . htmlspecialchars($lines[$i]) . "<br>";
    }
} else {
    echo "❌ web.php missing<br>";
}
?>
