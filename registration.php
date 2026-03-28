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

if (isset($_POST['form1'])) {

    $valid = 1;
    $error_message = '';

    if(empty($_POST['cust_name'])) { $valid = 0; $error_message .= LANG_VALUE_123."<br>"; }
    if(empty($_POST['cust_email'])) { 
        $valid = 0; $error_message .= LANG_VALUE_131."<br>"; 
    } else {
        if (filter_var($_POST['cust_email'], FILTER_VALIDATE_EMAIL) === false) {
            $valid = 0; $error_message .= LANG_VALUE_134."<br>";
        } else {
            $statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
            $statement->execute(array($_POST['cust_email']));
            if($statement->rowCount()) { $valid = 0; $error_message .= LANG_VALUE_147."<br>"; }
        }
    }
    if(empty($_POST['cust_phone'])) { $valid = 0; $error_message .= LANG_VALUE_124."<br>"; }
    
    // NOTE: Address, Country, City, State, and Zip validations have been safely removed.

    if(empty($_POST['cust_password']) || empty($_POST['cust_re_password'])) { $valid = 0; $error_message .= LANG_VALUE_138."<br>"; }
    if(!empty($_POST['cust_password']) && !empty($_POST['cust_re_password'])) {
        if($_POST['cust_password'] != $_POST['cust_re_password']) { $valid = 0; $error_message .= LANG_VALUE_139."<br>"; }
    }

    if($valid == 1) {
        $token = md5(time());
        $cust_datetime = date('Y-m-d h:i:s');
        $cust_timestamp = time();

        // Database Insertion (Passing empty strings for the removed address fields)
        $statement = $pdo->prepare("INSERT INTO tbl_customer (
            cust_name, cust_cname, cust_email, cust_phone, cust_country, cust_address, cust_city, cust_state, cust_zip,
            cust_b_name, cust_b_cname, cust_b_phone, cust_b_country, cust_b_address, cust_b_city, cust_b_state, cust_b_zip,
            cust_s_name, cust_s_cname, cust_s_phone, cust_s_country, cust_s_address, cust_s_city, cust_s_state, cust_s_zip,
            cust_password, cust_token, cust_datetime, cust_timestamp, cust_status
        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
        
        $statement->execute(array(
            strip_tags($_POST['cust_name']), 
            '', // cname
            strip_tags($_POST['cust_email']), 
            strip_tags($_POST['cust_phone']),
            '', // country
            '', // address
            '', // city
            '', // state
            '', // zip
            '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', // billing/shipping fields
            md5($_POST['cust_password']), 
            $token, 
            $cust_datetime, 
            $cust_timestamp, 
            0
        ));

        // Email dispatch
        $to = $_POST['cust_email'];
        $subject = LANG_VALUE_150;
        $verify_link = BASE_URL.'verify.php?email='.$to.'&token='.$token;
        $message = LANG_VALUE_151.'<br><br><a href="'.$verify_link.'">'.$verify_link.'</a>';
        $headers = "From: noreply@" . BASE_URL . "\r\n" . "Reply-To: noreply@" . BASE_URL . "\r\n" . "X-Mailer: PHP/" . phpversion() . "\r\n" . "MIME-Version: 1.0\r\n" . "Content-Type: text/html; charset=ISO-8859-1\r\n";
        mail($to, $subject, $message, $headers);

        unset($_POST['cust_name'], $_POST['cust_email'], $_POST['cust_phone']);
        $success_message = LANG_VALUE_152;
    }
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8"/>
    <title>Sign Up | Curator Tech</title>

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
    <style> .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; } </style>
</head>
<body class="bg-surface dark:bg-slate-900 font-body text-textMain dark:text-slate-200 antialiased min-h-screen flex items-center justify-center py-20 px-4 md:px-12 relative overflow-x-hidden transition-colors duration-300">

    <div class="absolute top-6 left-6 md:top-10 md:left-10 z-50">
        <a href="index.php" class="flex items-center gap-2 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md px-5 py-2.5 rounded-full border border-slate-200 dark:border-slate-700 text-surfaceDark dark:text-white font-headline font-bold text-sm tracking-widest uppercase hover:bg-slate-50 dark:hover:bg-slate-700 transition-all shadow-sm group">
            <span class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform">arrow_back</span>
            Back to Home Page
        </a>
    </div>

    <div class="w-full max-w-[1000px] mt-12 grid grid-cols-1 lg:grid-cols-5 bg-white dark:bg-slate-800 rounded-[2rem] overflow-hidden shadow-2xl shadow-slate-200/50 dark:shadow-black/50 border border-slate-100 dark:border-slate-700/50 relative z-20">
        
        <div class="hidden lg:block lg:col-span-2 relative overflow-hidden bg-surfaceDark dark:bg-black">
            <img class="absolute inset-0 w-full h-full object-cover mix-blend-overlay opacity-40" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA_92k9V-i044Pjviv8nEj2cl3E0h0w8EN_sID1PweD1vaZpO2HyrlLUv8FbUC3UjTs8E_qM6QsWpXh2gVh5Q4TBakBLRYR-nPyMApQ-1Bve9Z4ybikyffY2Q6HDkrHDAzXJp8WIB48t9JsTbm1bNMabugDYi0NdEGr29pktlxav5Cgim60plpXtdnvy-Y6ZNtqz7YmR3IF2Wi1Ow7me6tmPiZVsLLPl_jQG399zTqTfON302YBbvp2RQcA9IuGuRTPKIB1UBhHSUV_" alt="Promo"/>
            <div class="absolute inset-0 bg-gradient-to-t from-black via-black/40 to-transparent"></div>
            
            <div class="relative h-full flex flex-col justify-end p-12 z-10 text-white">
                <span class="text-primary dark:text-indigo-400 font-headline text-xs font-bold tracking-[0.3em] uppercase mb-4">E-Store Elite</span>
                <h2 class="font-headline text-4xl font-black tracking-tight leading-tight mb-6">Precision instruments for your digital workflow.</h2>
                <p class="text-slate-300 text-sm leading-relaxed mb-8">Join an exclusive community of curators and technologists. Early access to new hardware drops begins here.</p>
            </div>
        </div>
        
        <div class="lg:col-span-3 p-6 sm:p-10 md:p-12 bg-white dark:bg-slate-800 flex flex-col justify-center">
            
            <div class="mb-8">
                <h1 class="font-headline text-3xl font-black text-surfaceDark dark:text-white tracking-tight mb-2"><?php echo LANG_VALUE_16; ?></h1>
                <p class="text-textMuted dark:text-slate-400 text-sm">Enter your details to create your secure profile.</p>
            </div>

            <?php if(isset($error_message) && $error_message != ''): ?>
                <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/30 text-red-600 dark:text-red-400 px-4 py-3 rounded-xl text-sm font-medium mb-6"><?php echo $error_message; ?></div>
            <?php endif; ?>
            <?php if(isset($success_message) && $success_message != ''): ?>
                <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 text-green-600 dark:text-green-400 px-4 py-3 rounded-xl text-sm font-medium mb-6"><?php echo $success_message; ?></div>
            <?php endif; ?>

            <form action="" method="post" class="space-y-5">
                <?php $csrf->echoInputField(); ?>
                
                <div class="space-y-2">
                    <label class="block font-headline text-xs font-bold uppercase tracking-wider text-textMuted dark:text-slate-400"><?php echo LANG_VALUE_102; ?> *</label>
                    <input name="cust_name" value="<?php if(isset($_POST['cust_name'])){echo $_POST['cust_name'];} ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 text-surfaceDark dark:text-white text-sm outline-none" type="text" placeholder="John Doe" required/>
                </div>
                
                <div class="space-y-2">
                    <label class="block font-headline text-xs font-bold uppercase tracking-wider text-textMuted dark:text-slate-400"><?php echo LANG_VALUE_94; ?> *</label>
                    <input name="cust_email" value="<?php if(isset($_POST['cust_email'])){echo $_POST['cust_email'];} ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 text-surfaceDark dark:text-white text-sm outline-none" type="email" placeholder="name@company.com" required/>
                </div>

                <div class="space-y-2">
                    <label class="block font-headline text-xs font-bold uppercase tracking-wider text-textMuted dark:text-slate-400"><?php echo LANG_VALUE_104; ?> *</label>
                    <input name="cust_phone" value="<?php if(isset($_POST['cust_phone'])){echo $_POST['cust_phone'];} ?>" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 text-surfaceDark dark:text-white text-sm outline-none" type="text" placeholder="+1 (555) 000-0000" required/>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label class="block font-headline text-xs font-bold uppercase tracking-wider text-textMuted dark:text-slate-400"><?php echo LANG_VALUE_96; ?> *</label>
                        <input name="cust_password" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 text-surfaceDark dark:text-white text-sm outline-none" type="password" placeholder="••••••••" required/>
                    </div>
                    <div class="space-y-2">
                        <label class="block font-headline text-xs font-bold uppercase tracking-wider text-textMuted dark:text-slate-400"><?php echo LANG_VALUE_98; ?> *</label>
                        <input name="cust_re_password" class="w-full px-4 py-3 rounded-xl bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 text-surfaceDark dark:text-white text-sm outline-none" type="password" placeholder="••••••••" required/>
                    </div>
                </div>

                <div class="pt-4">
                    <button name="form1" type="submit" class="w-full py-4 bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-headline font-bold uppercase tracking-[0.1em] rounded-xl shadow-lg hover:shadow-xl active:scale-[0.98] transition-all flex justify-center items-center gap-2 group">
                        <span>Initialize Membership</span>
                        <span class="material-symbols-outlined text-sm group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>

                <div class="text-center pt-2">
                    <p class="text-sm text-textMuted dark:text-slate-400">
                        Already have an account? <a class="text-primary dark:text-indigo-400 font-bold hover:underline transition-all" href="login.php">Login here</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>