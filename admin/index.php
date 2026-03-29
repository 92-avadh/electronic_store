<?php require_once('header.php'); ?>

<?php
// Fetch Metrics
$total_product = 0; $total_customer = 0; $total_shipping_completed = 0; $total_shipping_pending = 0;
$total_order_completed = 0; $total_order_pending = 0; $available_amount = 0;

$statement = $pdo->prepare("SELECT * FROM tbl_product"); $statement->execute(); $total_product = $statement->rowCount();
$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_status='1'"); $statement->execute(); $total_customer = $statement->rowCount();
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE shipping_status=?"); $statement->execute(array('Completed')); $total_shipping_completed = $statement->rowCount();
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE shipping_status=?"); $statement->execute(array('Pending')); $total_shipping_pending = $statement->rowCount();
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?"); $statement->execute(array('Completed')); $total_order_completed = $statement->rowCount();
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?"); $statement->execute(array('Pending')); $total_order_pending = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?"); $statement->execute(array('Completed'));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) { $available_amount += $row['paid_amount']; }
?>

<main class="p-6 md:p-8 space-y-8 flex-grow max-w-[1600px] mx-auto w-full">
    
    <div class="mb-2 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <span class="text-slate-500 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal</span>
            <h1 class="font-headline text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Dashboard Overview</h1>
        </div>
        <a href="download-dashboard-report.php" class="flex-shrink-0 px-4 py-2.5 bg-[#0052CC] hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center gap-2 border border-transparent">
            <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
            <span class="hidden md:inline uppercase tracking-widest text-[10px]">Download Report</span>
        </a>
    </div>
    <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 transition-colors duration-200">
            <div class="p-3 bg-[#0052CC]/10 dark:bg-[#0052CC]/20 rounded-lg text-[#0052CC] dark:text-[#4da3ff] w-max mb-4"><span class="material-symbols-outlined">payments</span></div>
            <h3 class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Total Revenue</h3>
            <p class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">₹<?php echo number_format($available_amount, 2); ?></p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 transition-colors duration-200">
            <div class="p-3 bg-slate-100 dark:bg-slate-700 rounded-lg text-slate-600 dark:text-slate-300 w-max mb-4"><span class="material-symbols-outlined">inventory_2</span></div>
            <h3 class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Total Products</h3>
            <p class="text-2xl font-black tracking-tight text-slate-900 dark:text-white"><?php echo $total_product; ?></p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 transition-colors duration-200">
            <div class="p-3 bg-red-50 dark:bg-red-500/10 rounded-lg text-red-600 dark:text-red-400 w-max mb-4"><span class="material-symbols-outlined">pending_actions</span></div>
            <h3 class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Pending Orders</h3>
            <p class="text-2xl font-black tracking-tight text-slate-900 dark:text-white"><?php echo $total_order_pending; ?></p>
        </div>
        <div class="bg-white dark:bg-slate-800 p-6 rounded-xl shadow-sm border border-slate-100 dark:border-slate-700 transition-colors duration-200">
            <div class="p-3 bg-teal-50 dark:bg-teal-500/10 rounded-lg text-teal-600 dark:text-teal-400 w-max mb-4"><span class="material-symbols-outlined">group</span></div>
            <h3 class="text-slate-500 dark:text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Total Customers</h3>
            <p class="text-2xl font-black tracking-tight text-slate-900 dark:text-white"><?php echo $total_customer; ?></p>
        </div>
    </section>

    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 transition-colors duration-200">
        <div class="border-b border-slate-100 dark:border-slate-700 pb-4 mb-4 flex justify-between items-center">
            <h2 class="text-lg font-black tracking-tight text-slate-900 dark:text-white">Order Fulfillment Status</h2>
            <a href="order.php" class="text-xs font-bold text-[#0052CC] dark:text-[#4da3ff] flex items-center gap-1 hover:underline">View All Orders <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
        </div>
        <div class="grid grid-cols-2 gap-6 mt-6">
            <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-700 flex flex-col items-center text-center transition-colors">
                <span class="material-symbols-outlined text-4xl text-orange-500 mb-2">local_shipping</span>
                <h4 class="text-3xl font-black text-slate-900 dark:text-white"><?php echo $total_shipping_pending; ?></h4>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-1">Pending Shipment</p>
            </div>
            <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-6 border border-slate-200 dark:border-slate-700 flex flex-col items-center text-center transition-colors">
                <span class="material-symbols-outlined text-4xl text-green-500 mb-2">check_circle</span>
                <h4 class="text-3xl font-black text-slate-900 dark:text-white"><?php echo $total_shipping_completed; ?></h4>
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-1">Completed Shipments</p>
            </div>
        </div>
    </div>
</main>

<?php require_once('footer.php'); ?>