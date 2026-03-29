<?php
ob_start();
session_start();
include("admin/inc/config.php");
include("admin/inc/functions.php");
include("admin/inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();

// Security Check: Kick out if not logged in
if(!isset($_SESSION['customer'])) {
    header('location: login.php');
    exit;
}

$error_message = '';
$success_message = '';

// ==========================================
// HANDLE PROFILE UPDATE
// ==========================================
if(isset($_POST['update_profile'])) {
    $valid = 1;
    
    if(empty($_POST['cust_name'])) {
        $valid = 0; $error_message .= "Name cannot be empty.<br>";
    }
    if(empty($_POST['cust_phone'])) {
        $valid = 0; $error_message .= "Phone number cannot be empty.<br>";
    }

    if($valid == 1) {
        // 1. Update Basic & Location Info
        $statement = $pdo->prepare("UPDATE tbl_customer SET cust_name=?, cust_phone=?, cust_country=?, cust_address=?, cust_city=?, cust_state=?, cust_zip=? WHERE cust_id=?");
        $statement->execute(array(
            $_POST['cust_name'], 
            $_POST['cust_phone'], 
            $_POST['cust_country'], 
            $_POST['cust_address'], 
            $_POST['cust_city'], 
            $_POST['cust_state'], 
            $_POST['cust_zip'], 
            $_SESSION['customer']['cust_id']
        ));

        // Update Session Name to reflect immediately in the header
        $_SESSION['customer']['cust_name'] = $_POST['cust_name'];

        // 2. Handle Password Change (Only if the user typed a new one)
        if(!empty($_POST['new_password'])) {
            $statement = $pdo->prepare("UPDATE tbl_customer SET cust_password=? WHERE cust_id=?");
            $statement->execute(array(md5($_POST['new_password']), $_SESSION['customer']['cust_id']));
        }

        $success_message = 'Profile updated successfully.';
    }
}

// Fetch Current Customer Data to Pre-fill the form
$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=?");
$statement->execute(array($_SESSION['customer']['cust_id']));
$customer = $statement->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Profile Settings | TechPulse  Slate</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "primary": "#003c9d", "on-primary": "#ffffff", "surface": "#faf8ff", "on-surface": "#131b2e", "surface-variant": "#dae2fd", "on-surface-variant": "#434654", "outline": "#737685", "outline-variant": "#c3c6d6", "error": "#ba1a1a"
            },
            fontFamily: { "headline": ["Manrope"], "body": ["Inter"], "label": ["Inter"] },
          },
        },
      }
    </script>
    <style>
      .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
      body { -webkit-font-smoothing: antialiased; }
    </style>
