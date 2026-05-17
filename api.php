<?php
header("Content-Type:application/json");
header("Access-Control-Allow-Origin: *");

$host = "localhost";
$db = "purovue";
$user = "root";
$pass = "uMuHoZa@123hope";

$conn = new mysqli($host,$user,$pass,$db);

if($conn->connect_error){
exit('connection failed: '. $conn->connect_error);
}
echo('connected successfully');

$method = $_SERVER['REQEST_METHOD'];
if($method=='POST'){

}
?>