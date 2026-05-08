<?php
include "db.php";
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

/* ================= SELECT MONTH ================= */
$month = $_GET['month'] ?? date('Y-m');

/* ================= TOTAL SALES ================= */
$totalQuery = mysqli_query($conn, "
SELECT 
SUM(total_price) AS total_sales,
SUM(quantity) AS total_items
FROM sales
WHERE DATE_FORMAT(sale_date, '%Y-%m') = '$month'
");

$totalData = mysqli_fetch_assoc($totalQuery);
$totalSales = $totalData['total_sales'] ?? 0;
$totalItems = $totalData['total_items'] ?? 0;

/* ================= PROFIT CALCULATION ================= */
$profitQuery = mysqli_query($conn, "
SELECT 
SUM((p.selling_price - p.buying_price) * s.quantity) AS profit
FROM sales s
JOIN products p 
ON s.product_name = p.name
WHERE DATE_FORMAT(s.sale_date, '%Y-%m') = '$month'
");

$profitData = mysqli_fetch_assoc($profitQuery);
$totalProfit = $profitData['profit'] ?? 0;

/* ================= DAILY BREAKDOWN ================= */
$daily = mysqli_query($conn, "
SELECT sale_date, 
SUM(total_price) AS daily_sales
FROM sales
WHERE DATE_FORMAT(sale_date, '%Y-%m') = '$month'
GROUP BY sale_date
ORDER BY sale_date ASC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Monthly Report</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f9;
    text-align:center;
}

.container{
    width:85%;
    margin:auto;
    background:white;
    padding:20px;
    margin-top:20px;
    border-radius:10px;
}

.card{
    display:inline-block;
    padding:15px;
    margin:10px;
    background:#2a5298;
    color:white;
    border-radius:10px;
    width:200px;
}

table{
    width:100%;
    margin-top:20px;
    border-collapse:collapse;
}

th,td{
    padding:10px;
    border:1px solid #ddd;
    text-align:center;
}

th{
    background:#2a5298;
    color:white;
}

input,button{
    padding:8px;
    margin:5px;
}
</style>
</head>

<body>

<div class="container">

<h2>🧾 Monthly Profit Report</h2>

<!-- MONTH SELECT -->
<form method="GET">
    <input type="month" name="month" value="<?php echo $month; ?>">
    <button type="submit">View Report</button>
</form>

<!-- SUMMARY CARDS -->
<div class="card">
    <h3>Total Sales</h3>
    <p><?php echo $totalSales; ?></p>
</div>

<div class="card">
    <h3>Total Profit</h3>
    <p><?php echo $totalProfit; ?></p>
</div>

<div class="card">
    <h3>Total Items</h3>
    <p><?php echo $totalItems; ?></p>
</div>

<!-- DAILY REPORT -->
<h3 style="margin-top:20px;">📅 Daily Breakdown</h3>

<table>
<tr>
    <th>Date</th>
    <th>Sales</th>
</tr>

<?php while($row = mysqli_fetch_assoc($daily)) { ?>
<tr>
    <td><?php echo $row['sale_date']; ?></td>
    <td><?php echo $row['daily_sales']; ?></td>
</tr>
<?php } ?>

</table>

</div>

</body>
</html>