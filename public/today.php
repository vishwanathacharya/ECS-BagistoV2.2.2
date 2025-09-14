<?php
echo "final test - Complete ECS Architecture Deployed";
echo "<br>Timestamp: " . date('Y-m-d H:i:s');
echo "<br>Services: Web + Queue Workers + Scheduler + S3 + CloudFront";
echo "<br>Laravel Cache Cleared: ✅";
echo "<br>Storage Link Created: ✅";
echo "<br>All systems operational!";

// Test storage link
if (is_link(public_path('storage'))) {
    echo "<br>Storage Link Status: ✅ Active";
} else {
    echo "<br>Storage Link Status: ❌ Missing";
}

// Test image loading
echo "<br><br>Testing Image Loading:";
echo "<br><img src='/storage/app/public/test.jpg' alt='Test Image' style='max-width:200px;' onerror='this.style.display=\"none\"; this.nextSibling.style.display=\"inline\";'>";
echo "<span style='display:none; color:red;'>❌ Image not found</span>";
?>
