<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) {
    $featured_product_title = $row['featured_product_title'];
    $latest_product_title = $row['latest_product_title'];
    $total_featured_product_home = $row['total_featured_product_home'];
    $total_latest_product_home = $row['total_latest_product_home'];
    $home_featured_product_on_off = $row['home_featured_product_on_off'];
    $home_latest_product_on_off = $row['home_latest_product_on_off'];
    $home_service_on_off = $row['home_service_on_off'];
}
?>

<style>
    @keyframes ticker {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-ticker { display: flex; width: 200%; animation: ticker 30s linear infinite; }
    .animate-ticker:hover { animation-play-state: paused; }
    
    @keyframes blob {
      0% { transform: translate(0px, 0px) scale(1); }
      33% { transform: translate(30px, -50px) scale(1.1); }
      66% { transform: translate(-20px, 20px) scale(0.9); }
      100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob { animation: blob 7s infinite; }
    .animation-delay-2000 { animation-delay: 2s; }
    .animation-delay-4000 { animation-delay: 4s; }
</style>

<main class="pt-20 bg-[#faf8ff] dark:bg-slate-950 transition-colors duration-500 overflow-hidden">
    
    <section class="relative min-h-[85vh] flex items-center px-6 md:px-12 overflow-hidden">
        <div class="absolute top-1/4 -right-64 w-[800px] h-[800px] bg-[#0052CC]/10 dark:bg-sky-500/20 rounded-full blur-[120px] animate-[pulse_8s_ease-in-out_infinite] pointer-events-none"></div>
        <div class="absolute bottom-0 -left-64 w-[600px] h-[600px] bg-teal-500/10 dark:bg-teal-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        <div class="max-w-[1400px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center py-20 w-full relative z-10">
            
            <div class="space-y-8" data-aos="fade-right" data-aos-duration="1200">
                <div class="inline-flex items-center gap-3 px-4 py-2 bg-white/60 dark:bg-slate-900/60 backdrop-blur-md border border-slate-200/50 dark:border-slate-700/50 text-[#0052CC] dark:text-sky-400 text-[10px] font-black tracking-[0.2em] uppercase rounded-full shadow-sm">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#0052CC] dark:bg-sky-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-[#0052CC] dark:bg-sky-400"></span>
                    </span>
                    The Latest Technology
                </div>
                <h1 class="font-headline text-6xl lg:text-[5.5rem] font-black text-slate-900 dark:text-white leading-[1.05] tracking-tighter">
                    ELEVATE YOUR <br/><span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0052CC] to-teal-400 dark:from-sky-400 dark:to-cyan-300">DIGITAL LIFE.</span>
                </h1>
                <p class="text-lg md:text-xl text-slate-600 dark:text-slate-400 max-w-lg leading-relaxed font-medium">
                    Shop the newest smartphones, laptops, and premium accessories from the world's most trusted brands.
                </p>
                <div class="flex flex-wrap items-center gap-4 pt-4">
                    <a href="product-category.php" class="bg-[#0052CC] hover:bg-blue-700 dark:bg-sky-600 dark:hover:bg-sky-500 text-white px-10 py-4 rounded-full font-headline font-bold text-sm tracking-widest uppercase transition-all shadow-lg hover:shadow-xl hover:-translate-y-1">
                        Explore Collection
                    </a>
                </div>
            </div>
            
            <div class="relative w-full max-w-lg mx-auto lg:mx-0 mt-12 lg:mt-0" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
                <div class="absolute top-0 -left-4 w-64 h-64 bg-blue-400 dark:bg-sky-600 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-2xl opacity-50 dark:opacity-30 animate-blob"></div>
                <div class="absolute top-0 -right-4 w-64 h-64 bg-teal-400 dark:bg-teal-600 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-2xl opacity-50 dark:opacity-30 animate-blob animation-delay-2000"></div>
                <div class="absolute -bottom-8 left-20 w-64 h-64 bg-indigo-400 dark:bg-indigo-600 rounded-full mix-blend-multiply dark:mix-blend-screen filter blur-2xl opacity-50 dark:opacity-30 animate-blob animation-delay-4000"></div>

                <div class="relative grid grid-cols-2 gap-4">
                    <div class="col-span-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl p-8 rounded-3xl border border-white/40 dark:border-slate-700 shadow-2xl transform translate-y-2 hover:-translate-y-1 transition-transform duration-500">
                        <div class="flex items-center justify-between mb-4">
                            <div class="w-14 h-14 rounded-2xl bg-blue-50 dark:bg-blue-500/10 text-[#0052CC] dark:text-sky-400 flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl">memory</span>
                            </div>
                            <span class="px-3 py-1 bg-green-100 text-green-700 dark:bg-green-500/20 dark:text-green-400 text-[10px] font-black uppercase tracking-widest rounded-full">In Stock</span>
                        </div>
                        <h3 class="text-2xl font-headline font-black text-slate-900 dark:text-white mb-2">Next-Gen Processing</h3>
                        <p class="text-sm font-medium text-slate-500 dark:text-slate-400">Experience unparalleled speed and efficiency with our latest laptops.</p>
                    </div>

                    <div class="bg-gradient-to-br from-[#0052CC] to-indigo-700 dark:from-sky-600 dark:to-indigo-800 p-6 rounded-3xl shadow-xl transform hover:-translate-y-2 transition-transform duration-500 text-white flex flex-col justify-between">
                        <span class="material-symbols-outlined text-4xl mb-6 opacity-80">devices_wearables</span>
                        <div>
                            <h3 class="text-lg font-headline font-black mb-1">Smart Wearables</h3>
                            <p class="text-xs font-medium text-blue-100 opacity-80">Track your active life.</p>
                        </div>
                    </div>

                    <div class="bg-white/80 dark:bg-slate-800/80 backdrop-blur-xl p-6 rounded-3xl border border-white/40 dark:border-slate-700 shadow-xl transform translate-y-6 hover:translate-y-4 transition-transform duration-500 flex flex-col justify-between">
                        <span class="material-symbols-outlined text-4xl mb-6 text-teal-500 dark:text-teal-400">photo_camera</span>
                        <div>
                            <h3 class="text-lg font-headline font-black text-slate-900 dark:text-white mb-1">Premium Cameras</h3>
                            <p class="text-xs font-medium text-slate-500 dark:text-slate-400">Capture the moment.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="overflow-hidden bg-[#0052CC] dark:bg-sky-600 py-3 relative z-20 shadow-lg flex border-y border-blue-600 dark:border-sky-500">
        <div class="animate-ticker text-white font-black uppercase tracking-[0.2em] text-[10px] md:text-xs flex items-center shrink-0">
            <span class="px-8 flex items-center gap-4"><span class="material-symbols-outlined text-[16px]">verified</span> 100% AUTHENTIC BRANDS</span> <span class="opacity-50">•</span>
            <span class="px-8 flex items-center gap-4"><span class="material-symbols-outlined text-[16px]">local_shipping</span> FAST & SECURE DELIVERY</span> <span class="opacity-50">•</span>
            <span class="px-8 flex items-center gap-4"><span class="material-symbols-outlined text-[16px]">support_agent</span> 24/7 EXPERT SUPPORT</span> <span class="opacity-50">•</span>
            <span class="px-8 flex items-center gap-4"><span class="material-symbols-outlined text-[16px]">lock</span> SECURE CHECKOUT</span> <span class="opacity-50">•</span>
            <span class="px-8 flex items-center gap-4"><span class="material-symbols-outlined text-[16px]">verified</span> 100% AUTHENTIC BRANDS</span> <span class="opacity-50">•</span>
            <span class="px-8 flex items-center gap-4"><span class="material-symbols-outlined text-[16px]">local_shipping</span> FAST & SECURE DELIVERY</span> <span class="opacity-50">•</span>
            <span class="px-8 flex items-center gap-4"><span class="material-symbols-outlined text-[16px]">support_agent</span> 24/7 EXPERT SUPPORT</span> <span class="opacity-50">•</span>
            <span class="px-8 flex items-center gap-4"><span class="material-symbols-outlined text-[16px]">lock</span> SECURE CHECKOUT</span> <span class="opacity-50">•</span>
        </div>
    </div>

    <section class="py-10 border-b border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto px-6 md:px-12 text-center">
            <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em] mb-6">Trusted by global industry leaders</p>
            <div class="flex flex-wrap justify-center items-center gap-10 md:gap-20 opacity-40 hover:opacity-100 grayscale hover:grayscale-0 transition-all duration-700">
                <h3 class="text-2xl md:text-3xl font-black font-headline tracking-tighter text-slate-800 dark:text-slate-200">SAMSUNG</h3>
                <h3 class="text-2xl md:text-3xl font-black font-headline tracking-tight text-slate-800 dark:text-slate-200">SONY</h3>
                <h3 class="text-2xl md:text-3xl font-black font-headline tracking-widest text-slate-800 dark:text-slate-200">DELL</h3>
                <h3 class="text-2xl md:text-3xl font-black font-headline tracking-tighter text-slate-800 dark:text-slate-200">LG</h3>
                <h3 class="text-2xl md:text-3xl font-black font-headline tracking-tighter text-slate-800 dark:text-slate-200 italic">ASUS</h3>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 md:px-12 bg-[#faf8ff] dark:bg-slate-950 transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto">
            <div class="mb-16 text-center max-w-2xl mx-auto" data-aos="fade-up">
                <span class="text-[#0052CC] dark:text-sky-400 font-black text-[10px] tracking-[0.3em] uppercase mb-4 block">The Store Promise</span>
                <h2 class="font-headline text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight">WHY CHOOSE US?</h2>
                <p class="text-slate-500 dark:text-slate-400 mt-4 text-sm md:text-base font-medium">We provide a hassle-free shopping experience for all your electronic needs, backed by reliable customer service.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-slate-900 p-10 rounded-[2rem] border border-slate-200/50 dark:border-slate-800 hover:shadow-2xl hover:shadow-blue-500/10 dark:hover:shadow-sky-500/10 hover:-translate-y-2 transition-all duration-500 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-blue-50 dark:bg-sky-500/10 text-[#0052CC] dark:text-sky-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-3xl">star</span>
                    </div>
                    <h3 class="font-headline font-black text-xl text-slate-900 dark:text-white mb-3">Top-Tier Brands</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">We only stock products from reputable, globally recognized manufacturers. Guaranteed authentic, original packaging.</p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-10 rounded-[2rem] border border-slate-200/50 dark:border-slate-800 hover:shadow-2xl hover:shadow-blue-500/10 dark:hover:shadow-sky-500/10 hover:-translate-y-2 transition-all duration-500 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-teal-50 dark:bg-teal-500/10 text-teal-600 dark:text-teal-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-3xl">shield_locked</span>
                    </div>
                    <h3 class="font-headline font-black text-xl text-slate-900 dark:text-white mb-3">Secure Transactions</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Shop with confidence. Our checkout process uses industry-leading encryption to keep your payment details completely safe.</p>
                </div>
                <div class="bg-white dark:bg-slate-900 p-10 rounded-[2rem] border border-slate-200/50 dark:border-slate-800 hover:shadow-2xl hover:shadow-blue-500/10 dark:hover:shadow-sky-500/10 hover:-translate-y-2 transition-all duration-500 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-16 h-16 bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <span class="material-symbols-outlined text-3xl">headphones</span>
                    </div>
                    <h3 class="font-headline font-black text-xl text-slate-900 dark:text-white mb-3">Dedicated Support</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm leading-relaxed">Got a question? Our support team is ready to help you with order tracking, returns, and technical troubleshooting.</p>
                </div>
            </div>
        </div>
    </section>

    <?php if($home_latest_product_on_off == 1): ?>
    <section class="py-24 px-6 md:px-12 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto">
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6" data-aos="fade-up">
                <div>
                    <span class="text-[#0052CC] dark:text-sky-400 font-black text-[10px] tracking-[0.3em] uppercase mb-4 block">Fresh Tech</span>
                    <h2 class="font-headline text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight">
                        <?php echo empty($latest_product_title) ? 'NEW ARRIVALS' : strtoupper($latest_product_title); ?>
                    </h2>
                </div>
                <a href="product-category.php" class="font-headline font-bold text-sm tracking-widest uppercase text-[#0052CC] dark:text-sky-400 hover:text-blue-800 dark:hover:text-sky-300 transition-colors flex items-center gap-2">View All Inventory <span class="material-symbols-outlined text-[18px]">arrow_forward</span></a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_active=? ORDER BY p_id DESC LIMIT ".$total_latest_product_home);
                $statement->execute(array(1));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC); 
                $delay = 0;                           
                foreach ($result as $row) {
                ?>
                <div class="group relative bg-slate-50 dark:bg-slate-800 rounded-[2rem] p-6 border border-slate-200/50 dark:border-slate-700/50 hover:shadow-2xl hover:shadow-blue-500/5 dark:hover:shadow-sky-500/10 hover:-translate-y-2 transition-all duration-500 flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    
                    <div class="relative bg-white dark:bg-slate-900/50 rounded-xl aspect-[4/3] mb-6 overflow-hidden flex items-center justify-center p-4 border border-slate-100 dark:border-slate-700/50">
                        <a href="product.php?id=<?php echo $row['p_id']; ?>" class="w-full h-full flex items-center justify-center">
                            <img class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal group-hover:scale-110 transition-transform duration-700" src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo htmlspecialchars($row['p_name']); ?>"/>
                        </a>
                        
                        <?php if($row['p_qty'] == 0): ?>
                            <div class="absolute top-3 left-3 bg-red-500 text-white text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-md">Sold Out</div>
                        <?php else: ?>
                            <div class="absolute top-3 left-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-md">New</div>
                        <?php endif; ?>
                        
                        <?php if($row['p_qty'] > 0): ?>
                        <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                            <a href="product.php?id=<?php echo $row['p_id']; ?>" class="w-10 h-10 bg-[#0052CC] text-white rounded-full flex items-center justify-center hover:bg-blue-700 hover:scale-110 transition-all shadow-lg">
                                <span class="material-symbols-outlined text-[18px]">add_shopping_cart</span>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex-grow flex flex-col">
                        <h3 class="font-headline font-bold text-lg text-slate-900 dark:text-white leading-tight mb-2 line-clamp-2 group-hover:text-[#0052CC] dark:group-hover:text-sky-400 transition-colors">
                            <a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo htmlspecialchars($row['p_name']); ?></a>
                        </h3>
                        <div class="mt-auto pt-4 flex items-end justify-between">
                            <div class="text-2xl font-headline font-black text-slate-900 dark:text-white">
                                ₹<?php echo number_format($row['p_current_price']); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $delay += 100; } ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="py-12 px-6 md:px-12 bg-[#faf8ff] dark:bg-slate-950 transition-colors duration-500 border-t border-slate-100 dark:border-slate-800">
        <div class="max-w-[1400px] mx-auto relative rounded-[3rem] overflow-hidden bg-slate-900 min-h-[400px] flex items-center shadow-2xl" data-aos="zoom-in" data-aos-duration="1000">
            <div class="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat opacity-40 mix-blend-overlay" style="background-image: url('assets/uploads/slider-1.jpg');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-900/90 to-transparent"></div>
            
            <div class="relative z-10 px-8 md:px-20 max-w-2xl text-white" data-aos="fade-up" data-aos-delay="300">
                <span class="text-sky-400 font-black tracking-[0.4em] uppercase text-[10px] mb-4 block">Limited Time Deals</span>
                <h2 class="text-4xl md:text-6xl font-headline font-black mb-6 tracking-tighter leading-tight text-white">UPGRADE YOUR <br>WORKSPACE</h2>
                <p class="text-lg text-slate-300 mb-8 font-medium max-w-xl leading-relaxed">Shop our premium collection of powerful laptops, noise-canceling headphones, and productivity accessories.</p>
                <a href="product-category.php" class="bg-white text-slate-900 px-8 py-3.5 rounded-full font-headline font-bold text-sm tracking-widest uppercase hover:bg-slate-100 hover:scale-105 transition-all shadow-[0_0_40px_rgba(255,255,255,0.2)] active:scale-95 inline-block">
                    Shop Now
                </a>
            </div>
        </div>
    </section>

    <?php if($home_featured_product_on_off == 1): ?>
    <section class="py-24 px-6 md:px-12 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800 transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto">
            <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6" data-aos="fade-up">
                <div>
                    <span class="text-[#0052CC] dark:text-sky-400 font-black text-[10px] tracking-[0.3em] uppercase mb-4 block">Our Bestsellers</span>
                    <h2 class="font-headline text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight">
                        <?php echo empty($featured_product_title) ? 'FLAGSHIP DEVICES' : strtoupper($featured_product_title); ?>
                    </h2>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_featured=? AND p_is_active=? LIMIT ".$total_featured_product_home);
                $statement->execute(array(1,1));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC); 
                $delay = 0;                           
                foreach ($result as $row) {
                ?>
                <div class="group relative bg-slate-50 dark:bg-slate-800 rounded-[2rem] p-6 border border-slate-200/50 dark:border-slate-700/50 hover:shadow-2xl hover:shadow-blue-500/10 dark:hover:shadow-sky-500/10 hover:-translate-y-2 transition-all duration-500 flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    
                    <div class="relative bg-white dark:bg-slate-900/80 rounded-xl aspect-[4/3] mb-6 overflow-hidden flex items-center justify-center p-4 border border-slate-100 dark:border-slate-700/50 shadow-sm">
                        <a href="product.php?id=<?php echo $row['p_id']; ?>" class="w-full h-full flex items-center justify-center">
                            <img class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal group-hover:scale-110 transition-transform duration-700" src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo htmlspecialchars($row['p_name']); ?>"/>
                        </a>
                        
                        <div class="absolute top-3 left-3 bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-md">Top Rated</div>
                        
                        <?php if($row['p_qty'] > 0): ?>
                        <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0 transition-all duration-300">
                            <a href="product.php?id=<?php echo $row['p_id']; ?>" class="w-10 h-10 bg-slate-900 dark:bg-white text-white dark:text-slate-900 rounded-full flex items-center justify-center hover:scale-110 transition-all shadow-lg">
                                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex-grow flex flex-col">
                        <h3 class="font-headline font-bold text-lg text-slate-900 dark:text-white leading-tight mb-2 line-clamp-2 group-hover:text-[#0052CC] dark:group-hover:text-sky-400 transition-colors">
                            <a href="product.php?id=<?php echo $row['p_id']; ?>"><?php echo htmlspecialchars($row['p_name']); ?></a>
                        </h3>
                        <div class="mt-auto pt-4 flex flex-col">
                            <?php if($row['p_old_price'] != ''): ?>
                                <span class="text-xs font-bold text-slate-400 line-through mb-0.5">₹<?php echo number_format($row['p_old_price']); ?></span>
                            <?php endif; ?>
                            <span class="text-2xl font-headline font-black text-slate-900 dark:text-white">₹<?php echo number_format($row['p_current_price']); ?></span>
                        </div>
                    </div>
                </div>
                <?php $delay += 100; } ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="py-24 px-6 md:px-12 bg-[#faf8ff] dark:bg-slate-950 border-t border-slate-100 dark:border-slate-800 transition-colors duration-500">
        <div class="max-w-[1400px] mx-auto">
            <div class="mb-16 text-center max-w-2xl mx-auto" data-aos="fade-up">
                <span class="text-[#0052CC] dark:text-sky-400 font-black text-[10px] tracking-[0.3em] uppercase mb-4 block">Real Reviews</span>
                <h2 class="font-headline text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight">WHAT OUR CUSTOMERS SAY</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-200/50 dark:border-slate-800 shadow-sm hover:shadow-xl transition-shadow duration-300" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex text-yellow-400 mb-6">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 text-sm font-medium leading-relaxed mb-8 italic">"Absolutely incredible service. I ordered a new laptop for work and it arrived the very next day in pristine condition. The support team was also very helpful when I had a question about the warranty."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden flex-shrink-0">
                            <img src="assets/uploads/user-1.jpg" alt="Customer" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/100x100/e2e8f0/64748b?text=JD'">
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">James D.</h4>
                            <p class="text-xs text-slate-400 font-bold">Verified Buyer</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-200/50 dark:border-slate-800 shadow-sm hover:shadow-xl transition-shadow duration-300" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex text-yellow-400 mb-6">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 text-sm font-medium leading-relaxed mb-8 italic">"The prices here are unbeatable. I was able to find the exact camera lens I was looking for, and it was significantly cheaper than other major retailers. Highly recommend this store!"</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center text-slate-500 font-bold text-lg flex-shrink-0">S</div>
                        <div>
                            <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">Sarah M.</h4>
                            <p class="text-xs text-slate-400 font-bold">Verified Buyer</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-900 p-8 rounded-[2rem] border border-slate-200/50 dark:border-slate-800 shadow-sm hover:shadow-xl transition-shadow duration-300" data-aos="fade-up" data-aos-delay="300">
                    <div class="flex text-yellow-400 mb-6">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star</span>
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">star_half</span>
                    </div>
                    <p class="text-slate-600 dark:text-slate-300 text-sm font-medium leading-relaxed mb-8 italic">"Great selection of authentic products. My smartwatch setup was a breeze. Only giving 4.5 stars because the delivery driver got lost, but the product itself and the store's packaging were perfect."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-slate-200 dark:bg-slate-700 rounded-full flex items-center justify-center text-slate-500 font-bold text-lg flex-shrink-0">M</div>
                        <div>
                            <h4 class="text-sm font-black text-slate-900 dark:text-white uppercase tracking-wider">Michael T.</h4>
                            <p class="text-xs text-slate-400 font-bold">Verified Buyer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 px-6 md:px-12 bg-gradient-to-r from-[#0052CC] to-indigo-700 dark:from-indigo-900 dark:to-slate-900 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-white opacity-5 rounded-full blur-[80px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-sky-400 opacity-10 rounded-full blur-[60px] pointer-events-none"></div>
        
        <div class="max-w-4xl mx-auto relative z-10 text-center" data-aos="zoom-in">
            <span class="material-symbols-outlined text-5xl text-white/80 mb-4">mark_email_read</span>
            <h2 class="text-3xl md:text-5xl font-headline font-black text-white tracking-tight mb-4">JOIN OUR TECH COMMUNITY</h2>
            <p class="text-blue-100 font-medium text-sm md:text-base max-w-xl mx-auto mb-10">Subscribe to our newsletter to receive exclusive offers, early access to new releases, and expert tech tips directly in your inbox.</p>
            
            <form action="#" method="POST" class="flex flex-col sm:flex-row gap-3 max-w-2xl mx-auto">
                <input type="email" name="email_subscribe" placeholder="Enter your email address..." class="flex-grow bg-white/10 border border-white/20 text-white placeholder-blue-200 rounded-xl px-6 py-4 outline-none focus:bg-white/20 focus:border-white/50 transition-all font-medium" required>
                <button type="submit" class="bg-white text-[#0052CC] dark:text-indigo-900 px-8 py-4 rounded-xl font-black uppercase tracking-widest text-sm hover:scale-105 transition-transform shadow-xl active:scale-95 whitespace-nowrap">
                    Subscribe
                </button>
            </form>
            <p class="text-blue-200/60 text-xs mt-4">We respect your privacy. No spam, ever.</p>
        </div>
    </section>

</main>

<?php require_once('footer.php'); ?>