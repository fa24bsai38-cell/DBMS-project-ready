<?php
include "db.php";
session_start();

/* ================= SECURITY ================= */
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

/* ================= TODAY SALES ================= */
$query = "SELECT * FROM sales 
          WHERE sale_date = CURDATE()
          ORDER BY sale_time DESC";

$result = mysqli_query($conn, $query);

/* ================= TOTAL INCOME TODAY ================= */
$total_query = "SELECT SUM(total_price) AS total 
                FROM sales 
                WHERE sale_date = CURDATE()";

$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);

$today_income = $total_row['total'];
?>

<!DOCTYPE html>
<html>
<head>
<title>Daily Sales Report</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f9;
}

.container{
    width:80%;
    margin:30px auto;
}

h1{
    text-align:center;
    color:#2a5298;
}

/* TOTAL BOX */
.total-box{
    background:#2a5298;
    color:white;
    padding:15px;
    text-align:center;
    border-radius:10px;
    margin-bottom:20px;
    font-size:18px;
}

/* SALE CARD */
.card{
    background:white;
    padding:15px;
    margin-bottom:10px;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.1);
    transition:0.3s;
}

.card:hover{
    transform:scale(1.02);
}
</style>
</head>

<body>

<div class="container">

<h1>📊 Today Sales Report</h1>

<!-- TOTAL INCOME -->
<div class="total-box">
    💰 Total Income Today: 
    <?php echo $today_income ? $today_income : 0; ?>
</div>

<!-- SALES LIST -->
<?php while($row = mysqli_fetch_assoc($result)) { ?>

<div class="card">
    <h3>🛒 Product: <?php echo $row['product_name']; ?></h3>
    <p>📂 Category: <?php echo $row['category']; ?></p>
    <p>🔢 Quantity: <?php echo $row['quantity']; ?></p>
    <p>💵 Total Price: <?php echo $row['total_price']; ?></p>
    <p>⏰ Time: <?php echo $row['sale_time']; ?></p>
</div>

<?php } ?>

</div>

</body>
</html>