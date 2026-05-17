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

$method = $_SERVER['REQUEST_METHOD'];
if($method=='POST'){
    $username = "Happy";
    $email = "happy@gmail.com";
    $password = "12dhiegtudyjhiedjlk";

$sql = "INSERT INTO users (username,email,password)VALUES ('$username','$email',$password')";
$result = $conn->query($sql);

if($result){
    echo "successfully inserted";
}else{
    echo "Error:".$conn->error;
}
}
?>