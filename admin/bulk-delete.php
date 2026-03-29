<?php
session_start();
if(!isset($_SESSION['user'])) { die("Must be logged in to run cleanup."); }

echo "<div style='font-family: sans-serif; padding: 20px;'>";
echo "<h2>TechPulse  Slate - Mass File Cleanup</h2>";

$files_to_delete = [
    // Old 3-Tier Categories
    'top-category.php', 'top-category-add.php', 'top-category-edit.php', 'top-category-delete.php',
    'mid-category.php', 'mid-category-add.php', 'mid-category-edit.php', 'mid-category-delete.php', 'get-mid-category.php',
    'end-category.php', 'end-category-add.php', 'end-category-edit.php', 'end-category-delete.php', 'get-end-category.php',
    
    // Subscriber System (Removing)
    'subscriber.php', 'subscriber-add.php', 'subscriber-delete.php', 'subscriber-csv.php', 'subscriber-remove.php',
    
    // Old Split CRUD Files (We keep the main ones to overwrite later)
    'slider-add.php', 'slider-edit.php', 'slider-delete.php',
    'service-add.php', 'service-edit.php', 'service-delete.php',
    'photo-add.php', 'photo-edit.php', 'photo-delete.php',
    'country-add.php', 'country-edit.php', 'country-delete.php',
    'shipping-cost-edit.php',
    
    // Old Attributes
    'color.php', 'color-add.php', 'color-edit.php', 'color-delete.php',
    'size.php', 'size-add.php', 'size-edit.php', 'size-delete.php'
];

$deleted_count = 0;

foreach ($files_to_delete as $file) {
    $file_path = __DIR__ . '/' . $file;
    if (file_exists($file_path)) {
        if (unlink($file_path)) {
            echo "<p style='color:green; margin: 5px 0;'>✅ Deleted: <strong>{$file}</strong></p>";
            $deleted_count++;
        } else {
            echo "<p style='color:red; margin: 5px 0;'>❌ Failed to delete: <strong>{$file}</strong> (Check file permissions)</p>";
        }
    } else {
        echo "<p style='color:gray; margin: 5px 0;'>⏭️ Already removed: <strong>{$file}</strong></p>";
    }
}

echo "<h3 style='margin-top: 20px;'>🎉 Cleanup Complete! Deleted {$deleted_count} files.</h3>";
echo "<p>You can now delete this <code>bulk-delete.php</code> file.</p>";
echo "<a href='index.php' style='display:inline-block; padding:10px 15px; background:#0052cc; color:#fff; text-decoration:none; border-radius:5px;'>Return to Dashboard</a>";
echo "</div>";
?>