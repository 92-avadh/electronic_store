<?php require_once('header.php'); ?>

<main class="flex-grow p-6 md:p-8 transition-colors duration-200">
    <div class="max-w-[1600px] mx-auto">
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <span class="text-slate-500 dark:text-slate-400 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal > Inventory</span>
                <h1 class="font-headline text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Products Management</h1>
            </div>
            <a href="product-add.php" class="flex-shrink-0 px-5 py-2.5 bg-[#0052CC] hover:bg-blue-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white rounded-xl text-sm font-bold shadow-md hover:shadow-lg transition-all active:scale-95 flex items-center gap-2">
                <span class="material-symbols-outlined text-[20px]">add</span> Add New Product
            </a>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto table-scroll">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 w-16">#</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 w-24">Photo</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 min-w-[250px]">Product Details</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center">Price</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center">Stock</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center">Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <?php
                        $i=0;
                        $statement = $pdo->prepare("SELECT
                                                    t1.p_id, t1.p_name, t1.p_old_price, t1.p_current_price, t1.p_qty, t1.p_featured_photo, t1.p_is_featured, t1.p_is_active, t1.ecat_id,
                                                    t2.ecat_name, t3.mcat_name, t4.tcat_name
                                                    FROM tbl_product t1
                                                    JOIN tbl_end_category t2 ON t1.ecat_id = t2.ecat_id
                                                    JOIN tbl_mid_category t3 ON t2.mcat_id = t3.mcat_id
                                                    JOIN tbl_top_category t4 ON t3.tcat_id = t4.tcat_id
                                                    ORDER BY t1.p_id DESC");
                        $statement->execute();
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) {
                            $i++;
                            ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-5 text-sm font-bold text-slate-500 dark:text-slate-400"><?php echo $i; ?></td>
                                <td class="px-6 py-5">
                                    <div class="w-12 h-12 rounded-lg bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-1 flex items-center justify-center overflow-hidden">
                                        <img src="../assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo htmlspecialchars($row['p_name']); ?>" class="max-w-full max-h-full object-contain mix-blend-multiply dark:mix-blend-normal">
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1" title="<?php echo htmlspecialchars($row['p_name']); ?>"><?php echo htmlspecialchars($row['p_name']); ?></span>
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-1"><?php echo $row['tcat_name']; ?> > <?php echo $row['mcat_name']; ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <span class="text-sm font-black text-[#0052CC] dark:text-[#4da3ff]">₹<?php echo number_format($row['p_current_price'], 2); ?></span>
                                    <?php if($row['p_old_price'] != ''): ?>
                                        <span class="block text-[10px] font-bold line-through text-slate-400 mt-0.5">₹<?php echo number_format($row['p_old_price'], 2); ?></span>
                                    <?php endif; ?>
                                </td>
                                
                                <td class="px-6 py-5 text-center">
                                    <?php if($row['p_qty'] == 0): ?>
                                        <span class="inline-flex px-2.5 py-1 bg-red-100 dark:bg-red-500/10 text-red-700 dark:text-red-400 text-[10px] font-black rounded-md uppercase border border-red-200 dark:border-red-500/20">Out of Stock</span>
                                    <?php elseif($row['p_qty'] < 10): ?>
                                        <span class="inline-flex px-2.5 py-1 bg-orange-100 dark:bg-orange-500/10 text-orange-700 dark:text-orange-400 text-[10px] font-black rounded-md uppercase border border-orange-200 dark:border-orange-500/20" title="Low Stock Warning">Low: <?php echo $row['p_qty']; ?></span>
                                    <?php else: ?>
                                        <span class="inline-flex px-2.5 py-1 bg-green-50 dark:bg-green-500/10 text-green-700 dark:text-green-400 text-[10px] font-black rounded-md uppercase border border-green-200 dark:border-green-500/20"><?php echo $row['p_qty']; ?> Available</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="flex flex-col gap-1 items-center">
                                        <?php if($row['p_is_active'] == 1): ?>
                                            <span class="text-[9px] font-black uppercase tracking-widest text-green-600 dark:text-green-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Active</span>
                                        <?php else: ?>
                                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Hidden</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="product-edit.php?id=<?php echo $row['p_id']; ?>" class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-[#0052CC]/10 text-blue-600 dark:text-[#4da3ff] hover:bg-blue-100 dark:hover:bg-[#0052CC]/20 flex items-center justify-center transition-colors border border-blue-200 dark:border-[#0052CC]/20"><span class="material-symbols-outlined text-[16px]">edit</span></a>
                                        <button onclick="openDeleteModal(<?php echo $row['p_id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-500/10 text-slate-500 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 flex items-center justify-center border border-slate-200 dark:border-slate-600 transition-colors"><span class="material-symbols-outlined text-[16px]">delete</span></button>
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

<div id="deleteModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-sm w-full mx-4 modal-content transform scale-95 transition-transform border border-slate-200 dark:border-slate-700">
        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white mb-2">Delete Product?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 text-center mb-6">This action cannot be undone and will remove the product from the store entirely.</p>
        <div class="flex gap-3">
            <button type="button" onclick="closeModal('deleteModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600">Cancel</button>
            <a href="#" id="deleteConfirmBtn" class="flex-1 py-2.5 bg-red-600 text-white text-center rounded-lg font-bold text-sm shadow-md hover:bg-red-700">Delete</a>
        </div>
    </div>
</div>

<script>
    function openModal(id) { const m=document.getElementById(id); m.classList.remove('hidden'); void m.offsetWidth; m.classList.remove('opacity-0'); m.querySelector('.modal-content').classList.replace('scale-95','scale-100'); }
    function closeModal(id) { const m=document.getElementById(id); m.classList.add('opacity-0'); m.querySelector('.modal-content').classList.replace('scale-100','scale-95'); setTimeout(()=>m.classList.add('hidden'),300); }
    function openDeleteModal(id) { document.getElementById('deleteConfirmBtn').href = 'product-delete.php?id=' + id; openModal('deleteModal'); }
</script>

<?php require_once('footer.php'); ?>