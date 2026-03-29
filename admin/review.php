<?php require_once('header.php'); ?>

<?php
// Handle Review Deletion
if(isset($_GET['delete_id'])) {
    $statement = $pdo->prepare("DELETE FROM tbl_rating WHERE rating_id=?");
    $statement->execute(array($_GET['delete_id']));
    $success_message = 'Review deleted successfully.';
}
?>

<!-- ✅ FIXED: removed md:ml-64 -->
<div class="p-6 md:p-8 pt-24 md:pt-8 transition-all duration-300">
    
    <div class="flex justify-between items-center mb-8">
        <div>
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400 mb-1">
                Inventory Management
            </p>
            <h2 class="text-3xl font-extrabold font-headline text-slate-900 dark:text-white">
                Product Reviews
            </h2>
        </div>
    </div>

    <?php if(isset($success_message) && $success_message != ''): ?>
        <div class="bg-green-50 dark:bg-green-500/10 text-green-600 dark:text-green-400 px-6 py-4 rounded-2xl mb-8 text-sm font-bold border border-green-200 dark:border-green-500/20 shadow-sm flex items-center gap-3">
            <span class="material-symbols-outlined">check_circle</span>
            <?php echo $success_message; ?>
        </div>
    <?php endif; ?>

    <div class="bg-white dark:bg-slate-800 rounded-[2rem] border border-slate-100 dark:border-slate-700 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">#</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Product</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Customer</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Rating</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400">Review Comment</th>
                        <th class="px-6 py-5 text-[10px] font-black uppercase tracking-widest text-slate-400 text-right">Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    <?php
                    $i=0;
                    $statement = $pdo->prepare("
                        SELECT r.*, p.p_name, c.cust_name, c.cust_email 
                        FROM tbl_rating r 
                        JOIN tbl_product p ON r.p_id = p.p_id 
                        JOIN tbl_customer c ON r.cust_id = c.cust_id 
                        ORDER BY r.rating_id DESC
                    ");
                    $statement->execute();
                    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                    
                    if(count($result) == 0): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-sm text-slate-500 font-medium">
                                <span class="material-symbols-outlined text-5xl block mb-3 opacity-30">reviews</span>
                                No reviews found.
                            </td>
                        </tr>
                    <?php else:
                        foreach ($result as $row) {
                            $i++;
                            ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-5 text-sm font-bold"><?php echo $i; ?></td>

                                <td class="px-6 py-5">
                                    <a href="../product.php?id=<?php echo $row['p_id']; ?>" target="_blank"
                                       class="text-sm font-bold text-sky-600 hover:underline">
                                        <?php echo htmlspecialchars($row['p_name']); ?>
                                    </a>
                                </td>

                                <td class="px-6 py-5">
                                    <p class="text-sm font-bold"><?php echo htmlspecialchars($row['cust_name']); ?></p>
                                    <p class="text-xs text-slate-500"><?php echo htmlspecialchars($row['cust_email']); ?></p>
                                </td>

                                <td class="px-6 py-5">
                                    ⭐ <?php echo $row['rating']; ?>
                                </td>

                                <td class="px-6 py-5">
                                    "<?php echo htmlspecialchars($row['comment']); ?>"
                                </td>

                                <td class="px-6 py-5 text-right">
                                    <a href="review.php?delete_id=<?php echo $row['rating_id']; ?>"
                                       class="px-3 py-2 bg-red-500 text-white rounded"
                                       onclick="return confirm('Delete this review?');">
                                       Delete
                                    </a>
                                </td>
                            </tr>
                            <?php
                        }
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>