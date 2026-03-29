<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_product_category = $row['banner_product_category'];
}

// ==========================================
// CATEGORY IDENTIFICATION LOGIC (Preserved perfectly)
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
// ADVANCED FILTERING LOGIC
// ==========================================
$where_clauses = ["p_is_active = 1"];
$params = [];

if (!$is_all_products && count($final_ecat_ids) > 0) {
    $in_placeholders = str_repeat('?,', count($final_ecat_ids) - 1) . '?';
    $where_clauses[] = "ecat_id IN ($in_placeholders)";
    $params = array_merge($params, $final_ecat_ids);
}

if (isset($_GET['max_price']) && is_numeric($_GET['max_price']) && $_GET['max_price'] > 0) {
    $where_clauses[] = "p_current_price <= ?";
    $params[] = $_GET['max_price'];
}

if (isset($_GET['in_stock']) && $_GET['in_stock'] == '1') {
    $where_clauses[] = "p_qty > 0";
}

$order_by = "ORDER BY p_id DESC"; 
if (isset($_GET['sort'])) {
    if ($_GET['sort'] == 'price_low') $order_by = "ORDER BY CAST(p_current_price AS DECIMAL(10,2)) ASC";
    if ($_GET['sort'] == 'price_high') $order_by = "ORDER BY CAST(p_current_price AS DECIMAL(10,2)) DESC";
}

$where_sql = implode(' AND ', $where_clauses);
$sql = "SELECT * FROM tbl_product WHERE $where_sql $order_by";
$statement = $pdo->prepare($sql);
$statement->execute($params);
$products_to_display = $statement->fetchAll(PDO::FETCH_ASSOC);
$prod_count = count($products_to_display);

$current_id = isset($_GET['id']) ? $_GET['id'] : '';
$current_type = isset($_GET['type']) ? $_GET['type'] : '';
$current_max = isset($_GET['max_price']) ? $_GET['max_price'] : '500000'; 
$current_stock = isset($_GET['in_stock']) ? $_GET['in_stock'] : '';
$current_sort = isset($_GET['sort']) ? $_GET['sort'] : '';
?>

<style>
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

