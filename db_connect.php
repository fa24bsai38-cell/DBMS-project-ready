<?php
$hostname = "localhost";
$username = "root";
$password = "";
$database = "lab_db";

// Create connection
$conn = mysqli_connect($hostname, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connected successfully to MySQL database!";
}

// Close connection
mysqli_close($conn);
?>