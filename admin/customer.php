<?php require_once('header.php'); ?>

<main class="flex-grow p-6 md:p-8">
    <div class="max-w-[1600px] mx-auto">
        
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <span class="text-slate-500 dark:text-slate-400 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal > Customers</span>
                <h1 class="font-headline text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Customer Directory</h1>
            </div>
            <div class="relative w-full md:w-80">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search name, email, or phone..." class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-[#0052CC]/20 outline-none transition-all shadow-sm">
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto table-scroll">
                <table class="w-full text-left border-collapse" id="customerTable">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 w-16">#</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Customer Profile</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Contact Details</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center">Account Status</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <?php
                        $i=0;
                        $statement = $pdo->prepare("SELECT * FROM tbl_customer ORDER BY cust_id DESC");
                        $statement->execute();
                        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
                            $i++;
                            $initials = strtoupper(substr($row['cust_name'], 0, 2));
                            ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors group">
                                <td class="px-6 py-5 text-sm font-bold text-slate-400"><?php echo $i; ?></td>
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-700 dark:text-indigo-300 flex items-center justify-center font-bold text-sm border border-white dark:border-slate-800">
                                            <?php echo $initials; ?>
                                        </div>
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900 dark:text-white searchable"><?php echo htmlspecialchars($row['cust_name']); ?></span>
                                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 searchable"><?php echo htmlspecialchars($row['cust_email']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-slate-700 dark:text-slate-300 searchable"><?php echo htmlspecialchars($row['cust_phone']); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php if($row['cust_status'] == 1): ?>
                                        <span class="inline-flex px-2.5 py-1 bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 border border-green-200 dark:border-green-500/20 text-[10px] font-black rounded uppercase">Active</span>
                                    <?php else: ?>
                                        <span class="inline-flex px-2.5 py-1 bg-orange-50 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 border border-orange-200 dark:border-orange-500/20 text-[10px] font-black rounded uppercase">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <button onclick="openDeleteModal(<?php echo $row['cust_id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 hover:bg-red-50 text-slate-500 dark:text-slate-300 hover:text-red-600 flex items-center justify-center border border-slate-200 dark:border-slate-600 ml-auto"><span class="material-symbols-outlined text-[16px]">delete</span></button>
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
        <div class="w-16 h-16 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center mx-auto mb-6"><span class="material-symbols-outlined text-3xl">person_remove</span></div>
        <h3 class="text-2xl font-headline font-extrabold text-center text-slate-900 dark:text-white mb-2">Delete Customer?</h3>
        <p class="text-sm text-slate-500 dark:text-slate-400 text-center mb-8">This action cannot be undone.</p>
        <div class="flex gap-4">
            <button onclick="closeDeleteModal()" class="flex-1 py-3 bg-slate-50 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-xl font-bold text-sm">Cancel</button>
            <a href="#" id="confirmDeleteBtn" class="flex-1 py-3 bg-red-600 text-white rounded-xl font-bold text-sm text-center shadow-lg">Delete</a>
        </div>
    </div>
</div>

<script>
    function filterTable() {
        var input=document.getElementById("searchInput"), filter=input.value.toUpperCase(), table=document.getElementById("customerTable"), tr=table.getElementsByTagName("tr");
        for (var i=1; i<tr.length; i++) {
            var tdProfile=tr[i].getElementsByTagName("td")[1], tdContact=tr[i].getElementsByTagName("td")[2];
            if (tdProfile||tdContact) {
                var txtValueProfile=tdProfile.textContent||tdProfile.innerText, txtValueContact=tdContact.textContent||tdContact.innerText;
                tr[i].style.display = (txtValueProfile.toUpperCase().indexOf(filter)>-1 || txtValueContact.toUpperCase().indexOf(filter)>-1) ? "" : "none";
            }       
        }
    }
    function openDeleteModal(id) {
        document.getElementById('confirmDeleteBtn').href = 'customer-delete.php?id=' + id;
        var modal = document.getElementById('deleteModal'), content = document.getElementById('deleteModalContent');
        modal.classList.remove('hidden'); void modal.offsetWidth; modal.classList.remove('opacity-0'); content.classList.replace('scale-95','scale-100');
    }
    function closeDeleteModal() {
        var modal = document.getElementById('deleteModal'), content = document.getElementById('deleteModalContent');
        modal.classList.add('opacity-0'); content.classList.replace('scale-100','scale-95'); setTimeout(() => modal.classList.add('hidden'), 300);
    }
</script>

<?php require_once('footer.php'); ?>