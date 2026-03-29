<?php
$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);

// Define reusable Tailwind CSS classes for active and inactive sidebar links
$active_class = "bg-sky-500/10 dark:bg-sky-500/20 text-sky-600 dark:text-sky-400 font-bold shadow-sm border border-sky-100 dark:border-sky-500/10";
$inactive_class = "text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-700/50 font-semibold border border-transparent";
?>

<aside class="fixed inset-y-0 left-0 bg-white dark:bg-slate-800 shadow-[10px_0_40px_rgba(19,27,46,0.04)] dark:shadow-none w-64 z-50 flex flex-col border-r border-slate-100 dark:border-slate-700 transition-colors duration-200">
    
    <div class="h-20 flex items-center px-8 border-b border-slate-100 dark:border-slate-700 transition-colors duration-200">
        <a href="index.php" class="flex items-center gap-3 group">
            <div class="w-8 h-8 bg-sky-500 rounded-lg flex items-center justify-center text-white font-bold shadow-lg shadow-sky-500/30 group-hover:scale-105 transition-transform">ES</div>
            <span class="font-headline font-extrabold text-[16px] tracking-tight text-slate-900 dark:text-white group-hover:text-sky-500 dark:group-hover:text-sky-400 transition-colors">Electronic Store</span>
        </a>
    </div>

    <div class="flex-grow overflow-y-auto py-6 px-4 space-y-1 table-scroll">
        
        <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2 mt-2">Core</p>
        
        <a href="index.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'index.php') ? $active_class : $inactive_class; ?>">
            <span class="material-symbols-outlined text-[20px]">dashboard</span><span>Dashboard</span>
        </a>
        <a href="order.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'order.php') ? $active_class : $inactive_class; ?>">
            <span class="material-symbols-outlined text-[20px]">local_shipping</span><span>Orders</span>
        </a>
        <a href="customer.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'customer.php') ? $active_class : $inactive_class; ?>">
            <span class="material-symbols-outlined text-[20px]">group</span><span>Customers</span>
        </a>
        
        <a href="contact-messages.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'contact-messages.php') ? $active_class : $inactive_class; ?>">
            <span class="material-symbols-outlined text-[20px]">mail</span><span>Inbox</span>
        </a>

        <div class="my-4 border-t border-slate-100 dark:border-slate-700/50"></div>
        <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">Inventory</p>
        
        <a href="product.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'product.php' || $cur_page == 'product-add.php' || $cur_page == 'product-edit.php') ? $active_class : $inactive_class; ?>">
            <span class="material-symbols-outlined text-[20px]">inventory_2</span><span>Products</span>
        </a>
        <a href="category.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'category.php') ? $active_class : $inactive_class; ?>">
            <span class="material-symbols-outlined text-[20px]">category</span><span>Categories</span>
        </a>
        <a href="attributes.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'attributes.php') ? $active_class : $inactive_class; ?>">
            <span class="material-symbols-outlined text-[20px]">straighten</span><span>Attributes</span>
        </a>
        
        <a href="review.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'review.php') ? $active_class : $inactive_class; ?>">
            <span class="material-symbols-outlined text-[20px]">reviews</span><span>Product Reviews</span>
        </a>

        <div class="my-4 border-t border-slate-100 dark:border-slate-700/50"></div>
        <p class="px-4 text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-2">System</p>
        
        <a href="settings.php" class="flex items-center space-x-4 px-4 py-3 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'settings.php') ? $active_class : $inactive_class; ?>">
            <span class="material-symbols-outlined text-[20px]">settings</span><span>Global Settings</span>
        </a>
        
    </div>
</aside>