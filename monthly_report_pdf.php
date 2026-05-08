<?php
include "db.php";
session_start();

require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

if (!isset($_SESSION['user'])) {
    die("Access denied");
}

/* ================= MONTH ================= */
$month = $_GET['month'] ?? date('Y-m');

/* ================= DATA ================= */
$query = mysqli_query($conn, "
SELECT sale_date, SUM(total_price) AS total_sales
FROM sales
WHERE DATE_FORMAT(sale_date,'%Y-%m') = '$month'
GROUP BY sale_date
ORDER BY sale_date ASC
");

$totalQ = mysqli_query($conn, "
SELECT SUM(total_price) AS total_sales,
SUM(quantity) AS total_items
FROM sales
WHERE DATE_FORMAT(sale_date,'%Y-%m') = '$month'
");

$total = mysqli_fetch_assoc($totalQ);
$grandTotal = $total['total_sales'] ?? 0;
$items = $total['total_items'] ?? 0;

/* ================= BUILD HTML ================= */
$html = "
<h2 style='text-align:center;'>Monthly Report ($month)</h2>

<p><b>Total Sales:</b> $grandTotal</p>
<p><b>Total Items:</b> $items</p>

<table border='1' width='100%' cellpadding='5' cellspacing='0'>
<tr>
<th>Date</th>
<th>Sales</th>
</tr>
";

while($row = mysqli_fetch_assoc($query)) {
    $html .= "
    <tr>
        <td>{$row['sale_date']}</td>
        <td>{$row['total_sales']}</td>
    </tr>
    ";
}

$html .= "</table>";

/* ================= GENERATE PDF ================= */
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

/* FORCE DOWNLOAD */
$dompdf->stream("monthly_report_$month.pdf", ["Attachment" => true]);
exit;
?>