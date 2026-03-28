<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_product_category = $row['banner_product_category'];
}

// ==========================================
// CATEGORY IDENTIFICATION LOGIC
// ==========================================
$is_all_products = false;
$title = "All Inventory";
$final_ecat_ids = array();

if( !isset($_REQUEST['id']) || !isset($_REQUEST['type']) ) {
    $is_all_products = true;
} else {
    if( ($_REQUEST['type'] != 'top-category') && ($_REQUEST['type'] != 'mid-category') && ($_REQUEST['type'] != 'end-category') ) {
        header('location: index.php');
        exit;
    } else {
        $statement = $pdo->prepare("SELECT * FROM tbl_top_category");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $top[] = $row['tcat_id'];
            $top1[] = $row['tcat_name'];
        }

        $statement = $pdo->prepare("SELECT * FROM tbl_mid_category");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $mid[] = $row['mcat_id'];
            $mid1[] = $row['mcat_name'];
            $mid2[] = $row['tcat_id'];
        }

        $statement = $pdo->prepare("SELECT * FROM tbl_end_category");
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
        foreach ($result as $row) {
            $end[] = $row['ecat_id'];
            $end1[] = $row['ecat_name'];
            $end2[] = $row['mcat_id'];
        }

        if($_REQUEST['type'] == 'top-category') {
            if(!in_array($_REQUEST['id'],$top)) { header('location: index.php'); exit; } 
            else {
                for ($i=0; $i < count($top); $i++) { 
                    if($top[$i] == $_REQUEST['id']) { $title = $top1[$i]; break; }
                }
                $arr1 = array(); $arr2 = array();
                for ($i=0; $i < count($mid); $i++) { 
                    if($mid2[$i] == $_REQUEST['id']) { $arr1[] = $mid[$i]; }
                }
                for ($j=0; $j < count($arr1); $j++) {
                    for ($i=0; $i < count($end); $i++) { 
                        if($end2[$i] == $arr1[$j]) { $arr2[] = $end[$i]; }
                    }   
                }
                $final_ecat_ids = $arr2;
            }   
        }

        if($_REQUEST['type'] == 'mid-category') {
            if(!in_array($_REQUEST['id'],$mid)) { header('location: index.php'); exit; } 
            else {
                for ($i=0; $i < count($mid); $i++) { 
                    if($mid[$i] == $_REQUEST['id']) { $title = $mid1[$i]; break; }
                }
                $arr2 = array();        
                for ($i=0; $i < count($end); $i++) { 
                    if($end2[$i] == $_REQUEST['id']) { $arr2[] = $end[$i]; }
                }
                $final_ecat_ids = $arr2;
            }
        }

        if($_REQUEST['type'] == 'end-category') {
            if(!in_array($_REQUEST['id'],$end)) { header('location: index.php'); exit; } 
            else {
                for ($i=0; $i < count($end); $i++) { 
                    if($end[$i] == $_REQUEST['id']) { $title = $end1[$i]; break; }
                }
                $final_ecat_ids = array($_REQUEST['id']);
            }
        }
    }   
}

// ==========================================
// ADVANCED FILTERING LOGIC (PRICE, STOCK, SORT)
// ==========================================
$where_clauses = ["p_is_active = 1"];
$params = [];

// Filter by Category IDs if not viewing all products
if (!$is_all_products && count($final_ecat_ids) > 0) {
    $in_placeholders = str_repeat('?,', count($final_ecat_ids) - 1) . '?';
    $where_clauses[] = "ecat_id IN ($in_placeholders)";
    $params = array_merge($params, $final_ecat_ids);
}

// Filter by Maximum Price
if (isset($_GET['max_price']) && is_numeric($_GET['max_price']) && $_GET['max_price'] > 0) {
    $where_clauses[] = "p_current_price <= ?";
    $params[] = $_GET['max_price'];
}

// Filter by Stock Availability
if (isset($_GET['in_stock']) && $_GET['in_stock'] == '1') {
    $where_clauses[] = "p_qty > 0";
}

// Handle Sorting
$order_by = "ORDER BY p_id DESC"; // Default: Newest
if (isset($_GET['sort'])) {
    if ($_GET['sort'] == 'price_low') $order_by = "ORDER BY CAST(p_current_price AS DECIMAL(10,2)) ASC";
    if ($_GET['sort'] == 'price_high') $order_by = "ORDER BY CAST(p_current_price AS DECIMAL(10,2)) DESC";
}

