<?php require_once('header.php'); ?>

<?php
if(isset($_POST['add_category'])) {
    $statement = $pdo->prepare("INSERT INTO tbl_top_category (tcat_name, show_on_menu) VALUES (?,?)");
    $statement->execute(array($_POST['tcat_name'], $_POST['show_on_menu']));
    $success_message = 'Category added successfully.';
}
if(isset($_POST['edit_category'])) {
    $statement = $pdo->prepare("UPDATE tbl_top_category SET tcat_name=?, show_on_menu=? WHERE tcat_id=?");
    $statement->execute(array($_POST['tcat_name'], $_POST['show_on_menu'], $_POST['tcat_id']));
    $success_message = 'Category updated successfully.';
}
if(isset($_POST['delete_category'])) {
    $statement = $pdo->prepare("DELETE FROM tbl_top_category WHERE tcat_id=?");
    $statement->execute(array($_POST['tcat_id']));
    $success_message = 'Category deleted successfully.';
}
?>

<main class="flex-grow p-6 md:p-8">
    <div class="max-w-[1200px] mx-auto">
        <div class="mb-8 flex justify-between items-end">
            <div>
                <span class="text-slate-500 dark:text-slate-400 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal</span>
                <h1 class="font-headline text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Categories</h1>
            </div>
            <button onclick="openModal('addCatModal')" class="px-6 py-2.5 rounded-lg bg-[#0052CC] text-white font-headline text-sm font-bold shadow-md hover:bg-blue-700 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span> Add Category
            </button>
        </div>

        <?php if(isset($error_message) && $error_message): ?><div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-red-200 dark:border-red-500/20"><?php echo $error_message; ?></div><?php endif; ?>
        <?php if(isset($success_message) && $success_message): ?><div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-green-200 dark:border-green-500/20"><?php echo $success_message; ?></div><?php endif; ?>

        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 w-16">ID</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Category Name</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-center">In Menu?</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    <?php
                    $i=0;
                    $statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_id DESC"); $statement->execute();
                    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) { $i++; ?>
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-400"><?php echo $i; ?></td>
                            <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white"><?php echo htmlspecialchars($row['tcat_name']); ?></td>
                            <td class="px-6 py-4 text-center">
                                <?php echo ($row['show_on_menu'] == 1) ? '<span class="px-2 py-1 bg-green-100 dark:bg-green-500/10 text-green-700 dark:text-green-400 text-[10px] font-black rounded uppercase">Yes</span>' : '<span class="px-2 py-1 bg-slate-100 dark:bg-slate-700 text-slate-500 dark:text-slate-400 text-[10px] font-black rounded uppercase">No</span>'; ?>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditCatModal(<?php echo $row['tcat_id']; ?>, '<?php echo addslashes($row['tcat_name']); ?>', <?php echo $row['show_on_menu']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 hover:text-[#0052CC] dark:hover:text-[#4da3ff] flex items-center justify-center border border-slate-200 dark:border-slate-600"><span class="material-symbols-outlined text-[16px]">edit</span></button>
                                    <button onclick="openDeleteCatModal(<?php echo $row['tcat_id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 flex items-center justify-center border border-slate-200 dark:border-slate-600"><span class="material-symbols-outlined text-[16px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="addCatModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-sm w-full mx-4 modal-content transform scale-95 transition-transform border border-slate-200 dark:border-slate-700">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Add Category</h3>
        <form method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="text" name="tcat_name" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm mb-4 outline-none focus:border-[#0052CC]" placeholder="Category Name" required>
            <select name="show_on_menu" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm mb-6 outline-none focus:border-[#0052CC]">
                <option value="1">Show on Menu</option><option value="0">Hide from Menu</option>
            </select>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('addCatModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600">Cancel</button>
                <button type="submit" name="add_category" class="flex-1 py-2.5 bg-[#0052CC] text-white rounded-lg font-bold text-sm shadow-md hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="editCatModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-sm w-full mx-4 modal-content transform scale-95 transition-transform border border-slate-200 dark:border-slate-700">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Edit Category</h3>
        <form method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="tcat_id" id="edit_tcat_id">
            <input type="text" name="tcat_name" id="edit_tcat_name" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm mb-4 outline-none focus:border-[#0052CC]" required>
            <select name="show_on_menu" id="edit_show_menu" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm mb-6 outline-none focus:border-[#0052CC]">
                <option value="1">Show on Menu</option><option value="0">Hide from Menu</option>
            </select>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('editCatModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600">Cancel</button>
                <button type="submit" name="edit_category" class="flex-1 py-2.5 bg-[#0052CC] text-white rounded-lg font-bold text-sm shadow-md hover:bg-blue-700">Update</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteCatModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-sm w-full mx-4 modal-content transform scale-95 transition-transform border border-slate-200 dark:border-slate-700">
        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white mb-2">Delete Category?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 text-center mb-6">This action cannot be undone.</p>
        <form method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="tcat_id" id="delete_tcat_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteCatModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600">Cancel</button>
                <button type="submit" name="delete_category" class="flex-1 py-2.5 bg-red-600 text-white rounded-lg font-bold text-sm shadow-md hover:bg-red-700">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { const m=document.getElementById(id); m.classList.remove('hidden'); void m.offsetWidth; m.classList.remove('opacity-0'); m.querySelector('.modal-content').classList.replace('scale-95','scale-100'); }
    function closeModal(id) { const m=document.getElementById(id); m.classList.add('opacity-0'); m.querySelector('.modal-content').classList.replace('scale-100','scale-95'); setTimeout(()=>m.classList.add('hidden'),300); }
    function openEditCatModal(id, name, menu) { document.getElementById('edit_tcat_id').value = id; document.getElementById('edit_tcat_name').value = name; document.getElementById('edit_show_menu').value = menu; openModal('editCatModal'); }
    function openDeleteCatModal(id) { document.getElementById('delete_tcat_id').value = id; openModal('deleteCatModal'); }
</script>

<?php require_once('footer.php'); ?>