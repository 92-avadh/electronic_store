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

<main class="flex-grow p-6 md:p-8">
    <div class="max-w-[1600px] mx-auto">
        
        <div class="mb-8">
            <span class="text-slate-500 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal > Settings</span>
            <h1 class="font-headline text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Product Attributes</h1>
            <p class="text-sm text-slate-500 font-medium mt-2">Manage the sizes and colors available for your product variations.</p>
        </div>

        <?php if($error_message): ?>
            <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-red-200"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if($success_message): ?>
            <div class="bg-green-50 text-green-600 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-green-200"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-full">
                <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#0052CC]">straighten</span> Product Sizes
                    </h2>
                    <button onclick="openModal('addSizeModal')" class="px-4 py-2 rounded-lg bg-[#0052CC] text-white text-xs font-bold uppercase tracking-widest shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">add</span> Add Size
                    </button>
                </div>
                
                <div class="p-0 flex-grow overflow-x-auto table-scroll">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 w-16">ID</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Size Name</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php
                            $i=0;
                            $statement = $pdo->prepare("SELECT * FROM tbl_size ORDER BY size_id ASC");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                                ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4 text-sm font-bold text-slate-400"><?php echo $i; ?></td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-900"><?php echo htmlspecialchars($row['size_name']); ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="openEditSizeModal(<?php echo $row['size_id']; ?>, '<?php echo addslashes($row['size_name']); ?>')" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-[#0052CC]/10 text-slate-500 hover:text-[#0052CC] flex items-center justify-center transition-colors border border-slate-200" title="Edit">
                                                <span class="material-symbols-outlined text-[16px]">edit</span>
                                            </button>
                                            <button onclick="openDeleteSizeModal(<?php echo $row['size_id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-red-50 text-slate-500 hover:text-red-600 flex items-center justify-center transition-colors border border-slate-200" title="Delete">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            if($i==0) echo '<tr><td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500">No sizes found. Click "Add Size" to create one.</td></tr>';
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 flex flex-col h-full">
                <div class="p-6 border-b border-slate-200 flex justify-between items-center bg-slate-50/50 rounded-t-2xl">
                    <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#0052CC]">palette</span> Product Colors
                    </h2>
                    <button onclick="openModal('addColorModal')" class="px-4 py-2 rounded-lg bg-[#0052CC] text-white text-xs font-bold uppercase tracking-widest shadow-sm hover:bg-blue-700 transition-colors flex items-center gap-1">
                        <span class="material-symbols-outlined text-[16px]">add</span> Add Color
                    </button>
                </div>
                
                <div class="p-0 flex-grow overflow-x-auto table-scroll">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 w-16">ID</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Color Name</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php
                            $i=0;
                            $statement = $pdo->prepare("SELECT * FROM tbl_color ORDER BY color_id ASC");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                                ?>
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-4 text-sm font-bold text-slate-400"><?php echo $i; ?></td>
                                    <td class="px-6 py-4 text-sm font-bold text-slate-900"><?php echo htmlspecialchars($row['color_name']); ?></td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button onclick="openEditColorModal(<?php echo $row['color_id']; ?>, '<?php echo addslashes($row['color_name']); ?>')" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-[#0052CC]/10 text-slate-500 hover:text-[#0052CC] flex items-center justify-center transition-colors border border-slate-200" title="Edit">
                                                <span class="material-symbols-outlined text-[16px]">edit</span>
                                            </button>
                                            <button onclick="openDeleteColorModal(<?php echo $row['color_id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 hover:bg-red-50 text-slate-500 hover:text-red-600 flex items-center justify-center transition-colors border border-slate-200" title="Delete">
                                                <span class="material-symbols-outlined text-[16px]">delete</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php
                            }
                            if($i==0) echo '<tr><td colspan="3" class="px-6 py-8 text-center text-sm text-slate-500">No colors found. Click "Add Color" to create one.</td></tr>';
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</main>

<div id="addSizeModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300 modal-content">
        <h3 class="text-xl font-bold text-slate-900 mb-4">Add New Size</h3>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="text" name="size_name" class="w-full border border-slate-200 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] focus:ring-1 focus:ring-[#0052CC] mb-6" placeholder="e.g. XL, 15-inch, 256GB" required>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('addSizeModal')" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="add_size" class="flex-1 py-2.5 bg-[#0052CC] hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Save Size</button>
            </div>
        </form>
    </div>
</div>

<div id="editSizeModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300 modal-content">
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Size</h3>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="size_id" id="edit_size_id">
            <input type="text" name="size_name" id="edit_size_name" class="w-full border border-slate-200 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] focus:ring-1 focus:ring-[#0052CC] mb-6" required>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('editSizeModal')" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="edit_size" class="flex-1 py-2.5 bg-[#0052CC] hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Update Size</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteSizeModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300 modal-content">
        <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-2xl">warning</span></div>
        <h3 class="text-xl font-bold text-center text-slate-900 mb-2">Delete Size?</h3>
        <p class="text-xs text-slate-500 text-center mb-6">This action cannot be undone. You cannot delete a size if it is assigned to a product.</p>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="size_id" id="delete_size_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteSizeModal')" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="delete_size" class="flex-1 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Delete</button>
            </div>
        </form>
    </div>
</div>

<div id="addColorModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300 modal-content">
        <h3 class="text-xl font-bold text-slate-900 mb-4">Add New Color</h3>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="text" name="color_name" class="w-full border border-slate-200 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] focus:ring-1 focus:ring-[#0052CC] mb-6" placeholder="e.g. Space Gray, Midnight Black" required>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('addColorModal')" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="add_color" class="flex-1 py-2.5 bg-[#0052CC] hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Save Color</button>
            </div>
        </form>
    </div>
</div>

<div id="editColorModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300 modal-content">
        <h3 class="text-xl font-bold text-slate-900 mb-4">Edit Color</h3>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="color_id" id="edit_color_id">
            <input type="text" name="color_name" id="edit_color_name" class="w-full border border-slate-200 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] focus:ring-1 focus:ring-[#0052CC] mb-6" required>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('editColorModal')" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-sm transition-colors">Cancel</button>
                <button type="submit" name="edit_color" class="flex-1 py-2.5 bg-[#0052CC] hover:bg-blue-700 text-white rounded-lg font-bold text-sm transition-colors shadow-md">Update Color</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteColorModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-sm w-full mx-4 transform scale-95 transition-transform duration-300 modal-content">
        <div class="w-12 h-12 rounded-full bg-red-50 text-red-500 flex items-center justify-center mx-auto mb-4"><span class="material-symbols-outlined text-2xl">warning</span></div>
        <h3 class="text-xl font-bold text-center text-slate-900 mb-2">Delete Color?</h3>
        <p class="text-xs text-slate-500 text-center mb-6">This action cannot be undone. You cannot delete a color if it is assigned to a product.</p>
        <form action="" method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="color_id" id="delete_color_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteColorModal')" class="flex-1 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg font-bold text-sm transition-colors">Cancel</button>
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