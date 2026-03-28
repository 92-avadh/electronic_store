<?php require_once('header.php'); ?>

<?php
// Security Check
if(!isset($_SESSION['customer'])) {
    header('location: '.BASE_URL.'logout.php');
    exit;
}

// Save Address Logic
if (isset($_POST['form1'])) {
    $statement = $pdo->prepare("UPDATE tbl_customer SET 
                            cust_b_name=?, cust_b_cname=?, cust_b_phone=?, cust_b_country=?, cust_b_address=?, cust_b_city=?, cust_b_state=?, cust_b_zip=?,
                            cust_s_name=?, cust_s_cname=?, cust_s_phone=?, cust_s_country=?, cust_s_address=?, cust_s_city=?, cust_s_state=?, cust_s_zip=? 
                            WHERE cust_id=?");
    $statement->execute(array(
                            strip_tags($_POST['cust_b_name']), strip_tags($_POST['cust_b_cname']), strip_tags($_POST['cust_b_phone']), strip_tags($_POST['cust_b_country']), strip_tags($_POST['cust_b_address']), strip_tags($_POST['cust_b_city']), strip_tags($_POST['cust_b_state']), strip_tags($_POST['cust_b_zip']),
                            strip_tags($_POST['cust_s_name']), strip_tags($_POST['cust_s_cname']), strip_tags($_POST['cust_s_phone']), strip_tags($_POST['cust_s_country']), strip_tags($_POST['cust_s_address']), strip_tags($_POST['cust_s_city']), strip_tags($_POST['cust_s_state']), strip_tags($_POST['cust_s_zip']),
                            $_SESSION['customer']['cust_id']
                        ));
    
    // Update Session Variables
    $_SESSION['customer']['cust_b_name'] = strip_tags($_POST['cust_b_name']);
    $_SESSION['customer']['cust_b_cname'] = strip_tags($_POST['cust_b_cname']);
    $_SESSION['customer']['cust_b_phone'] = strip_tags($_POST['cust_b_phone']);
    $_SESSION['customer']['cust_b_country'] = strip_tags($_POST['cust_b_country']);
    $_SESSION['customer']['cust_b_address'] = strip_tags($_POST['cust_b_address']);
    $_SESSION['customer']['cust_b_city'] = strip_tags($_POST['cust_b_city']);
    $_SESSION['customer']['cust_b_state'] = strip_tags($_POST['cust_b_state']);
    $_SESSION['customer']['cust_b_zip'] = strip_tags($_POST['cust_b_zip']);
    
    $_SESSION['customer']['cust_s_name'] = strip_tags($_POST['cust_s_name']);
    $_SESSION['customer']['cust_s_cname'] = strip_tags($_POST['cust_s_cname']);
    $_SESSION['customer']['cust_s_phone'] = strip_tags($_POST['cust_s_phone']);
    $_SESSION['customer']['cust_s_country'] = strip_tags($_POST['cust_s_country']);
    $_SESSION['customer']['cust_s_address'] = strip_tags($_POST['cust_s_address']);
    $_SESSION['customer']['cust_s_city'] = strip_tags($_POST['cust_s_city']);
    $_SESSION['customer']['cust_s_state'] = strip_tags($_POST['cust_s_state']);
    $_SESSION['customer']['cust_s_zip'] = strip_tags($_POST['cust_s_zip']);

    $success_message = LANG_VALUE_122;
}
?>

<div class="flex min-h-screen pt-20 bg-surface dark:bg-slate-900 transition-colors duration-300">
    
    <?php require_once('customer-sidebar.php'); ?>

    <main class="ml-64 flex-grow flex flex-col p-8 md:p-12">
        <div class="max-w-5xl mx-auto w-full">
            
            <div class="mb-10 flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-headline font-black text-surfaceDark dark:text-white tracking-tight">Delivery Profile</h1>
                    <p class="text-textMuted dark:text-slate-400 mt-2 text-sm">Update your billing and shipping addresses for faster checkout.</p>
                </div>
                <a href="checkout.php" class="bg-slate-200 dark:bg-slate-800 text-surfaceDark dark:text-white px-5 py-2.5 rounded-full text-xs font-bold uppercase tracking-widest hover:bg-slate-300 dark:hover:bg-slate-700 transition-colors">
                    Back to Checkout
                </a>
            </div>

            <?php if(isset($success_message)): ?>
                <div class="bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/30 text-green-600 dark:text-green-400 px-4 py-3 rounded-xl text-sm font-medium mb-6">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>

            <form action="" method="post" class="grid grid-cols-1 xl:grid-cols-2 gap-10">
                <?php $csrf->echoInputField(); ?>

                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
                    <h3 class="font-headline font-black text-xl text-surfaceDark dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Billing Details</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_102; ?></label>
                            <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500" name="cust_b_name" value="<?php echo $_SESSION['customer']['cust_b_name']; ?>">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_103; ?></label>
                                <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500" name="cust_b_cname" value="<?php echo $_SESSION['customer']['cust_b_cname']; ?>">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_104; ?></label>
                                <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500" name="cust_b_phone" value="<?php echo $_SESSION['customer']['cust_b_phone']; ?>">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_106; ?></label>
                            <select name="cust_b_country" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500">
                                <option value="">Select Country</option>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $selected = ($_SESSION['customer']['cust_b_country'] == $row['country_id']) ? 'selected' : '';
                                    echo "<option value='".$row['country_id']."' ".$selected.">".$row['country_name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_105; ?></label>
                            <textarea name="cust_b_address" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-sm text-surfaceDark dark:text-white outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 h-24"><?php echo $_SESSION['customer']['cust_b_address']; ?></textarea>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_107; ?></label>
                                <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none" name="cust_b_city" value="<?php echo $_SESSION['customer']['cust_b_city']; ?>">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_108; ?></label>
                                <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none" name="cust_b_state" value="<?php echo $_SESSION['customer']['cust_b_state']; ?>">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_109; ?></label>
                                <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none" name="cust_b_zip" value="<?php echo $_SESSION['customer']['cust_b_zip']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-slate-800 p-8 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-sm">
                    <h3 class="font-headline font-black text-xl text-surfaceDark dark:text-white mb-6 border-b border-slate-100 dark:border-slate-700 pb-4">Shipping Details</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_102; ?></label>
                            <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500" name="cust_s_name" value="<?php echo $_SESSION['customer']['cust_s_name']; ?>">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_103; ?></label>
                                <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500" name="cust_s_cname" value="<?php echo $_SESSION['customer']['cust_s_cname']; ?>">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_104; ?></label>
                                <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500" name="cust_s_phone" value="<?php echo $_SESSION['customer']['cust_s_phone']; ?>">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_106; ?></label>
                            <select name="cust_s_country" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500">
                                <option value="">Select Country</option>
                                <?php
                                $statement = $pdo->prepare("SELECT * FROM tbl_country ORDER BY country_name ASC");
                                $statement->execute();
                                $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($result as $row) {
                                    $selected = ($_SESSION['customer']['cust_s_country'] == $row['country_id']) ? 'selected' : '';
                                    echo "<option value='".$row['country_id']."' ".$selected.">".$row['country_name']."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_105; ?></label>
                            <textarea name="cust_s_address" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-sm text-surfaceDark dark:text-white outline-none focus:ring-2 focus:ring-primary dark:focus:ring-indigo-500 h-24"><?php echo $_SESSION['customer']['cust_s_address']; ?></textarea>
                        </div>
                        <div class="grid grid-cols-3 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_107; ?></label>
                                <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none" name="cust_s_city" value="<?php echo $_SESSION['customer']['cust_s_city']; ?>">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_108; ?></label>
                                <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none" name="cust_s_state" value="<?php echo $_SESSION['customer']['cust_s_state']; ?>">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-textMuted dark:text-slate-400 uppercase mb-2"><?php echo LANG_VALUE_109; ?></label>
                                <input type="text" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-sm text-surfaceDark dark:text-white outline-none" name="cust_s_zip" value="<?php echo $_SESSION['customer']['cust_s_zip']; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="xl:col-span-2 flex justify-end mt-4">
                    <button type="submit" name="form1" class="bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white px-10 py-4 rounded-xl font-bold uppercase tracking-widest transition-all shadow-lg active:scale-95">
                        Save Addresses
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<?php require_once('footer.php'); ?>