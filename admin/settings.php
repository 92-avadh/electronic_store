<?php require_once('header.php'); ?>

<?php
$error_message = '';
$success_message = '';

// ==========================================
// 1. HANDLE PROFILE UPDATES
// ==========================================
if(isset($_POST['update_profile'])) {
    $valid = 1;
    $path = $_FILES['photo']['name'];
    $path_tmp = $_FILES['photo']['tmp_name'];

    if(empty($_POST['full_name'])) { $valid = 0; $error_message .= "Name can not be empty<br>"; }
    if(empty($_POST['email'])) { $valid = 0; $error_message .= "Email can not be empty<br>"; }

    if($valid == 1) {
        // Handle Password Change
        if(!empty($_POST['password'])) {
            $statement = $pdo->prepare("UPDATE tbl_user SET password=? WHERE id=?");
            $statement->execute(array(md5($_POST['password']), $_SESSION['user']['id']));
            $_SESSION['user']['password'] = md5($_POST['password']);
        }
        
        // Handle Avatar Update
        if($path != '') {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if($ext=='jpg' || $ext=='png' || $ext=='jpeg' || $ext=='gif') {
                if($_SESSION['user']['photo'] != '') { unlink('../assets/uploads/'.$_SESSION['user']['photo']); }
                $final_name = 'user-'.$_SESSION['user']['id'].'.'.$ext;
                move_uploaded_file($path_tmp, '../assets/uploads/'.$final_name);
                $_SESSION['user']['photo'] = $final_name;
                
                $statement = $pdo->prepare("UPDATE tbl_user SET photo=? WHERE id=?");
                $statement->execute(array($final_name, $_SESSION['user']['id']));
            }
        }
        
        // Update basic info
        $statement = $pdo->prepare("UPDATE tbl_user SET full_name=?, email=?, phone=? WHERE id=?");
        $statement->execute(array($_POST['full_name'], $_POST['email'], $_POST['phone'], $_SESSION['user']['id']));
        
        $_SESSION['user']['full_name'] = $_POST['full_name'];
        $_SESSION['user']['email'] = $_POST['email'];
        $_SESSION['user']['phone'] = $_POST['phone'];
        
        $success_message = 'Profile updated successfully.';
    }
}

// ==========================================
// 2. HANDLE SYSTEM ADMINS
// ==========================================
if(isset($_POST['add_admin'])) {
    $stmt = $pdo->prepare("SELECT * FROM tbl_user WHERE email=?");
    $stmt->execute([$_POST['email']]);
    if($stmt->rowCount() > 0) {
        $error_message = "Email already exists!";
    } else {
        $statement = $pdo->prepare("INSERT INTO tbl_user (full_name, email, phone, password, photo, role, status) VALUES (?,?,?,?,'','Super Admin','Active')");
        $statement->execute(array($_POST['full_name'], $_POST['email'], $_POST['phone'], md5($_POST['password'])));
        $success_message = 'New admin added successfully.';
    }
}

if(isset($_POST['delete_admin'])) {
    if($_POST['admin_id'] == $_SESSION['user']['id']) {
        $error_message = "You cannot delete yourself!";
    } else {
        $statement = $pdo->prepare("SELECT photo FROM tbl_user WHERE id=?");
        $statement->execute([$_POST['admin_id']]);
        foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
            if($row['photo'] != '') { unlink('../assets/uploads/'.$row['photo']); }
        }
        $statement = $pdo->prepare("DELETE FROM tbl_user WHERE id=?");
        $statement->execute([$_POST['admin_id']]);
        $success_message = 'Admin deleted successfully.';
    }
}
?>

