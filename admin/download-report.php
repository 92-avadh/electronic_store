<?php
ob_start();
session_start();
require_once('inc/config.php'); 

// Note the '../' because this file is inside the admin folder and dompdf is in the root
require_once '../dompdf/autoload.inc.php'; 

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Security Check (Admin Only)
if(!isset($_SESSION['user'])) { // Adjust 'user' to whatever session key your admin panel uses to verify logged-in admins
    header('location: login.php');
    exit;
}

if(!isset($_GET['id'])) {
    header('location: order.php');
    exit;
}

$payment_id = $_GET['id'];

// 2. Fetch Order Details
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_id=?");
$statement->execute(array($payment_id));
$order = $statement->fetch(PDO::FETCH_ASSOC);

if(!$order) {
    die("Invalid Order ID.");
}

// 3. Fetch Customer Details
$stmt_cust = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email=?");
$stmt_cust->execute(array($order['customer_email']));
$customer = $stmt_cust->fetch(PDO::FETCH_ASSOC);

// 4. Fetch Ordered Items
$stmt_items = $pdo->prepare("SELECT * FROM tbl_order WHERE payment_id=?");
$stmt_items->execute(array($payment_id));
$items = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

// 5. Build HTML for Admin Report
$html = '
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Order Report - ' . $order['payment_id'] . '</title>
    <style>
        body { font-family: "DejaVu Sans", sans-serif; color: #333; font-size: 13px; }
        .report-wrapper { padding: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #0052CC; padding-bottom: 15px; }
        .header h1 { margin: 0; color: #0052CC; font-size: 24px; text-transform: uppercase; }
        .header p { margin: 5px 0 0; color: #555; font-size: 12px; }
        
        .section-title { background-color: #f1f5f9; padding: 8px 12px; font-weight: bold; font-size: 14px; margin-bottom: 10px; border-left: 4px solid #0052CC; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 25px; }
        table th, table td { padding: 10px; border: 1px solid #e2e8f0; text-align: left; vertical-align: top; }
        table th { background-color: #f8fafc; color: #475569; width: 25%; font-size: 12px; }
        
        .items-table th { background-color: #0052CC; color: white; width: auto; font-size: 13px; border-color: #0052CC;}
        .right-align { text-align: right !important; }
        .bold { font-weight: bold; }
    </style>
</head>
<body>
    <div class="report-wrapper">
        <div class="header">
            <h1>Order Detailed Report</h1>
            <p>Generated on: ' . date('F d, Y h:i A') . '</p>
        </div>

        <div class="section-title">Order Information</div>
        <table>
            <tr>
                <th>Order Reference</th>
                <td class="bold">#' . $order['payment_id'] . '</td>
                <th>Order Date</th>
                <td>' . date('F d, Y - h:i A', strtotime($order['payment_date'])) . '</td>
            </tr>
            <tr>
                <th>Payment Method</th>
                <td>' . $order['payment_method'] . '</td>
                <th>Transaction ID</th>
                <td>' . (!empty($order['txnid']) ? htmlspecialchars($order['txnid']) : 'N/A') . '</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td class="bold">' . $order['payment_status'] . '</td>
                <th>Shipping Status</th>
                <td class="bold">' . $order['shipping_status'] . '</td>
            </tr>
        </table>

        <div class="section-title">Customer Information</div>
        <table>
            <tr>
                <th>Customer Name</th>
                <td>' . ($customer ? htmlspecialchars($customer['cust_name']) : 'N/A') . '</td>
                <th>Email Address</th>
                <td>' . htmlspecialchars($order['customer_email']) . '</td>
            </tr>
            <tr>
                <th>Phone Number</th>
                <td colspan="3">' . ($customer ? htmlspecialchars($customer['cust_phone']) : 'N/A') . '</td>
            </tr>
            <tr>
                <th>Billing Address</th>
                <td colspan="3">' . ($customer ? htmlspecialchars($customer['cust_b_address'].', '.$customer['cust_b_city'].', '.$customer['cust_b_state'].' - '.$customer['cust_b_zip']) : 'N/A') . '</td>
            </tr>
            <tr>
                <th>Shipping Address</th>
                <td colspan="3">' . ($customer ? htmlspecialchars($customer['cust_s_address'].', '.$customer['cust_s_city'].', '.$customer['cust_s_state'].' - '.$customer['cust_s_zip']) : 'N/A') . '</td>
            </tr>
        </table>

        <div class="section-title">Purchased Items</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product Name</th>
                    <th style="text-align: center;">Qty</th>
                    <th class="right-align">Unit Price</th>
                    <th class="right-align">Subtotal</th>
                </tr>
            </thead>
            <tbody>';
            $i = 1;
            foreach($items as $item) {
                $html .= '<tr>
                    <td>'.$i++.'</td>
                    <td>'.htmlspecialchars($item['product_name']).'</td>
                    <td style="text-align: center;">'.$item['quantity'].'</td>
                    <td class="right-align">₹'.number_format($item['unit_price'], 2).'</td>
                    <td class="right-align">₹'.number_format($item['unit_price'] * $item['quantity'], 2).'</td>
                </tr>';
            }
            $html .= '
                <tr>
                    <td colspan="4" class="right-align bold" style="font-size: 15px;">Grand Total</td>
                    <td class="right-align bold" style="font-size: 15px;">₹'.number_format($order['paid_amount'], 2).'</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>';

// 6. Generate and Download PDF
$options = new Options();
$options->set('isRemoteEnabled', true); 
$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("Order_Report_" . $order['payment_id'] . ".pdf", array("Attachment" => 1));
?>