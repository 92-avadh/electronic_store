<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();
$error_message = '';

if(isset($_POST['form1'])) {
    if(empty($_POST['email']) || empty($_POST['password'])) {
        $error_message = 'Email and/or Password can not be empty<br>';
    } else {
        $email = strip_tags($_POST['email']);
        $password = strip_tags($_POST['password']);

        $statement = $pdo->prepare("SELECT * FROM tbl_user WHERE email=? AND status=?");
        $statement->execute(array($email,'Active'));
        $total = $statement->rowCount();    
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);    
        if($total==0) {
            $error_message .= 'Email Address does not match<br>';
        } else {       
            foreach($result as $row) { 
                $row_password = $row['password'];
            }
            if($row_password != md5($password)) {
                $error_message .= 'Password does not match<br>';
            } else {       
                $_SESSION['user'] = $row;
                header("location: index.php");
                exit;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Access | Electronic store  SLATE</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "on-surface-variant": "#434654",
              "on-primary": "#ffffff",
              "on-secondary-fixed-variant": "#3a485b",
              "primary-fixed-dim": "#b3c5ff",
              "tertiary-fixed-dim": "#00daf3",
              "outline-variant": "#c3c6d6",
              "on-tertiary-container": "#33e6ff",
              "background": "#faf8ff",
              "tertiary": "#004a54",
              "on-background": "#131b2e",
              "secondary-fixed": "#d5e3fc",
              "on-primary-container": "#c5d2ff",
              "surface-tint": "#0054d6",
              "surface-container-highest": "#dae2fd",
              "on-primary-fixed-variant": "#003fa4",
              "on-error": "#ffffff",
              "secondary-fixed-dim": "#b9c7df",
              "secondary-container": "#d5e3fc",
              "tertiary-fixed": "#9cf0ff",
              "surface-dim": "#d2d9f4",
              "secondary": "#515f74",
              "surface-container": "#eaedff",
              "surface-container-lowest": "#ffffff",
              "on-tertiary": "#ffffff",
              "inverse-surface": "#283044",
              "on-surface": "#131b2e",
              "surface-bright": "#faf8ff",
              "on-secondary-container": "#57657a",
              "primary-fixed": "#dae1ff",
              "surface-container-low": "#f2f3ff",
              "surface-variant": "#dae2fd",
              "primary": "#003c9d",
              "on-tertiary-fixed": "#001f24",
              "on-secondary": "#ffffff",
              "tertiary-container": "#006470",
              "surface": "#faf8ff",
              "on-tertiary-fixed-variant": "#004f58",
              "inverse-primary": "#b3c5ff",
              "error-container": "#ffdad6",
              "outline": "#737685",
              "on-secondary-fixed": "#0d1c2e",
              "inverse-on-surface": "#eef0ff",
              "error": "#ba1a1a",
              "primary-container": "#0051ce",
              "surface-container-high": "#e2e7ff",
              "on-error-container": "#93000a",
              "on-primary-fixed": "#001849"
            },
            fontFamily: {
              "headline": ["Manrope"],
              "body": ["Inter"],
              "label": ["Inter"]
            },
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #faf8ff;
        }
        .headline-font {
            font-family: 'Manrope', sans-serif;
        }
    </style>
</head>
<body class="bg-surface text-on-surface min-h-screen flex flex-col">
    
    <header class="sticky top-0 z-50 bg-[#faf8ff]/70 backdrop-blur-xl flex justify-center items-center w-full py-8 px-8">
        <div class="flex items-center gap-2">
            <span class="text-xl font-black text-[#131b2e] tracking-[0.05em] uppercase headline-font">Electronic store  SLATE</span>
            <div class="h-4 w-[1px] bg-outline-variant mx-2"></div>
            <span class="text-xs font-bold tracking-widest text-primary uppercase headline-font">Admin Panel</span>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center p-6 relative overflow-hidden">
        
        <div class="absolute top-[-10%] left-[-5%] w-[40%] h-[40%] bg-primary-fixed-dim/20 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-5%] w-[30%] h-[30%] bg-tertiary-fixed-dim/20 rounded-full blur-[100px]"></div>
        
        <div class="w-full max-w-[460px] z-10">
            
            <div class="relative mb-12 flex justify-center">
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-surface-container-highest rounded-xl -rotate-12 z-0"></div>
                <div class="relative bg-surface-container-lowest p-1 shadow-[0px_20px_40px_rgba(19,27,46,0.06)] rounded-xl z-10 flex items-center justify-center h-32 w-32">
                    <span class="material-symbols-outlined text-6xl text-primary" style="font-variation-settings: 'FILL' 1;">deployed_code</span>
                </div>
            </div>

            <section class="bg-surface-container-low p-10 rounded-2xl shadow-sm border border-outline-variant/10">
                <div class="mb-10 text-center">
                    <p class="text-[10px] font-bold tracking-[0.2em] text-on-tertiary-fixed-variant uppercase headline-font mb-2">Curator Tech Systems</p>
                    <h1 class="text-3xl font-extrabold text-on-surface tracking-tight headline-font">Admin Access</h1>
                    <div class="mt-4 h-1 w-12 bg-primary mx-auto rounded-full"></div>
                </div>

                <?php if($error_message != ''): ?>
                    <div class="bg-error/10 border border-error/20 text-error px-4 py-3 rounded-lg text-xs font-bold mb-6 text-center tracking-wide">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>

                <form action="" method="post" class="space-y-6">
                    <?php $csrf->echoInputField(); ?>
                    
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-on-surface-variant tracking-wider uppercase ml-1">Work Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-sm">alternate_email</span>
                            <input name="email" class="w-full pl-11 pr-4 py-3.5 bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-surface-tint/20 transition-all text-sm text-on-surface placeholder:text-outline/50 outline-none" placeholder="name@Electronic store slate.tech" type="email" autofocus autocomplete="off" required/>
                        </div>
                    </div>
                    
                    <div class="space-y-2">
                        <div class="flex justify-between items-center ml-1">
                            <label class="block text-xs font-semibold text-on-surface-variant tracking-wider uppercase">Security Key</label>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline text-sm">lock</span>
                            <input name="password" class="w-full pl-11 pr-4 py-3.5 bg-surface-container-lowest border-none rounded-lg focus:ring-2 focus:ring-surface-tint/20 transition-all text-sm text-on-surface placeholder:text-outline/50 outline-none" placeholder="••••••••••••" type="password" required/>
                        </div>
                    </div>
                    
                    <div class="pt-4">
                        <button name="form1" class="w-full py-4 bg-gradient-to-r from-primary to-primary-container text-on-primary rounded-lg font-bold text-xs uppercase tracking-[0.1em] hover:opacity-90 active:scale-[0.98] transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2" type="submit">
                            <span>Sign In</span>
                            <span class="material-symbols-outlined text-sm">arrow_forward</span>
                        </button>
                    </div>
                </form>

                <div class="mt-12 pt-8 border-t border-outline-variant/30 flex justify-between items-center">
                    <div>
                        <p class="text-[10px] text-outline font-medium tracking-wider uppercase">Network Status</p>
                        <div class="flex items-center gap-1.5 mt-1">
                            <span class="w-1.5 h-1.5 rounded-full bg-tertiary-fixed-dim animate-pulse"></span>
                            <span class="text-[11px] font-bold text-on-surface tracking-tight">ENCRYPTED_OS_v4.2</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] text-outline font-medium tracking-wider uppercase">Location</p>
                        <p class="text-[11px] font-bold text-on-surface tracking-tight mt-1">MAIN_CLUSTER_01</p>
                    </div>
                </div>
            </section>
            
            <p class="text-center mt-8 text-[10px] text-outline font-medium tracking-[0.05em]">
                Unauthorized access to this terminal is strictly prohibited under Protocol 9.
            </p>
        </div>
    </main>

    <footer class="w-full py-12 flex flex-col items-center justify-center gap-4 bg-[#faf8ff]">
        <div class="flex gap-8">
            <span class="text-slate-400 font-['Inter'] text-xs tracking-wider uppercase">Privacy Policy</span>
            <span class="text-slate-400 font-['Inter'] text-xs tracking-wider uppercase">Terms of Service</span>
            <span class="text-slate-400 font-['Inter'] text-xs tracking-wider uppercase">System Status</span>
        </div>
        <p class="text-slate-400 font-['Inter'] text-xs tracking-wider uppercase">© 2024 Electronic store Admin. PRECISION INSTRUMENTATION.</p>
    </footer>
</body>
</html>