<?php 
include "config.php";
session_start();

if(!isset($_SESSION["user_id"])){
    header("location:login.php");
    exit();
}
if($_SERVER["REQUEST_METHOD"]=="POST"){
    $name = $_POST["username"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    $check = $conn->prepare("SELECT * FROM clients WHERE username = ?");
    $check->bind_param("s",$name);
    $check->execute();
    $result_check = $check->get_result();
    if($result_check->num_rows>0){
        echo "<p style='color:red;'>Username already taken. Please choose another.</p>";
    }else{
        $stmt = $conn->prepare("INSERT INTO clients(username,email,phone)VALUES(?,?,?)");
        $stmt->bind_param("sss", $name, $email, $phone);
        $stmt->execute();
        echo "<p style='color:green;'>Client added successfully!</p>";
    }
  //  $sql = "INSERT INTO clients(username,email,phone)
   // VALUES('$name','$email', '$phone')";
    //$conn->query($sql);
}

$result = $conn->query("SELECT * FROM clients");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>purovue</title>
    <link rel="stylesheet" href="server.css">
</head>
<body>
    <div class="container">
        <h2>PUROVUE Essentials dashboard</h2>

<a href="logout.php">Logout</a>

<form method="POST">
    <label for="name">username</label>
    <input type="text" id="name" name="username" placeholder="Client Name" required><br>
    <label for="email">Email</label>
    <input type="email" id="email" name="email" placeholder="Client Email" required><br>
    <label for="phone">Phone</label>
    <input type="text" id="phone" name="phone" placeholder="Phone" required>
    <button type="submit">Add Client</button>
</form>
<h3>Clients List</h3>

<table border="1">
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
</tr>

<?php while($row = $result->fetch_assoc()) { ?>
<tr>
    <td><?php echo $row["username"]; ?></td>
    <td><?php echo $row["email"]; ?></td>
    <td><?php echo $row["phone"]; ?></td>
    <td>
    <a href="delete.php?id=<?php echo $row['id']; ?>">Delete</a>
</td>
<td>
    <a href="update.php?id=<?php echo $row['id']; ?>">Edit</a>
</td>
</tr>
<?php } ?>

</table>
    </div>
</body>
</html>
