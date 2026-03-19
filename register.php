<?php
include "config.php";
if($_SERVER["REQUEST_METHOD"]=="POST"){
$username = $_POST["username"];
$email = $_POST["email"];
$password =password_hash($_POST["password"], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username,email,password) VALUES('$username','$email','$password')";
if($conn->query($sql)){
echo "Account created successfully!";
}
else{
    echo "Error:" . $conn->error;
}
}
?> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PUROVUE</title>
    <link rel="stylesheet" href="server.css">
</head>
<body>
    <div class="container">
        <form method="POST">
    <h2>Register - PUROVUE Essentials</h2>
    <label for="name">Username: </label>
    <input type="text" id="name" name="username" placeholder="Hope" required><br>
    <label for="email">Email: </label>
    <input type="email" id="email" name="email" placeholder="hope@gmail.com" required><br>
    <label for="pass">Password: </label>
    <input type="password"id="pass" name="password" placeholder="password" required><br>
    <button type="submit">Register</button>
</form>
    </div>
</body>
</html>
