<?php
include "db.php";
session_start();

/* ================= SECURITY ================= */
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

/* ================= MESSAGE VARIABLES ================= */
$msg = "";
$error = "";

/* ================= WHEN SELL BUTTON CLICKED ================= */
if (isset($_POST['sell'])) {

    // user input
    $category = $_POST['category'];
    $name = strtolower(trim($_POST['name'])); // case-insensitive
    $qty = intval($_POST['qty']);

    /* ================= FIND PRODUCT ================= */
    $query = "SELECT * FROM products 
              WHERE LOWER(name)='$name' 
              AND category='$category'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_assoc($result);

        /* ================= STOCK CHECK ================= */
        if ($row['stock'] >= $qty) {

            // new stock after selling
            $new_stock = $row['stock'] - $qty;

            // total price calculation
            $total = $qty * $row['selling_price'];

            /* ================= UPDATE STOCK ================= */
            mysqli_query($conn, "
                UPDATE products 
                SET stock='$new_stock' 
                WHERE id='{$row['id']}'
            ");

            /* ================= INSERT INTO SALES TABLE ================= */
            mysqli_query($conn, "
                INSERT INTO sales 
                (product_name, category, quantity, total_price, sale_date, sale_time)
                VALUES 
                ('{$row['name']}', '$category', '$qty', '$total', CURDATE(), CURTIME())
            ");

            $msg = "Sale Successful ✔ Stock Updated";

        } else {
            $error = "Not enough stock ❌";
        }

    } else {
        $error = "Product not found ❌ (check spelling)";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Sell Product</title>

<style>
body{
    font-family:Arial;
    background:#f4f6f9;
}

.box{
    width:350px;
    margin:50px auto;
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 5px 15px rgba(0,0,0,0.2);
}

input, select{
    width:100%;
    padding:10px;
    margin:10px 0;
}

button{
    width:100%;
    padding:10px;
    background:#2a5298;
    color:white;
    border:none;
    cursor:pointer;
}

.msg{
    color:green;
    text-align:center;
}

.err{
    color:red;
    text-align:center;
}
</style>
</head>

<body>

<div class="box">

<h2>🛒 Sell Product</h2>

<form method="POST">

<select name="category" required>
    <option>Sports</option>
    <option>Stationery</option>
    <option>Photostat</option>
</select>

<input type="text" name="name" placeholder="Product Name (Pen, Bat etc)" required>

<input type="number" name="qty" placeholder="Quantity" required>

<button name="sell">SELL PRODUCT</button>

</form>

<!-- messages -->
<div class="msg"><?php echo $msg; ?></div>
<div class="err"><?php echo $error; ?></div>

</div>

</body>
</html>