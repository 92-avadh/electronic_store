<?php require_once('header.php'); ?>

<?php
// =========================================
// SECURITY CHECK: Kick out unlogged users
// =========================================
if(!isset($_SESSION['customer'])) {
    header('location: '.BASE_URL.'login.php');
    exit;
}

$error_message = '';
$success_message = '';

// Handle Profile Update
if(isset($_POST['update_profile'])) {
    $valid = 1;
    if(empty($_POST['cust_name'])) { $valid = 0; $error_message .= "Name cannot be empty.<br>"; }
    if(empty($_POST['cust_phone'])) { $valid = 0; $error_message .= "Phone number cannot be empty.<br>"; }

    if($valid == 1) {
        $statement = $pdo->prepare("UPDATE tbl_customer SET cust_name=?, cust_phone=?, cust_country=?, cust_address=?, cust_city=?, cust_state=?, cust_zip=? WHERE cust_id=?");
        $statement->execute(array($_POST['cust_name'], $_POST['cust_phone'], $_POST['cust_country'], $_POST['cust_address'], $_POST['cust_city'], $_POST['cust_state'], $_POST['cust_zip'], $_SESSION['customer']['cust_id']));
        $_SESSION['customer']['cust_name'] = $_POST['cust_name'];

        if(!empty($_POST['new_password'])) {
            $statement = $pdo->prepare("UPDATE tbl_customer SET cust_password=? WHERE cust_id=?");
            $statement->execute(array(md5($_POST['new_password']), $_SESSION['customer']['cust_id']));
        }
        $success_message = 'Profile updated successfully.';
    }
}

// Fetch Current Customer Data
$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_id=?");
$statement->execute(array($_SESSION['customer']['cust_id']));
$customer = $statement->fetch(PDO::FETCH_ASSOC);

// Fetch user data from session for sidebar
$cust_name = $_SESSION['customer']['cust_name'];
$first_name = explode(' ', trim($cust_name))[0];
$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
?>

