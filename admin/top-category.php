<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();

// Security Check
if(!isset($_SESSION['user'])) { header('location: login.php'); exit; }
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Top Categories | Silicon Slate Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
      tailwind.config = { darkMode: "class", theme: { extend: { fontFamily: { "headline": ["Manrope"], "body": ["Inter"], "label": ["Inter"] } } } }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { background-color: #faf8ff; color: #131b2e; font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        .table-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
        .table-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 4px; }
        .table-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="antialiased selection:bg-[#b3c5ff]">
    
    <?php require_once('sidebar.php'); ?>

    <div class="lg:ml-64 min-h-screen flex flex-col relative">
        
        <header class="w-full sticky top-0 z-40 bg-[#faf8ff]/80 backdrop-blur-md flex items-center justify-between px-8 h-20 border-b border-slate-200/50 shadow-sm">
            <h1 class="font-bold tracking-tight text-xl hidden md:block">Category Management</h1>
            <div class="flex items-center gap-4 ml-auto">
                <a href="../index.php" target="_blank" class="p-2 text-[#0052CC] bg-[#0052CC]/10 hover:bg-[#0052CC]/20 rounded-full transition-all flex items-center gap-2 px-4">
                    <span class="material-symbols-outlined text-sm">storefront</span>
                    <span class="text-xs font-bold uppercase tracking-widest hidden md:inline">View Store</span>
                </a>
                <div class="w-10 h-10 rounded-full bg-[#0052CC] text-white flex items-center justify-center font-bold overflow-hidden shadow-sm">
                    <?php if($_SESSION['user']['photo'] == ''): ?>
                        <?php echo substr($_SESSION['user']['full_name'], 0, 1); ?>
                    <?php else: ?>
                        <img src="../assets/uploads/<?php echo $_SESSION['user']['photo']; ?>" alt="Admin Profile" class="w-full h-full object-cover"/>
                    <?php endif; ?>
                </div>
            </div>
        </header>

        <main class="flex-grow p-6 md:p-8">
            <div class="max-w-[1200px] mx-auto">
                
                <div class="flex gap-4 mb-8 border-b border-slate-200 pb-px overflow-x-auto">
                    <a href="top-category.php" class="px-4 py-2 border-b-2 border-[#0052CC] text-[#0052CC] font-bold text-sm whitespace-nowrap">Top-Level Categories</a>
                    <a href="mid-category.php" class="px-4 py-2 border-b-2 border-transparent text-slate-500 hover:text-slate-900 font-medium text-sm transition-colors whitespace-nowrap">Mid-Level Categories</a>
                    <a href="end-category.php" class="px-4 py-2 border-b-2 border-transparent text-slate-500 hover:text-slate-900 font-medium text-sm transition-colors whitespace-nowrap">End-Level Categories</a>
                </div>

                <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <h1 class="font-headline text-3xl font-extrabold text-slate-900 tracking-tight">Top Categories</h1>
                        <p class="text-sm text-slate-500 font-medium mt-1">Manage the primary navigation structural categories for your store.</p>
                    </div>
                    
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <div class="relative flex-grow md:flex-grow-0">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                            <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search categories..." class="w-full md:w-64 pl-9 pr-4 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-[#0052CC]/20 focus:border-[#0052CC] outline-none transition-all shadow-sm">
                        </div>
                        <a href="top-category-add.php" class="flex-shrink-0 px-6 py-2.5 rounded-lg bg-[#0052CC] text-white font-headline text-sm font-bold shadow-md hover:bg-blue-700 transition-all flex items-center gap-2">
                            <span class="material-symbols-outlined text-[18px]">add</span>
                            <span class="hidden sm:inline">Add New</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                    <div class="overflow-x-auto table-scroll">
                        <table class="w-full text-left border-collapse" id="categoryTable">
                            <thead class="bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 w-16">#</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Category Name</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 text-center">Show on Menu?</th>
                                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                
                                <?php
                                $i=0;
                                $statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_id DESC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $i++;
                                    ?>
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-6 py-4 text-sm font-bold text-slate-400"><?php echo $i; ?></td>
                                        
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-bold text-slate-900 searchable"><?php echo htmlspecialchars($row['tcat_name']); ?></span>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-center">
                                            <?php if($row['show_on_menu'] == 1): ?>
                                                <span class="inline-flex items-center justify-center px-2.5 py-1 bg-green-50 text-green-600 border border-green-200 text-[10px] font-black rounded-md tracking-widest uppercase">Yes</span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center justify-center px-2.5 py-1 bg-slate-100 text-slate-500 border border-slate-200 text-[10px] font-black rounded-md tracking-widest uppercase">No</span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="top-category-edit.php?id=<?php echo $row['tcat_id']; ?>" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-[#0052CC]/10 text-slate-500 hover:text-[#0052CC] flex items-center justify-center transition-colors border border-slate-200 hover:border-[#0052CC]/30" title="Edit">
                                                    <span class="material-symbols-outlined text-[16px]">edit</span>
                                                </a>
                                                <button onclick="openDeleteModal(<?php echo $row['tcat_id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-red-50 text-slate-500 hover:text-red-600 flex items-center justify-center transition-colors border border-slate-200 hover:border-red-200" title="Delete">
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
        
        <footer class="w-full py-6 px-8 border-t border-slate-200/50 flex items-center text-slate-400 font-['Inter'] text-[10px] uppercase tracking-widest mt-auto">
            <p>© 2024 Silicon Slate. Admin Console.</p>
        </footer>
    </div>

    <div id="deleteModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform duration-300" id="deleteModalContent">
            <div class="w-16 h-16 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-3xl">warning</span>
            </div>
            <h3 class="text-2xl font-headline font-extrabold text-center text-slate-900 mb-2">Delete Category?</h3>
            <p class="text-sm text-slate-500 text-center mb-8 font-medium">This will permanently delete the top category. Please ensure no mid or end categories are dependent on this before deleting.</p>
            
            <div class="flex gap-4">
                <button onclick="closeDeleteModal()" class="flex-1 py-3 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-xl font-bold text-sm transition-colors border border-slate-200">Cancel</button>
                <a href="#" id="confirmDeleteBtn" class="flex-1 py-3 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm text-center transition-colors shadow-lg shadow-red-600/20">Delete</a>
            </div>
        </div>
    </div>

    <script>
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("categoryTable");
            tr = table.getElementsByTagName("tr");
            
            for (i = 1; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1]; // Target Category Name column
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

        const modal = document.getElementById('deleteModal');
        const modalContent = document.getElementById('deleteModalContent');
        const confirmBtn = document.getElementById('confirmDeleteBtn');

        function openDeleteModal(id) {
            confirmBtn.href = 'top-category-delete.php?id=' + id;
            modal.classList.remove('hidden');
            void modal.offsetWidth;
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');
        }

        function closeDeleteModal() {
            modal.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }
    </script>
</body>
</html>