<?php require_once('header.php'); ?>

<?php
if(!isset($_REQUEST['id'])) {
    header('location: index.php');
    exit;
} else {
    // Check if the product exists and is active
    $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=? AND p_is_active=1");
    $statement->execute(array($_REQUEST['id']));
    $total = $statement->rowCount();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    if( $total == 0 ) {
        header('location: index.php');
        exit;
    }
}

foreach($result as $row) {
    $p_name = $row['p_name'];
    $p_old_price = $row['p_old_price'];
    $p_current_price = $row['p_current_price'];
    $p_qty = $row['p_qty'];
    $p_featured_photo = $row['p_featured_photo'];
    $p_description = $row['p_description'];
    $p_short_description = $row['p_short_description'];
    $p_feature = $row['p_feature'];
    $p_condition = $row['p_condition'];
    $p_return_policy = $row['p_return_policy'];
    $p_total_view = $row['p_total_view'];
    $p_is_featured = $row['p_is_featured'];
    $p_is_active = $row['p_is_active'];
    $ecat_id = $row['ecat_id'];
}

// Update View Count
$p_total_view = $p_total_view + 1;
$statement = $pdo->prepare("UPDATE tbl_product SET p_total_view=? WHERE p_id=?");
$statement->execute(array($p_total_view,$_REQUEST['id']));

// Fetch Category Name for Breadcrumbs
$statement = $pdo->prepare("SELECT ecat_name FROM tbl_end_category WHERE ecat_id=?");
$statement->execute(array($ecat_id));
$res = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach($res as $r) { $ecat_name = $r['ecat_name']; }


// ADD TO CART LOGIC
$error_message = '';
$success_message = '';

if(isset($_POST['form_add_to_cart'])) {
    if(empty($_POST['p_qty'])) {
        $error_message .= 'Please enter a valid quantity.<br>';
    } else {
        $qty = $_POST['p_qty'];
        if($qty > $p_qty) {
            $error_message .= 'Sorry! There are only '.$p_qty.' items in stock.<br>';
        }
    }

    $size_id = isset($_POST['size_id']) ? $_POST['size_id'] : 0;
    $color_id = isset($_POST['color_id']) ? $_POST['color_id'] : 0;
    
    $size_name = ''; $color_name = '';
    if($size_id != 0) {
        $statement = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
        $statement->execute(array($size_id));
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($res as $r) { $size_name = $r['size_name']; }
    }
    if($color_id != 0) {
        $statement = $pdo->prepare("SELECT * FROM tbl_color WHERE color_id=?");
        $statement->execute(array($color_id));
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        foreach($res as $r) { $color_name = $r['color_name']; }
    }

    if($error_message == '') {
        if(isset($_SESSION['cart_p_id'])) {
            $arr_cart_p_id = array(); $arr_cart_size_id = array(); $arr_cart_color_id = array(); $arr_cart_p_qty = array();

            $i=0; foreach($_SESSION['cart_p_id'] as $key => $value) { $i++; $arr_cart_p_id[$i] = $value; }
            $i=0; foreach($_SESSION['cart_size_id'] as $key => $value) { $i++; $arr_cart_size_id[$i] = $value; }
            $i=0; foreach($_SESSION['cart_color_id'] as $key => $value) { $i++; $arr_cart_color_id[$i] = $value; }
            $i=0; foreach($_SESSION['cart_p_qty'] as $key => $value) { $i++; $arr_cart_p_qty[$i] = $value; }

            $added = 0;
            for($i=1; $i<=count($arr_cart_p_id); $i++) {
                if( ($arr_cart_p_id[$i]==$_REQUEST['id']) && ($arr_cart_size_id[$i]==$size_id) && ($arr_cart_color_id[$i]==$color_id) ) {
                    $added = 1; break;
                }
            }
            if($added == 1) {
                $error_message .= 'This product is already added to the shopping cart.<br>';
            } else {
                $new_key = count($arr_cart_p_id) + 1;
                $_SESSION['cart_p_id'][$new_key] = $_REQUEST['id'];
                $_SESSION['cart_size_id'][$new_key] = $size_id;
                $_SESSION['cart_size_name'][$new_key] = $size_name;
                $_SESSION['cart_color_id'][$new_key] = $color_id;
                $_SESSION['cart_color_name'][$new_key] = $color_name;
                $_SESSION['cart_p_qty'][$new_key] = $qty;
                $_SESSION['cart_p_current_price'][$new_key] = $p_current_price;
                $_SESSION['cart_p_name'][$new_key] = $p_name;
                $_SESSION['cart_p_featured_photo'][$new_key] = $p_featured_photo;

                $success_message = 'Product is added to the cart successfully!';
            }
        } else {
            $_SESSION['cart_p_id'][1] = $_REQUEST['id'];
            $_SESSION['cart_size_id'][1] = $size_id;
            $_SESSION['cart_size_name'][1] = $size_name;
            $_SESSION['cart_color_id'][1] = $color_id;
            $_SESSION['cart_color_name'][1] = $color_name;
            $_SESSION['cart_p_qty'][1] = $qty;
            $_SESSION['cart_p_current_price'][1] = $p_current_price;
            $_SESSION['cart_p_name'][1] = $p_name;
            $_SESSION['cart_p_featured_photo'][1] = $p_featured_photo;

            $success_message = 'Product is added to the cart successfully!';
        }
    }
}
?>