<div class="flex min-h-screen pt-20 bg-surface dark:bg-slate-900 transition-colors duration-300">
    
    <aside class="h-[calc(100vh-80px)] w-64 sticky top-[80px] flex-shrink-0 bg-slate-50 dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col p-4 gap-2 z-40 overflow-y-auto transition-colors duration-300">
        
        <div class="mb-8 px-2 flex items-center gap-3 pt-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-indigo-500 flex items-center justify-center text-white font-headline font-black text-xl shadow-md">
                <?php echo strtoupper(substr($first_name, 0, 1)); ?>
            </div>
            <div>
                <h1 class="font-headline font-black text-sm text-surfaceDark dark:text-white truncate max-w-[140px]"><?php echo htmlspecialchars($cust_name); ?></h1>
                <p class="text-[10px] uppercase tracking-widest text-primary dark:text-indigo-400 font-bold">Verified Member</p>
            </div>
        </div>

        <nav class="flex-grow space-y-2">
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'dashboard.php') ? 'bg-white dark:bg-slate-800 text-primary dark:text-indigo-400 shadow-sm font-bold border border-slate-100 dark:border-slate-700' : 'text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-indigo-400 hover:bg-white dark:hover:bg-slate-800 font-semibold'; ?>" href="dashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-body text-sm">Overview</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'customer-order.php') ? 'bg-white dark:bg-slate-800 text-primary dark:text-indigo-400 shadow-sm font-bold border border-slate-100 dark:border-slate-700' : 'text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-indigo-400 hover:bg-white dark:hover:bg-slate-800 font-semibold'; ?>" href="customer-order.php">
                <span class="material-symbols-outlined">package_2</span>
                <span class="font-body text-sm">My Orders</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'customer-profile-update.php') ? 'bg-white dark:bg-slate-800 text-primary dark:text-indigo-400 shadow-sm font-bold border border-slate-100 dark:border-slate-700' : 'text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-indigo-400 hover:bg-white dark:hover:bg-slate-800 font-semibold'; ?>" href="customer-profile-update.php">
                <span class="material-symbols-outlined">manage_accounts</span>
                <span class="font-body text-sm">Profile Settings</span>
            </a>
        </nav>

        <div class="mt-auto pt-4 space-y-4">
            <div class="flex flex-col gap-1 border-t border-slate-200 dark:border-slate-700 pt-4">
                <a class="flex items-center gap-3 px-4 py-2 text-textMuted dark:text-slate-400 hover:text-red-500 transition-colors text-sm font-bold" href="logout.php">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Logout
                </a>
            </div>
        </div>
    </aside>

    <main class="flex-grow flex flex-col min-h-[calc(100vh-80px)] overflow-hidden">
        
        <section class="p-8 md:p-12 max-w-4xl mx-auto w-full" data-aos="fade-in" data-aos-duration="800">
            
            <div class="mb-12">
                <span class="text-xs font-bold text-primary dark:text-indigo-400 tracking-[0.2em] uppercase mb-2 block">Security & Data</span>
                <h1 class="text-4xl md:text-5xl font-headline font-black text-surfaceDark dark:text-white tracking-tight">Profile Settings</h1>
                <p class="text-textMuted dark:text-slate-400 mt-3 max-w-lg font-medium">Update your personal information and shipping details.</p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-12 border border-slate-100 dark:border-slate-700 shadow-sm transition-colors duration-200">
                
                <?php if($error_message): ?>
                    <div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-6 py-4 rounded-xl mb-8 text-sm font-bold border border-red-200 dark:border-red-500/20 flex items-center gap-3">
                        <span class="material-symbols-outlined">error</span>
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if($success_message): ?>
                    <div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-6 py-4 rounded-xl mb-8 text-sm font-bold border border-green-200 dark:border-green-500/20 flex items-center gap-3">
                        <span class="material-symbols-outlined">check_circle</span>
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" class="space-y-10">
                    <?php $csrf->echoInputField(); ?>
                    
                    <div>
                        <h2 class="text-lg font-bold font-headline text-surfaceDark dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">Personal Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400">Full Name *</label>
                                <input type="text" name="cust_name" value="<?php echo htmlspecialchars($customer['cust_name']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-surfaceDark dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 text-sm outline-none focus:border-primary dark:focus:border-indigo-500 transition-colors" required>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400">Email Address</label>
                                <input type="email" value="<?php echo htmlspecialchars($customer['cust_email']); ?>" class="w-full bg-slate-100 dark:bg-slate-800 text-textMuted dark:text-slate-500 border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 text-sm cursor-not-allowed" disabled>
                                <p class="text-[10px] text-textMuted mt-1">Contact support to change email.</p>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400">Phone Number *</label>
                                <input type="text" name="cust_phone" value="<?php echo htmlspecialchars($customer['cust_phone']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-surfaceDark dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 text-sm outline-none focus:border-primary dark:focus:border-indigo-500 transition-colors" required>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-lg font-bold font-headline text-surfaceDark dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">Shipping Details</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400">Street Address</label>
                                <input type="text" name="cust_address" value="<?php echo htmlspecialchars($customer['cust_address']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-surfaceDark dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 text-sm outline-none focus:border-primary transition-colors">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400">City</label>
                                <input type="text" name="cust_city" value="<?php echo htmlspecialchars($customer['cust_city']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-surfaceDark dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 text-sm outline-none focus:border-primary transition-colors">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400">State / Province</label>
                                <input type="text" name="cust_state" value="<?php echo htmlspecialchars($customer['cust_state']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-surfaceDark dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 text-sm outline-none focus:border-primary transition-colors">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400">Zip / Postal Code</label>
                                <input type="text" name="cust_zip" value="<?php echo htmlspecialchars($customer['cust_zip']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-surfaceDark dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 text-sm outline-none focus:border-primary transition-colors">
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400">Country</label>
                                <select name="cust_country" class="w-full bg-slate-50 dark:bg-slate-900 text-surfaceDark dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 text-sm outline-none focus:border-primary transition-colors">
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
                        <h2 class="text-lg font-bold font-headline text-surfaceDark dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-2">Security</h2>
                        <div class="space-y-2 max-w-md">
                            <label class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400">Update Password</label>
                            <input type="password" name="new_password" placeholder="Leave blank to keep current" class="w-full bg-slate-50 dark:bg-slate-900 text-surfaceDark dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl py-3 px-4 text-sm outline-none focus:border-primary transition-colors">
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-100 dark:border-slate-700 mt-8 flex justify-end">
                        <button type="submit" name="update_profile" class="w-full sm:w-auto px-10 py-4 bg-primary hover:bg-primaryHover text-white rounded-xl font-bold uppercase tracking-widest text-sm shadow-lg shadow-primary/30 hover:-translate-y-0.5 transition-all">
                            Save Changes
                        </button>
                    </div>

                </form>
            </div>
            
        </section>
    </main>
</div>

<?php require_once('footer.php'); ?>