<?php
ob_start();
session_start();
include("admin/inc/config.php"); // Connect to your database automatically

echo "<div style='font-family: sans-serif; max-width: 800px; margin: 50px auto; padding: 30px; background: #f8fafc; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);'>";
echo "<h1 style='color: #0ea5e9;'>TechPulse Automated Store Setup</h1>";

// =======================================================
// 1. AUTOMATICALLY DOWNLOAD IMAGES
// =======================================================
$upload_dir = __DIR__ . '/assets/uploads/';
if (!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

$images = [
    // Watches
    'watch-1.jpg' => 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?q=80&w=800&auto=format&fit=crop',
    'watch-2.jpg' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?q=80&w=800&auto=format&fit=crop',
    'watch-3.jpg' => 'https://images.unsplash.com/photo-1434493789847-2f02dc6ca35d?q=80&w=800&auto=format&fit=crop',
    'watch-4.jpg' => 'https://images.unsplash.com/photo-1522312346375-d1a52e2b99b3?q=80&w=800&auto=format&fit=crop',
    // Laptops
    'laptop-1.jpg' => 'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=800&auto=format&fit=crop',
    'laptop-2.jpg' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?q=80&w=800&auto=format&fit=crop',
    'laptop-3.jpg' => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?q=80&w=800&auto=format&fit=crop',
    'laptop-4.jpg' => 'https://images.unsplash.com/photo-1593640408182-31c70c8268f5?q=80&w=800&auto=format&fit=crop',
    // Cameras
    'camera-1.jpg' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?q=80&w=800&auto=format&fit=crop',
    'camera-2.jpg' => 'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?q=80&w=800&auto=format&fit=crop',
    'camera-3.jpg' => 'https://images.unsplash.com/photo-1516961642265-531546e84af2?q=80&w=800&auto=format&fit=crop',
    'camera-4.jpg' => 'https://images.unsplash.com/photo-1564466809058-bf4114d55352?q=80&w=800&auto=format&fit=crop'
];

echo "<h3>📥 1. Downloading Premium Images...</h3><ul style='font-size: 14px; color: #475569;'>";
foreach ($images as $filename => $url) {
    $filepath = $upload_dir . $filename;
    // Only download if it doesn't already exist to save time
    if(!file_exists($filepath)) {
        $image_data = @file_get_contents($url);
        if ($image_data !== false) {
            file_put_contents($filepath, $image_data);
            echo "<li>✅ Saved: <b>$filename</b></li>";
        } else {
            echo "<li style='color:red;'>❌ Failed: <b>$filename</b></li>";
        }
    } else {
        echo "<li>⚡ Skipped (Already exists): <b>$filename</b></li>";
    }
}
echo "</ul>";


// =======================================================
// 2. AUTOMATICALLY CREATE CATEGORIES & PRODUCTS
// =======================================================
echo "<h3>🗄️ 2. Setting up Database Categories & Products...</h3>";

try {
    // Disable Foreign Key checks temporarily
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");

    // --- CREATE WATCHES CATEGORY ---
    $pdo->exec("INSERT INTO tbl_top_category (tcat_name, show_on_menu) VALUES ('Watches', 1)");
    $tcat_watches = $pdo->lastInsertId();
    $pdo->exec("INSERT INTO tbl_mid_category (mcat_name, tcat_id) VALUES ('Premium Watches', $tcat_watches)");
    $mcat_watches = $pdo->lastInsertId();
    $pdo->exec("INSERT INTO tbl_end_category (ecat_name, mcat_id) VALUES ('Smart & Analog', $mcat_watches)");
    $ecat_watches = $pdo->lastInsertId();

    // --- CREATE LAPTOPS CATEGORY ---
    $pdo->exec("INSERT INTO tbl_top_category (tcat_name, show_on_menu) VALUES ('Computers', 1)");
    $tcat_laptops = $pdo->lastInsertId();
    $pdo->exec("INSERT INTO tbl_mid_category (mcat_name, tcat_id) VALUES ('Laptops', $tcat_laptops)");
    $mcat_laptops = $pdo->lastInsertId();
    $pdo->exec("INSERT INTO tbl_end_category (ecat_name, mcat_id) VALUES ('Pro Series Laptops', $mcat_laptops)");
    $ecat_laptops = $pdo->lastInsertId();

    // --- CREATE CAMERAS CATEGORY ---
    $pdo->exec("INSERT INTO tbl_top_category (tcat_name, show_on_menu) VALUES ('Photography', 1)");
    $tcat_cameras = $pdo->lastInsertId();
    $pdo->exec("INSERT INTO tbl_mid_category (mcat_name, tcat_id) VALUES ('Cameras', $tcat_cameras)");
    $mcat_cameras = $pdo->lastInsertId();
    $pdo->exec("INSERT INTO tbl_end_category (ecat_name, mcat_id) VALUES ('DSLR & Mirrorless', $mcat_cameras)");
    $ecat_cameras = $pdo->lastInsertId();

    // --- INSERT PRODUCTS (Automatically using the correct IDs) ---
    $stmt = $pdo->prepare("INSERT INTO tbl_product (p_name, p_old_price, p_current_price, p_qty, p_featured_photo, p_description, p_short_description, p_feature, p_condition, p_return_policy, p_total_view, p_is_featured, p_is_active, ecat_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    // Laptops
    $stmt->execute(['Apple MacBook Air M3 Chip', 149900, 134900, 15, 'laptop-1.jpg', '<p>The superlight and incredibly fast MacBook Air with M3 chip.</p>', 'Superlight, powerful ultrabook.', 'Apple M3 Chip\n8GB RAM\n256GB SSD', 'New', '1-year warranty.', 0, 1, 1, $ecat_laptops]);
    $stmt->execute(['ASUS ROG Strix G16 Gaming', 149990, 124990, 10, 'laptop-2.jpg', '<p>Dominate your games with the ASUS ROG Strix.</p>', 'Powerful gaming laptop.', 'Intel Core i7\nRTX 4050\n16GB RAM', 'New', '1-year warranty.', 0, 1, 1, $ecat_laptops]);
    $stmt->execute(['Dell Inspiron 15 (12th Gen)', 46490, 39490, 40, 'laptop-3.jpg', '<p>A great value laptop for everyday tasks.</p>', 'Reliable everyday laptop.', 'Intel Core i3\n8GB RAM\n512GB SSD', 'New', '1-year warranty.', 0, 0, 1, $ecat_laptops]);
    $stmt->execute(['Lenovo IdeaPad Slim 1', 32490, 25990, 60, 'laptop-4.jpg', '<p>A stylish and portable budget laptop.</p>', 'Ultra-portable budget laptop.', 'AMD Ryzen 3\n8GB RAM\n512GB SSD', 'New', '1-year warranty.', 0, 0, 1, $ecat_laptops]);

    // Watches
    $stmt->execute(['Apple Watch Ultra 2 (Titanium)', 99900, 89900, 10, 'watch-1.jpg', '<p>The ultimate rugged smartwatch.</p>', 'Rugged smartwatches for extreme performance.', '49mm Titanium Case\nDual-Frequency GPS', 'New', '1-year warranty.', 0, 1, 1, $ecat_watches]);
    $stmt->execute(['Samsung Galaxy Watch 6 Classic', 35999, 29999, 25, 'watch-2.jpg', '<p>Elevate your health and style.</p>', 'Premium smartwatch with health monitoring.', '1.3" AMOLED Display\nRotating Bezel', 'New', '1-year warranty.', 0, 1, 1, $ecat_watches]);
    $stmt->execute(['Titan Classic Silver Dial', 3295, 2495, 50, 'watch-3.jpg', '<p>A sophisticated addition to your everyday attire.</p>', 'A classic, analogue watch.', 'Analogue Display\n50m Water Resistance', 'New', '30-day warranty.', 0, 0, 1, $ecat_watches]);
    $stmt->execute(['Noise ColorFit Pro 4', 5999, 2999, 100, 'watch-4.jpg', '<p>Your ultimate budget health companion.</p>', 'Affordable smartwatch with big display.', '1.72" Display\nBluetooth Calling', 'New', '7-day returns.', 0, 0, 1, $ecat_watches]);

    // Cameras
    $stmt->execute(['Sony Alpha 7 IV (Full-Frame)', 269990, 242990, 8, 'camera-1.jpg', '<p>The pro standard for high-resolution imaging.</p>', 'Professional full-frame mirrorless camera.', '33MP Sensor\n4K 60p Video', 'New', '2-year warranty.', 0, 1, 1, $ecat_cameras]);
    $stmt->execute(['Nikon Z fc Retro Mirrorless', 105995, 96995, 12, 'camera-2.jpg', '<p>Iconic retro design meets Z series power.</p>', 'Iconic retro design mirrorless camera.', '20.9MP Sensor\nVari-Angle Touch Screen', 'New', '1-year warranty.', 0, 1, 1, $ecat_cameras]);
    $stmt->execute(['Canon PowerShot G7 X Mark III', 69995, 62995, 20, 'camera-3.jpg', '<p>The compact camera of choice for vloggers.</p>', 'Compact vlogging camera.', '20.1MP Stacked CMOS\n4K Video', 'New', '1-year warranty.', 0, 0, 1, $ecat_cameras]);
    $stmt->execute(['GoPro HERO 12 Black', 49900, 44900, 30, 'camera-4.jpg', '<p>The ultimate action camera, now better than ever.</p>', 'Ultimate action camera.', '5.3K 60p Video\nHyperSmooth 6.0', 'New', '1-year warranty.', 0, 0, 1, $ecat_cameras]);

    // Re-enable Foreign Key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");

    echo "<h3 style='color: #10b981;'>🎉 Success! 12 Realistic Products and Categories have been added perfectly.</h3>";
    echo "<p>You can now go back to your <a href='index.php' style='color:#0ea5e9; font-weight:bold;'>Home Page</a> to see your beautiful new store!</p>";

} catch(PDOException $e) {
    echo "<h3 style='color:red;'>Database Error:</h3> <p>" . $e->getMessage() . "</p>";
}

echo "</div>";
?>