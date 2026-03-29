<?php
ob_start();
session_start();
include("admin/inc/config.php");
include("admin/inc/functions.php");
include("admin/inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
$error_message = '';
$success_message = '';
$error_message1 = '';
$success_message1 = '';

$i=1;
$statement = $pdo->prepare("SELECT * FROM tbl_language");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	define('LANG_VALUE_'.$i,$row['lang_value']);
	$i++;
}

$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id = 1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC); 
foreach ($result as $row) {
	$logo = $row['logo'];
	$favicon = $row['favicon'];
	$contact_email = $row['contact_email'];
	$contact_phone = $row['contact_phone'];
	$meta_title_home = $row['meta_title_home'];
    $meta_keyword_home = $row['meta_keyword_home'];
    $meta_description_home = $row['meta_description_home'];
    $before_head = $row['before_head'];
    $after_body = $row['after_body'];
}

$current_date_time = date('Y-m-d H:i:s');
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?");
$statement->execute(array('Pending'));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	$ts1 = strtotime($row['payment_date']);
	$ts2 = strtotime($current_date_time);     
	$diff = $ts2 - $ts1;
	$time = $diff/(3600);
	if($time>24) {
		$statement1 = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
		$statement1->execute(array($row['payment_id']));
		$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
		foreach ($result1 as $row1) {
			$statement2 = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id=?");
			$statement2->execute(array($row1['product_id']));
			$result2 = $statement2->fetchAll(PDO::FETCH_ASSOC);							
			foreach ($result2 as $row2) { $p_qty = $row2['p_qty']; }
			$final = $p_qty+$row1['quantity'];
			$statement = $pdo->prepare("UPDATE tbl_product SET p_qty=? WHERE p_id=?");
			$statement->execute(array($final,$row1['product_id']));
		}
		$statement1 = $pdo->prepare("DELETE FROM tbl_order WHERE payment_id=?");
		$statement1->execute(array($row['payment_id']));
		$statement1 = $pdo->prepare("DELETE FROM tbl_payment WHERE id=?");
		$statement1->execute(array($row['id']));
	}
}

