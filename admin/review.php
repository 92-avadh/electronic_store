<?php require_once('header.php'); ?>

<?php
// Handle Review Deletion
if(isset($_GET['delete_id'])) {
    $statement = $pdo->prepare("DELETE FROM tbl_rating WHERE rating_id=?");
    $statement->execute(array($_GET['delete_id']));
    $success_message = 'Review deleted successfully.';
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Product Reviews</h1>
	</div>
</section>

<section class="content">
    <div class="row">
        <div class="col-md-12">

            <?php if(isset($success_message) && $success_message != ''): ?>
                <div class="callout callout-success">
                    <p><?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>

            <div class="box box-info">
                <div class="box-body table-responsive">
                    <table id="example1" class="table table-bordered table-hover table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product Name</th>
                                <th>Customer Name</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i=0;
                            $statement = $pdo->prepare("
                                SELECT r.*, p.p_name, c.cust_name 
                                FROM tbl_rating r 
                                JOIN tbl_product p ON r.p_id = p.p_id 
                                JOIN tbl_customer c ON r.cust_id = c.cust_id 
                                ORDER BY r.rating_id DESC
                            ");
                            $statement->execute();
                            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
                            foreach ($result as $row) {
                                $i++;
                                ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><a href="../product.php?id=<?php echo $row['p_id']; ?>" target="_blank"><?php echo $row['p_name']; ?></a></td>
                                    <td><?php echo htmlspecialchars($row['cust_name']); ?></td>
                                    <td>
                                        <b style="color:#f39c12; font-size:16px;"><?php echo $row['rating']; ?> / 5</b>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['comment']); ?></td>
                                    <td><?php echo date('d M, Y', strtotime($row['rating_date'])); ?></td>
                                    <td>
                                        <a href="review.php?delete_id=<?php echo $row['rating_id']; ?>" class="btn btn-danger btn-xs" onClick="return confirm('Are you sure?');">Delete</a>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once('footer.php'); ?>