<?php
include "db.php";
session_start();

// security
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// fetch products
$query = "SELECT * FROM products";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
<title>View Products</title>

<style>
body{
    font-family: Arial;
    background:#f4f6f9;
    padding:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th, td{
    padding:12px;
    border:1px solid #ddd;
    text-align:center;
}

th{
    background:#2a5298;
    color:white;
}

a{
    text-decoration:none;
    font-weight:bold;
}
</style>

</head>

<body>

<h2>📦 All Products</h2>

<a href="dashboard.php">⬅ Back</a>

<br><br>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Category</th>
    <th>Buying Price</th>
    <th>Selling Price</th>
    <th>Stock</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['category']; ?></td>
    <td><?php echo $row['buying_price']; ?></td>
    <td><?php echo $row['selling_price']; ?></td>
    <td><?php echo $row['stock']; ?></td>
</tr>

<?php } ?>

</table>

</body>
</html>