$cur_page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <link rel="icon" type="image/png" href="assets/uploads/<?php echo $favicon; ?>">

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              primary: "#0ea5e9", // Vivid Blue
              primaryHover: "#0284c7",
              surface: "#f8fafc", 
              surfaceDark: "#0f172a",
              textMain: "#0f172a",
              textMuted: "#64748b",
            },
            fontFamily: { 
              headline: ["Outfit", "sans-serif"], 
              body: ["Plus Jakarta Sans", "sans-serif"] 
            },
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        .text-gradient { background: linear-gradient(135deg, #0f172a 0%, #0ea5e9 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .dark .text-gradient { background: linear-gradient(135deg, #ffffff 0%, #38bdf8 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
    </style>

    <link rel="stylesheet" href="assets/css/magnific-popup.css">
    <link rel="stylesheet" href="assets/css/rating.css">

    <?php
    if($cur_page == 'index.php' || $cur_page == 'login.php' || $cur_page == 'registration.php' || $cur_page == 'cart.php' || $cur_page == 'checkout.php' || $cur_page == 'product.php' || $cur_page == 'product-category.php') {
        ?>
        <title><?php echo $meta_title_home; ?></title>
        <meta name="keywords" content="<?php echo $meta_keyword_home; ?>">
        <meta name="description" content="<?php echo $meta_description_home; ?>">
        <?php
    }
    ?>
    <?php echo $before_head; ?>
</head>
<body class="bg-surface dark:bg-slate-900 font-body text-textMain dark:text-slate-200 antialiased overflow-x-hidden transition-colors duration-300">
<?php echo $after_body; ?>

<header class="fixed top-0 w-full z-50 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 transition-all duration-300">
    <div class="flex justify-between items-center h-20 px-6 md:px-12 max-w-[1440px] mx-auto">
        
        <div class="text-2xl font-headline font-black tracking-tight text-surfaceDark dark:text-white uppercase flex items-center gap-2">
            <a href="index.php" class="hover:scale-105 transition-transform block">
                <?php if($logo != ''): ?>
                    <img src="assets/uploads/<?php echo $logo; ?>" alt="Electronic store" class="h-10 transition-all">
                <?php else: ?>
                    <span class="text-primary dark:text-sky-400">Electronic</span>Store
                <?php endif; ?>
            </a>
        </div>
        
        <nav class="hidden md:flex items-center space-x-10 font-headline font-bold text-sm tracking-widest uppercase">
            <a class="<?php echo ($cur_page == 'index.php') ? 'text-primary dark:text-sky-400 border-b-2 border-primary dark:border-sky-400 pb-1' : 'text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-sky-400 transition-colors'; ?>" href="index.php">Home</a>
            <a class="<?php echo ($cur_page == 'product-category.php' || $cur_page == 'product.php') ? 'text-primary dark:text-sky-400 border-b-2 border-primary dark:border-sky-400 pb-1' : 'text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-sky-400 transition-colors'; ?>" href="product-category.php">Products</a>
            <a class="<?php echo ($cur_page == 'about.php') ? 'text-primary dark:text-sky-400 border-b-2 border-primary dark:border-sky-400 pb-1' : 'text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-sky-400 transition-colors'; ?>" href="about.php">About Us</a>
            <a class="<?php echo ($cur_page == 'contact.php') ? 'text-primary dark:text-sky-400 border-b-2 border-primary dark:border-sky-400 pb-1' : 'text-textMuted dark:text-slate-400 hover:text-primary dark:hover:text-sky-400 transition-colors'; ?>" href="contact.php">Contact Us</a>
        </nav>
        
        <div class="flex items-center space-x-2 md:space-x-5">
            <form action="search-result.php" method="get" class="hidden lg:flex items-center bg-surface dark:bg-slate-800 px-4 py-2.5 rounded-full border border-slate-200 dark:border-slate-700 focus-within:ring-2 ring-primary/20 dark:ring-sky-500/30 transition-all">
                <?php $csrf->echoInputField(); ?>
                <button type="submit" class="material-symbols-outlined text-textMuted dark:text-slate-400 mr-2 cursor-pointer text-lg">search</button>
                <input name="search_text" class="bg-transparent border-none focus:ring-0 text-sm w-48 font-medium focus:outline-none placeholder-slate-400 dark:placeholder-slate-500 dark:text-white" placeholder="Search electronics..." type="text"/>
            </form>
            
            <button id="theme-toggle" type="button" class="text-textMuted dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 p-2.5 rounded-full transition-all active:scale-95">
                <span id="theme-toggle-dark-icon" class="material-symbols-outlined hidden text-xl">dark_mode</span>
                <span id="theme-toggle-light-icon" class="material-symbols-outlined hidden text-xl">light_mode</span>
            </button>

            <?php if(isset($_SESSION['customer'])): ?>
                <a href="dashboard.php" class="text-textMain dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 px-4 py-2 rounded-full font-headline font-bold text-sm tracking-widest uppercase transition-all active:scale-95 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">person</span>
                    <span class="hidden sm:inline">Account</span>
                </a>
            <?php else: ?>
                <div class="flex items-center space-x-2">
                    <a href="login.php" class="text-textMain dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 px-4 py-2 rounded-full font-headline font-bold text-sm tracking-widest uppercase transition-all active:scale-95">
                        Log In
                    </a>
                    <a href="registration.php" class="bg-primary hover:bg-primaryHover dark:bg-sky-600 dark:hover:bg-sky-500 text-white px-5 py-2.5 rounded-full font-headline font-bold text-sm tracking-widest uppercase transition-all active:scale-95 shadow-md shadow-primary/20">
                        Sign Up
                    </a>
                </div>
            <?php endif; ?>
            
            <a href="cart.php" class="text-textMain dark:text-white hover:bg-slate-100 dark:hover:bg-slate-800 p-2.5 rounded-full transition-all active:scale-95 relative">
                <span class="material-symbols-outlined">shopping_bag</span>
                <?php if(isset($_SESSION['cart_p_id']) && count($_SESSION['cart_p_id']) > 0): ?>
                    <span class="absolute top-1 right-1 bg-primary text-white text-[10px] font-bold h-4 w-4 flex items-center justify-center rounded-full shadow-md animate-pulse">
                        <?php echo count($_SESSION['cart_p_id']); ?>
                    </span>
                <?php endif; ?>
            </a>
        </div>
    </div>
</header>