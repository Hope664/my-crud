<?php 
include "config.php";
session_start();

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
       $user = $result->fetch_assoc();

if (password_verify($password, $user["password"])){
            $_SESSION["user_id"] = $user["id"];
            header("location: dashboard.php");
        }else{
            echo "wrong password";
        }
    }
    else{
        echo "user not found";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>crud in php</title>
    <link rel="stylesheet" href="server.css">
</head>
<body>
    <div class="container">
<form method="POST">
    <h2>Log in - PUROVUE Essentials</h2>
    <label for="email">Email: </label>
    <input type="email" id="email" name="email" placeholder="hope@gmail.com" required><br>
    <label for="pass">Password: </label>
    <input type="password"id="pass" name="password" placeholder="password" required><br>
    <button type="submit">Login</button>
</form>
    </div>
</body>
</html>
