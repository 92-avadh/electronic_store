<?php require_once('header.php'); ?>

<?php
// ==========================================
// HANDLE FORM SUBMISSIONS (CRUD LOGIC)
// ==========================================

// --- ADD SIZE ---
if(isset($_POST['add_size'])) {
    $valid = 1;
    if(empty($_POST['size_name'])) {
        $valid = 0; $error_message .= 'Size Name cannot be empty.<br>';
    }
    if($valid == 1) {
        $statement = $pdo->prepare("INSERT INTO tbl_size (size_name) VALUES (?)");
        $statement->execute(array($_POST['size_name']));
        $success_message = 'Size added successfully.';
    }
}

// --- EDIT SIZE ---
if(isset($_POST['edit_size'])) {
    $valid = 1;
    if(empty($_POST['size_name'])) {
        $valid = 0; $error_message .= 'Size Name cannot be empty.<br>';
    }
    if($valid == 1) {
        $statement = $pdo->prepare("UPDATE tbl_size SET size_name=? WHERE size_id=?");
        $statement->execute(array($_POST['size_name'], $_POST['size_id']));
        $success_message = 'Size updated successfully.';
    }
}

// --- DELETE SIZE ---
if(isset($_POST['delete_size'])) {
    // Safety check: Is this size used by any product?
    $statement = $pdo->prepare("SELECT * FROM tbl_product_size WHERE size_id=?");
    $statement->execute(array($_POST['size_id']));
    if($statement->rowCount() > 0) {
        $error_message .= 'Cannot delete this size because it is currently assigned to one or more products.<br>';
    } else {
        $statement = $pdo->prepare("DELETE FROM tbl_size WHERE size_id=?");
        $statement->execute(array($_POST['size_id']));
        $success_message = 'Size deleted successfully.';
    }
}

// --- ADD COLOR ---
if(isset($_POST['add_color'])) {
    $valid = 1;
    if(empty($_POST['color_name'])) {
        $valid = 0; $error_message .= 'Color Name cannot be empty.<br>';
    }
    if($valid == 1) {
        $statement = $pdo->prepare("INSERT INTO tbl_color (color_name) VALUES (?)");
        $statement->execute(array($_POST['color_name']));
        $success_message = 'Color added successfully.';
    }
}

// --- EDIT COLOR ---
if(isset($_POST['edit_color'])) {
    $valid = 1;
    if(empty($_POST['color_name'])) {
        $valid = 0; $error_message .= 'Color Name cannot be empty.<br>';
    }
    if($valid == 1) {
        $statement = $pdo->prepare("UPDATE tbl_color SET color_name=? WHERE color_id=?");
        $statement->execute(array($_POST['color_name'], $_POST['color_id']));
        $success_message = 'Color updated successfully.';
    }
}

// --- DELETE COLOR ---
if(isset($_POST['delete_color'])) {
    // Safety check: Is this color used by any product?
    $statement = $pdo->prepare("SELECT * FROM tbl_product_color WHERE color_id=?");
    $statement->execute(array($_POST['color_id']));
    if($statement->rowCount() > 0) {
        $error_message .= 'Cannot delete this color because it is currently assigned to one or more products.<br>';
    } else {
        $statement = $pdo->prepare("DELETE FROM tbl_color WHERE color_id=?");
        $statement->execute(array($_POST['color_id']));
        $success_message = 'Color deleted successfully.';
    }
}
?>

