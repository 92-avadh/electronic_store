<?php
ob_start();
session_start();
require_once('admin/inc/config.php'); // Ensure this points to your database connection file
require_once 'dompdf/autoload.inc.php'; // Adjust path if you placed dompdf elsewhere

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Security Check
if(!isset($_SESSION['customer'])) {
    header('location: login.php');
    exit;
}

if(!isset($_GET['payment_id'])) {
    header('location: customer-order.php');
    exit;
}

$payment_id = $_GET['payment_id'];
$cust_email = $_SESSION['customer']['cust_email'];

// 2. Fetch Order and Payment Details
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id=? AND customer_email=?");
$statement->execute(array($payment_id, $cust_email));
$order = $statement->fetch(PDO::FETCH_ASSOC);

if(!$order) {
    die("Invalid Order or Authorization Failed.");
}

// 3. Fetch Purchased Items
$stmt_items = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
$stmt_items->execute(array($payment_id));
$items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

// 4. Build the HTML for the Invoice
// 4. Build the HTML for the Invoice
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - ' . $order['payment_id'] . '</title>
    <style>
        /* Changed font-family to DejaVu Sans to support ₹ symbol */
        body { font-family: "DejaVu Sans", sans-serif; color: #333; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); }
        .header { text-align: center; margin-bottom: 40px; }
        .header h2 { margin: 0; padding: 0; color: #4f46e5; }
        .details-table, .items-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .details-table td { padding: 5px 0; }
        .items-table th, .items-table td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        .items-table th { background-color: #f8f9fa; }
        .total-row { font-weight: bold; font-size: 18px; color: #4f46e5; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <h2>Order Invoice</h2>
            <p>Thank you for your purchase!</p>
        </div>

        <table class="details-table">
            <tr>
                <td><strong>Order ID:</strong> #'.$order['payment_id'].'</td>
                <td style="text-align: right;"><strong>Order Date:</strong> '.date('F d, Y', strtotime($order['payment_date'])).'</td>
            </tr>
            <tr>
                <td><strong>Payment Method:</strong> '.$order['payment_method'].'</td>
                <td style="text-align: right;"><strong>Payment Status:</strong> '.$order['payment_status'].'</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>';

            foreach($items as $item) {
                // Adjust variables below if your tbl_order column names differ
                $html .= '
                <tr>
                    <td>'.htmlspecialchars($item['product_name']).'</td>
                    <td>'.$item['quantity'].'</td>
                    <td style="text-align:right;">₹'.number_format($item['unit_price'] * $item['quantity'], 2).'</td>
                </tr>';
            }

$html .= '
                <tr class="total-row">
                    <td colspan="2" style="text-align: right;">Grand Total:</td>
                    <td style="text-align: right;">₹'.number_format($order['paid_amount'], 2).'</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>';

// 5. Generate and Download PDF
$options = new Options();
$options->set('isRemoteEnabled', true); // Allows external CSS/images if needed
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the generated PDF to Browser (Attachment => 1 forces download, 0 opens in browser)
$dompdf->stream("Invoice_" . $order['payment_id'] . ".pdf", array("Attachment" => 1));
?>