<?php
ob_start();
session_start();
include("inc/config.php");
include("inc/functions.php");

// Autoload Dompdf (from the root folder of your project)
require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Check if user is logged in as admin (assuming you have a session variable like this)
// if(!isset($_SESSION['user'])) { header('location: login.php'); exit; }

// Initialize HTML for the PDF
$html = '
<html>
<head>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; }
        h2 { text-align: center; color: #333; margin-bottom: 20px; font-size: 24px; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f4f4f4; color: #333; font-weight: bold; text-transform: uppercase; }
        .status-completed { color: green; font-weight: bold; }
        .status-pending { color: orange; font-weight: bold; }
    </style>
</head>
<body>
    <h2>All Orders Report</h2>
    <table>
        <thead>
            <tr>
                <th>Order Ref</th>
                <th>Date</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Amount (INR)</th>
                <th>Method</th>
                <th>Payment</th>
                <th>Shipping</th>
            </tr>
        </thead>
        <tbody>';

// Fetch all orders from the database
$statement = $pdo->prepare("SELECT * FROM tbl_payment ORDER BY id DESC");
$statement->execute();
$result = $statement->fetchAll(PDO::FETCH_ASSOC);

foreach ($result as $row) {
    // Fetch customer name based on email
    $stmt1 = $pdo->prepare("SELECT cust_name FROM tbl_customer WHERE cust_email=?");
    $stmt1->execute(array($row['customer_email']));
    $cust_name = "Unknown";
    if ($stmt1->rowCount() > 0) {
        $row1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $cust_name = $row1['cust_name'];
    }

    $payment_class = ($row['payment_status'] == 'Completed') ? 'status-completed' : 'status-pending';
    $shipping_class = ($row['shipping_status'] == 'Completed' || $row['shipping_status'] == 'Shipped') ? 'status-completed' : 'status-pending';

    $html .= '<tr>
                <td>#' . $row['payment_id'] . '</td>
                <td>' . date('M d, Y', strtotime($row['payment_date'])) . '</td>
                <td>' . htmlspecialchars($cust_name) . '</td>
                <td>' . htmlspecialchars($row['customer_email']) . '</td>
                <td>' . number_format($row['paid_amount'], 2) . '</td>
                <td>' . $row['payment_method'] . '</td>
                <td class="' . $payment_class . '">' . $row['payment_status'] . '</td>
                <td class="' . $shipping_class . '">' . $row['shipping_status'] . '</td>
              </tr>';
}

$html .= '</tbody>
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
$dompdf->setPaper('A4', 'landscape');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser (force download)
$filename = "Order_Report_" . date('Ymd_His') . ".pdf";
$dompdf->stream($filename, array("Attachment" => 1)); // 1 = force download
?>