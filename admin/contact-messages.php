<?php require_once('header.php'); ?>

<?php
if(isset($_POST['delete_message'])) {
    $statement = $pdo->prepare("DELETE FROM tbl_contact WHERE id=?");
    $statement->execute(array($_POST['message_id']));
    $success_message = 'Message deleted successfully.';
}
?>

<main class="flex-grow p-6 md:p-8">
    <div class="max-w-[1600px] mx-auto">
        <div class="mb-8 flex justify-between items-end gap-6">
            <div>
                <span class="text-slate-500 dark:text-slate-400 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal > Inbox</span>
                <h1 class="font-headline text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Customer Complaints & Messages</h1>
            </div>
        </div>

        <?php if(isset($error_message) && $error_message): ?><div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-red-200 dark:border-red-500/20"><?php echo $error_message; ?></div><?php endif; ?>
        <?php if(isset($success_message) && $success_message): ?><div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-green-200 dark:border-green-500/20"><?php echo $success_message; ?></div><?php endif; ?>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
            <div class="overflow-x-auto table-scroll">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Date</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Customer Details</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Message / Complaint</th>
                            <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                        <?php
                        // Check if table exists first (prevents errors if nobody has visited contact page yet)
                        $tableExists = $pdo->query("SHOW TABLES LIKE 'tbl_contact'")->rowCount() > 0;
                        if($tableExists) {
                            $statement = $pdo->prepare("SELECT * FROM tbl_contact ORDER BY id DESC");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            
                            if(count($result) == 0) {
                                echo '<tr><td colspan="4" class="px-6 py-8 text-center text-sm font-bold text-slate-500">No messages found.</td></tr>';
                            }

                            foreach ($result as $row) {
                                ?>
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors">
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-slate-900 dark:text-white"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></span>
                                            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-400 mt-0.5"><?php echo date('h:i A', strtotime($row['created_at'])); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-slate-900 dark:text-white"><?php echo htmlspecialchars($row['visitor_name']); ?></span>
                                            <a href="mailto:<?php echo htmlspecialchars($row['visitor_email']); ?>" class="text-xs font-semibold text-[#0052CC] hover:underline"><?php echo htmlspecialchars($row['visitor_email']); ?></a>
                                            <span class="text-xs text-slate-500 mt-1"><?php echo htmlspecialchars($row['visitor_phone']); ?></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <p class="text-sm text-slate-700 dark:text-slate-300 bg-slate-50 dark:bg-slate-900/50 p-4 rounded-xl border border-slate-100 dark:border-slate-700 max-w-2xl whitespace-pre-wrap leading-relaxed"><?php echo htmlspecialchars($row['visitor_message']); ?></p>
                                    </td>
                                    <td class="px-6 py-5 text-right align-top">
                                        <button onclick="openDeleteModal(<?php echo $row['id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-500/10 text-slate-500 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 flex items-center justify-center border border-slate-200 dark:border-slate-600 inline-flex transition-colors"><span class="material-symbols-outlined text-[16px]">delete</span></button>
                                    </td>
                                </tr>
                                <?php 
                            }
                        } else {
                            echo '<tr><td colspan="4" class="px-6 py-8 text-center text-sm font-bold text-slate-500">Inbox is empty.</td></tr>';
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
        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white mb-2">Delete Message?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 text-center mb-6">This action cannot be undone.</p>
        <form method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="message_id" id="delete_message_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600">Cancel</button>
                <button type="submit" name="delete_message" class="flex-1 py-2.5 bg-red-600 text-white rounded-lg font-bold text-sm shadow-md hover:bg-red-700">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { const m=document.getElementById(id); m.classList.remove('hidden'); void m.offsetWidth; m.classList.remove('opacity-0'); m.querySelector('.modal-content').classList.replace('scale-95','scale-100'); }
    function closeModal(id) { const m=document.getElementById(id); m.classList.add('opacity-0'); m.querySelector('.modal-content').classList.replace('scale-100','scale-95'); setTimeout(()=>m.classList.add('hidden'),300); }
    function openDeleteModal(id) { document.getElementById('delete_message_id').value = id; openModal('deleteModal'); }
</script>

<?php require_once('footer.php'); ?>