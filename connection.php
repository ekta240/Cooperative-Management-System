<!-- <?php
$server='localhost';
$username='root';
$password='';
$database='summer_project';

$con=mysqli_connect($server,$username,$password) or die("connection error");

$db=mysqli_select_db($con,$database) or die("cannot find database");
?> -->


<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'summer_project';

// Establish connection
$con = mysqli_connect($server, $username, $password);

// Check connection
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Select database
$db_selected = mysqli_select_db($con, $database);

// Check if database selection was successful
if (!$db_selected) {
    die("Cannot find database: " . mysqli_error($con));
}

// Return the connection object
return $con;
?>