<main class="flex-grow p-6 md:p-8">
    <div class="max-w-[1400px] mx-auto">
        
        <div class="mb-8">
            <span class="text-slate-500 dark:text-slate-400 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin Portal</span>
            <h1 class="font-headline text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Global Settings</h1>
        </div>

        <?php if($error_message): ?>
            <div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-red-200 dark:border-red-500/20"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if($success_message): ?>
            <div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-green-200 dark:border-green-500/20"><?php echo $success_message; ?></div>
        <?php endif; ?>

        <div class="flex flex-col lg:flex-row gap-8 items-start">
            
            <div class="w-full lg:w-64 flex-shrink-0 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-2 sticky top-28 transition-colors duration-200">
                <button onclick="switchTab('profile')" id="btn-profile" class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-all mb-1 bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff]">
                    <span class="material-symbols-outlined align-middle mr-2 text-[18px]">manage_accounts</span> My Profile
                </button>
                <button onclick="switchTab('admins')" id="btn-admins" class="w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-all text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50">
                    <span class="material-symbols-outlined align-middle mr-2 text-[18px]">admin_panel_settings</span> System Admins
                </button>
            </div>

            <div class="flex-grow w-full">
                
                <div id="tab-profile" class="tab-content bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-6 md:p-8 transition-colors duration-200">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-white mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">manage_accounts</span> Update Profile
                    </h2>
                    <form method="post" enctype="multipart/form-data" class="space-y-6">
                        <?php $csrf->echoInputField(); ?>
                        <div class="flex items-center gap-6 mb-6">
                            <div class="w-24 h-24 rounded-full bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 overflow-hidden flex-shrink-0">
                                <?php if($_SESSION['user']['photo'] == ''): ?>
                                    <div class="w-full h-full flex items-center justify-center text-3xl font-bold text-slate-400 dark:text-slate-500"><?php echo substr($_SESSION['user']['full_name'], 0, 1); ?></div>
                                <?php else: ?>
                                    <img src="../assets/uploads/<?php echo $_SESSION['user']['photo']; ?>" class="w-full h-full object-cover">
                                <?php endif; ?>
                            </div>
                            <div class="flex-grow">
                                <label class="text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400 block mb-1">Update Avatar</label>
                                <input type="file" name="photo" class="w-full text-xs bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 p-2 rounded-lg">
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400 block mb-1">Full Name *</label>
                                <input type="text" name="full_name" value="<?php echo htmlspecialchars($_SESSION['user']['full_name']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 px-3 text-sm outline-none focus:border-[#0052CC]" required>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400 block mb-1">Email Address *</label>
                                <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['user']['email']); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 px-3 text-sm outline-none focus:border-[#0052CC]" required>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400 block mb-1">Phone Number</label>
                                <input type="text" name="phone" value="<?php echo htmlspecialchars($_SESSION['user']['phone'] ?? ''); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 px-3 text-sm outline-none focus:border-[#0052CC]">
                            </div>
                            <div>
                                <label class="text-[10px] font-bold uppercase text-slate-500 dark:text-slate-400 block mb-1">New Password (Leave blank to keep current)</label>
                                <input type="password" name="password" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 px-3 text-sm outline-none focus:border-[#0052CC]">
                            </div>
                        </div>
                        <button type="submit" name="update_profile" class="px-6 py-2.5 bg-[#0052CC] text-white rounded-lg font-bold text-sm shadow-md mt-4 hover:bg-blue-700 transition-colors">Save Profile</button>
                    </form>
                </div>

                <div id="tab-admins" class="tab-content hidden bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden transition-colors duration-200">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-700 flex justify-between items-center bg-slate-50/50 dark:bg-slate-900/50">
                        <h2 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2">
                            <span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">admin_panel_settings</span> System Administrators
                        </h2>
                        <button onclick="openModal('addAdminModal')" class="px-4 py-2 rounded-lg bg-[#0052CC] text-white text-xs font-bold uppercase tracking-widest shadow-sm hover:bg-blue-700 flex items-center gap-1 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">add</span> Add Admin
                        </button>
                    </div>
                    <div class="overflow-x-auto table-scroll">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <tr>
                                    <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Name & Email</th>
                                    <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Phone</th>
                                    <th class="px-6 py-3 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_user ORDER BY id ASC");
                                $statement->execute();
                                foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
                                    ?>
                                    <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-slate-900 dark:text-white">
                                                <?php echo htmlspecialchars($row['full_name']); ?> 
                                                <?php if($row['id'] == $_SESSION['user']['id']) echo '<span class="text-[10px] bg-blue-100 dark:bg-[#0052CC]/20 text-blue-600 dark:text-[#4da3ff] px-2 py-0.5 rounded ml-2 uppercase tracking-widest">You</span>'; ?>
                                            </p>
                                            <p class="text-xs text-slate-500 dark:text-slate-400"><?php echo htmlspecialchars($row['email']); ?></p>
                                        </td>
                                        <td class="px-6 py-4 text-sm font-medium text-slate-700 dark:text-slate-300">
                                            <?php echo htmlspecialchars($row['phone'] ?? '-'); ?>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <?php if($row['id'] != $_SESSION['user']['id']): ?>
                                                <button onclick="openDeleteAdminModal(<?php echo $row['id']; ?>)" class="w-8 h-8 inline-flex rounded-lg bg-slate-50 dark:bg-slate-700 text-slate-500 dark:text-slate-300 hover:text-red-600 dark:hover:text-red-400 items-center justify-center border border-slate-200 dark:border-slate-600 transition-colors">
                                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-xs text-slate-400 dark:text-slate-500 italic">No Actions</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<div id="addAdminModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-sm w-full mx-4 modal-content transform scale-95 transition-transform border border-slate-200 dark:border-slate-700">
        <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-4">Add New Admin</h3>
        <form method="post">
            <?php $csrf->echoInputField(); ?>
            <div class="space-y-3 mb-6">
                <input type="text" name="full_name" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#0052CC]" placeholder="Full Name" required>
                <input type="email" name="email" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#0052CC]" placeholder="Email Address" required>
                <input type="text" name="phone" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#0052CC]" placeholder="Phone Number">
                <input type="password" name="password" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#0052CC]" placeholder="Secure Password" required>
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('addAdminModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" name="add_admin" class="flex-1 py-2.5 bg-[#0052CC] text-white rounded-lg font-bold text-sm shadow-md hover:bg-blue-700 transition-colors">Create Admin</button>
            </div>
        </form>
    </div>
</div>

<div id="deleteAdminModal" class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300">
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 max-w-sm w-full mx-4 modal-content transform scale-95 transition-transform border border-slate-200 dark:border-slate-700">
        <h3 class="text-xl font-bold text-center text-slate-900 dark:text-white mb-2">Revoke Admin Access?</h3>
        <p class="text-xs text-slate-500 dark:text-slate-400 text-center mb-6">This will permanently delete this administrator's account.</p>
        <form method="post">
            <?php $csrf->echoInputField(); ?>
            <input type="hidden" name="admin_id" id="delete_admin_id">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal('deleteAdminModal')" class="flex-1 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 rounded-lg font-bold text-sm hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                <button type="submit" name="delete_admin" class="flex-1 py-2.5 bg-red-600 text-white rounded-lg font-bold text-sm shadow-md hover:bg-red-700 transition-colors">Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(tab => tab.classList.add('hidden'));
        document.querySelectorAll('[id^="btn-"]').forEach(btn => { 
            btn.className = "w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-all mb-1 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50"; 
        });
        
        document.getElementById('tab-' + tabId).classList.remove('hidden');
        document.getElementById('btn-' + tabId).className = "w-full text-left px-4 py-3 rounded-xl text-sm font-bold transition-all mb-1 bg-[#0052CC]/10 dark:bg-[#0052CC]/20 text-[#0052CC] dark:text-[#4da3ff]";
    }

    function openModal(id) { 
        const m = document.getElementById(id); 
        m.classList.remove('hidden'); 
        void m.offsetWidth; 
        m.classList.remove('opacity-0'); 
        m.querySelector('.modal-content').classList.replace('scale-95','scale-100'); 
    }
    
    function closeModal(id) { 
        const m = document.getElementById(id); 
        m.classList.add('opacity-0'); 
        m.querySelector('.modal-content').classList.replace('scale-100','scale-95'); 
        setTimeout(() => m.classList.add('hidden'), 300); 
    }
    
    function openDeleteAdminModal(id) { 
        document.getElementById('delete_admin_id').value = id; 
        openModal('deleteAdminModal'); 
    }
</script>

<?php require_once('footer.php'); ?>