<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Request::capture();
$response = $kernel->handle($request, $request);

echo "<h2>Laravel Scheduler Status</h2>";

try {
    // Get scheduled tasks
    $schedule = app()->make(\Illuminate\Console\Scheduling\Schedule::class);
    $events = $schedule->events();
    
    echo "<h3>Scheduled Tasks (" . count($events) . " total):</h3>";
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
    echo "<tr><th>Command</th><th>Expression</th><th>Description</th></tr>";
    
    foreach ($events as $event) {
        $command = $event->command ?? $event->getSummaryForDisplay();
        $expression = $event->getExpression();
        $description = $event->description ?? 'No description';
        
        echo "<tr>";
        echo "<td>" . htmlspecialchars($command) . "</td>";
        echo "<td>" . htmlspecialchars($expression) . "</td>";
        echo "<td>" . htmlspecialchars($description) . "</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    echo "<br><h3>Next Run Times:</h3>";
    foreach ($events as $event) {
        if (method_exists($event, 'nextRunDate')) {
            $nextRun = $event->nextRunDate();
            echo "• " . ($event->getSummaryForDisplay()) . " → " . $nextRun->format('Y-m-d H:i:s') . "<br>";
        }
    }
    
    echo "<br><p><strong>Scheduler Server:</strong> Running separately from main app</p>";
    echo "<p><strong>Current Time:</strong> " . now() . "</p>";
    
} catch (Exception $e) {
    echo "Scheduler error: " . $e->getMessage();
}

$kernel->terminate($request, $response);
?>
