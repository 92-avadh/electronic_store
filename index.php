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

<main class="pt-20 bg-white dark:bg-slate-900 transition-colors duration-300">
    <section class="min-h-[85vh] flex items-center px-6 md:px-12 relative overflow-hidden bg-gradient-to-tr from-indigo-50/50 to-white dark:from-slate-900 dark:to-slate-900">
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/5 dark:bg-indigo-500/10 rounded-full blur-3xl -z-10 animate-[pulse_6s_ease-in-out_infinite]"></div>
        
        <div class="max-w-[1440px] mx-auto grid grid-cols-1 lg:grid-cols-2 gap-16 items-center py-20 w-full">
            <div class="space-y-8" data-aos="fade-right" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-primary dark:text-indigo-400 text-xs font-bold tracking-[0.2em] uppercase rounded-full shadow-sm transition-colors duration-300">
                    <span class="w-2 h-2 rounded-full bg-primary dark:bg-indigo-400 animate-ping"></span>
                    Next-Gen Architecture
                </div>
                <h1 class="font-headline text-6xl lg:text-8xl font-black text-surfaceDark dark:text-white leading-[1] tracking-tighter">
                    THE FUTURE <br/><span class="text-gradient">IS NOW.</span>
                </h1>
                <p class="text-lg text-textMuted dark:text-slate-400 max-w-md leading-relaxed font-medium">
                    Experience unrivaled performance with our curated selection of elite computing and smart home innovations. Designed for those who demand the best.
                </p>
                <div class="flex items-center space-x-5 pt-4">
                    <a href="product-category.php" class="bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white px-8 py-4 rounded-full font-headline font-bold text-sm tracking-widest uppercase transition-all shadow-lg shadow-primary/30 dark:shadow-indigo-500/20 active:scale-95 inline-block">
                        Shop Collection
                    </a>
                </div>
            </div>
            <div class="relative group" data-aos="fade-left" data-aos-duration="1200" data-aos-delay="200">
                <div class="absolute inset-0 bg-primary/10 dark:bg-indigo-500/20 rounded-[2rem] blur-2xl transform group-hover:scale-105 transition-transform duration-700"></div>
                <img alt="Premium tech device" class="relative rounded-2xl transform transition-transform duration-700 group-hover:-translate-y-4 drop-shadow-2xl z-10" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBmM0vk2pt3bgNFDLMJGi-nu5JSE7ImYHWX-bmnIwVM-mdg70TX76Cf0VmrymbxHSTaw8yockxj_HXwJeDbMx7zDyAFwysxTX_-zPnjizzLUyymz2mWEOg8M4TxIuJ-FjLXPGzAR-Hx4ZSgpzK3zyacTLZup-s-lDPvJ6BPJL6y5E35tr-9oviWLSrUzeew-ig8p0QVg1IH4PCLNcCiB6UPnDDApExT2O1QdbvGrn8WtjdOolPsqm1a-jvJAz2dv2H1ECfwtOAz4n0f" style="width: 100%; height: auto; object-fit: cover; aspect-ratio: 4/3;"/>
            </div>
        </div>
    </section>

    <section class="py-24 px-6 md:px-12 bg-surface dark:bg-slate-800/30 transition-colors duration-300 border-y border-slate-100 dark:border-slate-800/50">
        <div class="max-w-[1440px] mx-auto">
            <div class="mb-10" data-aos="fade-up">
                <span class="text-primary dark:text-indigo-400 font-bold text-xs tracking-[0.3em] uppercase mb-4 block">Ecosystems</span>
                <h2 class="font-headline text-4xl font-black text-surfaceDark dark:text-white tracking-tight">CURATED COLLECTIONS</h2>
            </div>
            <div class="grid grid-cols-1 gap-6 h-[400px] md:h-[450px]">
                
                <div class="relative rounded-3xl overflow-hidden bg-slate-200 dark:bg-slate-800 group cursor-pointer shadow-xl" onclick="window.location.href='product-category.php';">
                    <img class="absolute inset-0 w-full h-full object-cover mix-blend-multiply dark:mix-blend-normal opacity-90 transition-transform duration-1000 group-hover:scale-105" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDecRGXKpxy-quMvXQngfCGJr-KYUTdNw904H67-dy730H6mUGnNkxJqF-L-7WZc57pu4VDP7F1tpXExahDAOAAHZes271Xfwpp6G_4tBlxuiVvvF0DxC9ui44XG6bbb9iU-qW__6XHxjhNDxeosidOOFqkpYiQ3bMrHmNlVaLYV3AZ7FlIaZzLvCTg7VYAfMVt8ky32g0nUfgN5jWxA_Ms7RBdt29UmXbFTU0urHy9baWzxjIW2VX4q8AtHo-PgZGvqDYWah1kji4d" alt="Laptops"/>
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent"></div>
                    <div class="absolute bottom-12 left-12 text-white">
                        <h3 class="text-4xl md:text-6xl font-headline font-black mb-3">LAPTOPS</h3>
                        <p class="text-sm md:text-base tracking-widest font-bold opacity-80 uppercase">Precision Engineered. Performance First.</p>
                    </div>
                    <div class="absolute bottom-12 right-12 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-500 hidden md:block">
                        <span class="material-symbols-outlined text-5xl">arrow_forward</span>
                    </div>
                </div>
                
            </div>
        </div>
    </section>

    <?php if($home_latest_product_on_off == 1): ?>
    <section class="py-24 px-6 md:px-12 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-[1440px] mx-auto">
            <div class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-6" data-aos="fade-up">
                <div>
                    <span class="text-primary dark:text-indigo-400 font-bold text-xs tracking-[0.3em] uppercase mb-4 block">Just Dropped</span>
                    <h2 class="font-headline text-4xl font-black text-surfaceDark dark:text-white tracking-tight">
                        <?php echo empty($latest_product_title) ? 'NEW ARRIVALS' : strtoupper($latest_product_title); ?>
                    </h2>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_active=? ORDER BY p_id DESC LIMIT ".$total_latest_product_home);
                $statement->execute(array(1));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC); 
                $delay = 0;                           
                foreach ($result as $row) {
                ?>
                <div class="group cursor-pointer" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>" onclick="window.location.href='product.php?id=<?php echo $row['p_id']; ?>';">
                    <div class="relative bg-surface dark:bg-slate-800 rounded-[2rem] aspect-square mb-6 overflow-hidden flex items-center justify-center p-6 border border-slate-100 dark:border-slate-700/50 group-hover:shadow-xl dark:group-hover:shadow-indigo-500/10 transition-all duration-300">
                        <img class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal group-hover:scale-110 transition-transform duration-500" src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>"/>
                        <?php if($row['p_qty'] == 0): ?>
                            <div class="absolute top-4 left-4 bg-red-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">Sold Out</div>
                        <?php else: ?>
                            <div class="absolute top-4 left-4 bg-primary dark:bg-indigo-500 text-white text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-wider">New</div>
                        <?php endif; ?>
                    </div>
                    <h3 class="font-headline font-bold text-lg text-surfaceDark dark:text-white leading-tight mb-1 truncate group-hover:text-primary dark:group-hover:text-indigo-400 transition-colors">
                        <?php echo $row['p_name']; ?>
                    </h3>
                    <div class="text-lg font-black text-textMuted dark:text-slate-400">
                        ₹<?php echo $row['p_current_price']; ?>
                    </div>
                </div>
                <?php 
                $delay += 100;
                } ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <section class="py-12 px-6 md:px-12 bg-white dark:bg-slate-900 transition-colors duration-300">
        <div class="max-w-[1440px] mx-auto relative rounded-[2.5rem] overflow-hidden bg-surfaceDark min-h-[500px] flex items-center" data-aos="zoom-in" data-aos-duration="1000">
            <div class="absolute inset-0 w-full h-full bg-cover bg-center bg-no-repeat opacity-40 mix-blend-overlay" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDEXZHDJoC3nymERWwvAalQJ2jgOtGryxiVxTUXiH8h8hbQhiDNjyobYFxWTSeake0qqfpjX9CeC5Ax-12i361TB52o3iN01r31Qvd1s2eDY7rzmCYsKDgfTr9B9TXhuBqcbS8IoGctBwu8qyHhYb0xiSqSSlZVJEFSa6bv2NYzMs5-akEhBzjuTxlYmRvoVrFNalioIsRLJzfs9f8n5rLxhAJGS228_xVHZmkt2qwcV5Kpt9wBlHq8z7UGt7i3ArjHwO0JIGt9Nc7V');"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-surfaceDark via-surfaceDark/90 to-transparent"></div>
            
            <div class="relative z-10 px-8 md:px-20 max-w-3xl text-white" data-aos="fade-up" data-aos-delay="300">
                <span class="text-primary dark:text-indigo-400 font-bold tracking-[0.4em] uppercase text-xs mb-6 block">Signature Collection</span>
                <h2 class="text-5xl md:text-7xl font-headline font-black mb-6 tracking-tighter leading-tight">THE SMART<br>HOME REVOLUTION</h2>
                <p class="text-lg md:text-xl text-slate-300 mb-10 font-medium max-w-xl">Upgrade your living space with intelligent, energy-efficient precision. Experience seamless automation designed around you.</p>
                <button class="bg-white text-surfaceDark px-10 py-4 rounded-full font-headline font-bold text-sm tracking-widest uppercase hover:bg-slate-100 hover:scale-105 transition-all shadow-xl active:scale-95">
                    Explore Ecosystem
                </button>
            </div>
        </div>
    </section>

    <?php if($home_featured_product_on_off == 1): ?>
    <section class="py-24 px-6 md:px-12 bg-white dark:bg-slate-900 transition-colors duration-300 border-t border-slate-100 dark:border-slate-800/50">
        <div class="max-w-[1440px] mx-auto">
            <div class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-6" data-aos="fade-up">
                <div>
                    <span class="text-primary dark:text-indigo-400 font-bold text-xs tracking-[0.3em] uppercase mb-4 block">Curated Selection</span>
                    <h2 class="font-headline text-4xl md:text-5xl font-black text-surfaceDark dark:text-white tracking-tight">
                        <?php echo empty($featured_product_title) ? 'ELITE INSTRUMENTS' : strtoupper($featured_product_title); ?>
                    </h2>
                </div>
                <a href="product-category.php" class="font-headline font-bold text-sm tracking-widest uppercase text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-white transition-colors border-b border-transparent hover:border-primary dark:hover:border-white pb-1">View All Products</a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_is_featured=? AND p_is_active=? LIMIT ".$total_featured_product_home);
                $statement->execute(array(1,1));
                $result = $statement->fetchAll(PDO::FETCH_ASSOC); 
                $delay = 0;                           
                foreach ($result as $row) {
                ?>
                <div class="group bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 rounded-3xl p-5 transition-all duration-500 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.1)] dark:hover:shadow-[0_20px_40px_-15px_rgba(79,70,229,0.15)] hover:-translate-y-2 relative flex flex-col" data-aos="fade-up" data-aos-delay="<?php echo $delay; ?>">
                    
                    <div class="aspect-[4/3] bg-surface dark:bg-slate-900/50 rounded-2xl mb-6 overflow-hidden relative flex items-center justify-center">
                        <a href="product.php?id=<?php echo $row['p_id']; ?>">
                            <img class="w-full h-full object-contain p-6 mix-blend-multiply dark:mix-blend-normal group-hover:scale-110 transition-transform duration-700 ease-out" src="assets/uploads/<?php echo $row['p_featured_photo']; ?>" alt="<?php echo $row['p_name']; ?>"/>
                        </a>
                        
                        <?php if($row['p_qty'] > 0): ?>
                            <div class="absolute top-4 right-4 px-3 py-1 bg-white/90 dark:bg-slate-800/90 backdrop-blur-sm text-[10px] font-bold text-surfaceDark dark:text-white tracking-widest uppercase rounded-full shadow-sm border border-slate-200 dark:border-slate-600">Available</div>
                        <?php else: ?>
                            <div class="absolute top-4 right-4 px-3 py-1 bg-red-500 text-[10px] font-bold text-white tracking-widest uppercase rounded-full shadow-sm">Sold Out</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="flex-grow flex flex-col">
                        <h3 class="font-headline font-bold text-lg text-surfaceDark dark:text-white leading-tight mb-2 line-clamp-2">
                            <a href="product.php?id=<?php echo $row['p_id']; ?>" class="hover:text-primary dark:hover:text-indigo-400 transition-colors"><?php echo $row['p_name']; ?></a>
                        </h3>
                        
                        <div class="mt-auto pt-6 flex items-end justify-between">
                            <div>
                                <?php if($row['p_old_price'] != ''): ?>
                                    <div class="text-xs font-semibold line-through text-slate-400 dark:text-slate-500 mb-1">₹<?php echo $row['p_old_price']; ?></div>
                                <?php endif; ?>
                                <div class="text-2xl font-headline font-black text-surfaceDark dark:text-white">
                                    ₹<?php echo $row['p_current_price']; ?>
                                </div>
                            </div>
                            
                            <?php if($row['p_qty'] > 0): ?>
                                <a href="product.php?id=<?php echo $row['p_id']; ?>" class="h-12 w-12 bg-surfaceDark dark:bg-slate-700 text-white rounded-full flex items-center justify-center hover:bg-primary dark:hover:bg-indigo-500 active:scale-90 transition-all shadow-md group-hover:shadow-lg">
                                    <span class="material-symbols-outlined text-xl" data-icon="add_shopping_cart">add_shopping_cart</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php 
                $delay += 100;
                } ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php if($home_service_on_off == 1): ?>
    <section class="py-24 px-6 md:px-12 bg-surface dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 transition-colors duration-300">
        <div class="max-w-[1440px] mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                <?php
                $statement = $pdo->prepare("SELECT * FROM tbl_service LIMIT 3");
                $statement->execute();
                $result = $statement->fetchAll(PDO::FETCH_ASSOC);  
                
                // Fallback elegant icons for the tech store aesthetic
                $icons = ['local_shipping', 'support_agent', 'security']; 
                
                foreach ($result as $index => $row) {
                ?>
                <div class="flex flex-col items-center group" data-aos="fade-up" data-aos-delay="<?php echo $index * 150; ?>">
                    <div class="w-20 h-20 rounded-3xl bg-white dark:bg-slate-800 border border-slate-100 dark:border-slate-700 flex items-center justify-center mb-6 shadow-lg group-hover:bg-primary dark:group-hover:bg-indigo-600 group-hover:text-white text-surfaceDark dark:text-white transition-all duration-300 group-hover:-translate-y-2 group-hover:shadow-primary/20 dark:group-hover:shadow-indigo-500/20">
                        <?php if($row['photo'] != ''): ?>
                            <img src="assets/uploads/<?php echo $row['photo']; ?>" 
                                 class="w-10 h-10 object-contain dark:invert dark:brightness-0 group-hover:invert group-hover:brightness-0 transition-all" 
                                 alt="<?php echo $row['title']; ?>"
                                 onerror="this.onerror=null; this.outerHTML='<span class=\'material-symbols-outlined text-4xl\'><?php echo $icons[$index % 3]; ?></span>';">
                        <?php else: ?>
                            <span class="material-symbols-outlined text-4xl"><?php echo $icons[$index % 3]; ?></span>
                        <?php endif; ?>
                    </div>
                    <h4 class="text-xl font-headline font-bold text-surfaceDark dark:text-white tracking-tight mb-3"><?php echo $row['title']; ?></h4>
                    <p class="text-textMuted dark:text-slate-400 leading-relaxed max-w-sm"><?php echo nl2br($row['content']); ?></p>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

</main>

<?php require_once('footer.php'); ?>