<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();

if(!isset($_SESSION['user'])) { header('location: login.php'); exit; }

$error_message = ''; $success_message = '';

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
            $valid = 0; $error_message .= 'You must upload jpg, jpeg, gif or png file for featured photo<br>';
        }
    } else {
        $valid = 0; $error_message .= 'You must select a featured photo<br>';
    }

    if($valid == 1) {
        $statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product'");
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as $row) { $ai_id=$row[10]; }

        if( isset($_FILES['photo']["name"]) && isset($_FILES['photo']["tmp_name"]) ) {
            $photo = array_values(array_filter($_FILES['photo']["name"]));
            $photo_temp = array_values(array_filter($_FILES['photo']["tmp_name"]));

            $statement = $pdo->prepare("SHOW TABLE STATUS LIKE 'tbl_product_photo'");
            $statement->execute();
            $result = $statement->fetchAll();
            foreach($result as $row) { $next_id1=$row[10]; }
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
                    $statement->execute(array($final_name1[$i],$ai_id));
                }
            }            
        }

        $final_name = 'product-featured-'.$ai_id.'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

        $statement = $pdo->prepare("INSERT INTO tbl_product(p_name, p_old_price, p_current_price, p_qty, p_featured_photo, p_description, p_short_description, p_feature, p_condition, p_return_policy, p_total_view, p_is_featured, p_is_active, ecat_id) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array($_POST['p_name'], $_POST['p_old_price'], $_POST['p_current_price'], $_POST['p_qty'], $final_name, $_POST['p_description'], $_POST['p_short_description'], $_POST['p_feature'], $_POST['p_condition'], $_POST['p_return_policy'], 0, $_POST['p_is_featured'], $_POST['p_is_active'], $_POST['ecat_id']));

        if(isset($_POST['size'])) {
            foreach($_POST['size'] as $value) {
                $statement = $pdo->prepare("INSERT INTO tbl_product_size (size_id,p_id) VALUES (?,?)");
                $statement->execute(array($value,$ai_id));
            }
        }
        if(isset($_POST['color'])) {
            foreach($_POST['color'] as $value) {
                $statement = $pdo->prepare("INSERT INTO tbl_product_color (color_id,p_id) VALUES (?,?)");
                $statement->execute(array($value,$ai_id));
            }
        }
        $success_message = 'Product is added successfully.';
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Add New Product | Silicon Slate Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { background-color: #faf8ff; color: #131b2e; font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        .cke_chrome { border: 1px solid #c3c6d6 !important; border-radius: 0.5rem !important; overflow: hidden; }
    </style>
</head>
<body class="antialiased">
    
    <?php require_once('sidebar.php'); ?>

    <div class="lg:ml-64 min-h-screen flex flex-col">
        
        <header class="w-full sticky top-0 z-40 bg-[#faf8ff]/70 backdrop-blur-2xl flex items-center justify-between px-8 h-20 shadow-[0px_20px_40px_rgba(19,27,46,0.06)]">
            <h1 class="font-bold tracking-tight text-xl">Inventory Management</h1>
            <div class="flex items-center gap-4">
                <a href="../index.php" target="_blank" class="p-2 text-[#0052CC] bg-[#0052CC]/10 hover:bg-[#0052CC]/20 rounded-full transition-all flex items-center gap-2 px-4">
                    <span class="material-symbols-outlined text-sm">storefront</span>
                    <span class="text-xs font-bold uppercase tracking-widest hidden md:inline">View Store</span>
                </a>
            </div>
        </header>

        <main class="flex-grow p-6 md:p-8">
            <form action="" method="post" enctype="multipart/form-data" class="max-w-[1200px] mx-auto">
                <?php $csrf->echoInputField(); ?>
                
                <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                    <div>
                        <span class="text-slate-500 text-[10px] uppercase tracking-[0.2em] font-bold">Admin > Inventory > New</span>
                        <h1 class="text-3xl font-extrabold tracking-tight mt-1">Add New Product</h1>
                    </div>
                    <div class="flex gap-4 w-full md:w-auto">
                        <a href="product.php" class="px-6 py-2.5 rounded-lg text-[#0052CC] text-sm font-bold border border-[#0052CC]/20 hover:bg-slate-100 transition-all text-center">Cancel</a>
                        <button type="submit" name="form1" class="px-8 py-2.5 rounded-lg bg-[#0052CC] text-white text-sm font-bold shadow-md hover:bg-blue-700 transition-all">Publish Product</button>
                    </div>
                </div>

                <?php if($error_message): ?>
                    <div class="bg-red-50 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-red-200"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <?php if($success_message): ?>
                    <div class="bg-green-50 text-green-600 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-green-200 flex items-center justify-between">
                        <span><?php echo $success_message; ?></span>
                        <a href="product.php" class="bg-green-600 text-white px-4 py-1.5 rounded-md text-xs font-bold uppercase tracking-widest hover:bg-green-700 transition-colors">View All</a>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <div class="lg:col-span-8 space-y-8">
                        
                        <section class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
                            <h2 class="text-lg font-bold mb-6 flex items-center gap-2"><span class="material-symbols-outlined text-[#0052CC]">category</span> Categorization</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Top Category *</label>
                                    <select name="tcat_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm outline-none cursor-pointer">
                                        <option value="">Select Top Category</option>
                                        <?php
                                        $statement = $pdo->prepare("SELECT * FROM tbl_top_category ORDER BY tcat_name ASC");
                                        $statement->execute();
                                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result as $row) { echo '<option value="'.$row['tcat_id'].'">'.$row['tcat_name'].'</option>'; }
                                        ?>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Mid Category *</label>
                                    <select name="mcat_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm outline-none cursor-pointer">
                                        <option value="">Select Mid Category</option>
                                    </select>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">End Category *</label>
                                    <select name="ecat_id" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm outline-none cursor-pointer">
                                        <option value="">Select End Category</option>
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
                            <h2 class="text-lg font-bold mb-6 flex items-center gap-2"><span class="material-symbols-outlined text-[#0052CC]">info</span> Product Details</h2>
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Product Name *</label>
                                    <input name="p_name" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 outline-none" type="text" required/>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Short Description</label>
                                    <textarea name="p_short_description" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 outline-none" rows="3"></textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Full Description</label>
                                    <textarea name="p_description" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 outline-none" id="editor1" rows="5"></textarea>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Features / Specifications</label>
                                    <textarea name="p_feature" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 outline-none" id="editor2" rows="4"></textarea>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Condition</label>
                                        <textarea name="p_condition" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 outline-none" id="editor3" rows="3"></textarea>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-xs font-bold uppercase tracking-wider text-slate-500">Return Policy</label>
                                        <textarea name="p_return_policy" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 outline-none" id="editor4" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="lg:col-span-4 space-y-8">
                        
                        <section class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
                            <h2 class="text-lg font-bold mb-4 flex items-center gap-2"><span class="material-symbols-outlined text-[#0052CC]">image</span> Media Assets</h2>
                            <div class="space-y-6">
                                <div>
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-2">Featured Image *</label>
                                    <div class="relative group cursor-pointer border-2 border-dashed border-slate-300 rounded-xl bg-slate-50 hover:bg-slate-100 transition-colors">
                                        <input type="file" name="p_featured_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <div class="p-8 flex flex-col items-center justify-center text-center">
                                            <span class="material-symbols-outlined text-4xl text-[#0052CC] mb-2 group-hover:scale-110 transition-transform">cloud_upload</span>
                                            <p class="text-sm font-bold">Upload Image</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 block mb-2">Other Photos (Optional)</label>
                                    <input type="button" id="btnAddNew" value="Add Item" class="mb-3 px-4 py-2 bg-slate-200 text-slate-700 text-xs font-bold uppercase tracking-widest rounded-lg cursor-pointer w-full">
                                    <table id="ProductTable" style="width:100%;">
                                        <tbody>
                                            <tr>
                                                <td><input type="file" name="photo[]" class="w-full text-xs mb-2 border border-slate-200 p-2 rounded"></td>
                                                <td style="width:28px;"><input type="button" value="X" class="btnDelete px-2 py-1 bg-red-100 text-red-600 rounded font-bold text-xs cursor-pointer ml-1 mb-2"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </section>

                        <section class="bg-white p-6 rounded-xl border border-slate-100 shadow-sm">
                            <h2 class="text-lg font-bold mb-6 flex items-center gap-2"><span class="material-symbols-outlined text-[#0052CC]">payments</span> Pricing & Stock</h2>
                            <div class="space-y-6">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Old Price</label>
                                        <input name="p_old_price" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-3 text-sm outline-none" type="text"/>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Current Price *</label>
                                        <input name="p_current_price" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2.5 px-3 text-sm font-bold outline-none" type="text" required/>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Stock Quantity *</label>
                                    <input name="p_qty" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-3 px-4 text-sm outline-none" type="number" required/>
                                </div>
                                <div class="pt-4 border-t border-slate-100 space-y-4">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Select Sizes</label>
                                        <select name="size[]" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 px-3 text-sm outline-none" multiple="multiple">
                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM tbl_size ORDER BY size_id ASC");
                                            $statement->execute();
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) { echo '<option value="'.$row['size_id'].'">'.$row['size_name'].'</option>'; }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500">Select Colors</label>
                                        <select name="color[]" class="w-full bg-slate-50 border border-slate-200 rounded-lg py-2 px-3 text-sm outline-none" multiple="multiple">
                                            <?php
                                            $statement = $pdo->prepare("SELECT * FROM tbl_color ORDER BY color_id ASC");
                                            $statement->execute();
                                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($result as $row) { echo '<option value="'.$row['color_id'].'">'.$row['color_name'].'</option>'; }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="pt-4 border-t border-slate-100 space-y-3">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input name="p_is_featured" value="1" class="w-5 h-5 rounded text-[#0052CC]" type="checkbox"/>
                                        <span class="text-sm font-semibold">Is Featured?</span>
                                    </label>
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input name="p_is_active" value="1" class="w-5 h-5 rounded text-[#0052CC]" type="checkbox" checked/>
                                        <span class="text-sm font-semibold">Is Active?</span>
                                    </label>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </form>
        </main>
    </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        var id = 1;
        $("#btnAddNew").click(function () {
            id++;
            var row = '<tr><td><input type="file" name="photo[]" class="w-full text-xs mb-2 border border-slate-200 p-2 rounded"></td><td style="width:28px;"><input type="button" value="X" class="btnDelete px-2 py-1 bg-red-100 text-red-600 rounded font-bold text-xs cursor-pointer ml-1 mb-2"></td></tr>';
            $("#ProductTable").append(row);
        });
        $("body").on("click", ".btnDelete", function () { if (confirm("Are you sure want to delete?")) { $(this).closest("tr").remove(); } });
    });
</script>
</body>
</html>