<div class="pt-20 min-h-screen bg-surface dark:bg-slate-900 transition-colors duration-300">
    <main>
        
        <div class="max-w-[1440px] mx-auto px-6 md:px-12 pt-8" data-aos="fade-in">
            <div class="flex items-center gap-2 text-[10px] font-bold text-textMuted dark:text-slate-500 uppercase tracking-widest mb-6">
                <a href="index.php" class="hover:text-primary dark:hover:text-indigo-400 transition-colors">Home</a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <a href="product-category.php" class="hover:text-primary dark:hover:text-indigo-400 transition-colors"><?php echo $ecat_name; ?></a>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-surfaceDark dark:text-white truncate max-w-[200px]"><?php echo $p_name; ?></span>
            </div>

            <?php if($error_message != ''): ?>
                <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 px-6 py-4 rounded-xl text-sm font-bold mb-8 shadow-sm">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if($success_message != ''): ?>
                <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 text-green-600 dark:text-green-400 px-6 py-4 rounded-xl text-sm font-bold mb-8 flex justify-between items-center shadow-sm">
                    <span><?php echo $success_message; ?></span>
                    <a href="cart.php" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-colors shadow-md">View Cart</a>
                </div>
            <?php endif; ?>
        </div>

        <section class="max-w-[1440px] mx-auto px-6 md:px-12 pb-12 grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-16 items-start">
            
            <div class="lg:col-span-7 grid grid-cols-2 gap-4" data-aos="fade-right">
                <div class="col-span-2 aspect-[16/10] bg-white dark:bg-slate-800 overflow-hidden rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-8 flex items-center justify-center cursor-zoom-in" onclick="openLightbox(this)">
                    <img class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal transition-transform duration-700 hover:scale-105" src="assets/uploads/<?php echo $p_featured_photo; ?>" alt="<?php echo $p_name; ?>"/>
                </div>
                
                <?php
                $statement = $pdo->prepare("SELECT * FROM tbl_product_photo WHERE p_id=? LIMIT 2");
                $statement->execute(array($_REQUEST['id']));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                foreach ($result as $row) {
                    ?>
                    <div class="aspect-square bg-white dark:bg-slate-800 overflow-hidden rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6 flex items-center justify-center cursor-zoom-in" onclick="openLightbox(this)">
                        <img class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal transition-transform duration-700 hover:scale-105" src="assets/uploads/product_photos/<?php echo $row['photo']; ?>" alt="Detail view"/>
                    </div>
                    <?php
                }
                ?>
            </div>

            <div class="lg:col-span-5 space-y-8 lg:sticky lg:top-28" data-aos="fade-left">
                
                <div>
                    <?php if($p_is_featured == 1): ?>
                        <span class="font-headline text-[10px] font-black tracking-widest uppercase text-primary dark:text-indigo-400 mb-3 block">Professional Grade Series</span>
                    <?php endif; ?>
                    
                    <h1 class="font-headline text-4xl md:text-5xl font-black tracking-tight text-surfaceDark dark:text-white mb-4 leading-tight"><?php echo $p_name; ?></h1>
                    
                    <p class="text-textMuted dark:text-slate-400 text-sm md:text-base leading-relaxed mb-6 font-medium">
                        <?php echo nl2br($p_short_description); ?>
                    </p>

                    <div class="flex items-baseline gap-4 mb-8">
                        <span class="text-4xl font-headline font-black text-surfaceDark dark:text-white tracking-tighter">₹<?php echo number_format($p_current_price); ?></span>
                        <?php if($p_old_price != ''): ?>
                            <span class="text-slate-400 dark:text-slate-500 line-through text-lg font-bold">₹<?php echo number_format($p_old_price); ?></span>
                            <?php 
                            $discount = round((($p_old_price - $p_current_price) / $p_old_price) * 100);
                            ?>
                            <span class="px-2.5 py-1 bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400 text-[10px] font-black uppercase tracking-widest rounded-md"><?php echo $discount; ?>% OFF</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="w-full h-px bg-slate-200 dark:bg-slate-700"></div>

                <form action="" method="post" class="space-y-6">
                    <?php $csrf->echoInputField(); ?>
                    
                    <?php
                    // Fetch Sizes if they exist
                    $statement = $pdo->prepare("SELECT * FROM tbl_product_size WHERE p_id=?");
                    $statement->execute(array($_REQUEST['id']));
                    $has_sizes = $statement->rowCount() > 0;
                    
                    // Fetch Colors if they exist
                    $statement_color = $pdo->prepare("SELECT * FROM tbl_product_color WHERE p_id=?");
                    $statement_color->execute(array($_REQUEST['id']));
                    $has_colors = $statement_color->rowCount() > 0;

                    if($has_sizes || $has_colors):
                    ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php if($has_sizes): ?>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400 mb-2">Configuration</label>
                                <select name="size_id" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl h-14 px-4 text-surfaceDark dark:text-white font-bold text-sm outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 cursor-pointer">
                                    <option value="">Select Option</option>
                                    <?php
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) {
                                        $stmt2 = $pdo->prepare("SELECT * FROM tbl_size WHERE size_id=?");
                                        $stmt2->execute(array($row['size_id']));
                                        $res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($res2 as $r2) { echo '<option value="'.$r2['size_id'].'">'.$r2['size_name'].'</option>'; }
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php endif; ?>

                            <?php if($has_colors): ?>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400 mb-2">Finish</label>
                                <select name="color_id" class="w-full bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl h-14 px-4 text-surfaceDark dark:text-white font-bold text-sm outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 cursor-pointer">
                                    <option value="">Select Finish</option>
                                    <?php
                                    $result_color = $statement_color->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result_color as $row) {
                                        $stmt2 = $pdo->prepare("SELECT * FROM tbl_color WHERE color_id=?");
                                        $stmt2->execute(array($row['color_id']));
                                        $res2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);
                                        foreach($res2 as $r2) { echo '<option value="'.$r2['color_id'].'">'.$r2['color_name'].'</option>'; }
                                    }
                                    ?>
                                </select>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <?php if($p_qty == 0): ?>
                        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 rounded-2xl p-6 text-center shadow-sm">
                            <span class="material-symbols-outlined text-4xl text-red-500 mb-2 block">inventory_2</span>
                            <h4 class="font-headline font-black text-xl text-red-600 dark:text-red-400">Currently Unavailable</h4>
                            <p class="text-sm font-medium text-red-500 dark:text-red-300/80 mt-1">This instrument is out of stock.</p>
                        </div>
                    <?php else: ?>
                        <div class="flex gap-4 items-end">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-400 mb-2">Qty</label>
                                <div class="flex items-center bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl px-2 h-14 w-24">
                                    <input type="number" name="p_qty" value="1" min="1" max="<?php echo $p_qty; ?>" class="w-full bg-transparent border-none text-center font-headline font-bold text-lg focus:ring-0 text-surfaceDark dark:text-white p-0 outline-none">
                                </div>
                            </div>
                            
                            <button type="submit" name="form_add_to_cart" class="flex-grow py-4 bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-headline font-bold text-sm tracking-widest uppercase rounded-xl shadow-lg shadow-primary/20 dark:shadow-indigo-500/20 hover:shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span> Secure Allocation
                            </button>
                        </div>
                        <p class="text-[10px] font-black uppercase tracking-widest text-green-600 dark:text-green-400 mt-3 flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                            In Stock: Ready to Ship
                        </p>
                    <?php endif; ?>
                </form>

                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6 pt-6 border-t border-slate-200 dark:border-slate-700">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary dark:text-indigo-400 text-2xl">local_shipping</span>
                        <span class="text-xs font-bold text-textMuted dark:text-slate-400 uppercase tracking-widest">Free Express<br>Shipping</span>
                    </div>
                    <div class="hidden sm:block w-px h-8 bg-slate-200 dark:bg-slate-700"></div>
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-primary dark:text-indigo-400 text-2xl">verified</span>
                        <span class="text-xs font-bold text-textMuted dark:text-slate-400 uppercase tracking-widest">Precision<br>Warranty</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-white dark:bg-slate-800 border-y border-slate-100 dark:border-slate-700 py-24 my-12 transition-colors duration-300" data-aos="fade-up">
            <div class="max-w-[1440px] mx-auto px-6 md:px-12 grid grid-cols-1 md:grid-cols-2 gap-16 lg:gap-24 items-center">
                <div class="order-2 md:order-1">
                    <span class="font-headline text-[10px] font-black tracking-widest uppercase text-primary dark:text-indigo-400 mb-4 block">The Architecture</span>
                    <h2 class="font-headline text-4xl font-black tracking-tight text-surfaceDark dark:text-white mb-8">Performance is the Only Metric.</h2>
                    <div class="prose dark:prose-invert prose-slate max-w-none text-textMuted dark:text-slate-400 text-base md:text-lg leading-relaxed font-medium">
                        <?php echo $p_description; ?>
                    </div>
                </div>
                <div class="order-1 md:order-2 aspect-[4/5] bg-surface dark:bg-slate-900 rounded-3xl overflow-hidden shadow-2xl relative border border-slate-100 dark:border-slate-700 p-12 flex items-center justify-center">
                    <img class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal" src="assets/uploads/<?php echo $p_featured_photo; ?>" alt="<?php echo $p_name; ?> Design"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-surfaceDark/20 dark:from-black/40 to-transparent"></div>
                </div>
            </div>
        </section>

        <section class="max-w-4xl mx-auto px-6 md:px-12 py-20" data-aos="fade-up">
            <div class="space-y-16">
                
                <?php if($p_feature != ''): ?>
                <div>
                    <h3 class="font-headline text-2xl font-black text-surfaceDark dark:text-white mb-6 uppercase tracking-tight">Technical Specifications</h3>
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-10 border border-slate-100 dark:border-slate-700 shadow-sm prose dark:prose-invert prose-slate max-w-none prose-li:font-bold prose-li:text-sm">
                        <?php echo $p_feature; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($p_condition != ''): ?>
                <div>
                    <h3 class="font-headline text-2xl font-black text-surfaceDark dark:text-white mb-6 uppercase tracking-tight">Instrument Condition</h3>
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm text-textMuted dark:text-slate-400 text-sm font-medium leading-relaxed">
                        <?php echo nl2br($p_condition); ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($p_return_policy != ''): ?>
                <div>
                    <h3 class="font-headline text-2xl font-black text-surfaceDark dark:text-white mb-6 uppercase tracking-tight">Return Policy</h3>
                    <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-100 dark:border-slate-700 shadow-sm text-textMuted dark:text-slate-400 text-sm font-medium leading-relaxed">
                        <?php echo nl2br($p_return_policy); ?>
                    </div>
                </div>
                <?php endif; ?>
                
            </div>
        </section>

    </main>
</div>

<div id="imageModal" class="fixed inset-0 z-[100] bg-black/90 backdrop-blur-sm hidden flex items-center justify-center opacity-0 transition-opacity duration-300 p-4 md:p-12 cursor-zoom-out" onclick="closeLightbox()">
    <img id="modalImage" src="" class="max-w-full max-h-full object-contain scale-95 transition-transform duration-300">
</div>

<script>
    // Lightbox Logic for Images
    const modal = document.getElementById('imageModal');
    const modalImg = document.getElementById('modalImage');

    function openLightbox(element) {
        // Get the image inside the clicked container
        const imgSrc = element.querySelector('img').src;
        modalImg.src = imgSrc;
        
        modal.classList.remove('hidden');
        // Trigger reflow for animation
        void modal.offsetWidth;
        
        modal.classList.remove('opacity-0');
        modalImg.classList.remove('scale-95');
        modalImg.classList.add('scale-100');
    }

    function closeLightbox() {
        modal.classList.add('opacity-0');
        modalImg.classList.remove('scale-100');
        modalImg.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>

<?php require_once('footer.php'); ?>