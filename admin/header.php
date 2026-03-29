<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");
include("inc/CSRF_Protect.php");
$csrf = new CSRF_Protect();

$error_message = '';
$success_message = '';

// Security Check
if(!isset($_SESSION['user'])) {
    header('location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Silicon Slate Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Manrope:wght@600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    
    <script id="tailwind-config">
      tailwind.config = { darkMode: "class", theme: { extend: { fontFamily: { "headline": ["Manrope"], "body": ["Inter"], "label": ["Inter"] } } } }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        .cke_chrome { border: 1px solid #c3c6d6 !important; border-radius: 0.5rem !important; overflow: hidden; }
        .table-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
        .table-scroll::-webkit-scrollbar-track { background: transparent; border-radius: 4px; }
        .table-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
    
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-icon');
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                localStorage.theme = 'light';
                icon.innerText = 'dark_mode';
            } else {
                html.classList.add('dark');
                localStorage.theme = 'dark';
                icon.innerText = 'light_mode';
            }
        }
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="antialiased selection:bg-[#0052CC]/20 bg-[#faf8ff] dark:bg-slate-900 text-slate-900 dark:text-slate-100 transition-colors duration-200">
    
    <?php require_once('sidebar.php'); ?>

    <div class="lg:ml-64 min-h-screen flex flex-col relative">
        
        <header class="w-full sticky top-0 z-40 bg-[#faf8ff]/80 dark:bg-slate-900/80 backdrop-blur-md flex items-center justify-between px-8 h-20 border-b border-slate-200/50 dark:border-slate-700/50 shadow-sm transition-colors duration-200">
            <h1 class="font-bold tracking-tight text-xl hidden md:block">Silicon Slate Admin</h1>
            <div class="flex items-center gap-4 ml-auto">
                
                <button onclick="toggleTheme()" class="w-10 h-10 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition-colors flex items-center justify-center border border-slate-200 dark:border-slate-600" title="Toggle Dark Mode">
                    <span id="theme-icon" class="material-symbols-outlined text-[20px]">
                        <script>document.write(document.documentElement.classList.contains('dark') ? 'light_mode' : 'dark_mode');</script>
                    </span>
                </button>

                <a href="../index.php" target="_blank" class="p-2 text-[#0052CC] dark:text-[#4da3ff] bg-[#0052CC]/10 dark:bg-[#4da3ff]/10 hover:bg-[#0052CC]/20 rounded-full transition-all flex items-center gap-2 px-4" title="View Storefront">
                    <span class="material-symbols-outlined text-sm">storefront</span>
                    <span class="text-xs font-bold uppercase tracking-widest hidden md:inline">View Store</span>
                </a>
                <div class="w-10 h-10 rounded-full bg-[#0052CC] text-white flex items-center justify-center font-bold overflow-hidden shadow-sm" title="<?php echo htmlspecialchars($_SESSION['user']['full_name']); ?>">
                    <?php if($_SESSION['user']['photo'] == ''): ?>
                        <?php echo substr($_SESSION['user']['full_name'], 0, 1); ?>
                    <?php else: ?>
                        <img src="../assets/uploads/<?php echo $_SESSION['user']['photo']; ?>" alt="Admin Profile" class="w-full h-full object-cover"/>
                    <?php endif; ?>
                </div>
                <a href="logout.php" class="w-10 h-10 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500/20 rounded-full transition-colors flex items-center justify-center border border-red-100 dark:border-red-500/20" title="Logout">
                    <span class="material-symbols-outlined text-[20px]">logout</span>
                </a>
            </div>
        </header>