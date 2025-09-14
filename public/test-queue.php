<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Request::capture();
$response = $kernel->handle($request);

// Test queue job dispatch
try {
    // Simple test job - send email or log
    \Illuminate\Support\Facades\Queue::push(function($job) {
        \Illuminate\Support\Facades\Log::info('Queue job executed at: ' . now());
        $job->delete();
    });
    
    echo "Queue job dispatched successfully!<br>";
    echo "Check logs: storage/logs/laravel.log<br>";
    echo "Queue connection: " . config('queue.default') . "<br>";
    echo "Time: " . now() . "<br>";
    
} catch (Exception $e) {
    echo "Queue error: " . $e->getMessage();
}

$kernel->terminate($request, $response);
?>
