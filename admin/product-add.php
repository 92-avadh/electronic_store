<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
    $valid = 1;

    // Categorization validation has been completely removed.
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
            
            if(!empty($photo)) {
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
        }

        $final_name = 'product-featured-'.$ai_id.'.'.$ext;
        move_uploaded_file( $path_tmp, '../assets/uploads/'.$final_name );

        // INSERT Query modified to completely exclude categories (ecat_id)
        $is_featured = isset($_POST['p_is_featured']) ? 1 : 0;
        $is_active = isset($_POST['p_is_active']) ? 1 : 0;

        $statement = $pdo->prepare("INSERT INTO tbl_product(p_name, p_old_price, p_current_price, p_qty, p_featured_photo, p_description, p_short_description, p_feature, p_condition, p_return_policy, p_total_view, p_is_featured, p_is_active) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
        $statement->execute(array($_POST['p_name'], $_POST['p_old_price'], $_POST['p_current_price'], $_POST['p_qty'], $final_name, $_POST['p_description'], $_POST['p_short_description'], $_POST['p_feature'], $_POST['p_condition'], $_POST['p_return_policy'], 0, $is_featured, $is_active));

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

<main class="flex-grow p-6 md:p-8">
    <form action="" method="post" enctype="multipart/form-data" class="max-w-[1200px] mx-auto">
        <?php $csrf->echoInputField(); ?>
        
        <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
            <div>
                <span class="text-slate-500 font-label text-[10px] uppercase tracking-[0.2em] font-bold">Admin > Inventory > New</span>
                <h1 class="font-headline text-3xl font-extrabold tracking-tight mt-1 text-slate-900 dark:text-white">Add New Product</h1>
            </div>
            <div class="flex gap-4 w-full md:w-auto">
                <a href="product.php" class="px-6 py-2.5 rounded-lg text-[#0052CC] dark:text-[#4da3ff] text-sm font-bold border border-[#0052CC]/20 dark:border-[#4da3ff]/20 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all text-center">Cancel</a>
                <button type="submit" name="form1" class="px-8 py-2.5 rounded-lg bg-[#0052CC] text-white text-sm font-bold shadow-md hover:bg-blue-700 transition-all">Publish Product</button>
            </div>
        </div>

        <?php if(isset($error_message) && $error_message): ?>
            <div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-red-200 dark:border-red-500/20"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <?php if(isset($success_message) && $success_message): ?>
            <div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-4 py-3 rounded-lg mb-6 text-sm font-bold border border-green-200 dark:border-green-500/20 flex items-center justify-between">
                <span><?php echo $success_message; ?></span>
                <a href="product.php" class="bg-green-600 text-white px-4 py-1.5 rounded-md text-xs font-bold uppercase tracking-widest hover:bg-green-700 transition-colors">View All</a>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            
            <div class="lg:col-span-8 space-y-8">
                
                <section class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors duration-200">
                    <h2 class="text-lg font-bold mb-6 flex items-center gap-2 text-slate-900 dark:text-white"><span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">info</span> Product Details</h2>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Product Name *</label>
                            <input name="p_name" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-3 px-4 outline-none focus:border-[#0052CC]" type="text" required/>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Short Description</label>
                            <textarea name="p_short_description" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-3 px-4 outline-none focus:border-[#0052CC]" rows="3"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Full Description</label>
                            <textarea name="p_description" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-3 px-4 outline-none focus:border-[#0052CC]" id="editor1" rows="5"></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Features / Specifications</label>
                            <textarea name="p_feature" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-3 px-4 outline-none focus:border-[#0052CC]" id="editor2" rows="4"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Condition</label>
                                <textarea name="p_condition" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-3 px-4 outline-none focus:border-[#0052CC]" id="editor3" rows="3"></textarea>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Return Policy</label>
                                <textarea name="p_return_policy" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-3 px-4 outline-none focus:border-[#0052CC]" id="editor4" rows="3"></textarea>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="lg:col-span-4 space-y-8">
                
                <section class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors duration-200">
                    <h2 class="text-lg font-bold mb-4 flex items-center gap-2 text-slate-900 dark:text-white"><span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">image</span> Media Assets</h2>
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">Featured Image *</label>
                            <div class="relative group cursor-pointer border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-900 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <input type="file" name="p_featured_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                <div class="p-8 flex flex-col items-center justify-center text-center">
                                    <span class="material-symbols-outlined text-4xl text-[#0052CC] dark:text-[#4da3ff] mb-2 group-hover:scale-110 transition-transform">cloud_upload</span>
                                    <p class="text-sm font-bold text-slate-700 dark:text-slate-300">Upload Image</p>
                                </div>
                            </div>
                        </div>
                        <div class="border-t border-slate-100 dark:border-slate-700 pt-4">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400 block mb-2">Other Photos (Optional)</label>
                            <input type="button" id="btnAddNew" value="Add Photo Field" class="mb-3 px-4 py-2 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-bold uppercase tracking-widest rounded-lg cursor-pointer hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors w-full">
                            <table id="ProductTable" style="width:100%;">
                                <tbody>
                                    </tbody>
                            </table>
                        </div>
                    </div>
                </section>

                <section class="bg-white dark:bg-slate-800 p-6 rounded-xl border border-slate-200 dark:border-slate-700 shadow-sm transition-colors duration-200">
                    <h2 class="text-lg font-bold mb-6 flex items-center gap-2 text-slate-900 dark:text-white"><span class="material-symbols-outlined text-[#0052CC] dark:text-[#4da3ff]">payments</span> Pricing & Stock</h2>
                    <div class="space-y-6">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Old Price</label>
                                <input name="p_old_price" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-2.5 px-3 text-sm outline-none focus:border-[#0052CC]" type="number" step="0.01"/>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Current Price *</label>
                                <input name="p_current_price" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-2.5 px-3 text-sm font-bold outline-none focus:border-[#0052CC]" type="number" step="0.01" required/>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Stock Quantity *</label>
                            <input name="p_qty" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-3 px-4 text-sm outline-none focus:border-[#0052CC]" type="number" required/>
                        </div>
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700 space-y-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Select Sizes</label>
                                <select name="size[]" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-2 px-3 text-sm outline-none" multiple="multiple">
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM tbl_size ORDER BY size_id ASC");
                                    $statement->execute();
                                    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) { echo '<option value="'.$row['size_id'].'">'.$row['size_name'].'</option>'; }
                                    ?>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] font-bold uppercase tracking-wider text-slate-500 dark:text-slate-400">Select Colors</label>
                                <select name="color[]" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-600 text-slate-900 dark:text-white rounded-lg py-2 px-3 text-sm outline-none" multiple="multiple">
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM tbl_color ORDER BY color_id ASC");
                                    $statement->execute();
                                    foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $row) { echo '<option value="'.$row['color_id'].'">'.$row['color_name'].'</option>'; }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 dark:border-slate-700 space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input name="p_is_featured" value="1" class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-[#0052CC] focus:ring-[#0052CC]" type="checkbox"/>
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-[#0052CC] dark:group-hover:text-[#4da3ff]">Is Featured?</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input name="p_is_active" value="1" class="w-5 h-5 rounded border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-900 text-[#0052CC] focus:ring-[#0052CC]" type="checkbox" checked/>
                                <span class="text-sm font-semibold text-slate-700 dark:text-slate-300 group-hover:text-[#0052CC] dark:group-hover:text-[#4da3ff]">Is Active?</span>
                            </label>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </form>
</main>

<script>
    $(document).ready(function() {
        // Dynamic File Upload Field Adder
        $("#btnAddNew").click(function () {
            var row = '<tr><td><input type="file" name="photo[]" class="w-full text-xs mb-2 border border-slate-200 dark:border-slate-600 p-2 rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-900 dark:text-white"></td><td style="width:32px; padding-left:4px;"><button type="button" class="btnDelete w-8 h-8 flex items-center justify-center bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-200 dark:hover:bg-red-500/20 transition-colors mb-2"><span class="material-symbols-outlined text-[16px]">close</span></button></td></tr>';
            $("#ProductTable").append(row);
        });
        $("body").on("click", ".btnDelete", function () { $(this).closest("tr").remove(); });
    });
</script>

<?php require_once('footer.php'); ?>