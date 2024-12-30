<?php
$server='localhost';
$username='root';
$password='';
$database='summer_project';

$con=mysqli_connect($server,$username,$password) or die("connection error");

$db=mysqli_select_db($con,$database) or die("cannot find database");
?>