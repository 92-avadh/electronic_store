<?php require_once('header.php'); ?>

<?php
// Initialize variables to prevent PHP errors
$tcat_id = ''; $mcat_id = ''; $ecat_id = '';
$p_name = ''; $p_old_price = ''; $p_current_price = ''; $p_qty = '';
$p_featured_photo = ''; $p_description = ''; $p_short_description = '';
$p_feature = ''; $p_condition = ''; $p_return_policy = '';
$p_is_featured = 0; $p_is_active = 0;

if(!isset($_REQUEST['id'])) { 
    header('location: product.php'); exit; 
} else {
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
    $statement->execute(array($_REQUEST['id']));
    if($statement->rowCount() == 0) { header('location: product.php'); exit; }
}

if(isset($_POST['form1'])) {
    $valid = 1;
    if(empty($_POST['tcat_id'])) { $valid = 0; $error_message .= "You must select a top level category<br>"; }
    if(empty($_POST['mcat_id'])) { $valid = 0; $error_message .= "You must select a mid level category<br>"; }
    if(empty($_POST['ecat_id'])) { $valid = 0; $error_message .= "You must select an end level category<br>"; }
    if(empty($_POST['p_name'])) { $valid = 0; $error_message .= "Product name can not be empty<br>"; }
    if(empty($_POST['p_current_price'])) { $valid = 0; $error_message .= "Current Price can not be empty<br>"; }
    if($_POST['p_qty'] == '') { $valid = 0; $error_message .= "Quantity can not be empty<br>"; }

    $path = $_FILES['p_featured_photo']['name'];
    $path_tmp = $_FILES['p_featured_photo']['tmp_name'];

    if($path!='') {
        $ext = pathinfo( $path, PATHINFO_EXTENSION );
        if( $ext!='jpg' && $ext!='png' && $ext!='jpeg' && $ext!='gif' ) {
            $valid = 0; $error_message .= 'You must upload a jpg, jpeg, gif or png file for the featured photo<br>';
        }
    }

    if($valid == 1) {
        $statement = $pdo->prepare("DELETE FROM tbl_product_size WHERE p_id=?");
        $statement->execute(array($_REQUEST['id']));
        if(isset($_POST['size'])) {
            foreach($_POST['size'] as $value) {
                $statement = $pdo->prepare("INSERT INTO tbl_product_size (size_id,p_id) VALUES (?,?)");
                $statement->execute(array($value,$_REQUEST['id']));
            }
        }

        $statement = $pdo->prepare("DELETE FROM tbl_product_color WHERE p_id=?");
        $statement->execute(array($_REQUEST['id']));
        if(isset($_POST['color'])) {
            foreach($_POST['color'] as $value) {
                $statement = $pdo->prepare("INSERT INTO tbl_product_color (color_id,p_id) VALUES (?,?)");
                $statement->execute(array($value,$_REQUEST['id']));
            }
        }

        $is_featured = isset($_POST['p_is_featured']) ? 1 : 0;
        $is_active = isset($_POST['p_is_active']) ? 1 : 0;
        
        // Safely capture condition and return policy
        $p_condition_post = isset($_POST['p_condition']) ? $_POST['p_condition'] : '';
        $p_return_policy_post = isset($_POST['p_return_policy']) ? $_POST['p_return_policy'] : '';

        if($path == '') {
            $statement = $pdo->prepare("UPDATE tbl_product SET p_name=?, p_old_price=?, p_current_price=?, p_qty=?, p_description=?, p_short_description=?, p_feature=?, p_condition=?, p_return_policy=?, p_is_featured=?, p_is_active=?, ecat_id=? WHERE p_id=?");
            $statement->execute(array($_POST['p_name'], $_POST['p_old_price'], $_POST['p_current_price'], $_POST['p_qty'], $_POST['p_description'], $_POST['p_short_description'], $_POST['p_feature'], $p_condition_post, $p_return_policy_post, $is_featured, $is_active, $_POST['ecat_id'], $_REQUEST['id']));
        } else {
            $statement = $pdo->prepare("SELECT p_featured_photo FROM tbl_product WHERE p_id=?");
            $statement->execute(array($_REQUEST['id']));
            foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $row) { $old_photo = $row['p_featured_photo']; }
            if($old_photo != '' && file_exists('../assets/uploads/'.$old_photo)) { unlink('../assets/uploads/'.$old_photo); }

            $ext = pathinfo($path, PATHINFO_EXTENSION);
            $final_name = 'product-featured-'.$_REQUEST['id'].'.'.$ext;
            move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

            $statement = $pdo->prepare("UPDATE tbl_product SET p_name=?, p_old_price=?, p_current_price=?, p_qty=?, p_featured_photo=?, p_description=?, p_short_description=?, p_feature=?, p_condition=?, p_return_policy=?, p_is_featured=?, p_is_active=?, ecat_id=? WHERE p_id=?");
            $statement->execute(array($_POST['p_name'], $_POST['p_old_price'], $_POST['p_current_price'], $_POST['p_qty'], $final_name, $_POST['p_description'], $_POST['p_short_description'], $_POST['p_feature'], $p_condition_post, $p_return_policy_post, $is_featured, $is_active, $_POST['ecat_id'], $_REQUEST['id']));
        }
        
        // Handle Additional Photos
        if( isset($_FILES['photo']["name"]) && isset($_FILES['photo']["tmp_name"]) ) {
            $photo = array_values(array_filter($_FILES['photo']["name"]));
            $photo_temp = array_values(array_filter($_FILES['photo']["tmp_name"]));

            if(!empty($photo)) {
                $statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product_photo'");
                $statement->execute();
                foreach($statement->fetchAll() as $row) { $next_id1=$row[10]; }
                $z = $next_id1; $m=0;

                for($i=0;$i<count($photo);$i++) {
                    $my_ext1 = pathinfo( $photo[$i], PATHINFO_EXTENSION );
                    if( $my_ext1=='jpg' || $my_ext1=='png' || $my_ext1=='jpeg' || $my_ext1=='gif' ) {
                        $final_name1[$m] = $z.'.'.$my_ext1;
                        move_uploaded_file($photo_temp[$i],"../assets/uploads/product_photos/".$final_name1[$m]);
                        $m++; $z++;
                    }
                }
                if(isset($final_name1)) {
                    for($i=0;$i<count($final_name1);$i++) {
                        $statement = $pdo->prepare("INSERT INTO tbl_product_photo (photo,p_id) VALUES (?,?)");
                        $statement->execute(array($final_name1[$i],$_REQUEST['id']));
                    }
                }
            }
        }
        $success_message = 'Product updated successfully.';
    }
}

