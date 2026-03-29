<?php require_once('header.php'); ?>

<?php
// =========================================
// SECURITY CHECK: Kick out unlogged users
// =========================================
if(!isset($_SESSION['customer'])) {
    header('location: '.BASE_URL.'login.php');
    exit;
}

// Fetch user data from session
$cust_name = $_SESSION['customer']['cust_name'];
$cust_email = $_SESSION['customer']['cust_email'];
$first_name = explode(' ', trim($cust_name))[0];

// Fetch all orders for this customer
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_email=? ORDER BY id DESC");
$statement->execute(array($cust_email));
$orders = $statement->fetchAll(PDO::FETCH_ASSOC);
$total_orders = $statement->rowCount();

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
        
        <section class="p-8 md:p-12 max-w-7xl mx-auto w-full" data-aos="fade-in" data-aos-duration="800">
            
            <div class="mb-12">
                <span class="text-xs font-bold text-primary dark:text-indigo-400 tracking-[0.2em] uppercase mb-2 block">Acquisitions</span>
                <h1 class="text-4xl md:text-5xl font-headline font-black text-surfaceDark dark:text-white tracking-tight">Order History</h1>
                <p class="text-textMuted dark:text-slate-400 mt-3 max-w-lg font-medium">Review your recent transactions and monitor active shipments.</p>
            </div>

            <?php if($total_orders == 0): ?>
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-16 text-center border border-slate-100 dark:border-slate-700 shadow-sm">
                    <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-4 block">receipt_long</span>
                    <h3 class="font-headline font-bold text-2xl text-surfaceDark dark:text-white mb-2">No orders found</h3>
                    <p class="text-textMuted dark:text-slate-400 text-base mb-8 max-w-md mx-auto">You haven't made any purchases yet. Your curated hardware journey starts here.</p>
                    <a href="product-category.php" class="bg-primary text-white px-8 py-3 rounded-full font-bold text-sm tracking-wider uppercase inline-block hover:bg-primaryHover transition-colors shadow-lg">Explore Shop</a>
                </div>
            <?php else: ?>
                <div class="space-y-6">
                    <?php
                    foreach($orders as $order) {
                        $stmt_items = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
                        $stmt_items->execute(array($order['payment_id']));
                        $items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);
                        $item_count = count($items);
                        
                        if($item_count > 0) {
                            $first_item = $items[0];
                            
                            $stmt_img = $pdo->prepare("SELECT p_featured_photo FROM tbl_product WHERE p_name=?");
                            $stmt_img->execute(array($first_item['product_name']));
                            $img_row = $stmt_img->fetch(PDO::FETCH_ASSOC);
                            $product_image = $img_row ? 'assets/uploads/'.$img_row['p_featured_photo'] : 'https://placehold.co/400?text=No+Image';

                            if($order['shipping_status'] == 'Completed') {
                                $badge_class = 'bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 border-teal-200 dark:border-teal-500/20';
                                $badge_text = 'Shipped';
                            } elseif($order['payment_status'] == 'Completed') {
                                $badge_class = 'bg-indigo-50 dark:bg-indigo-500/10 text-primary dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/20';
                                $badge_text = 'Processing';
                            } else {
                                $badge_class = 'bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border-orange-200 dark:border-orange-500/20';
                                $badge_text = 'Pending Payment';
                            }
                            ?>
                            
                            <div class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm transition-all hover:shadow-lg dark:hover:shadow-none dark:hover:border-indigo-500/30 group">
                                <div class="flex flex-col md:flex-row gap-6 items-center md:items-start">
                                    <div class="w-32 h-32 bg-slate-50 dark:bg-slate-900 rounded-2xl overflow-hidden flex-shrink-0 p-3 flex items-center justify-center border border-slate-100 dark:border-slate-800">
                                        <img alt="<?php echo htmlspecialchars($first_item['product_name']); ?>" class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal group-hover:scale-105 transition-transform duration-500" src="<?php echo $product_image; ?>"/>
                                    </div>
                                    <div class="flex-grow w-full flex flex-col justify-between">
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-3">
                                            <div class="flex flex-wrap items-center gap-3">
                                                <span class="px-3 py-1 rounded-full border <?php echo $badge_class; ?> text-[10px] font-black uppercase tracking-[0.1em]"><?php echo $badge_text; ?></span>
                                                <span class="text-xs text-textMuted dark:text-slate-400 font-bold uppercase tracking-widest">Order #<?php echo $order['payment_id']; ?></span>
                                            </div>
                                            <p class="text-2xl font-black font-headline text-surfaceDark dark:text-white">₹<?php echo number_format($order['paid_amount']); ?></p>
                                        </div>
                                        
                                        <h4 class="text-xl font-bold font-headline text-surfaceDark dark:text-white line-clamp-1 mb-1">
                                            <?php echo htmlspecialchars($first_item['product_name']); ?>
                                        </h4>
                                        
                                        <p class="text-sm text-textMuted dark:text-slate-400 font-medium mb-6">
                                            Placed on <?php echo date('F d, Y', strtotime($order['payment_date'])); ?>
                                            <?php if($item_count > 1) echo '<span class="font-bold text-primary dark:text-indigo-400 ml-1">+' . ($item_count - 1) . ' items</span>'; ?>
                                        </p>
                                        
                                        <div class="flex flex-wrap gap-3">
                                            <a href="javascript:void(0);" class="text-xs font-bold uppercase tracking-widest text-surfaceDark dark:text-white hover:text-primary dark:hover:text-indigo-400 transition-colors flex items-center gap-2 bg-slate-50 dark:bg-slate-900 px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-primary/30">
                                                <span class="material-symbols-outlined text-[18px]">receipt_long</span> Invoice
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <?php
                        }
                    }
                    ?>
                </div>
            <?php endif; ?>
        </section>
    </main>
</div>

<?php require_once('footer.php'); ?>