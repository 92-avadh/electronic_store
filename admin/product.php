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
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Inventory Management | Silicon Slate Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "surface": "#faf8ff",
              "primary-container": "#0051ce",
              "background": "#faf8ff",
              "surface-container-lowest": "#ffffff",
              "secondary-fixed-dim": "#b9c7df",
              "surface-container-high": "#e2e7ff",
              "inverse-surface": "#283044",
              "primary-fixed-dim": "#b3c5ff",
              "on-primary-fixed-variant": "#003fa4",
              "primary-fixed": "#dae1ff",
              "on-tertiary-fixed-variant": "#004f58",
              "on-tertiary": "#ffffff",
              "on-tertiary-container": "#33e6ff",
              "on-surface-variant": "#434654",
              "error-container": "#ffdad6",
              "outline-variant": "#c3c6d6",
              "on-primary-fixed": "#001849",
              "surface-tint": "#0054d6",
              "tertiary-container": "#006470",
              "error": "#ba1a1a",
              "on-surface": "#131b2e",
              "on-secondary-container": "#57657a",
              "surface-dim": "#d2d9f4",
              "secondary-fixed": "#d5e3fc",
              "on-background": "#131b2e",
              "on-error-container": "#93000a",
              "tertiary-fixed": "#9cf0ff",
              "surface-container-highest": "#dae2fd",
              "on-primary-container": "#c5d2ff",
              "inverse-on-surface": "#eef0ff",
              "surface-container-low": "#f2f3ff",
              "surface-container": "#eaedff",
              "secondary-container": "#d5e3fc",
              "primary": "#003c9d",
              "on-secondary": "#ffffff",
              "on-secondary-fixed": "#0d1c2e",
              "surface-variant": "#dae2fd",
              "outline": "#737685",
              "on-error": "#ffffff",
              "inverse-primary": "#b3c5ff",
              "on-secondary-fixed-variant": "#3a485b",
              "on-primary": "#ffffff",
              "tertiary": "#004a54",
              "tertiary-fixed-dim": "#00daf3",
              "on-tertiary-fixed": "#001f24",
              "surface-bright": "#faf8ff",
              "secondary": "#515f74"
            },
            fontFamily: {
              "headline": ["Manrope"],
              "body": ["Inter"],
              "label": ["Inter"]
            },
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { background-color: #faf8ff; color: #131b2e; font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        
        /* Custom scrollbar for the table */
        .table-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
        .table-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .table-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .table-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</head>
<body class="antialiased selection:bg-primary-fixed-dim">
    
    <?php require_once('sidebar.php'); ?>

    <div class="lg:ml-64 min-h-screen flex flex-col relative">
        
        <header class="w-full sticky top-0 z-40 bg-[#faf8ff]/70 backdrop-blur-2xl flex items-center justify-between px-8 h-20 border-b border-slate-200/50 shadow-sm">
            <h1 class="font-bold tracking-tight text-xl hidden md:block">Inventory Management</h1>
            <div class="flex items-center gap-4 ml-auto">
                <a href="../index.php" target="_blank" class="p-2 text-[#0052CC] bg-[#0052CC]/10 hover:bg-[#0052CC]/20 rounded-full transition-all flex items-center gap-2 px-4" title="View Storefront">
                    <span class="material-symbols-outlined text-sm">storefront</span>
                    <span class="text-xs font-bold uppercase tracking-widest hidden md:inline">View Store</span>
                </a>
                <div class="w-10 h-10 rounded-full bg-[#0052CC] text-white flex items-center justify-center font-bold overflow-hidden shadow-sm" title="<?php echo $_SESSION['user']['full_name']; ?>">
                    <?php if($_SESSION['user']['photo'] == ''): ?>
                        <?php echo substr($_SESSION['user']['full_name'], 0, 1); ?>
                    <?php else: ?>
                        <img src="../assets/uploads/<?php echo $_SESSION['user']['photo']; ?>" alt="Admin Profile" class="w-full h-full object-cover"/>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main class="flex-grow p-6 md:p-8">
            <div class="max-w-[1400px] mx-auto">
                
                <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <span class="text-slate-500 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal > Inventory</span>
                        <h1 class="font-headline text-3xl font-extrabold text-on-surface tracking-tight mt-1">Product Catalog</h1>
                        <p class="text-sm text-slate-500 font-medium mt-2">Manage your inventory, prices, and stock levels.</p>
                    </div>
                    
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <div class="relative flex-grow md:flex-grow-0">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search products..." class="w-full md:w-64 pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0052CC]/20 focus:border-[#0052CC] outline-none transition-all shadow-sm">
                        </div>
                        <a href="product-add.php" class="flex-shrink-0 px-6 py-2.5 rounded-lg bg-[#0052CC] text-white font-headline text-sm font-bold shadow-md hover:bg-blue-700 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            <span class="hidden sm:inline">Add Product</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto table-scroll">
                        <table class="w-full text-left border-collapse" id="productTable">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 w-16">#</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Product Detail</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Price</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 text-center">Stock</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 text-center">Status</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                
                                <?php
                                $i=0;
                                // Fetch all products with their category names using JOINs
                                $statement = $pdo->prepare("SELECT p.*, ec.ecat_name, mc.mcat_name, tc.tcat_name 
                                                            FROM tbl_product p 
                                                            JOIN tbl_end_category ec ON p.ecat_id = ec.ecat_id 
                                                            JOIN tbl_mid_category mc ON ec.mcat_id = mc.mcat_id 
                                                            JOIN tbl_top_category tc ON mc.tcat_id = tc.tcat_id 
                                                            ORDER BY p.p_id DESC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $i++;
                                    ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-6 py-5 text-sm font-bold text-slate-400"><?php echo $i; ?></td>
                                        
                                        <td class="px-6 py-5">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-lg bg-slate-100 border border-slate-200 overflow-hidden flex-shrink-0 p-1 flex items-center justify-center">
                                                    <img src="../assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>" class="w-full h-full object-contain mix-blend-multiply">
                                                </div>
                                                <div>
                                                    <h3 class="text-sm font-bold text-slate-900 line-clamp-1 max-w-[250px]" title="<?php echo $row['p_name']; ?>"><?php echo $row['p_name']; ?></h3>
                                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mt-0.5">
                                                        <?php echo $row['tcat_name']; ?> > <?php echo $row['ecat_name']; ?>
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-5">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-slate-900">₹<?php echo number_format($row['p_current_price']); ?></span>
                                                <?php if($row['p_old_price'] != ''): ?>
                                                    <span class="text-[10px] font-bold text-slate-400 line-through">₹<?php echo number_format($row['p_old_price']); ?></span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-5 text-center">
                                            <?php if($row['p_qty'] == 0): ?>
                                                <span class="inline-flex items-center justify-center px-2.5 py-1 bg-red-100 text-red-700 text-[10px] font-black rounded-md tracking-widest uppercase">Out of Stock</span>
                                            <?php elseif($row['p_qty'] < 5): ?>
                                                <span class="inline-flex items-center justify-center px-2.5 py-1 bg-orange-100 text-orange-700 text-[10px] font-black rounded-md tracking-widest uppercase">Low: <?php echo $row['p_qty']; ?></span>
                                            <?php else: ?>
                                                <span class="text-sm font-bold text-slate-700"><?php echo $row['p_qty']; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="px-6 py-5 text-center">
                                            <div class="flex flex-col items-center gap-1.5">
                                                <?php if($row['p_is_active'] == 1): ?>
                                                    <span class="w-20 inline-flex items-center justify-center px-2 py-0.5 bg-green-50 text-green-600 border border-green-200 text-[9px] font-black rounded tracking-widest uppercase">Active</span>
                                                <?php else: ?>
                                                    <span class="w-20 inline-flex items-center justify-center px-2 py-0.5 bg-slate-100 text-slate-500 border border-slate-200 text-[9px] font-black rounded tracking-widest uppercase">Inactive</span>
                                                <?php endif; ?>
                                                
                                                <?php if($row['p_is_featured'] == 1): ?>
                                                    <span class="w-20 inline-flex items-center justify-center px-2 py-0.5 bg-[#0052CC]/10 text-[#0052CC] border border-[#0052CC]/20 text-[9px] font-black rounded tracking-widest uppercase">Featured</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-5 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="product-edit.php?id=<?php echo $row['p_id']; ?>" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-[#0052CC]/10 text-slate-500 hover:text-[#0052CC] flex items-center justify-center transition-colors border border-slate-200 hover:border-[#0052CC]/30" title="Edit">
                                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                                </a>
                                                <button onclick="openDeleteModal(<?php echo $row['p_id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-red-50 text-slate-500 hover:text-red-600 flex items-center justify-center transition-colors border border-slate-200 hover:border-red-200" title="Delete">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
        </main>
        
        <footer class="w-full py-6 px-8 border-t border-slate-200/50 flex flex-col md:flex-row justify-between items-center text-slate-400 font-['Inter'] text-[10px] uppercase tracking-widest mt-auto">
            <p>© 2024 Silicon Slate. Admin Console.</p>
        </footer>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform duration-300" id="deleteModalContent">
            <div class="w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-3xl">warning</span>
            </div>
            <h3 class="text-2xl font-headline font-extrabold text-center text-slate-900 mb-2">Delete Product?</h3>
            <p class="text-sm text-slate-500 text-center mb-8 font-medium">This action cannot be undone. This will permanently remove the product and its associated images from the database.</p>
            
            <div class="flex gap-4">
                <button onclick="closeDeleteModal()" class="flex-1 py-3 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl font-bold text-sm transition-colors border border-slate-200">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm text-center transition-colors shadow-lg shadow-red-600/20">Delete Product</a>
            </div>
        </div>
    </div>

    <script>
        // Simple Table Filter/Search Script
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("productTable");
            tr = table.getElementsByTagName("tr");
            
            // Loop through all table rows, hide those that don't match the search query (skipping header)
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Search based on the second column (Product Detail)
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }

        // Delete Modal Logic
        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        function openDeleteModal(productId) {
            // Set the delete link dynamically
            confirmBtn.href = 'product-delete.php?id=' + productId;
            
            // Show modal
            modal.classList.remove('hidden');
            // Trigger reflow
            void modal.offsetWidth;
            // Animate in
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }

        function closeDeleteModal() {
            // Animate out
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>