// Fetch Current Data
$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
$statement->execute(array($_REQUEST['id']));
foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $p_name = $row['p_name']; $p_old_price = $row['p_old_price']; $p_current_price = $row['p_current_price'];
    $p_qty = $row['p_qty']; $p_featured_photo = $row['p_featured_photo']; $p_description = $row['p_description'];
    $p_short_description = $row['p_short_description']; $p_feature = $row['p_feature']; $p_condition = $row['p_condition'];
    $p_return_policy = $row['p_return_policy']; $p_is_featured = $row['p_is_featured']; $p_is_active = $row['p_is_active'];
    $ecat_id = $row['ecat_id'];
}

// Reverse Category Lookup
if($ecat_id != '') {
    $statement = $pdo->prepare("SELECT ec.mcat_id, mc.tcat_id FROM tbl_end_category ec JOIN tbl_mid_category mc ON ec.mcat_id = mc.mcat_id WHERE ec.ecat_id = ?");
    $statement->execute(array($ecat_id));
    foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $r) { $mcat_id = $r['mcat_id']; $tcat_id = $r['tcat_id']; }
}

$assigned_sizes = []; $assigned_colors = [];
$statement = $pdo->prepare("SELECT size_id FROM tbl_product_size WHERE p_id=?"); $statement->execute(array($_REQUEST['id']));
foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $r) { $assigned_sizes[] = $r['size_id']; }
$statement = $pdo->prepare("SELECT color_id FROM tbl_product_color WHERE p_id=?"); $statement->execute(array($_REQUEST['id']));
foreach($statement->fetchAll(PDO::FETCH_ASSOC) as $r) { $assigned_colors[] = $r['color_id']; }
?>

