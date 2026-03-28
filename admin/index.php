<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();

// Security Check: If not logged in, redirect to login page
if(!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}

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

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Curator Tech Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@700;800&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; background-color: #faf8ff; color: #131b2e; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
    </style>
</head>
<body class="antialiased">

    <?php require_once('sidebar.php'); ?>

    <div class="lg:ml-64 min-h-screen flex flex-col">
        
        <header class="w-full sticky top-0 z-40 bg-[#faf8ff]/70 backdrop-blur-2xl flex items-center justify-between px-8 h-20 shadow-[0px_20px_40px_rgba(19,27,46,0.06)]">
            <div class="flex items-center space-x-4">
                <h1 class="font-bold tracking-tight text-xl">Dashboard Overview</h1>
            </div>
            <div class="flex items-center space-x-6">
                <div class="flex items-center space-x-2">
                    <a href="../index.php" target="_blank" class="p-2 text-[#0052CC] bg-[#0052CC]/10 hover:bg-[#0052CC]/20 rounded-full transition-all flex items-center gap-2 px-4">
                        <span class="material-symbols-outlined text-sm">storefront</span>
                        <span class="text-xs font-bold uppercase tracking-widest">View Store</span>
                    </a>
                </div>
                <div class="flex items-center space-x-3 pl-4 border-l border-slate-200">
                    <div class="text-right">
                        <p class="text-sm font-bold"><?php echo $_SESSION['user']['full_name']; ?></p>
                        <p class="text-[10px] text-slate-500 uppercase font-bold tracking-tighter">System Manager</p>
                    </div>
                    <?php if($_SESSION['user']['photo'] == ''): ?>
                        <div class="w-10 h-10 rounded-full bg-[#0052CC] text-white flex items-center justify-center font-bold">
                            <?php echo substr($_SESSION['user']['full_name'], 0, 1); ?>
                        </div>
                    <?php else: ?>
                        <img src="../assets/uploads/<?php echo $_SESSION['user']['photo']; ?>" alt="Admin Profile" class="w-10 h-10 rounded-full object-cover ring-2 ring-[#0052CC]/20"/>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main class="p-8 space-y-8 flex-grow">
            <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                    <div class="p-3 bg-[#0052CC]/10 rounded-lg text-[#0052CC] w-max mb-4"><span class="material-symbols-outlined">payments</span></div>
                    <h3 class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Revenue</h3>
                    <p class="text-2xl font-black tracking-tight">₹<?php echo number_format($available_amount, 2); ?></p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                    <div class="p-3 bg-slate-100 rounded-lg text-slate-600 w-max mb-4"><span class="material-symbols-outlined">inventory_2</span></div>
                    <h3 class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Products</h3>
                    <p class="text-2xl font-black tracking-tight"><?php echo $total_product; ?></p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                    <div class="p-3 bg-red-50 rounded-lg text-red-600 w-max mb-4"><span class="material-symbols-outlined">pending_actions</span></div>
                    <h3 class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Pending Orders</h3>
                    <p class="text-2xl font-black tracking-tight"><?php echo $total_order_pending; ?></p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-slate-100">
                    <div class="p-3 bg-teal-50 rounded-lg text-teal-600 w-max mb-4"><span class="material-symbols-outlined">group</span></div>
                    <h3 class="text-slate-500 text-xs font-bold uppercase tracking-widest mb-1">Total Customers</h3>
                    <p class="text-2xl font-black tracking-tight"><?php echo $total_customer; ?></p>
                </div>
            </section>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
                <div class="border-b border-slate-100 pb-4 mb-4 flex justify-between items-center">
                    <h2 class="text-lg font-black tracking-tight">Order Fulfillment Status</h2>
                    <a href="order.php" class="text-xs font-bold text-[#0052CC] flex items-center gap-1 hover:underline">View All Orders <span class="material-symbols-outlined text-sm">arrow_forward</span></a>
                </div>
                <div class="grid grid-cols-2 gap-6 mt-6">
                    <div class="bg-slate-50 rounded-xl p-6 border border-slate-200 flex flex-col items-center text-center">
                        <span class="material-symbols-outlined text-4xl text-orange-500 mb-2">local_shipping</span>
                        <h4 class="text-3xl font-black"><?php echo $total_shipping_pending; ?></h4>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mt-1">Pending Shipment</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-6 border border-slate-200 flex flex-col items-center text-center">
                        <span class="material-symbols-outlined text-4xl text-green-500 mb-2">check_circle</span>
                        <h4 class="text-3xl font-black"><?php echo $total_shipping_completed; ?></h4>
                        <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mt-1">Completed Shipments</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>