</head>
<body class="bg-[#faf8ff] text-[#131b2e] font-body dark:bg-slate-900 dark:text-slate-100 transition-colors duration-200">

    <nav class="fixed top-0 w-full z-50 bg-[#faf8ff]/80 dark:bg-slate-950/80 backdrop-blur-xl border-b border-slate-200 dark:border-slate-800">
        <div class="flex justify-between items-center px-8 h-16 max-w-7xl mx-auto">
            <a href="index.php" class="text-xl font-bold tracking-tighter text-slate-900 dark:text-slate-50 font-headline">TechPulse  Slate</a>
            <div class="hidden md:flex items-center space-x-8">
                <a class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-all font-semibold" href="product.php">Shop</a>
                <a class="text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-100 transition-all font-semibold" href="contact.php">Support</a>
                <a class="text-blue-700 dark:text-blue-400 border-b-2 border-blue-700 dark:border-blue-400 pb-1 font-bold" href="profile.php">Account</a>
            </div>
            <div class="flex items-center space-x-6">
                <a href="cart.php" class="transition-all hover:opacity-80 active:scale-95 text-slate-600 dark:text-slate-400 relative">
                    <span class="material-symbols-outlined">shopping_cart</span>
                    <?php if(isset($_SESSION['cart_p_id'])): ?>
                        <span class="absolute -top-1 -right-2 bg-blue-600 text-white text-[9px] font-bold px-1.5 py-0.5 rounded-full"><?php echo count($_SESSION['cart_p_id']); ?></span>
                    <?php endif; ?>
                </a>
                <a href="profile.php" class="transition-all hover:opacity-80 active:scale-95 text-slate-600 dark:text-slate-400">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">account_circle</span>
                </a>
            </div>
        </div>
    </nav>

    <main class="pt-24 pb-20 px-4 md:px-8 max-w-7xl mx-auto min-h-[80vh]">
        
        <header class="mb-12">
            <p class="text-[0.625rem] uppercase tracking-[0.2em] font-bold text-slate-500 dark:text-slate-400 mb-3 font-label">Account Dashboard</p>
            <h1 class="text-4xl md:text-5xl font-extrabold font-headline tracking-tighter text-slate-900 dark:text-white">Profile Settings</h1>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
            
            <aside class="lg:col-span-3">
                <nav class="flex flex-col gap-2 bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">My Account</p>
                    
                    <a href="customer-order.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <span class="material-symbols-outlined text-[20px]">local_shipping</span> Orders
                    </a>
                    
                    <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400">
                        <span class="material-symbols-outlined text-[20px]">manage_accounts</span> Profile Settings
                    </a>
                    
                    <div class="border-t border-slate-100 dark:border-slate-700 my-2"></div>
                    
                    <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">logout</span> Sign Out
                    </a>
                </nav>
            </aside>

            <section class="lg:col-span-9">
                <div class="bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm">
                    
                    <?php if($error_message): ?>
                        <div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-8 text-sm font-bold border border-red-200 dark:border-red-500/20"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    <?php if($success_message): ?>
                        <div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-4 py-3 rounded-lg mb-8 text-sm font-bold border border-green-200 dark:border-green-500/20"><?php echo $success_message; ?></div>
                    <?php endif; ?>

                    <form action="" method="post" class="space-y-10">
                        <?php $csrf->echoInputField(); ?>
                        
                        <div>
                            <h2 class="text-lg font-bold font-headline text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">Personal Information</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Full Name *</label>
                                    <input type="text" name="cust_name" value="<?php echo htmlspecialchars($customer['cust_name']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-blue-600 focus:ring-1 focus:ring-blue-600 transition-colors" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Email Address</label>
                                    <input type="email" value="<?php echo htmlspecialchars($customer['cust_email']); ?>" class="w-full bg-slate-100 dark:bg-slate-900/50 text-slate-500 dark:text-slate-500 border border-slate-200 dark:border-slate-700 rounded-lg py-3 px-4 text-sm cursor-not-allowed" disabled>
                                    <p class="text-[10px] text-slate-400 mt-1">To change your email, contact support.</p>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Phone Number *</label>
                                    <input type="text" name="cust_phone" value="<?php echo htmlspecialchars($customer['cust_phone']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-blue-600 transition-colors" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold font-headline text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">Shipping Details</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Street Address</label>
                                    <input type="text" name="cust_address" value="<?php echo htmlspecialchars($customer['cust_address']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-blue-600 transition-colors">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">City</label>
                                    <input type="text" name="cust_city" value="<?php echo htmlspecialchars($customer['cust_city']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-blue-600 transition-colors">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">State / Province</label>
                                    <input type="text" name="cust_state" value="<?php echo htmlspecialchars($customer['cust_state']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-blue-600 transition-colors">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Zip / Postal Code</label>
                                    <input type="text" name="cust_zip" value="<?php echo htmlspecialchars($customer['cust_zip']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-blue-600 transition-colors">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Country</label>
                                    <select name="cust_country" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-blue-600 transition-colors">
                                        <option value="">Select Country</option>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                        $statement->execute();
                                        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
                                            $selected = ($row['country_id'] == $customer['cust_country']) ? 'selected' : '';
                                            echo '<option value="'.$row['country_id'].'" '.$selected.'>'.htmlspecialchars($row['country_name']).'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h2 class="text-lg font-bold font-headline text-slate-900 dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">Security</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Update Password</label>
                                    <input type="password" name="new_password" placeholder="Leave blank to keep current password" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-blue-600 transition-colors">
                                </div>
                            </div>
                        </div>

                        <div class="pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end">
                            <button type="submit" name="update_profile" class="px-8 py-3 bg-blue-600 text-white rounded-lg font-bold uppercase tracking-widest text-sm shadow-lg shadow-blue-600/30 hover:bg-blue-700 transition-all hover:-translate-y-0.5">
                                Save Changes
                            </button>
                        </div>

                    </form>
                </div>
            </section>
        </div>
    </main>

    <footer class="w-full border-t border-slate-200 dark:border-slate-800 bg-[#f2f3ff] dark:bg-slate-900">
        <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 max-w-7xl mx-auto font-['Inter'] text-sm">
            <div class="mb-8 md:mb-0">
                <div class="text-lg font-bold text-slate-900 dark:text-slate-50 font-headline mb-2">TechPulse  Slate</div>
                <p class="text-slate-500 dark:text-slate-400">© <?php echo date('Y'); ?> TechPulse  Slate. Precision Engineering.</p>
            </div>
            <div class="flex gap-8">
                <a class="text-slate-500 dark:text-slate-400 hover:text-blue-600 transition-colors font-bold" href="#">Privacy Policy</a>
                <a class="text-slate-500 dark:text-slate-400 hover:text-blue-600 transition-colors font-bold" href="#">Terms of Service</a>
                <a class="text-slate-500 dark:text-slate-400 hover:text-blue-600 transition-colors font-bold" href="contact.php">Support</a>
            </div>
        </div>
    </footer>

</body>
</html>