// Construct and Execute Final Query
$where_sql = implode(' AND ', $where_clauses);
$sql = "SELECT * FROM tbl_product WHERE $where_sql $order_by";
$statement = $pdo->prepare($sql);
$statement->execute($params);
$products_to_display = $statement->fetchAll(PDO::FETCH_ASSOC);
$prod_count = count($products_to_display);

// Preserve existing GET parameters for the form
$current_id = isset($_GET['id']) ? $_GET['id'] : '';
$current_type = isset($_GET['type']) ? $_GET['type'] : '';
$current_max = isset($_GET['max_price']) ? $_GET['max_price'] : '500000'; // Default max
$current_stock = isset($_GET['in_stock']) ? $_GET['in_stock'] : '';
$current_sort = isset($_GET['sort']) ? $_GET['sort'] : '';
?>

<style>
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* Custom Range Slider Thumb */
input[type=range] {
    -webkit-appearance: none;
    background: transparent;
}
input[type=range]::-webkit-slider-thumb {
    -webkit-appearance: none;
    height: 16px;
    width: 16px;
    border-radius: 50%;
    background: #4f46e5;
    cursor: pointer;
    margin-top: -6px;
    box-shadow: 0 0 10px rgba(79, 70, 229, 0.4);
}
input[type=range]::-webkit-slider-runnable-track {
    width: 100%;
    height: 4px;
    cursor: pointer;
    background: #e2e8f0;
    border-radius: 4px;
}
.dark input[type=range]::-webkit-slider-runnable-track {
    background: #334155;
}
</style>

