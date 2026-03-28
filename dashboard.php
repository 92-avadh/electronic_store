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
$first_name = explode(' ', trim($cust_name))[0]; // Get just the first name for the greeting

// Fetch Total Orders for this customer
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_email=?");
$statement->execute(array($cust_email));
$total_orders = $statement->rowCount();

// Fetch Recent Orders (Limit to 3)
$statement_recent = $pdo->prepare("SELECT * FROM tbl_payment WHERE customer_email=? ORDER BY id DESC LIMIT 3");
$statement_recent->execute(array($cust_email));
$recent_orders = $statement_recent->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="flex min-h-screen pt-20 bg-surface dark:bg-slate-900 transition-colors duration-300">
    
    <aside class="h-[calc(100vh-80px)] w-64 fixed left-0 top-[80px] bg-slate-50 dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col p-4 gap-2 z-40 overflow-y-auto transition-colors duration-300">
        
        <div class="mb-8 px-2 flex items-center gap-3 pt-4">
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary to-indigo-500 flex items-center justify-center text-white font-headline font-black text-xl shadow-md">
                <?php echo strtoupper(substr($first_name, 0, 1)); ?>
            </div>
            <div>
                <h1 class="font-headline font-black text-sm text-surfaceDark dark:text-white truncate max-w-[140px]"><?php echo $cust_name; ?></h1>
                <p class="text-[10px] uppercase tracking-widest text-primary dark:text-indigo-400 font-bold">Verified Member</p>
            </div>
        </div>

        <nav class="flex-grow space-y-2">
            <a class="flex items-center gap-3 px-4 py-3 bg-white dark:bg-slate-800 text-primary dark:text-indigo-400 rounded-xl shadow-sm font-bold border border-slate-100 dark:border-slate-700 transition-all duration-200" href="dashboard.php">
                <span class="material-symbols-outlined">dashboard</span>
                <span class="font-body text-sm font-semibold">Overview</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-indigo-400 hover:bg-white dark:hover:bg-slate-800 rounded-xl transition-all duration-200" href="customer-order.php">
                <span class="material-symbols-outlined">package_2</span>
                <span class="font-body text-sm font-semibold">My Orders</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-indigo-400 hover:bg-white dark:hover:bg-slate-800 rounded-xl transition-all duration-200" href="customer-profile-update.php">
                <span class="material-symbols-outlined">manage_accounts</span>
                <span class="font-body text-sm font-semibold">Profile Settings</span>
            </a>
            <a class="flex items-center gap-3 px-4 py-3 text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-indigo-400 hover:bg-white dark:hover:bg-slate-800 rounded-xl transition-all duration-200" href="customer-password-update.php">
                <span class="material-symbols-outlined">lock_reset</span>
                <span class="font-body text-sm font-semibold">Security</span>
            </a>
        </nav>

        <div class="mt-auto pt-4 space-y-4">
            <button class="w-full py-3 px-4 bg-gradient-to-br from-surfaceDark to-slate-700 dark:from-slate-700 dark:to-slate-600 text-white text-xs font-bold rounded-xl tracking-wider uppercase shadow-lg hover:scale-[1.02] transition-transform">
                Upgrade to Pro
            </button>
            <div class="flex flex-col gap-1 border-t border-slate-200 dark:border-slate-700 pt-4">
                <a class="flex items-center gap-3 px-4 py-2 text-textMuted dark:text-slate-400 hover:text-red-500 transition-colors text-sm font-bold" href="logout.php">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Logout
                </a>
            </div>
        </div>
    </aside>

    <main class="ml-64 flex-grow flex flex-col min-h-[calc(100vh-80px)]">
        
        <section class="p-8 md:p-12 max-w-7xl mx-auto w-full" data-aos="fade-in" data-aos-duration="800">
            
            <div class="mb-12">
                <span class="text-xs font-bold text-primary dark:text-indigo-400 tracking-[0.2em] uppercase mb-2 block">System Pulse / Overview</span>
                <h1 class="text-4xl md:text-5xl font-headline font-black text-surfaceDark dark:text-white tracking-tight">
                    Welcome back, <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-indigo-400"><?php echo $first_name; ?>.</span>
                </h1>
                <p class="text-textMuted dark:text-slate-400 mt-3 max-w-lg font-medium">Your curated ecosystem is performing at optimal levels. Review your recent acquisitions below.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 relative overflow-hidden group hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] dark:hover:shadow-[0_20px_40px_-15px_rgba(79,70,229,0.15)] border border-slate-100 dark:border-slate-700 transition-all duration-300">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-textMuted dark:text-slate-400 uppercase tracking-widest mb-2">Total Orders</p>
                        <h3 class="text-4xl font-headline font-black text-surfaceDark dark:text-white"><?php echo $total_orders; ?></h3>
                        <div class="mt-4 flex items-center gap-2 text-xs font-bold text-primary dark:text-indigo-400">
                            <span class="material-symbols-outlined text-sm">trending_up</span>
                            <span>Active Account</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined absolute -bottom-6 -right-6 text-9xl text-slate-100 dark:text-slate-700/30 group-hover:text-primary/10 dark:group-hover:text-indigo-500/20 transition-colors duration-500">shopping_bag</span>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 relative overflow-hidden group hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] dark:hover:shadow-[0_20px_40px_-15px_rgba(79,70,229,0.15)] border border-slate-100 dark:border-slate-700 transition-all duration-300">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-textMuted dark:text-slate-400 uppercase tracking-widest mb-2">Active Warranties</p>
                        <h3 class="text-4xl font-headline font-black text-surfaceDark dark:text-white"><?php echo $total_orders; ?></h3>
                        <div class="mt-4 flex items-center gap-2 text-xs font-bold text-green-500">
                            <span class="material-symbols-outlined text-sm">verified</span>
                            <span>All secure</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined absolute -bottom-6 -right-6 text-9xl text-slate-100 dark:text-slate-700/30 group-hover:text-green-500/10 transition-colors duration-500">shield_with_heart</span>
                </div>

                <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 relative overflow-hidden group hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] dark:hover:shadow-[0_20px_40px_-15px_rgba(79,70,229,0.15)] border border-slate-100 dark:border-slate-700 transition-all duration-300">
                    <div class="relative z-10">
                        <p class="text-xs font-bold text-textMuted dark:text-slate-400 uppercase tracking-widest mb-2">Reward Points</p>
                        <h3 class="text-4xl font-headline font-black text-surfaceDark dark:text-white"><?php echo number_format($total_orders * 150); ?></h3>
                        <div class="mt-4 flex items-center gap-2 text-xs font-bold text-orange-500">
                            <span class="material-symbols-outlined text-sm">stars</span>
                            <span>Level: Elite Curator</span>
                        </div>
                    </div>
                    <span class="material-symbols-outlined absolute -bottom-6 -right-6 text-9xl text-slate-100 dark:text-slate-700/30 group-hover:text-orange-500/10 transition-colors duration-500">card_membership</span>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-12">
                
                <div class="xl:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-headline font-black tracking-tight text-surfaceDark dark:text-white">Recent Acquisitions</h2>
                        <a class="text-xs font-bold text-primary dark:text-indigo-400 hover:underline underline-offset-4 uppercase tracking-widest transition-all" href="customer-order.php">View Archives</a>
                    </div>
                    
                    <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-700 shadow-sm">
                        <?php if($total_orders == 0): ?>
                            <div class="p-12 text-center">
                                <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-4 block">receipt_long</span>
                                <h3 class="font-headline font-bold text-lg text-surfaceDark dark:text-white mb-2">No orders found</h3>
                                <p class="text-textMuted dark:text-slate-400 text-sm mb-6">You haven't made any purchases yet.</p>
                                <a href="product-category.php" class="bg-primary text-white px-6 py-3 rounded-full font-bold text-sm tracking-wider uppercase inline-block hover:bg-primaryHover transition-colors">Start Exploring</a>
                            </div>
                        <?php else: ?>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-100 dark:border-slate-700">
                                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-textMuted dark:text-slate-400">Transaction ID</th>
                                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-textMuted dark:text-slate-400">Status</th>
                                            <th class="px-6 py-5 text-[10px] font-black uppercase tracking-[0.2em] text-textMuted dark:text-slate-400 text-right">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                        <?php foreach ($recent_orders as $row): ?>
                                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors group">
                                            <td class="px-6 py-6">
                                                <div class="flex items-center gap-4">
                                                    <div class="w-12 h-12 bg-surface dark:bg-slate-900 rounded-xl flex items-center justify-center text-primary dark:text-indigo-400">
                                                        <span class="material-symbols-outlined">inventory_2</span>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-surfaceDark dark:text-white uppercase tracking-wider">#<?php echo $row['payment_id']; ?></p>
                                                        <p class="text-xs text-textMuted dark:text-slate-500 font-medium mt-0.5"><?php echo date('M d, Y', strtotime($row['payment_date'])); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-6">
                                                <?php if($row['payment_status'] == 'Pending'): ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-yellow-100 text-yellow-700 dark:bg-yellow-500/10 dark:text-yellow-400">Pending</span>
                                                <?php else: ?>
                                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400">Completed</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-6 text-right font-headline font-black text-base text-surfaceDark dark:text-white">
                                                ₹<?php echo number_format($row['paid_amount'], 2); ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-headline font-black tracking-tight text-surfaceDark dark:text-white mb-6">Exclusive Drops</h2>
                    <div class="space-y-6">
                        <div class="group cursor-pointer relative rounded-3xl overflow-hidden shadow-lg border border-slate-100 dark:border-slate-700" onclick="window.location.href='product-category.php';">
                            <div class="absolute inset-0 bg-surfaceDark dark:bg-black z-0"></div>
                            <img class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-overlay transition-transform duration-1000 group-hover:scale-110" src="https://lh3.googleusercontent.com/aida-public/AB6AXuALsPAfCynCHbAqjDDcdMuhkye8NNn2Bp-CSnUToq-kKShYhqWl_alL0Q2X8E7SZdFhYB3s82iDduvq5h_46a2t3T8LLywlIc2WAVctSbRR32MMWQpGIbwSun0eN0ilkAgPVZnhz0SjCcFJ1WBzkza2KFOxl5xRyna9tXpGkup3WPQCliuMR1wQy3wKOLpfxdah_yAcUvKtRz33jcUtXqbPuQE4nJ22eUlaeufpXVokPSZHHpwgOFMWa50mlHxtUCzLO83yP_ksIU7W" alt="Exclusive Promo"/>
                            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
                            
                            <div class="relative z-10 p-8 h-[350px] flex flex-col justify-end">
                                <span class="bg-white text-surfaceDark px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest w-max mb-4 shadow-md">VIP Access</span>
                                <h4 class="font-headline text-3xl font-black text-white mb-2 leading-tight">Titan V4<br>Workstation</h4>
                                <p class="text-slate-300 text-sm font-medium mb-6 line-clamp-2">Early access available for Elite Curators.</p>
                                
                                <button class="w-full py-3 bg-primary hover:bg-primaryHover text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-colors shadow-lg">
                                    Unlock Now
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>
    </main>
</div>

<?php require_once('footer.php'); ?>