<?php
include "db.php";
session_start();

// security
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// insert product
if (isset($_POST['add'])) {

    $name = $_POST['name'];
    $category = $_POST['category'];
    $buy = $_POST['buying_price'];
    $sell = $_POST['selling_price'];
    $stock = $_POST['stock'];

    $query = "INSERT INTO products (name, category, buying_price, selling_price, stock)
              VALUES ('$name','$category','$buy','$sell','$stock')";

    mysqli_query($conn, $query);

    echo "<script>alert('Product Added Successfully');</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Product</title>

<style>
body{
    font-family: Arial;
    background:#f4f6f9;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}

.form-box{
    background:white;
    padding:30px;
    border-radius:10px;
    width:350px;
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
}
</style>

</head>

<body>

<div class="form-box">
<h2>Add Product</h2>

<form method="POST">

<input type="text" name="name" placeholder="Product Name" required>

<select name="category">
    <option>Sports</option>
    <option>Stationery</option>
    <option>Photostat</option>
</select>

<input type="number" name="buying_price" placeholder="Buying Price" required>
<input type="number" name="selling_price" placeholder="Selling Price" required>
<input type="number" name="stock" placeholder="Stock Quantity" required>

<button name="add">Add Product</button>

</form>

</div>

</body>
</html>