<div class="pt-20 min-h-screen bg-surface dark:bg-slate-900 transition-colors duration-300">
    
    <main class="flex-1 w-full max-w-[1440px] mx-auto px-6 md:px-12 py-8">

        <header class="mb-8" data-aos="fade-in">
            <h1 class="text-4xl md:text-5xl font-headline font-black tracking-tight text-surfaceDark dark:text-white mb-2"><?php echo $title; ?></h1>
            <p class="text-textMuted dark:text-slate-400 font-medium max-w-xl">Curated precision instruments engineered for your specific digital workflow and lifestyle.</p>
        </header>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 md:p-6 mb-12 shadow-[0_10px_30px_-15px_rgba(0,0,0,0.05)] dark:shadow-none border border-slate-100 dark:border-slate-700/60 sticky top-24 z-30" data-aos="fade-up">
            <form action="product-category.php" method="GET" class="flex flex-col xl:flex-row gap-6 xl:items-center justify-between">
                
                <?php if($current_id != ''): ?>
                    <input type="hidden" name="id" value="<?php echo $current_id; ?>">
                    <input type="hidden" name="type" value="<?php echo $current_type; ?>">
                <?php endif; ?>

                <div class="flex items-center gap-3 overflow-x-auto pb-2 xl:pb-0 hide-scrollbar">
                    <a href="product-category.php" class="flex-shrink-0 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest transition-all <?php echo $is_all_products ? 'bg-surfaceDark text-white dark:bg-white dark:text-surfaceDark' : 'bg-slate-50 dark:bg-slate-900 text-textMuted dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700'; ?>">
                        All
                    </a>
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu=1");
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        $isActive = (!$is_all_products && $current_type == 'top-category' && $current_id == $row['tcat_id']);
                        $activeClass = $isActive ? 'bg-surfaceDark text-white dark:bg-white dark:text-surfaceDark' : 'bg-slate-50 dark:bg-slate-900 text-textMuted dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700';
                        echo '<a href="product-category.php?id='.$row['tcat_id'].'&type=top-category" class="flex-shrink-0 px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest transition-all '.$activeClass.'">'.$row['tcat_name'].'</a>';
                    }
                    ?>
                </div>

                <div class="w-full xl:w-px h-px xl:h-8 bg-slate-200 dark:bg-slate-700"></div>

                <div class="flex flex-wrap items-center gap-4 md:gap-6">
                    
                    <div class="flex items-center gap-4">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-500 hidden md:block">Max Price:</span>
                        <div class="flex items-center gap-3 w-32 md:w-48">
                            <input type="range" name="max_price" min="0" max="1000000" step="500" value="<?php echo $current_max; ?>" class="w-full" oninput="document.getElementById('price_display').innerText = new Intl.NumberFormat('en-IN').format(this.value)">
                        </div>
                        <span class="text-xs font-bold text-surfaceDark dark:text-white w-16">₹<span id="price_display"><?php echo number_format($current_max); ?></span></span>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="in_stock" value="1" <?php if($current_stock == '1') echo 'checked'; ?> class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-primary focus:ring-primary dark:bg-slate-900 cursor-pointer">
                        <span class="text-xs font-bold uppercase tracking-widest text-surfaceDark dark:text-slate-300">In Stock</span>
                    </label>

                    <div class="flex items-center bg-slate-50 dark:bg-slate-900 rounded-xl p-1 px-3 border border-slate-200 dark:border-slate-700">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-textMuted dark:text-slate-500 mr-2">Sort:</span>
                        <select name="sort" class="bg-transparent border-none text-xs font-bold text-surfaceDark dark:text-white focus:ring-0 cursor-pointer outline-none p-1.5" onchange="this.form.submit()">
                            <option value="newest" <?php if($current_sort == 'newest' || $current_sort == '') echo 'selected'; ?>>Newest First</option>
                            <option value="price_low" <?php if($current_sort == 'price_low') echo 'selected'; ?>>Price: Low to High</option>
                            <option value="price_high" <?php if($current_sort == 'price_high') echo 'selected'; ?>>Price: High to Low</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 border-l border-slate-200 dark:border-slate-700 pl-4">
                        <button type="submit" class="bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest transition-all shadow-md active:scale-95 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">filter_list</span> Apply
                        </button>

                        <?php if($current_max != '500000' || $current_stock != '' || $current_sort != ''): ?>
                            <a href="product-category.php<?php echo ($current_id != '') ? '?id='.$current_id.'&type='.$current_type : ''; ?>" class="text-xs font-bold uppercase tracking-widest text-red-500 hover:text-red-600 dark:hover:text-red-400 transition-all flex items-center gap-1 px-2">
                                <span class="material-symbols-outlined text-[16px]">close</span> Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            <?php
            if($prod_count == 0) {
                echo '<div class="col-span-full py-24 text-center">';
                echo '<span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-4 block">inventory_2</span>';
                echo '<h3 class="font-headline font-black text-2xl text-surfaceDark dark:text-white mb-2">No instruments found</h3>';
                echo '<p class="text-textMuted dark:text-slate-400">Try adjusting your filters or price range to find what you are looking for.</p>';
                echo '</div>';
            } else {
                $delay = 0;
                foreach ($products_to_display as $row) {
                    ?>
                    <div class="group bg-white dark:bg-slate-800 rounded-3xl overflow-hidden border border-slate-100 dark:border-slate-700 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] dark:hover:shadow-[0_20px_40px_-15px_rgba(79,70,229,0.15)] transition-all duration-300 flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                        
                        <div class="relative aspect-[4/3] bg-surface dark:bg-slate-900/50 p-6 flex items-center justify-center overflow-hidden cursor-pointer" onclick="window.location.href='product.php?id=<?php echo $row['p_id']; ?>';">
                            <img src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>" class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal group-hover:scale-110 transition-transform duration-700 ease-out"/>
                            
                            <?php if($row['p_qty'] == 0): ?>
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 bg-red-500 text-white text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">Out of Stock</span>
                                </div>
                            <?php else: ?>
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 bg-white/90 dark:bg-slate-700/90 backdrop-blur-md text-surfaceDark dark:text-white border border-slate-200 dark:border-slate-600 text-[10px] font-black uppercase tracking-widest rounded-full shadow-sm">Available</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="p-6 md:p-8 flex-1 flex flex-col">
                            <div class="mb-4">
                                <h3 class="text-lg md:text-xl font-headline font-black tracking-tight text-surfaceDark dark:text-white mb-2 line-clamp-2">
                                    <a href="product.php?id=<?php echo $row['p_id']; ?>" class="hover:text-primary dark:hover:text-indigo-400 transition-colors"><?php echo $row['p_name']; ?></a>
                                </h3>
                                
                                <div class="flex items-center gap-1 text-yellow-400">
                                    <?php
                                    $t_rating = 0;
                                    $statement1 = $pdo->prepare("SELECT * FROM tbl_rating WHERE p_id=?");
                                    $statement1->execute(array($row['p_id']));
                                    $tot_rating = $statement1->rowCount();
                                    if($tot_rating == 0) { $avg_rating = 0; } 
                                    else {
                                        $result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
                                        foreach ($result1 as $row1) { $t_rating = $t_rating + $row1['rating']; }
                                        $avg_rating = $t_rating / $tot_rating;
                                    }

                                    for($i=1; $i<=5; $i++) {
                                        if($i <= $avg_rating) {
                                            echo '<span class="material-symbols-outlined text-sm" style="font-variation-settings: \'FILL\' 1;">star</span>';
                                        } elseif ($i - 0.5 == $avg_rating) {
                                            echo '<span class="material-symbols-outlined text-sm" style="font-variation-settings: \'FILL\' 1;">star_half</span>';
                                        } else {
                                            echo '<span class="material-symbols-outlined text-sm" style="font-variation-settings: \'FILL\' 0;">star</span>';
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="mt-auto flex items-center justify-between pt-6 border-t border-slate-100 dark:border-slate-700">
                                <div>
                                    <?php if($row['p_old_price'] != ''): ?>
                                        <div class="text-[10px] font-bold line-through text-slate-400 mb-0.5">₹<?php echo $row['p_old_price']; ?></div>
                                    <?php endif; ?>
                                    <span class="text-xl font-headline font-black text-surfaceDark dark:text-white tracking-tighter">₹<?php echo number_format($row['p_current_price'], 2); ?></span>
                                </div>
                                
                                <div class="flex gap-2">
                                    <?php if($row['p_qty'] > 0): ?>
                                        <a href="product.php?id=<?php echo $row['p_id']; ?>" class="h-10 w-10 flex items-center justify-center bg-surfaceDark dark:bg-slate-700 text-white rounded-xl hover:bg-primary dark:hover:bg-indigo-500 transition-all duration-300 active:scale-95 shadow-md">
                                            <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $delay += 50;
                    if($delay > 300) $delay = 0;
                }
            }
            ?>
        </div>

        <section class="mt-24 pt-12 border-t border-slate-200 dark:border-slate-800" data-aos="fade-up">
            <h2 class="text-xs font-black uppercase tracking-[0.3em] text-primary dark:text-indigo-400 mb-8">Curated Ecosystems</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 h-[400px]">
                
                <div class="md:col-span-2 bg-surfaceDark dark:bg-black rounded-3xl relative overflow-hidden group cursor-pointer" onclick="window.location.href='product-category.php?id=1&type=top-category';">
                    <img class="absolute inset-0 w-full h-full object-cover opacity-50 mix-blend-overlay group-hover:scale-105 transition-transform duration-1000" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCLl9I830xjbjxsauuiJ7VGubzwghYkE3meKLfK55-2zjkoqad6HYt-o_wZUGOeMnKAyxVdZjA8F7x2iBSID1bmNRv6AK3fIvcGVLs9Roq_OwPudSHs3_3SVw36Fe4DkuJK2isgnxAq6oPohlrqPyIf6muAuX8Ry76DsSXIAugCX1SldKJj5hKFzFNQYWq-0xjDiTNteQRApRxnyrAmxaFkcDQCYQrqzddpfjg3rCXa-4wdQrjbxBVSQpX2EuUXwqu12LzsMlwaG5Zy" alt="Laptops"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent p-12 flex flex-col justify-end">
                        <span class="bg-primary text-white w-max px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest mb-4">Pro Series</span>
                        <h3 class="text-3xl md:text-4xl font-headline font-black text-white tracking-tighter mb-2">The Creator Studio</h3>
                        <p class="text-slate-300 max-w-sm mb-6 text-sm">Experience desktop-class performance in portable frameworks.</p>
                        <button class="w-fit px-8 py-3 bg-white text-surfaceDark text-xs font-black uppercase tracking-widest rounded-xl hover:shadow-xl transition-all">Explore Laptops</button>
                    </div>
                </div>

                <div class="bg-indigo-50 dark:bg-indigo-500/10 rounded-3xl p-10 flex flex-col justify-between border border-indigo-100 dark:border-indigo-500/20 group cursor-pointer" onclick="window.location.href='product-category.php?id=2&type=top-category';">
                    <span class="material-symbols-outlined text-5xl text-primary dark:text-indigo-400 group-hover:scale-110 transition-transform">photo_camera</span>
                    <div>
                        <h3 class="text-2xl font-headline font-black tracking-tight text-surfaceDark dark:text-white mb-2">Optics & Lenses</h3>
                        <p class="text-sm text-textMuted dark:text-slate-400 leading-relaxed">Capture the world in stunning cinematic detail with our curated cameras.</p>
                    </div>
                    <span class="text-xs font-black uppercase tracking-widest text-primary dark:text-indigo-400 flex items-center gap-2 group-hover:translate-x-2 transition-transform">
                        View Optics
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </span>
                </div>

            </div>
        </section>

    </main>
</div>

<?php require_once('footer.php'); ?>