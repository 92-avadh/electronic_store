<?php require_once('header.php'); ?>

<?php
if(isset($_POST['add_service'])) {
    $path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];
    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $final_name = 'service-'.time().'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

        $statement = $pdo->prepare("INSERT INTO tbl_service (title, content, photo) VALUES (?,?,?)");
        $statement->execute(array($_POST['title'], $_POST['content'], $final_name));
        $success_message = 'Service added successfully.';
    } else {
        $error_message = 'You must select a photo.';
    }
}

if(isset($_POST['edit_service'])) {
    $path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if($path == '') {
        $statement = $pdo->prepare("UPDATE tbl_service SET title=?, content=? WHERE id=?");
        $statement->execute(array($_POST['title'], $_POST['content'], $_POST['id']));
    } else {
        $statement = $pdo->prepare("SELECT photo FROM tbl_service WHERE id=?");
        $statement->execute(array($_POST['id']));
        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if($row['photo'] != '') { unlink('../assets/uploads/'.$row['photo']); }
        }
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        $final_name = 'service-'.time().'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

        $statement = $pdo->prepare("UPDATE tbl_service SET title=?, content=?, photo=? WHERE id=?");
        $statement->execute(array($_POST['title'], $_POST['content'], $final_name, $_POST['id']));
    }
    $success_message = 'Service updated successfully.';
}

if(isset($_POST['delete_service'])) {
    $statement = $pdo->prepare("SELECT photo FROM tbl_service WHERE id=?");
    $statement->execute(array($_POST['id']));
    foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
        if($row['photo'] != '') { unlink('../assets/uploads/'.$row['photo']); }
    }
    $statement = $pdo->prepare("DELETE FROM tbl_service WHERE id=?");
    $statement->execute(array($_POST['id']));
    $success_message = 'Service deleted successfully.';
}
?>