<main class="flex-grow p-6 md:p-8 transition-colors duration-200">
    <div class="max-w-[1600px] mx-auto">
        
        <div class="mb-8">
            <span class="text-slate-500 dark:text-slate-400 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal > Settings</span>
            <h1 class="font-headline text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Product Attributes</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 font-medium mt-2">Manage the sizes and colors available for your product variations.</p>
        </div>

        <?php if($error_message): ?>
            <div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-red-200 dark:border-red-500/20"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if($success_message): ?>
            <div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-green-200 dark:border-green-500/20"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col h-full transition-colors duration-200">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 rounded-t-2xl transition-colors">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">straighten</span> Product Sizes
                    </h2>
                    <button onclick="openModal('addSizeModal')" class="px-4 py-2 rounded-lg bg-[#0052CC] text-white text-xs font-bold uppercase tracking-widest shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">add</span> Add Size
                    </button>
                </div>
                
                <div class="p-0 flex-grow overflow-x-auto table-scroll">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 transition-colors">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 w-16">ID</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Size Name</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            <?php
                            $i=0;
                            $statement = $pdo->prepare("SELECT * FROM tbl_size ORDER BY size_id ASC");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                                ?>
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-bold text-slate-400 dark:text-slate-500"><?php echo $i; ?></td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white"><?php echo htmlspecialchars($row['size_name']); ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="openEditSizeModal(<?php echo $row['size_id']; ?>, '<?php echo addslashes($row['size_name']); ?>')" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 hover:bg-[#0052CC]/10 dark:hover:bg-[#0052CC]/20 text-slate-500 dark:text-slate-300 hover:text-[#0052CC] dark:hover:text-[#4da3ff] flex items-center justify-center transition-colors border border-slate-200 dark:border-slate-600" title="Edit">
                                                <span class="material-symbols-outlined text-[16px]">edit</span>
                                            </button>
                                            <button onclick="openDeleteSizeModal(<?php echo $row['size_id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-500/10 text-slate-500 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 flex items-center justify-center transition-colors border border-slate-200 dark:border-slate-600" title="Delete">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            if($i==0) echo '<tr><td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">No sizes found. Click "Add Size" to create one.</td></tr>';
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 flex flex-col h-full transition-colors duration-200">
                <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50 rounded-t-2xl transition-colors">
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">palette</span> Product Colors
                    </h2>
                    <button onclick="openModal('addColorModal')" class="px-4 py-2 rounded-lg bg-[#0052CC] text-white text-xs font-bold uppercase tracking-widest shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">add</span> Add Color
                    </button>
                </div>
                
                <div class="p-0 flex-grow overflow-x-auto table-scroll">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700 transition-colors">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 w-16">ID</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Color Name</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                            <?php
                            $i=0;
                            $statement = $pdo->prepare("SELECT * FROM tbl_color ORDER BY color_id ASC");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                                ?>
                                <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors">
                                    <td class="px-6 py-4 text-sm font-bold text-slate-400 dark:text-slate-500"><?php echo $i; ?></td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-900 dark:text-white"><?php echo htmlspecialchars($row['color_name']); ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="openEditColorModal(<?php echo $row['color_id']; ?>, '<?php echo addslashes($row['color_name']); ?>')" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 hover:bg-[#0052CC]/10 dark:hover:bg-[#0052CC]/20 text-slate-500 dark:text-slate-300 hover:text-[#0052CC] dark:hover:text-[#4da3ff] flex items-center justify-center transition-colors border border-slate-200 dark:border-slate-600" title="Edit">
                                                <span class="material-symbols-outlined text-[16px]">edit</span>
                                            </button>
                                            <button onclick="openDeleteColorModal(<?php echo $row['color_id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-500/10 text-slate-500 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 flex items-center justify-center transition-colors border border-slate-200 dark:border-slate-600" title="Delete">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            if($i==0) echo '<tr><td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500 dark:text-slate-400">No colors found. Click "Add Color" to create one.</td></tr>';
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>

<div id="addSizeModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 border border-transparent dark:border-slate-700 rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-all duration-300 modal-content">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Add New Size</h3>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="text" name="size_name" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] focus:ring-1 focus:ring-[#0052CC] dark:focus:ring-[#4da3ff] mb-6 transition-colors" placeholder="e.g. XL, 15-inch, 256GB" required>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('addSizeModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="add_size" class="flex-1 py-2.5 bg-[#0052CC] hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Save Size</button>
            </div>
        </form>
    </div>
</div>

<div id="editSizeModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 border border-transparent dark:border-slate-700 rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-all duration-300 modal-content">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Edit Size</h3>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="size_id" id="edit_size_id">
            <input type="text" name="size_name" id="edit_size_name" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] focus:ring-1 focus:ring-[#0052CC] dark:focus:ring-[#4da3ff] mb-6 transition-colors" required>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('editSizeModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="edit_size" class="flex-1 py-2.5 bg-[#0052CC] hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Update Size</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteSizeModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 border border-transparent dark:border-slate-700 rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-all duration-300 modal-content">
        <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-2xl">warning</span></div>
        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white mb-2">Delete Size?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 text-center mb-6">This action cannot be undone. You cannot delete a size if it is assigned to a product.</p>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="size_id" id="delete_size_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteSizeModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="delete_size" class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Delete</button>
            </div>
        </form>
    </div>
</div>

<div id="addColorModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 border border-transparent dark:border-slate-700 rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-all duration-300 modal-content">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Add New Color</h3>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="text" name="color_name" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] focus:ring-1 focus:ring-[#0052CC] dark:focus:ring-[#4da3ff] mb-6 transition-colors" placeholder="e.g. Space Gray, Midnight Black" required>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('addColorModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="add_color" class="flex-1 py-2.5 bg-[#0052CC] hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Save Color</button>
            </div>
        </form>
    </div>
</div>

<div id="editColorModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 border border-transparent dark:border-slate-700 rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-all duration-300 modal-content">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Edit Color</h3>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="color_id" id="edit_color_id">
            <input type="text" name="color_name" id="edit_color_name" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] focus:ring-1 focus:ring-[#0052CC] dark:focus:ring-[#4da3ff] mb-6 transition-colors" required>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('editColorModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="edit_color" class="flex-1 py-2.5 bg-[#0052CC] hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Update Color</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteColorModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 border border-transparent dark:border-slate-700 rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-all duration-300 modal-content">
        <div class="w-12 h-12 rounded-full bg-red-50 dark:bg-red-500/10 text-red-500 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-2xl">warning</span></div>
        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white mb-2">Delete Color?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 text-center mb-6">This action cannot be undone. You cannot delete a color if it is assigned to a product.</p>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="color_id" id="delete_color_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteColorModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="delete_color" class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Generic Modal Control
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = modal.querySelector('.modal-content');
        modal.classList.remove('hidden');
        void modal.offsetWidth; // Trigger reflow
        modal.classList.remove('opacity-0');
        content.classList.remove('scale-95');
        content.classList.add('scale-100');
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const content = modal.querySelector('.modal-content');
        modal.classList.add('opacity-0');
        content.classList.remove('scale-100');
        content.classList.add('scale-95');
        setTimeout(() => { modal.classList.add('hidden'); }, 300);
    }

    // Modal Specific Data Triggers
    function openEditSizeModal(id, name) {
        document.getElementById('edit_size_id').value = id;
        document.getElementById('edit_size_name').value = name;
        openModal('editSizeModal');
    }

    function openDeleteSizeModal(id) {
        document.getElementById('delete_size_id').value = id;
        openModal('deleteSizeModal');
    }

    function openEditColorModal(id, name) {
        document.getElementById('edit_color_id').value = id;
        document.getElementById('edit_color_name').value = name;
        openModal('editColorModal');
    }

    function openDeleteColorModal(id) {
        document.getElementById('delete_color_id').value = id;
        openModal('deleteColorModal');
    }
</script>

<?php require_once('footer.php'); ?>