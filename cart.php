<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_cart = $row['banner_cart'];
}

$error_message = '';
if(isset($_POST['form1'])) {
    $i = 0;
    $statement = $pdo->prepare("SELECT * FROM tbl_product");
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    foreach ($result as $row) {
        $table_product_id[$i] = $row['p_id'];
        $table_quantity[$i] = $row['p_qty'];
        $i++;
    }

    $i=0;
    foreach($_POST['product_id'] as $val) {
        $arr1[$i] = $val;
        $i++;
    }
    $i=0;
    foreach($_POST['product_name'] as $val) {
        $arr2[$i] = $val;
        $i++;
    }
    $i=0;
    foreach($_POST['product_qty'] as $val) {
        $arr3[$i] = $val;
        $i++;
    }

    $allow_update = 1;
    for($i=0;$i<count($arr1);$i++) {
        for($j=0;$j<count($table_product_id);$j++) {
            if($arr1[$i] == $table_product_id[$j]) {
                $temp_index = $j;
                break;
            }
        }
        if($table_quantity[$temp_index] < $arr3[$i]) {
            $allow_update = 0;
            $error_message .= '"'.$arr2[$i].'" '.LANG_VALUE_155.'<br>';
        } else {
            $_SESSION['cart_p_qty'][$i+1] = $arr3[$i];
        }
    }
    $error_message .= '\n';
}
?>