<main class="flex-grow p-6 md:p-8">
    <div class="max-w-[1400px] mx-auto">
        
        <div class="mb-8 flex justify-between items-end">
            <div>
                <span class="text-slate-500 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal</span>
                <h1 class="font-headline text-3xl font-extrabold text-slate-900 tracking-tight mt-1">Our Services</h1>
            </div>
            <button onclick="openModal('addServiceModal')" class="px-6 py-2.5 rounded-lg bg-[#0052CC] text-white font-headline text-sm font-bold shadow-md hover:bg-blue-700 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">add</span> Add Service
            </button>
        </div>

        <?php if($error_message): ?><div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-red-200"><?php echo $error_message; ?></div><?php endif; ?>
        <?php if($success_message): ?><div class="bg-green-50 text-green-600 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-green-200"><?php echo $success_message; ?></div><?php endif; ?>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 w-16">#</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 w-32">Icon/Photo</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Service Details</th>
                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <?php
                    $i=0;
                    $statement = $pdo->prepare("SELECT * FROM tbl_service ORDER BY id DESC");
                    $statement->execute();
                    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
                        $i++;
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-6 py-4 text-sm font-bold text-slate-400"><?php echo $i; ?></td>
                            <td class="px-6 py-4">
                                <div class="w-16 h-16 rounded-xl bg-slate-50 border border-slate-200 p-2 flex items-center justify-center">
                                    <img src="../assets/uploads/<?php echo $row['photo']; ?>" class="max-w-full max-h-full object-contain">
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold text-slate-900 mb-1"><?php echo htmlspecialchars($row['title']); ?></p>
                                <p class="text-xs text-slate-500 max-w-lg line-clamp-2"><?php echo htmlspecialchars($row['content']); ?></p>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <button onclick="openEditServiceModal(<?php echo $row['id']; ?>, '<?php echo addslashes($row['title']); ?>', '<?php echo addslashes($row['content']); ?>')" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-500 hover:text-[#0052CC] flex items-center justify-center border border-slate-200"><span class="material-symbols-outlined text-[16px]">edit</span></button>
                                    <button onclick="openDeleteServiceModal(<?php echo $row['id']; ?>)" class="w-8 h-8 rounded-lg bg-slate-50 text-slate-500 hover:text-red-600 flex items-center justify-center border border-slate-200"><span class="material-symbols-outlined text-[16px]">delete</span></button>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div id="addServiceModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 modal-content transform scale-95 transition-transform">
        <h3 class="text-xl font-bold text-slate-900 mb-6">Add New Service</h3>
        <form method="post" enctype="multipart/form-data">
            <?php $csrf->echoInputField(); ?>
            <div class="space-y-4 mb-6">
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 block mb-1">Service Title *</label>
                    <input type="text" name="title" class="w-full border border-slate-200 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#0052CC]" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 block mb-1">Description *</label>
                    <textarea name="content" class="w-full border border-slate-200 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#0052CC]" rows="4" required></textarea>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 block mb-1">Icon/Photo *</label>
                    <input type="file" name="photo" class="w-full text-xs border border-slate-200 p-2 rounded-lg bg-slate-50" required>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('addServiceModal')" class="flex-1 py-2.5 bg-slate-100 text-slate-700 rounded-lg font-bold text-sm">Cancel</button>
                <button type="submit" name="add_service" class="flex-1 py-2.5 bg-[#0052CC] text-white rounded-lg font-bold text-sm shadow-md">Save Service</button>
            </div>
        </form>
    </div>
</div>

<div id="editServiceModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 modal-content transform scale-95 transition-transform">
        <h3 class="text-xl font-bold text-slate-900 mb-6">Edit Service</h3>
        <form method="post" enctype="multipart/form-data">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="id" id="edit_service_id">
            <div class="space-y-4 mb-6">
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 block mb-1">Service Title *</label>
                    <input type="text" name="title" id="edit_service_title" class="w-full border border-slate-200 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#0052CC]" required>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 block mb-1">Description *</label>
                    <textarea name="content" id="edit_service_content" class="w-full border border-slate-200 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#0052CC]" rows="4" required></textarea>
                </div>
                <div>
                    <label class="text-[10px] font-bold uppercase text-slate-500 block mb-1">Replace Icon/Photo (Optional)</label>
                    <input type="file" name="photo" class="w-full text-xs border border-slate-200 p-2 rounded-lg bg-slate-50">
                </div>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('editServiceModal')" class="flex-1 py-2.5 bg-slate-100 text-slate-700 rounded-lg font-bold text-sm">Cancel</button>
                <button type="submit" name="edit_service" class="flex-1 py-2.5 bg-[#0052CC] text-white rounded-lg font-bold text-sm shadow-md">Update Service</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteServiceModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl p-6 max-w-sm w-full mx-4 modal-content transform scale-95 transition-transform">
        <h3 class="text-xl font-bold text-center text-slate-900 mb-2">Delete Service?</h3>
        <p class="text-xs text-slate-500 text-center mb-6">This will permanently remove the service and its image.</p>
        <form method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="id" id="delete_service_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteServiceModal')" class="flex-1 py-2.5 bg-slate-100 text-slate-700 rounded-lg font-bold text-sm">Cancel</button>
                <button type="submit" name="delete_service" class="flex-1 py-2.5 bg-red-600 text-white rounded-lg font-bold text-sm shadow-md">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openModal(id) { const m=document.getElementById(id); m.classList.remove('hidden'); void m.offsetWidth; m.classList.remove('opacity-0'); m.querySelector('.modal-content').classList.replace('scale-95','scale-100'); }
    function closeModal(id) { const m=document.getElementById(id); m.classList.add('opacity-0'); m.querySelector('.modal-content').classList.replace('scale-100','scale-95'); setTimeout(()=>m.classList.add('hidden'),300); }
    function openEditServiceModal(id, title, content) { document.getElementById('edit_service_id').value = id; document.getElementById('edit_service_title').value = title; document.getElementById('edit_service_content').value = content; openModal('editServiceModal'); }
    function openDeleteServiceModal(id) { document.getElementById('delete_service_id').value = id; openModal('deleteServiceModal'); }
</script>

<?php require_once('footer.php'); ?>