/* Custom Minimalist Range Slider */
input[type=range] { -webkit-appearance: none; background: transparent; }
input[type=range]::-webkit-slider-thumb { -webkit-appearance: none; height: 16px; width: 16px; border-radius: 50%; background: #2563eb; cursor: pointer; margin-top: -6px; box-shadow: 0 0 10px rgba(37, 99, 235, 0.4); }
input[type=range]::-webkit-slider-runnable-track { width: 100%; height: 4px; cursor: pointer; background: #e2e8f0; border-radius: 4px; }
.dark input[type=range]::-webkit-slider-runnable-track { background: #334155; }
</style>

<main class="min-h-screen bg-[#faf8ff] dark:bg-slate-950 transition-colors duration-500 overflow-hidden pb-24">
    
    <section class="relative pt-32 pb-20 md:pt-40 md:pb-24 px-6 md:px-12 flex items-center justify-center overflow-hidden border-b border-slate-200/50 dark:border-slate-800/50">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[400px] bg-blue-600/10 dark:bg-indigo-600/20 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-teal-500/10 dark:bg-teal-500/10 rounded-full blur-[80px] pointer-events-none"></div>
        
        <div class="relative z-10 text-center max-w-3xl mx-auto" data-aos="fade-up">
            <span class="inline-block py-1.5 px-4 rounded-full bg-white/60 dark:bg-slate-800/60 backdrop-blur-md text-blue-600 dark:text-indigo-400 border border-slate-200 dark:border-slate-700 text-[10px] font-black uppercase tracking-[0.3em] mb-6 shadow-sm">
                Curated Ecosystems
            </span>
            <h1 class="text-5xl md:text-7xl font-extrabold font-headline text-slate-900 dark:text-white tracking-tighter mb-4 leading-tight">
                <?php echo htmlspecialchars($title); ?>
            </h1>
            <p class="text-lg text-slate-500 dark:text-slate-400 font-medium">
                Precision instruments engineered for your specific digital workflow.
            </p>
        </div>
    </section>

    <div class="max-w-[1440px] mx-auto px-6 md:px-12 pt-8">

        <div class="sticky top-20 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl rounded-[2rem] p-4 md:p-5 mb-12 shadow-[0_8px_30px_rgb(0,0,0,0.04)] dark:shadow-none border border-slate-200/60 dark:border-slate-700/60" data-aos="fade-up" data-aos-delay="100">
            <form action="product-category.php" method="GET" class="flex flex-col xl:flex-row gap-6 xl:items-center justify-between">
                
                <?php if($current_id != ''): ?>
                    <input type="hidden" name="id" value="<?php echo $current_id; ?>">
                    <input type="hidden" name="type" value="<?php echo $current_type; ?>">
                <?php endif; ?>

                <div class="flex items-center gap-3 overflow-x-auto pb-2 xl:pb-0 hide-scrollbar flex-shrink-0">
                    <a href="product-category.php" class="flex-shrink-0 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest transition-all <?php echo $is_all_products ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-md' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700'; ?>">
                        All
                    </a>
                    <?php
                    $statement = $pdo->prepare("SELECT * FROM tbl_top_category WHERE show_on_menu=1");
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($result as $row) {
                        $isActive = (!$is_all_products && $current_type == 'top-category' && $current_id == $row['tcat_id']);
                        $activeClass = $isActive ? 'bg-slate-900 text-white dark:bg-white dark:text-slate-900 shadow-md' : 'bg-slate-100 dark:bg-slate-800 text-slate-500 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700';
                        echo '<a href="product-category.php?id='.$row['tcat_id'].'&type=top-category" class="flex-shrink-0 px-6 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest transition-all '.$activeClass.'">'.$row['tcat_name'].'</a>';
                    }
                    ?>
                </div>

                <div class="hidden xl:block w-px h-8 bg-slate-200 dark:bg-slate-700"></div>

                <div class="flex flex-wrap items-center gap-4 md:gap-6 w-full xl:w-auto">
                    
                    <div class="flex items-center gap-3 flex-grow md:flex-grow-0 bg-slate-50 dark:bg-slate-800/50 py-2 px-4 rounded-xl border border-slate-100 dark:border-slate-700">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Max:</span>
                        <input type="range" name="max_price" min="0" max="1000000" step="500" value="<?php echo $current_max; ?>" class="w-24 md:w-32" oninput="document.getElementById('price_display').innerText = new Intl.NumberFormat('en-IN').format(this.value)">
                        <span class="text-xs font-bold text-slate-900 dark:text-white w-14 text-right">₹<span id="price_display"><?php echo number_format($current_max); ?></span></span>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer bg-slate-50 dark:bg-slate-800/50 py-2 px-4 rounded-xl border border-slate-100 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                        <input type="checkbox" name="in_stock" value="1" <?php if($current_stock == '1') echo 'checked'; ?> class="w-4 h-4 rounded border-slate-300 dark:border-slate-600 text-blue-600 focus:ring-blue-600 dark:bg-slate-900 cursor-pointer">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-700 dark:text-slate-300">In Stock Only</span>
                    </label>

                    <div class="flex items-center bg-slate-50 dark:bg-slate-800/50 py-1.5 px-3 rounded-xl border border-slate-100 dark:border-slate-700">
                        <span class="material-symbols-outlined text-[16px] text-slate-400 mr-1">sort</span>
                        <select name="sort" class="bg-transparent border-none text-xs font-bold text-slate-700 dark:text-slate-300 focus:ring-0 cursor-pointer outline-none p-1 uppercase tracking-wider" onchange="this.form.submit()">
                            <option value="newest" <?php if($current_sort == 'newest' || $current_sort == '') echo 'selected'; ?>>Newest</option>
                            <option value="price_low" <?php if($current_sort == 'price_low') echo 'selected'; ?>>Price: Low ➔ High</option>
                            <option value="price_high" <?php if($current_sort == 'price_high') echo 'selected'; ?>>Price: High ➔ Low</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 ml-auto xl:ml-0">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-md hover:shadow-lg active:scale-95 flex items-center gap-2">
                            Apply Filter
                        </button>

                        <?php if($current_max != '500000' || $current_stock != '' || $current_sort != ''): ?>
                            <a href="product-category.php<?php echo ($current_id != '') ? '?id='.$current_id.'&type='.$current_type : ''; ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-500 dark:bg-red-500/10 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors" title="Clear Filters">
                                <span class="material-symbols-outlined text-[18px]">close</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 md:gap-8">
            <?php
            if($prod_count == 0) {
                echo '<div class="col-span-full py-32 text-center bg-white dark:bg-slate-800/50 rounded-[3rem] border border-slate-200/50 dark:border-slate-700/50 shadow-sm" data-aos="fade-up">';
                echo '<span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-6 block">inventory_2</span>';
                echo '<h3 class="font-headline font-black text-3xl text-slate-900 dark:text-white mb-3">No instruments found</h3>';
                echo '<p class="text-slate-500 dark:text-slate-400 font-medium">Try adjusting your filters or price range to find what you are looking for.</p>';
                echo '</div>';
            } else {
                $delay = 0;
                foreach ($products_to_display as $row) {
                    ?>
                    <div class="group bg-white dark:bg-slate-800 rounded-[2.5rem] p-5 border border-slate-100 dark:border-slate-700/60 hover:shadow-2xl hover:shadow-blue-500/5 dark:hover:shadow-indigo-500/10 hover:-translate-y-2 transition-all duration-500 flex flex-col relative overflow-hidden" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                        
                        <div class="relative bg-slate-50 dark:bg-slate-900/60 rounded-[2rem] aspect-[4/3] mb-6 overflow-hidden flex items-center justify-center p-6 border border-slate-100/50 dark:border-slate-700/30">
                            <a href="product.php?id=<?php echo $row['p_id']; ?>" class="w-full h-full flex items-center justify-center">
                                <img src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo htmlspecialchars($row['p_name']); ?>" class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal group-hover:scale-110 transition-transform duration-700 ease-out"/>
                            </a>
                            
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                <?php if($row['p_qty'] == 0): ?>
                                    <span class="px-3 py-1 bg-red-500 text-white text-[9px] font-black uppercase tracking-widest rounded-full shadow-md">Out of Stock</span>
                                <?php else: ?>
                                    <span class="px-3 py-1 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black uppercase tracking-widest rounded-full shadow-md">Available</span>
                                <?php endif; ?>
                            </div>
                            
                            <?php if($row['p_qty'] > 0): ?>
                            <div class="absolute bottom-4 right-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                <a href="product.php?id=<?php echo $row['p_id']; ?>" class="w-12 h-12 bg-blue-600 text-white rounded-full flex items-center justify-center hover:bg-blue-700 hover:scale-110 transition-all shadow-lg shadow-blue-600/30">
                                    <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                                </a>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex-grow flex flex-col px-2">
                            <h3 class="text-lg font-headline font-bold tracking-tight text-slate-900 dark:text-white mb-1 line-clamp-2 group-hover:text-blue-600 dark:group-hover:text-indigo-400 transition-colors">
                                <a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo htmlspecialchars($row['p_name']); ?></a>
                            </h3>
                            
                            <div class="flex items-center gap-1 text-yellow-400 mb-4">
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
                                        echo '<span class="material-symbols-outlined text-[14px]" style="font-variation-settings: \'FILL\' 1;">star</span>';
                                    } elseif ($i - 0.5 == $avg_rating) {
                                        echo '<span class="material-symbols-outlined text-[14px]" style="font-variation-settings: \'FILL\' 1;">star_half</span>';
                                    } else {
                                        echo '<span class="material-symbols-outlined text-[14px]" style="font-variation-settings: \'FILL\' 0;">star</span>';
                                    }
                                }
                                ?>
                            </div>
                            
                            <div class="mt-auto flex items-end justify-between">
                                <div>
                                    <?php if($row['p_old_price'] != ''): ?>
                                        <div class="text-[10px] font-black uppercase tracking-widest line-through text-slate-400 mb-0.5">₹<?php echo number_format($row['p_old_price']); ?></div>
                                    <?php endif; ?>
                                    <span class="text-2xl font-headline font-black text-slate-900 dark:text-white tracking-tighter">₹<?php echo number_format($row['p_current_price']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $delay += 50;
                    if($delay > 200) $delay = 0;
                }
            }
            ?>
        </div>

        <section class="mt-24 pt-16 border-t border-slate-200 dark:border-slate-800" data-aos="fade-up">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 h-[450px]">
                
                <div class="bg-slate-900 rounded-[3rem] relative overflow-hidden group cursor-pointer shadow-xl" onclick="window.location.href='product-category.php';">
                    <img class="absolute inset-0 w-full h-full object-cover opacity-60 mix-blend-overlay group-hover:scale-105 transition-transform duration-1000" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCLl9I830xjbjxsauuiJ7VGubzwghYkE3meKLfK55-2zjkoqad6HYt-o_wZUGOeMnKAyxVdZjA8F7x2iBSID1bmNRv6AK3fIvcGVLs9Roq_OwPudSHs3_3SVw36Fe4DkuJK2isgnxAq6oPohlrqPyIf6muAuX8Ry76DsSXIAugCX1SldKJj5hKFzFNQYWq-0xjDiTNteQRApRxnyrAmxaFkcDQCYQrqzddpfjg3rCXa-4wdQrjbxBVSQpX2EuUXwqu12LzsMlwaG5Zy" alt="Pro Laptops"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent p-12 flex flex-col justify-end">
                        <span class="bg-blue-600 text-white w-max px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-[0.2em] mb-4 shadow-lg">Pro Series</span>
                        <h3 class="text-4xl font-headline font-black text-white tracking-tighter mb-3 leading-none">The Creator<br>Studio.</h3>
                        <p class="text-slate-300 max-w-sm mb-8 text-sm font-medium">Desktop-class performance in a portable, aerospace-grade framework.</p>
                        <span class="text-xs font-bold uppercase tracking-widest text-blue-400 flex items-center gap-2 group-hover:translate-x-2 transition-transform">Explore <span class="material-symbols-outlined text-[16px]">arrow_forward</span></span>
                    </div>
                </div>

                <div class="bg-indigo-900 rounded-[3rem] relative overflow-hidden group cursor-pointer shadow-xl" onclick="window.location.href='product-category.php';">
                    <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 to-purple-800 opacity-90 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-white/10 via-transparent to-transparent opacity-30 group-hover:scale-150 transition-transform duration-1000"></div>
                    
                    <div class="absolute inset-0 p-12 flex flex-col justify-end relative z-10">
                        <span class="material-symbols-outlined text-6xl text-white/50 mb-auto group-hover:scale-110 transition-transform duration-500">devices_wearables</span>
                        <h3 class="text-4xl font-headline font-black text-white tracking-tighter mb-3 leading-none">Smart<br>Ecosystems.</h3>
                        <p class="text-indigo-100 max-w-sm mb-8 text-sm font-medium leading-relaxed">Seamlessly connect your world with our curated selection of intelligent peripherals and smart home devices.</p>
                        <span class="text-xs font-bold uppercase tracking-widest text-white flex items-center gap-2 group-hover:translate-x-2 transition-transform">Discover <span class="material-symbols-outlined text-[16px]">arrow_forward</span></span>
                    </div>
                </div>

            </div>
        </section>

    </div>
</main>

<?php require_once('footer.php'); ?>