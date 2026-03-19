<?php
$host = "localhost";
$user = "root";
$password  = "uMuHoZa@123hope";
$dbname = "purovue";

$conn = new mysqli($host,$user,$password,$dbname);
if($conn->connect_error){
    die("connection failed". $conn->connect_error);
}
?>