<main class="flex-grow p-6 md:p-8 transition-colors duration-200">
    <form action="" method="post" enctype="multipart/form-data" class="max-w-[1400px] mx-auto">
        <?php $csrf->echoInputField(); ?>
        
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <span class="text-slate-500 dark:text-slate-400 text-[10px] uppercase tracking-[0.2em] font-bold">Admin > Inventory > Edit</span>
                <h1 class="text-3xl font-extrabold tracking-tight mt-1 text-slate-900 dark:text-white">Edit: <?php echo htmlspecialchars($p_name); ?></h1>
            </div>
            <div class="flex gap-4 w-full md:w-auto">
                <a href="product.php" class="px-6 py-2.5 rounded-lg text-[#0052CC] dark:text-[#4da3ff] text-sm font-bold border border-[#0052CC]/20 dark:border-[#4da3ff]/30 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all text-center flex-1 md:flex-none">Discard Changes</a>
                <button type="submit" name="form1" class="px-8 py-2.5 rounded-lg bg-[#0052CC] dark:bg-indigo-600 hover:bg-blue-700 dark:hover:bg-indigo-500 text-white text-sm font-bold shadow-md transition-all flex-1 md:flex-none">Save Changes</button>
            </div>
        </div>

        <?php if($error_message): ?>
            <div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-red-200 dark:border-red-500/20"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if($success_message): ?>
            <div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-green-200 dark:border-green-500/20 flex justify-between items-center">
                <span><?php echo $success_message; ?></span>
                <a href="product.php" class="bg-green-600 text-white px-3 py-1.5 rounded text-[10px] font-bold uppercase tracking-widest hover:bg-green-700">Back to List</a>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <div class="lg:col-span-8 space-y-8">
                
                <section class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors duration-200">
                    <h2 class="text-lg font-bold mb-6 flex items-center gap-2 text-slate-900 dark:text-white"><span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">category</span> Categorization</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Top Category *</label>
                            <select name="tcat_id" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors">
                                <option value="">Select Top Category</option>
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_name ASC");
                                $stmt->execute();
                                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                                    $selected = ($row['tcat_id'] == $tcat_id) ? 'selected' : '';
                                    echo '<option value="'.$row['tcat_id'].'" '.$selected.'>'.$row['tcat_name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Mid Category *</label>
                            <select name="mcat_id" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors">
                                <option value="">Select Mid Category</option>
                                <?php
                                if($tcat_id != '') {
                                    $stmt = $pdo->prepare("SELECT * FROM tbl_mid_category WHERE tcat_id = ? ORDER BY mcat_name ASC");
                                    $stmt->execute(array($tcat_id));
                                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                                        $selected = ($row['mcat_id'] == $mcat_id) ? 'selected' : '';
                                        echo '<option value="'.$row['mcat_id'].'" '.$selected.'>'.$row['mcat_name'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">End Category *</label>
                            <select name="ecat_id" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors">
                                <option value="">Select End Category</option>
                                <?php
                                if($mcat_id != '') {
                                    $stmt = $pdo->prepare("SELECT * FROM tbl_end_category WHERE mcat_id = ? ORDER BY ecat_name ASC");
                                    $stmt->execute(array($mcat_id));
                                    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                                        $selected = ($row['ecat_id'] == $ecat_id) ? 'selected' : '';
                                        echo '<option value="'.$row['ecat_id'].'" '.$selected.'>'.$row['ecat_name'].'</option>';
                                    }
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </section>

                <section class="bg-white dark:bg-slate-800 p-6 md:p-8 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors duration-200">
                    <h2 class="text-lg font-bold mb-6 flex items-center gap-2 text-slate-900 dark:text-white"><span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">info</span> Product Information</h2>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Product Name *</label>
                            <input name="p_name" value="<?php echo htmlspecialchars($p_name); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors font-medium" type="text" required/>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Short Description</label>
                            <textarea name="p_short_description" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors" rows="2"><?php echo htmlspecialchars($p_short_description); ?></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Full Description</label>
                            <textarea name="p_description" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors" id="editor1" rows="5"><?php echo htmlspecialchars($p_description); ?></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Features / Specifications</label>
                            <textarea name="p_feature" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors" id="editor2" rows="4"><?php echo htmlspecialchars($p_feature); ?></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Product Condition</label>
                                <textarea name="p_condition" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors" id="editor3" rows="3"><?php echo htmlspecialchars($p_condition); ?></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Return Policy</label>
                                <textarea name="p_return_policy" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors" id="editor4" rows="3"><?php echo htmlspecialchars($p_return_policy); ?></textarea>
                            </div>
                        </div>
                        </div>
                </section>
            </div>

            <div class="lg:col-span-4 space-y-8">
                
                <section class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors duration-200">
                    <h2 class="text-lg font-bold mb-4 flex items-center gap-2 text-slate-900 dark:text-white"><span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">image</span> Media Assets</h2>
                    <div class="mb-4">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">Current Featured Image</label>
                        <div class="aspect-video w-full rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 flex items-center justify-center p-2">
                            <?php if($p_featured_photo == ''): ?>
                                <span class="text-slate-400 dark:text-slate-500 text-sm font-bold">No Image Uploaded</span>
                            <?php else: ?>
                                <img src="../assets/uploads/<?php echo $p_featured_photo; ?>" class="max-w-full max-h-full object-contain mix-blend-multiply dark:mix-blend-normal">
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">Replace Image (Optional)</label>
                        <div class="relative group cursor-pointer border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-900/50 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                            <input type="file" name="p_featured_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="p-6 flex flex-col items-center justify-center text-center">
                                <span class="material-symbols-outlined text-3xl text-[#0052CC] dark:text-[#4da3ff] mb-1">cloud_upload</span>
                                <p class="text-xs font-bold text-slate-600 dark:text-slate-400">Upload New Image</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors duration-200">
                    <h2 class="text-lg font-bold mb-6 flex items-center gap-2 text-slate-900 dark:text-white"><span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">payments</span> Pricing & Stock</h2>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Old Price</label>
                            <input name="p_old_price" value="<?php echo htmlspecialchars($p_old_price); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 px-3 text-sm outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors" type="text"/>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Current Price *</label>
                            <input name="p_current_price" value="<?php echo htmlspecialchars($p_current_price); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2.5 px-3 text-sm font-bold outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors" type="text" required/>
                        </div>
                    </div>
                    <div class="space-y-2 mb-6">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Stock Quantity *</label>
                        <input name="p_qty" value="<?php echo htmlspecialchars($p_qty); ?>" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-3 px-4 text-sm font-bold outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors" type="number" required/>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700 space-y-4 mb-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Sizes</label>
                            <select name="size[]" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors h-24" multiple="multiple">
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM tbl_size ORDER BY size_id ASC");
                                $stmt->execute();
                                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                                    $selected = in_array($row['size_id'], $assigned_sizes) ? 'selected' : '';
                                    echo '<option value="'.$row['size_id'].'" '.$selected.'>'.$row['size_name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Colors</label>
                            <select name="color[]" class="w-full bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-lg py-2 px-3 text-sm outline-none focus:border-[#0052CC] dark:focus:border-[#4da3ff] transition-colors h-24" multiple="multiple">
                                <?php
                                $stmt = $pdo->prepare("SELECT * FROM tbl_color ORDER BY color_id ASC");
                                $stmt->execute();
                                foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
                                    $selected = in_array($row['color_id'], $assigned_colors) ? 'selected' : '';
                                    echo '<option value="'.$row['color_id'].'" '.$selected.'>'.$row['color_name'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100 dark:border-slate-700 space-y-3">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="p_is_featured" value="1" class="w-5 h-5 rounded text-[#0052CC] border-slate-300 dark:border-slate-600 dark:bg-slate-900 focus:ring-[#0052CC]" type="checkbox" <?php if($p_is_featured == 1) echo 'checked'; ?>/>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-[#0052CC] dark:group-hover:text-[#4da3ff]">Featured on Homepage?</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <input name="p_is_active" value="1" class="w-5 h-5 rounded text-[#0052CC] border-slate-300 dark:border-slate-600 dark:bg-slate-900 focus:ring-[#0052CC]" type="checkbox" <?php if($p_is_active == 1) echo 'checked'; ?>/>
                            <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-[#0052CC] dark:group-hover:text-[#4da3ff]">Active (Visible in Store)?</span>
                        </label>
                    </div>
                </section>
            </div>
        </div>
    </form>
</main>

<script>
    $(document).ready(function() {
        $("select[name='tcat_id']").on('change', function() {
            var tcat_id = $(this).val();
            if (tcat_id) {
                $.ajax({ type: 'POST', url: 'get-mid-category.php', data: 'id='+tcat_id, success: function(html) { $("select[name='mcat_id']").html(html); $("select[name='ecat_id']").html('<option value="">Select End Category</option>'); }}); 
            } else {
                $("select[name='mcat_id']").html('<option value="">Select Mid Category</option>'); $("select[name='ecat_id']").html('<option value="">Select End Category</option>');
            }
        });
        $("select[name='mcat_id']").on('change', function() {
            var mcat_id = $(this).val();
            if (mcat_id) {
                $.ajax({ type: 'POST', url: 'get-end-category.php', data: 'id='+mcat_id, success: function(html) { $("select[name='ecat_id']").html(html); }}); 
            } else { $("select[name='ecat_id']").html('<option value="">Select End Category</option>'); }
        });
    });
</script>

<?php require_once('footer.php'); ?>