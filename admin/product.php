<?php require_once('header.php'); ?>

<main class="flex-grow p-6 md:p-8">
    <div class="max-w-[1600px] mx-auto">
        
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <span class="text-slate-500 dark:text-slate-400 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal > Inventory</span>
                <h1 class="font-headline text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Product Catalog</h1>
            </div>
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="relative flex-grow md:flex-grow-0">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search products..." class="w-full md:w-64 pl-9 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-[#0052CC]/20 outline-none transition-all shadow-sm">
                </div>
                <a href="product-add.php" class="flex-shrink-0 px-6 py-2.5 rounded-lg bg-[#0052CC] text-white text-sm font-bold shadow-md hover:bg-blue-700 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">add</span> Add Product
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto table-scroll">
                <table class="w-full text-left border-collapse" id="productTable">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 w-16">#</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Product Detail</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Price</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center">Stock</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <?php
                        $i=0;
                        // Since we removed categorization, we pull straight from tbl_product
                        $statement = $pdo->prepare("SELECT * FROM tbl_product ORDER BY p_id DESC");
                        $statement->execute();
                        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
                            $i++;
                            ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-5 text-sm font-bold text-slate-400"><?php echo $i; ?></td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-lg bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 overflow-hidden flex-shrink-0 p-1 flex items-center justify-center">
                                            <img src="../assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="Product" class="w-full h-full object-contain">
                                        </div>
                                        <div>
                                            <h3 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1 max-w-[250px]"><?php echo htmlspecialchars($row['p_name']); ?></h3>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 dark:text-white">₹<?php echo number_format($row['p_current_price']); ?></span>
                                        <?php if($row['p_old_price'] != ''): ?>
                                            <span class="text-[10px] font-bold text-slate-400 line-through">₹<?php echo number_format($row['p_old_price']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php if($row['p_qty'] == 0): ?>
                                        <span class="inline-flex px-2.5 py-1 bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-400 text-[10px] font-black rounded-md uppercase">Out of Stock</span>
                                    <?php elseif($row['p_qty'] < 5): ?>
                                        <span class="inline-flex px-2.5 py-1 bg-orange-100 dark:bg-orange-500/10 text-orange-700 dark:text-orange-400 text-[10px] font-black rounded-md uppercase">Low: <?php echo $row['p_qty']; ?></span>
                                    <?php else: ?>
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300"><?php echo $row['p_qty']; ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <?php if($row['p_is_active'] == 1): ?>
                                            <span class="w-20 inline-flex justify-center px-2 py-0.5 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-500/20 text-[9px] font-black rounded uppercase">Active</span>
                                        <?php else: ?>
                                            <span class="w-20 inline-flex justify-center px-2 py-0.5 bg-slate-100 dark:bg-slate-700 text-slate-500 border border-slate-200 dark:border-slate-600 text-[9px] font-black rounded uppercase">Inactive</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="product-edit.php?id=<?php echo $row['p_id']; ?>" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 hover:bg-[#0052CC]/10 text-slate-500 dark:text-slate-300 flex items-center justify-center transition-colors border border-slate-200 dark:border-slate-600"><span class="material-symbols-outlined text-[16px]">edit</span></a>
                                        <button onclick="openDeleteModal(<?php echo $row['p_id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 hover:bg-red-50 text-slate-500 dark:text-slate-300 flex items-center justify-center transition-colors border border-slate-200 dark:border-slate-600"><span class="material-symbols-outlined text-[16px]">delete</span></button>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<div id="deleteModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-8 max-w-md w-full mx-4 transform scale-95 transition-transform" id="deleteModalContent">
        <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center mx-auto mb-6"><span class="material-symbols-outlined text-3xl">warning</span></div>
        <h3 class="text-2xl font-headline font-extrabold text-center text-slate-900 dark:text-white mb-2">Delete Product?</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 text-center mb-8">This action cannot be undone.</p>
        <div class="flex gap-4">
            <button onclick="closeDeleteModal()" class="flex-1 py-3 bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-bold text-sm">Cancel</button>
            <a href="#" id="confirmDeleteBtn" class="flex-1 py-3 bg-red-600 text-white rounded-xl font-bold text-sm text-center shadow-lg">Delete</a>
        </div>
    </div>
</div>

<script>
    function filterTable() {
        var input=document.getElementById("searchInput"), filter=input.value.toUpperCase(), table=document.getElementById("productTable"), tr=table.getElementsByTagName("tr");
        for (var i=1; i<tr.length; i++) {
            var td = tr[i].getElementsByTagName("td")[1];
            if (td) {
                var txtValue = td.textContent || td.innerText;
                tr[i].style.display = txtValue.toUpperCase().indexOf(filter) > -1 ? "" : "none";
            }       
        }
    }
    function openDeleteModal(id) {
        document.getElementById('confirmDeleteBtn').href = 'product-delete.php?id=' + id;
        var modal = document.getElementById('deleteModal'), content = document.getElementById('deleteModalContent');
        modal.classList.remove('hidden'); void modal.offsetWidth; modal.classList.remove('opacity-0'); content.classList.replace('scale-95','scale-100');
    }
    function closeDeleteModal() {
        var modal = document.getElementById('deleteModal'), content = document.getElementById('deleteModalContent');
        modal.classList.add('opacity-0'); content.classList.replace('scale-100','scale-95'); setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>

<?php require_once('footer.php'); ?>