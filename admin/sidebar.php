<?php 
// Get the current page name to automatically apply the blue "Active" highlight
$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
?>

<aside class="fixed left-0 top-0 h-full w-64 z-50 bg-[#faf8ff] flex flex-col p-4 border-r border-slate-200/60 font-['Inter'] text-sm font-medium tracking-wide">
    
    <div class="flex items-center space-x-3 mb-10 mt-2 px-2">
        <div class="w-12 h-12 bg-[#0052CC] rounded-xl flex items-center justify-center text-white shadow-md shadow-[#0052CC]/20 flex-shrink-0">
            <span class="material-symbols-outlined text-2xl" style="font-variation-settings: 'FILL' 1;">view_in_ar</span>
        </div>
        <div class="flex flex-col">
            <span class="font-['Manrope'] text-lg leading-tight font-extrabold text-[#131b2e]">Curator Tech</span>
            <span class="text-[9px] leading-tight text-slate-500 uppercase tracking-widest font-bold mt-1">Digital Curator<br>Admin</span>
        </div>
    </div>

    <nav class="flex-1 space-y-1.5 px-2">
        
        <a href="index.php" class="flex items-center space-x-4 px-4 py-3.5 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'index.php') ? 'bg-[#0052CC] text-white shadow-lg shadow-[#0052CC]/30' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'; ?>">
            <span class="material-symbols-outlined text-[22px]">grid_view</span>
            <span class="font-semibold">Overview</span>
        </a>

        <a href="settings.php" class="flex items-center space-x-4 px-4 py-3.5 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'settings.php') ? 'bg-[#0052CC] text-white shadow-lg shadow-[#0052CC]/30' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'; ?>">
            <span class="material-symbols-outlined text-[22px]">settings</span>
            <span class="font-semibold">Settings</span>
        </a>

        <a href="product.php" class="flex items-center space-x-4 px-4 py-3.5 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'product.php' || $cur_page == 'product-add.php' || $cur_page == 'product-edit.php') ? 'bg-[#0052CC] text-white shadow-lg shadow-[#0052CC]/30' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'; ?>">
            <span class="material-symbols-outlined text-[22px]">inventory_2</span>
            <span class="font-semibold">Products</span>
        </a>

        <a href="order.php" class="flex items-center space-x-4 px-4 py-3.5 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'order.php') ? 'bg-[#0052CC] text-white shadow-lg shadow-[#0052CC]/30' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'; ?>">
            <span class="material-symbols-outlined text-[22px]">payments</span>
            <span class="font-semibold">Order Management</span>
        </a>

        <a href="customer.php" class="flex items-center space-x-4 px-4 py-3.5 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'customer.php') ? 'bg-[#0052CC] text-white shadow-lg shadow-[#0052CC]/30' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'; ?>">
            <span class="material-symbols-outlined text-[22px]">group</span>
            <span class="font-semibold">Customers</span>
        </a>

        <a href="top-category.php" class="flex items-center space-x-4 px-4 py-3.5 rounded-xl transition-all duration-200 <?php echo ($cur_page == 'top-category.php' || $cur_page == 'mid-category.php' || $cur_page == 'end-category.php') ? 'bg-[#0052CC] text-white shadow-lg shadow-[#0052CC]/30' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-100'; ?>">
            <span class="material-symbols-outlined text-[22px]">category</span>
            <span class="font-semibold">Categories</span>
        </a>

    </nav>

    <div class="px-4 pb-6">
        <div class="w-full h-[1px] bg-slate-200/60 mb-6"></div>
        <a href="logout.php" class="flex items-center justify-center space-x-2 px-4 py-3.5 bg-red-50/80 text-red-600 rounded-xl font-bold hover:bg-red-100 transition-colors">
            <span class="material-symbols-outlined text-[20px]">logout</span>
            <span>Logout</span>
        </a>
    </div>
</aside>