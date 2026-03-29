<?php require_once('header.php'); ?>

<?php
if(isset($_POST['update_status'])) {
    $statement = $pdo->prepare("UPDATE tbl_payment SET payment_status=?, shipping_status=? WHERE id=?");
    $statement->execute(array($_POST['payment_status'], $_POST['shipping_status'], $_POST['order_id']));
    $success_message = 'Order status updated successfully.';
}
if(isset($_POST['delete_order'])) {
    $statement = $pdo->prepare("DELETE FROM tbl_payment WHERE id=?");
    $statement->execute(array($_POST['order_id']));
    $success_message = 'Order deleted successfully.';
}
?>

<main class="flex-grow p-6 md:p-8">
    <div class="max-w-[1600px] mx-auto">
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <span class="text-slate-500 dark:text-slate-400 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal > Orders</span>
                <h1 class="font-headline text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Order Overview</h1>
            </div>
            
            <div class="flex items-center gap-3 w-full md:w-auto">
                <div class="relative w-full md:w-80">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    <input type="text" id="searchInput" onkeyup="filterTable()" placeholder="Search by ID, Name, or Email..." class="w-full pl-9 pr-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg text-sm text-slate-900 dark:text-white focus:ring-2 focus:ring-[#0052CC]/20 outline-none shadow-sm transition-colors">
                </div>
                <a href="download-report.php" class="flex-shrink-0 px-4 py-2.5 bg-[#0052CC] hover:bg-blue-700 text-white rounded-lg text-sm font-bold shadow-sm transition-colors flex items-center gap-2 border border-transparent">
                    <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                    <span class="hidden md:inline uppercase tracking-widest text-[10px]">Report</span>
                </a>
            </div>
            </div>

        <?php if(isset($error_message) && $error_message): ?><div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-red-200 dark:border-red-500/20"><?php echo $error_message; ?></div><?php endif; ?>
        <?php if(isset($success_message) && $success_message): ?><div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-green-200 dark:border-green-500/20"><?php echo $success_message; ?></div><?php endif; ?>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto table-scroll">
                <table class="w-full text-left border-collapse" id="orderTable">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Order Ref</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Customer Details</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Amount & Method</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center">Payment</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center">Shipping</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <?php
                        $statement = $pdo->prepare("SELECT * FROM tbl_payment ORDER BY id DESC"); $statement->execute();
                        foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
                            $stmt1 = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?"); $stmt1->execute(array($row['customer_email']));
                            $cust_name = "Unknown"; foreach ($stmt1->fetchAll(PDO::FETCH_ASSOC) as $row1) { $cust_name = $row1['cust_name']; }
                            ?>
                            <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors">
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 dark:text-white">#<?php echo $row['payment_id']; ?></span>
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-0.5"><?php echo date('M d, Y', strtotime($row['payment_date'])); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900 dark:text-white"><?php echo htmlspecialchars($cust_name); ?></span>
                                        <span class="text-xs text-slate-500 dark:text-slate-400"><?php echo htmlspecialchars($row['customer_email']); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-[#0052CC] dark:text-[#4da3ff]">₹<?php echo number_format($row['paid_amount']); ?></span>
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-0.5"><?php echo $row['payment_method']; ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php if($row['payment_status'] == 'Pending'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 bg-orange-100 dark:bg-orange-500/10 text-orange-700 dark:text-orange-400 text-[10px] font-black rounded-md uppercase">Pending</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400 text-[10px] font-black rounded-md uppercase">Completed</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <?php if($row['shipping_status'] == 'Pending'): ?>
                                        <span class="inline-flex items-center px-2.5 py-1 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 text-[10px] font-black rounded-md uppercase border border-red-200 dark:border-red-500/20">Pending</span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-1 bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 text-[10px] font-black rounded-md uppercase border border-teal-200 dark:border-teal-500/20">Shipped</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-5 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button onclick="openUpdateStatusModal(<?php echo $row['id']; ?>, '<?php echo $row['payment_status']; ?>', '<?php echo $row['shipping_status']; ?>')" class="px-3 py-1.5 rounded-lg bg-blue-50 dark:bg-[#0052CC]/10 text-blue-600 dark:text-[#4da3ff] hover:bg-blue-100 dark:hover:bg-[#0052CC]/20 flex items-center justify-center gap-1 transition-colors border border-blue-200 dark:border-[#0052CC]/20 text-[10px] font-bold uppercase tracking-widest">Update</button>
                                        <button onclick="openDeleteModal(<?php echo $row['id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-500/10 text-slate-500 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 flex items-center justify-center border border-slate-200 dark:border-slate-600"><span class="material-symbols-outlined text-[16px]">delete</span></button>
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

<div id="updateStatusModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-sm w-full mx-4 modal-content transform scale-95 transition-transform border border-slate-200 dark:border-slate-700">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-6">Update Order Status</h3>
        <form method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="order_id" id="status_order_id">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400 block mb-1">Payment Status</label>
                    <select name="payment_status" id="status_payment" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC]">
                        <option value="Pending">Pending</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400 block mb-1">Shipping Status</label>
                    <select name="shipping_status" id="status_shipping" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC]">
                        <option value="Pending">Pending</option>
                        <option value="Completed">Shipped / Completed</option>
                    </select>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('updateStatusModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600">Cancel</button>
                <button type="submit" name="update_status" class="flex-1 py-2.5 bg-[#0052CC] text-white rounded-lg font-bold text-sm shadow-md hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-sm w-full mx-4 modal-content transform scale-95 transition-transform border border-slate-200 dark:border-slate-700">
        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white mb-2">Delete Order?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 text-center mb-6">This action cannot be undone.</p>
        <form method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="order_id" id="delete_order_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600">Cancel</button>
                <button type="submit" name="delete_order" class="flex-1 py-2.5 bg-red-600 text-white rounded-lg font-bold text-sm shadow-md hover:bg-red-700">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function filterTable() { var input=document.getElementById("searchInput"), filter=input.value.toUpperCase(), table=document.getElementById("orderTable"), tr=table.getElementsByTagName("tr"); for (var i=1; i<tr.length; i++) { var tdRef=tr[i].getElementsByTagName("td")[0], tdCust=tr[i].getElementsByTagName("td")[1]; if (tdRef||tdCust) { var txtValueRef=tdRef.textContent||tdRef.innerText, txtValueCust=tdCust.textContent||tdCust.innerText; tr[i].style.display = (txtValueRef.toUpperCase().indexOf(filter)>-1 || txtValueCust.toUpperCase().indexOf(filter)>-1) ? "" : "none"; } } }
    function openModal(id) { const m=document.getElementById(id); m.classList.remove('hidden'); void m.offsetWidth; m.classList.remove('opacity-0'); m.querySelector('.modal-content').classList.replace('scale-95','scale-100'); }
    function closeModal(id) { const m=document.getElementById(id); m.classList.add('opacity-0'); m.querySelector('.modal-content').classList.replace('scale-100','scale-95'); setTimeout(()=>m.classList.add('hidden'),300); }
    function openUpdateStatusModal(id, payStatus, shipStatus) { document.getElementById('status_order_id').value = id; document.getElementById('status_payment').value = payStatus; document.getElementById('status_shipping').value = (shipStatus == 'Pending') ? 'Pending' : 'Completed'; openModal('updateStatusModal'); }
    function openDeleteModal(id) { document.getElementById('delete_order_id').value = id; openModal('deleteModal'); }
</script>

<?php require_once('footer.php'); ?>