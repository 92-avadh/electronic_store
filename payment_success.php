<?php
ob_start();
session_start();
include("admin/inc/config.php");
include("admin/inc/functions.php");

// Fetch language variables so LANG_VALUE_121 still works
$i=1;
$statement = $pdo->prepare("SELECT * FROM tbl_language");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);							
foreach ($result as $row) {
	define('LANG_VALUE_'.$i,$row['lang_value']);
	$i++;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful</title>
    
    <meta http-equiv="refresh" content="2;url=customer-order.php">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
      }
    </script>
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark')
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 48; }
        
        /* Simple pop-in animation */
        @keyframes popIn {
            0% { transform: scale(0.9); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        .animate-pop { animation: popIn 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-900 flex items-center justify-center min-h-screen transition-colors duration-300">

    <div class="bg-white dark:bg-slate-800 p-10 rounded-3xl shadow-xl text-center max-w-md w-full border border-slate-100 dark:border-slate-700 mx-4 animate-pop">
        
        <div class="w-24 h-24 bg-green-100 dark:bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-6xl text-green-500 dark:text-green-400">check_circle</span>
        </div>
        
        <h2 class="text-3xl font-black text-slate-800 dark:text-white mb-3 tracking-tight">Payment Successful!</h2>
        
        <p class="text-slate-500 dark:text-slate-400 mb-8 font-medium">
            <?php echo LANG_VALUE_121; ?>
        </p>
        
        <div class="flex items-center justify-center gap-3 text-sm text-slate-400 dark:text-slate-500 font-bold tracking-wide uppercase">
            <span class="material-symbols-outlined text-[20px] animate-spin">progress_activity</span>
            Redirecting to your orders...
        </div>

        <div class="mt-6 pt-6 border-t border-slate-100 dark:border-slate-700">
            <a href="customer-order.php" class="text-sky-500 hover:text-sky-600 dark:text-sky-400 dark:hover:text-sky-300 text-sm font-bold transition-colors">
                Click here if you are not redirected
            </a>
        </div>
    </div>

</body>
</html>