<div class="flex pt-20 min-h-screen bg-surface dark:bg-slate-900 transition-colors duration-300">
    <main class="flex-grow max-w-screen-2xl mx-auto w-full px-6 py-12 md:py-20">
        
        <header class="mb-12" data-aos="fade-in">
            <div class="flex items-center gap-2 text-xs font-bold text-textMuted dark:text-slate-500 uppercase tracking-widest mb-4">
                <span>Inventory</span>
                <span class="material-symbols-outlined text-[14px]">chevron_right</span>
                <span class="text-primary dark:text-indigo-400">Checkout</span>
            </div>
            <h1 class="font-headline text-5xl md:text-6xl font-black tracking-tight text-surfaceDark dark:text-white">Your Cart</h1>
        </header>

        <?php if($error_message != '' && $error_message != '\n'): ?>
            <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 px-6 py-4 rounded-2xl text-sm font-bold mb-8">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if(!isset($_SESSION['cart_p_id']) || count($_SESSION['cart_p_id']) == 0): ?>
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-16 text-center shadow-sm border border-slate-100 dark:border-slate-700/50" data-aos="zoom-in">
                <span class="material-symbols-outlined text-7xl text-slate-300 dark:text-slate-600 mb-6 block">remove_shopping_cart</span>
                <h2 class="font-headline text-3xl font-black text-surfaceDark dark:text-white mb-4"><?php echo LANG_VALUE_20; ?></h2>
                <p class="text-textMuted dark:text-slate-400 mb-10 max-w-md mx-auto">Your selection is currently empty. Explore our curated inventory to find your next precision instrument.</p>
                <a href="product-category.php" class="bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white px-10 py-4 rounded-xl font-headline font-bold text-sm tracking-widest uppercase transition-all shadow-lg active:scale-95 inline-block">
                    Explore Ecosystems
                </a>
            </div>
        <?php else: ?>
            <form action="" method="post">
                <?php $csrf->echoInputField(); ?>
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-12 items-start">
                    
                    <section class="xl:col-span-8 space-y-6">
                        <?php
                        $table_total_price = 0;
                        
                        $i=0; foreach($_SESSION['cart_p_id'] as $key => $value) { $i++; $arr_cart_p_id[$i] = $value; }
                        // Ensure arrays fetch properly to prevent data mismatch during delete
                        $i=0; if(isset($_SESSION['cart_size_id'])) { foreach($_SESSION['cart_size_id'] as $key => $value) { $i++; $arr_cart_size_id[$i] = $value; } }
                        $i=0; if(isset($_SESSION['cart_size_name'])) { foreach($_SESSION['cart_size_name'] as $key => $value) { $i++; $arr_cart_size_name[$i] = $value; } }
                        $i=0; if(isset($_SESSION['cart_color_id'])) { foreach($_SESSION['cart_color_id'] as $key => $value) { $i++; $arr_cart_color_id[$i] = $value; } }
                        $i=0; if(isset($_SESSION['cart_color_name'])) { foreach($_SESSION['cart_color_name'] as $key => $value) { $i++; $arr_cart_color_name[$i] = $value; } }
                        
                        $i=0; foreach($_SESSION['cart_p_qty'] as $key => $value) { $i++; $arr_cart_p_qty[$i] = $value; }
                        $i=0; foreach($_SESSION['cart_p_current_price'] as $key => $value) { $i++; $arr_cart_p_current_price[$i] = $value; }
                        $i=0; foreach($_SESSION['cart_p_name'] as $key => $value) { $i++; $arr_cart_p_name[$i] = $value; }
                        $i=0; foreach($_SESSION['cart_p_featured_photo'] as $key => $value) { $i++; $arr_cart_p_featured_photo[$i] = $value; }
                        ?>
                        
                        <?php for($i=1; $i<=count($arr_cart_p_id); $i++): ?>
                            <div class="group bg-white dark:bg-slate-800 p-6 md:p-8 rounded-3xl flex flex-col md:flex-row gap-8 transition-all hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] dark:hover:shadow-[0_20px_40px_-15px_rgba(79,70,229,0.15)] border border-slate-100 dark:border-slate-700/50" data-aos="fade-up" data-aos-delay="<?php echo $i * 50; ?>">
                                
                                <div class="w-full md:w-48 h-48 rounded-2xl overflow-hidden bg-surface dark:bg-slate-900 shrink-0 p-4 relative border border-slate-100 dark:border-slate-700">
                                    <img src="assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>" alt="<?php echo $arr_cart_p_name[$i]; ?>" class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal group-hover:scale-110 transition-transform duration-700">
                                </div>
                                
                                <div class="flex-grow flex flex-col justify-between">
                                    <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                        <div>
                                            <h3 class="font-headline text-2xl font-black text-surfaceDark dark:text-white mb-2 leading-tight">
                                                <?php echo $arr_cart_p_name[$i]; ?>
                                            </h3>
                                            <p class="text-xs font-bold text-textMuted dark:text-slate-400 tracking-widest uppercase">Base Price: ₹<?php echo $arr_cart_p_current_price[$i]; ?></p>
                                            
                                            <?php 
                                            $s_name = isset($arr_cart_size_name[$i]) ? $arr_cart_size_name[$i] : ''; 
                                            $c_name = isset($arr_cart_color_name[$i]) ? $arr_cart_color_name[$i] : ''; 
                                            if ($s_name != '' || $c_name != ''): 
                                            ?>
                                            <div class="flex gap-2 mt-2">
                                                <?php if($s_name != ''): ?><span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] px-2 py-1 rounded font-bold uppercase tracking-widest">Size: <?php echo $s_name; ?></span><?php endif; ?>
                                                <?php if($c_name != ''): ?><span class="bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-300 text-[10px] px-2 py-1 rounded font-bold uppercase tracking-widest">Color: <?php echo $c_name; ?></span><?php endif; ?>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <?php $row_total_price = $arr_cart_p_current_price[$i] * $arr_cart_p_qty[$i]; $table_total_price += $row_total_price; ?>
                                        <p class="font-headline text-2xl font-black text-primary dark:text-indigo-400 md:text-right">₹<?php echo number_format($row_total_price, 2); ?></p>
                                    </div>
                                    
                                    <div class="flex items-center justify-between mt-8 pt-6 border-t border-slate-100 dark:border-slate-700">
                                        <div class="flex items-center bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl px-2 h-12">
                                            <input type="hidden" name="product_id[]" value="<?php echo $arr_cart_p_id[$i]; ?>">
                                            <input type="hidden" name="product_name[]" value="<?php echo $arr_cart_p_name[$i]; ?>">
                                            <span class="text-xs font-bold text-textMuted dark:text-slate-400 uppercase tracking-widest px-4 border-r border-slate-200 dark:border-slate-700">Qty</span>
                                            <input type="number" class="w-16 bg-transparent border-none text-center font-headline font-bold text-lg focus:ring-0 text-surfaceDark dark:text-white p-0" step="1" min="1" max="" name="product_qty[]" value="<?php echo $arr_cart_p_qty[$i]; ?>" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
                                        </div>
                                        
                                        <?php 
                                        $s_id = isset($arr_cart_size_id[$i]) ? $arr_cart_size_id[$i] : ''; 
                                        $c_id = isset($arr_cart_color_id[$i]) ? $arr_cart_color_id[$i] : ''; 
                                        ?>
                                        <a href="cart-item-delete.php?id=<?php echo $arr_cart_p_id[$i]; ?>&size=<?php echo $s_id; ?>&color=<?php echo $c_id; ?>" class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-600 transition-colors bg-red-50 dark:bg-red-500/10 dark:hover:bg-red-500/20 px-4 py-3 rounded-xl" onclick="return confirm('Are you sure you want to remove this item?');">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                            <span class="hidden sm:inline">Remove</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endfor; ?>

                        <div class="flex justify-end pt-4" data-aos="fade-up">
                            <button type="submit" name="form1" class="flex items-center gap-2 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-surfaceDark dark:text-white px-8 py-4 rounded-xl font-headline font-bold text-sm tracking-widest uppercase transition-all shadow-sm active:scale-95">
                                <span class="material-symbols-outlined text-[18px]">sync</span>
                                <?php echo LANG_VALUE_20; ?>
                            </button>
                        </div>
                    </section>

                    <aside class="xl:col-span-4 space-y-6" data-aos="fade-left">
                        <div class="bg-surfaceDark dark:bg-black p-8 rounded-3xl shadow-xl relative overflow-hidden text-white">
                            <div class="absolute top-0 right-0 w-64 h-64 bg-primary/20 dark:bg-indigo-500/20 blur-[80px] rounded-full"></div>
                            
                            <div class="relative z-10">
                                <h2 class="font-headline text-2xl font-black tracking-tight mb-8 text-white">Order Summary</h2>
                                
                                <div class="space-y-5 mb-8">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-400 font-medium">Subtotal</span>
                                        <span class="text-white font-bold">₹<?php echo number_format($table_total_price, 2); ?></span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-400 font-medium">Shipping (Calculated at checkout)</span>
                                        <span class="text-primary dark:text-indigo-400 font-bold uppercase text-[10px] tracking-widest">Pending</span>
                                    </div>
                                    
                                    <div class="pt-6 border-t border-slate-700 flex justify-between items-end">
                                        <span class="font-headline text-sm font-bold uppercase tracking-widest text-slate-400">Total</span>
                                        <span class="font-headline text-3xl font-black text-white tracking-tighter">₹<?php echo number_format($table_total_price, 2); ?></span>
                                    </div>
                                </div>
                                
                                <a href="checkout.php" class="w-full bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white py-5 rounded-xl font-headline font-bold text-sm uppercase tracking-[0.1em] shadow-lg hover:shadow-xl active:scale-[0.98] transition-all flex items-center justify-center gap-3">
                                    <span>Proceed to Checkout</span>
                                    <span class="material-symbols-outlined">arrow_forward</span>
                                </a>
                                
                                <div class="mt-8 pt-8 border-t border-slate-800 grid grid-cols-2 gap-4">
                                    <div class="flex flex-col items-center text-center p-4 rounded-2xl bg-white/5 border border-white/10">
                                        <span class="material-symbols-outlined text-primary dark:text-indigo-400 mb-2">verified_user</span>
                                        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Secure<br>Checkout</p>
                                    </div>
                                    <div class="flex flex-col items-center text-center p-4 rounded-2xl bg-white/5 border border-white/10">
                                        <span class="material-symbols-outlined text-primary dark:text-indigo-400 mb-2">local_shipping</span>
                                        <p class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Express<br>Delivery</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white dark:bg-slate-800 p-6 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm cursor-pointer group" onclick="window.location.href='product-category.php';">
                            <p class="font-body text-[10px] font-black uppercase tracking-widest text-primary dark:text-indigo-400 mb-4">Complete Your Setup</p>
                            <div class="flex gap-4 items-center">
                                <div class="w-16 h-16 bg-surface dark:bg-slate-900 rounded-xl p-3 shrink-0 border border-slate-100 dark:border-slate-700">
                                    <span class="material-symbols-outlined text-4xl text-slate-300 dark:text-slate-600 w-full h-full flex items-center justify-center group-hover:scale-110 transition-transform">inventory_2</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-surfaceDark dark:text-white line-clamp-1">Explore Peripherals</p>
                                    <p class="text-xs text-textMuted dark:text-slate-400 font-bold mt-1">View Collection</p>
                                </div>
                                <span class="ml-auto material-symbols-outlined text-primary dark:text-indigo-400 group-hover:translate-x-1 transition-transform">arrow_forward</span>
                            </div>
                        </div>
                    </aside>
                </div>
            </form>
        <?php endif; ?>
    </main>
</div>

<?php require_once('footer.php'); ?>