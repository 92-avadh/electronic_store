<?php require_once('header.php'); ?>

<?php
$statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);                            
foreach ($result as $row) {
    $banner_checkout = $row['banner_checkout'];
}

if(!isset($_SESSION['cart_p_id'])) {
    header('location: cart.php');
    exit;
}
?>

<main class="pt-24 pb-12 bg-surface dark:bg-slate-900 min-h-screen transition-colors duration-300">
    
    <div class="relative bg-surfaceDark dark:bg-black py-16 mb-12">
        <div class="absolute inset-0 bg-cover bg-center opacity-30 mix-blend-overlay" style="background-image: url(assets/uploads/<?php echo $banner_checkout; ?>)"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-surfaceDark dark:from-black to-transparent"></div>
        <div class="relative z-10 max-w-[1440px] mx-auto px-6 md:px-12 text-center">
            <h1 class="font-headline text-4xl md:text-5xl font-black text-white tracking-tight uppercase"><?php echo LANG_VALUE_22; ?></h1>
        </div>
    </div>

    <div class="max-w-[1440px] mx-auto px-6 md:px-12">
        
        <?php if(!isset($_SESSION['customer'])): ?>
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-12 text-center shadow-lg border border-slate-100 dark:border-slate-700 max-w-2xl mx-auto">
                <span class="material-symbols-outlined text-6xl text-slate-300 dark:text-slate-600 mb-4 block">lock</span>
                <h3 class="font-headline font-black text-2xl text-surfaceDark dark:text-white mb-4">Authentication Required</h3>
                <p class="text-textMuted dark:text-slate-400 mb-8">You must be logged in to securely process your checkout.</p>
                <a href="login.php" class="bg-primary hover:bg-primaryHover text-white px-8 py-3 rounded-full font-bold uppercase tracking-widest transition-all inline-block shadow-md">
                    <?php echo LANG_VALUE_160; ?>
                </a>
            </div>
        <?php else: ?>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <div class="lg:col-span-2 space-y-8">
                    <h2 class="font-headline font-black text-2xl text-surfaceDark dark:text-white tracking-tight">Order Summary</h2>
                    
                    <div class="bg-white dark:bg-slate-800 rounded-3xl overflow-hidden shadow-sm border border-slate-100 dark:border-slate-700">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-900 border-b border-slate-100 dark:border-slate-700">
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-textMuted dark:text-slate-400">#</th>
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-textMuted dark:text-slate-400"><?php echo LANG_VALUE_8; ?></th>
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-textMuted dark:text-slate-400"><?php echo LANG_VALUE_47; ?></th>
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-textMuted dark:text-slate-400 text-center">Qty</th>
                                        <th class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-textMuted dark:text-slate-400 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                    <?php
                                    $table_total_price = 0;
                                    $arr_cart_p_id = $_SESSION['cart_p_id'];
                                    $arr_cart_p_qty = $_SESSION['cart_p_qty'];
                                    $arr_cart_p_current_price = $_SESSION['cart_p_current_price'];
                                    $arr_cart_p_name = $_SESSION['cart_p_name'];
                                    $arr_cart_p_featured_photo = $_SESSION['cart_p_featured_photo'];
                                    
                                    for($i=1; $i<=count($arr_cart_p_id); $i++): 
                                        $row_total_price = $arr_cart_p_current_price[$i] * $arr_cart_p_qty[$i];
                                        $table_total_price += $row_total_price;
                                    ?>
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30 transition-colors">
                                        <td class="px-6 py-4 text-sm font-bold text-slate-400"><?php echo $i; ?></td>
                                        <td class="px-6 py-4">
                                            <div class="w-16 h-16 bg-surface dark:bg-slate-900 rounded-xl p-2 flex items-center justify-center">
                                                <img src="assets/uploads/<?php echo $arr_cart_p_featured_photo[$i]; ?>" class="object-contain h-full mix-blend-multiply dark:mix-blend-normal">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-surfaceDark dark:text-white"><?php echo $arr_cart_p_name[$i]; ?></p>
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-surfaceDark dark:text-white">
                                            <?php echo $arr_cart_p_qty[$i]; ?>
                                        </td>
                                        <td class="px-6 py-4 text-right font-headline font-black text-primary dark:text-indigo-400">
                                            ₹<?php echo number_format($row_total_price, 2); ?>
                                        </td>
                                    </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-8">
                    
                    <?php
                    // Fetch Shipping Cost
                    $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost WHERE country_id=?");
                    $statement->execute(array($_SESSION['customer']['cust_country']));
                    if($statement->rowCount()) {
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) { $shipping_cost = $row['amount']; }
                    } else {
                        $statement = $pdo->prepare("SELECT * FROM tbl_shipping_cost_all WHERE sca_id=1");
                        $statement->execute();
                        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                        foreach ($result as $row) { $shipping_cost = $row['amount']; }
                    }
                    $final_total = $table_total_price + $shipping_cost;

                    // Address Check Logic
                    $checkout_access = 1;
                    if(
                        empty($_SESSION['customer']['cust_b_name']) || empty($_SESSION['customer']['cust_b_phone']) || empty($_SESSION['customer']['cust_b_address']) ||
                        empty($_SESSION['customer']['cust_s_name']) || empty($_SESSION['customer']['cust_s_phone']) || empty($_SESSION['customer']['cust_s_address'])
                    ) {
                        $checkout_access = 0;
                    }
                    ?>

                    <div class="bg-slate-50 dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700">
                        <h3 class="font-headline font-black text-xl text-surfaceDark dark:text-white mb-6">Payment Summary</h3>
                        
                        <div class="space-y-4 mb-6 text-sm font-medium text-textMuted dark:text-slate-400">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>₹<?php echo number_format($table_total_price, 2); ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Shipping & Handling</span>
                                <span>₹<?php echo number_format($shipping_cost, 2); ?></span>
                            </div>
                        </div>
                        
                        <div class="border-t border-slate-200 dark:border-slate-700 pt-6 flex justify-between items-end">
                            <span class="font-headline font-bold text-sm uppercase tracking-widest text-surfaceDark dark:text-white">Total</span>
                            <span class="font-headline font-black text-3xl text-surfaceDark dark:text-white">₹<?php echo number_format($final_total, 2); ?></span>
                        </div>
                    </div>

                    <?php if($checkout_access == 0): ?>
                        <div class="bg-indigo-50 dark:bg-indigo-500/10 rounded-3xl p-8 border border-indigo-100 dark:border-indigo-500/30 text-center">
                            <span class="material-symbols-outlined text-4xl text-primary dark:text-indigo-400 mb-4 block">local_shipping</span>
                            <h3 class="font-headline font-black text-xl text-surfaceDark dark:text-white mb-2">Delivery Details Required</h3>
                            <p class="text-sm text-textMuted dark:text-slate-400 mb-6">To ensure secure and accurate delivery of your instruments, please add your shipping and billing addresses.</p>
                            <a href="customer-billing-shipping-update.php" class="w-full block text-center py-3 bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-bold text-sm uppercase tracking-widest rounded-xl transition-all shadow-md">
                                Add Address
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-sm">
                            <h3 class="font-headline font-black text-xl text-surfaceDark dark:text-white mb-6">Select Payment Method</h3>
                            
                            <select name="payment_method" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-surfaceDark dark:text-white font-bold text-sm outline-none mb-6" id="advFieldsStatus">
                                <option value=""><?php echo LANG_VALUE_35; ?></option>
                                <option value="PayPal"><?php echo LANG_VALUE_36; ?></option>
                                <option value="Stripe">Credit/Debit Card (Stripe)</option>
                                <option value="Bank Deposit"><?php echo LANG_VALUE_38; ?></option>
                            </select>

                            <form class="paypal" action="<?php echo BASE_URL; ?>payment/paypal/payment_process.php" method="post" id="paypal_form" target="_blank">
                                <input type="hidden" name="cmd" value="_xclick" />
                                <input type="hidden" name="no_note" value="1" />
                                <input type="hidden" name="lc" value="UK" />
                                <input type="hidden" name="currency_code" value="USD" />
                                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                                <input type="hidden" name="final_total" value="<?php echo $final_total; ?>">
                                <button type="submit" class="w-full py-4 bg-[#003087] hover:bg-[#001c56] text-white font-bold rounded-xl shadow-md transition-colors flex justify-center items-center gap-2" name="form1">
                                    Pay with PayPal
                                </button>
                            </form>

                            <form action="payment/stripe/init.php" method="post" id="stripe_form">
                                <input type="hidden" name="payment" value="posted">
                                <input type="hidden" name="amount" value="<?php echo $final_total; ?>">
                                <div class="space-y-4 mb-6">
                                    <input type="text" name="card_number" class="card-number w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-surfaceDark dark:text-white placeholder:text-slate-400" placeholder="Card Number">
                                    <div class="grid grid-cols-3 gap-2">
                                        <input type="text" name="card_cvv" class="card-cvc w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-surfaceDark dark:text-white placeholder:text-slate-400" placeholder="CVV">
                                        <input type="text" name="card_month" class="card-expiry-month w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-surfaceDark dark:text-white placeholder:text-slate-400" placeholder="MM">
                                        <input type="text" name="card_year" class="card-expiry-year w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl h-12 px-4 text-surfaceDark dark:text-white placeholder:text-slate-400" placeholder="YYYY">
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-4 bg-[#635BFF] hover:bg-[#4a42d4] text-white font-bold rounded-xl shadow-md transition-colors flex justify-center items-center" name="form2" id="submit-button">
                                    Pay Securely via Stripe
                                </button>
                                <div id="msg-container"></div>
                            </form>

                            <form action="payment/bank/init.php" method="post" id="bank_form">
                                <input type="hidden" name="amount" value="<?php echo $final_total; ?>">
                                <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-4 mb-4 text-sm text-textMuted dark:text-slate-400 border border-slate-200 dark:border-slate-700">
                                    <p class="font-bold text-surfaceDark dark:text-white mb-2"><?php echo LANG_VALUE_43; ?></p>
                                    <?php
                                    $statement = $pdo->prepare("SELECT * FROM tbl_settings WHERE id=1");
                                    $statement->execute();
                                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($result as $row) { echo nl2br($row['bank_detail']); }
                                    ?>
                                </div>
                                <div class="mb-6">
                                    <label class="block text-xs font-bold text-surfaceDark dark:text-white mb-2 uppercase"><?php echo LANG_VALUE_44; ?></label>
                                    <textarea name="transaction_info" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-4 text-surfaceDark dark:text-white outline-none" rows="3" placeholder="Enter transaction ID or reference..."></textarea>
                                </div>
                                <button type="submit" class="w-full py-4 bg-primary hover:bg-primaryHover dark:bg-indigo-600 dark:hover:bg-indigo-500 text-white font-bold rounded-xl shadow-md transition-colors" name="form3">
                                    Confirm Bank Transfer
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once('footer.php'); ?>