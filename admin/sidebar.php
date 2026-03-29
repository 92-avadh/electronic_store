<?php
$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
?>

<aside class="fixed inset-y-0 left-0 bg-white dark:bg-slate-800 shadow-[10px_0_40px_rgba(19,27,46,0.04)] dark:shadow-none w-64 z-50 flex flex-col border-r border-slate-100 dark:border-slate-700 transition-colors duration-200">
    
    <div class="h-20 flex items-center px-8 border-b border-slate-100 dark:border-slate-700 transition-colors duration-200">
        <a href="index.php" class="flex items-center gap-3 group">
            <div class="w-8 h-8 bg-[#0052CC] rounded-lg flex items-center justify-center text-white font-bold shadow-lg shadow-[#0052CC]/30 group-hover:scale-105 transition-transform">S</div>
            <span class="font-headline font-extrabold text-xl tracking-tight text-slate-900 dark:text-white group-hover:text-[#0052CC] dark:group-hover:text-[#4da3ff] transition-colors">Slate</span>
        </a>
    </div>

    <div class="flex-grow overflow-y-auto py-6 px-4 space-y-1 table-scroll">
        <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 mt-4">Core</p>
        
        <a href="index.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'index.php') ? 'bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 font-semibold'; ?>">
            <span class="material-symbols-outlined text-[20px]">dashboard</span><span>Dashboard</span>
        </a>
        <a href="order.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'order.php') ? 'bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 font-semibold'; ?>">
            <span class="material-symbols-outlined text-[20px]">local_shipping</span><span>Orders</span>
        </a>
        <a href="customer.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'customer.php') ? 'bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 font-semibold'; ?>">
            <span class="material-symbols-outlined text-[20px]">group</span><span>Customers</span>
        </a>

        <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 mt-6">Inventory</p>
        <a href="product.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'product.php' || $cur_page == 'product-add.php' || $cur_page == 'product-edit.php') ? 'bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 font-semibold'; ?>">
            <span class="material-symbols-outlined text-[20px]">inventory_2</span><span>Products</span>
        </a>
        <a href="category.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'category.php') ? 'bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 font-semibold'; ?>">
            <span class="material-symbols-outlined text-[20px]">category</span><span>Categories</span>
        </a>
        <a href="attributes.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'attributes.php') ? 'bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 font-semibold'; ?>">
            <span class="material-symbols-outlined text-[20px]">straighten</span><span>Attributes</span>
        </a>

        <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 mt-6">System</p>
        <a href="settings.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'settings.php') ? 'bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff] font-bold' : 'text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 font-semibold'; ?>">
            <span class="material-symbols-outlined text-[20px]">settings</span><span>Global Settings</span>
        </a>
    </div>
</aside>