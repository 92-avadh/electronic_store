<?php
ob_start();
session_start();
include("admin/inc/config.php");
include("admin/inc/functions.php");
include("admin/inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();

// Getting all language variables into array as global variable
$i=1;
$statement = $pdo->prepare("SELECT * FROM tbl_language");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	define('LANG_VALUE_'.$i,$row['lang_value']);
	$i++;
}

// Redirect if already logged in
if(isset($_SESSION['customer'])) {
    header("location: ".BASE_URL."dashboard.php");
    exit;
}

$error_message = '';

if(isset($_POST['form1'])) {
    if(empty($_POST['cust_email']) || empty($_POST['cust_password'])) {
        $error_message = LANG_VALUE_132.'<br>';
    } else {
        $cust_email = strip_tags($_POST['cust_email']);
        $cust_password = strip_tags($_POST['cust_password']);

        $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
        $statement->execute(array($cust_email));
        $total = $statement->rowCount();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        if($total==0) {
            $error_message .= LANG_VALUE_133.'<br>';
        } else {
            foreach($result as $row) {
                $row_password = $row['cust_password'];
                $row_status = $row['cust_status'];
            }
            if( $row_password != md5($cust_password) ) {
                $error_message .= LANG_VALUE_139.'<br>';
            } else {
                if($row_status == 0) {
                    $error_message .= LANG_VALUE_148.'<br>';
                } else {
                    $_SESSION['customer'] = $row;
                    header("location: ".BASE_URL."dashboard.php");
                    exit;
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Login | Curator Tech</title>

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
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            colors: {
              primary: "#4f46e5", primaryHover: "#4338ca", surface: "#f8fafc", surfaceDark: "#0f172a", textMain: "#0f172a", textMuted: "#64748b",
            },
            fontFamily: { headline: ["Outfit", "sans-serif"], body: ["Plus Jakarta Sans", "sans-serif"] },
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
    </style>
</head>
<body class="bg-surface dark:bg-slate-900 font-body text-textMain dark:text-slate-200 antialiased min-h-screen flex items-center justify-center relative overflow-hidden transition-colors duration-300">

    <div class="absolute top-6 left-6 md:top-10 md:left-10 z-50">
        <a href="index.php" class="flex items-center gap-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md px-5 py-2.5 rounded-full border border-slate-200 dark:border-slate-700 text-surfaceDark dark:text-white font-headline font-bold text-sm tracking-widest uppercase hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm group">
            <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Back to Home Page
        </a>
    </div>

    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-white/50 dark:bg-slate-900/60 backdrop-blur-2xl z-10 transition-colors duration-300"></div>
        <div class="absolute top-1/4 -right-40 w-96 h-96 bg-primary/20 dark:bg-indigo-500/20 blur-[100px] rounded-full animate-pulse"></div>
        <div class="h-full w-full bg-cover bg-center opacity-40 dark:opacity-20" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBuWpN2hFREtyV6U_bDSf3cb4Mf-4s8OkyoOX49-sAYRp7U8Kondb6aQ8sAsXOJZe66chdovWioLCH99DhXQLTzwR2UZHmEkRXETe4wHy8QXstzEhWg6Hd4XfhujsatuHMNdXsXI6x9nzMWmrVHBqe5Ex_RpLu60rH1Yvv9qX6ffcbyZn2N9gaeNQnxOMM6_gg5g0aOdrH86XUnKjRiZrs8GvQWvouaMPtuUtiB-yaE-7fqkg7xs527ODjoCorPDOdB5eFm0mw-NaaO');"></div>
    </div>

    <div class="relative z-20 w-full max-w-[480px] p-4">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-12 shadow-2xl shadow-slate-200/50 dark:shadow-black/50 border border-slate-100 dark:border-slate-700/50 transition-all duration-300">
            
            <div class="mb-10 text-center">
                <span class="text-primary dark:text-indigo-400 font-headline text-[10px] uppercase tracking-[0.2em] font-bold mb-3 block">Member Access</span>
                <h1 class="font-headline text-3xl font-black text-surfaceDark dark:text-white leading-tight tracking-tight">Welcome Back</h1>
                <p class="text-textMuted dark:text-slate-400 mt-2 text-sm">Sign in to manage your high-performance gear.</p>
            </div>

            <?php if($error_message != ''): ?>
                <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl text-sm font-medium mb-6 text-center">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['success_message'])): ?>
                <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 text-green-600 dark:text-green-400 px-4 py-3 rounded-xl text-sm font-medium mb-6 text-center">
                    <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-6">
                <?php $csrf->echoInputField(); ?>

                <div class="space-y-2">
                    <label class="block text-xs font-headline font-bold text-textMuted dark:text-slate-400 uppercase tracking-wider ml-1"><?php echo LANG_VALUE_94; ?></label>
                    <input name="cust_email" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-14 px-4 text-surfaceDark dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 transition-all outline-none" placeholder="name@company.com" type="email" autofocus required/>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between items-center ml-1">
                        <label class="block text-xs font-headline font-bold text-textMuted dark:text-slate-400 uppercase tracking-wider"><?php echo LANG_VALUE_96; ?></label>
                        <a class="text-primary dark:text-indigo-400 text-xs font-bold hover:underline transition-all" href="forget-password.php"><?php echo LANG_VALUE_97; ?></a>
                    </div>
                    <input name="cust_password" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-14 px-4 text-surfaceDark dark:text-white placeholder:text-slate-400 focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 transition-all outline-none" placeholder="••••••••" type="password" required/>
                </div>

                <div class="pt-4">
                    <button name="form1" type="submit" class="w-full bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white h-14 rounded-xl font-headline font-bold text-sm uppercase tracking-[0.1em] shadow-lg hover:shadow-xl active:scale-[0.98] transition-all">
                        <?php echo LANG_VALUE_4; ?>
                    </button>
                </div>
            </form>

            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700/50 text-center">
                <p class="text-textMuted dark:text-slate-400 text-sm font-medium">
                    Don't have an account? 
                    <a class="text-primary dark:text-indigo-400 font-bold ml-1 hover:underline transition-all" href="registration.php">Sign up for free</a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>