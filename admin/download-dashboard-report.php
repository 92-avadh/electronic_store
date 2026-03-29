<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");

// Autoload Dompdf
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Fetch Dashboard Metrics (Identical to index.php logic)
$total_product = 0; $total_customer = 0; $total_shipping_completed = 0; $total_shipping_pending = 0;
$total_order_completed = 0; $total_order_pending = 0; $available_amount = 0;

$statement = $pdo->prepare("SELECT * FROM tbl_product"); $statement->execute(); $total_product = $statement->rowCount();
$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_status='1'"); $statement->execute(); $total_customer = $statement->rowCount();
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE shipping_status=?"); $statement->execute(array('Completed')); $total_shipping_completed = $statement->rowCount();
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE shipping_status=?"); $statement->execute(array('Pending')); $total_shipping_pending = $statement->rowCount();
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?"); $statement->execute(array('Completed')); $total_order_completed = $statement->rowCount();
$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?"); $statement->execute(array('Pending')); $total_order_pending = $statement->rowCount();

$statement = $pdo->prepare("SELECT * FROM tbl_payment WHERE payment_status=?"); $statement->execute(array('Completed'));
$result = $statement->fetchAll(PDO::FETCH_ASSOC);
foreach ($result as $row) { $available_amount += $row['paid_amount']; }

// Initialize HTML for the PDF
$html = '
<html>
<head>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #333; }
        h2 { text-align: center; color: #0052CC; margin-bottom: 5px; font-size: 26px; text-transform: uppercase; letter-spacing: 1px; }
        .date { text-align: center; font-size: 12px; color: #777; margin-bottom: 30px; border-bottom: 2px solid #eee; padding-bottom: 15px;}
        
        table { width: 100%; border-collapse: separate; border-spacing: 15px; }
        td { background: #f8fafc; padding: 25px; border-radius: 8px; border: 1px solid #e2e8f0; width: 50%; }
        
        .metric-title { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; color: #64748b; margin-bottom: 10px; display:block; }
        .metric-value { font-size: 32px; font-weight: bold; color: #0f172a; display:block; }
        .revenue { color: #0052CC; }
        
        .section-title { font-size: 16px; font-weight: bold; color: #333; margin-top: 20px; padding-left: 10px; border-left: 4px solid #0052CC; }
    </style>
</head>
<body>
    <h2>Admin Dashboard Report</h2>
    <div class="date">Generated on: ' . date('M d, Y h:i A') . '</div>
    
    <div class="section-title">Store Overview</div>
    <table>
        <tr>
            <td>
                <span class="metric-title">Total Revenue</span>
                <span class="metric-value revenue">INR ' . number_format($available_amount, 2) . '</span>
            </td>
            <td>
                <span class="metric-title">Total Products</span>
                <span class="metric-value">' . $total_product . '</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="metric-title">Total Customers</span>
                <span class="metric-value">' . $total_customer . '</span>
            </td>
            <td>
                <span class="metric-title">Pending Orders</span>
                <span class="metric-value">' . $total_order_pending . '</span>
            </td>
        </tr>
    </table>

    <div class="section-title">Fulfillment Status</div>
    <table>
        <tr>
            <td>
                <span class="metric-title">Pending Shipment</span>
                <span class="metric-value" style="color: #f97316;">' . $total_shipping_pending . '</span>
            </td>
            <td>
                <span class="metric-title">Completed Shipments</span>
                <span class="metric-value" style="color: #22c55e;">' . $total_shipping_completed . '</span>
            </td>
        </tr>
    </table>
</body>
</html>';

// Initialize and configure Dompdf
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// Load HTML string
$dompdf->loadHtml($html);

// Setup paper size and orientation ('portrait' or 'landscape')
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser (force download)
$filename = "Dashboard_Report_" . date('Ymd_His') . ".pdf";
$dompdf->stream($filename, array("Attachment" => 1));
?>