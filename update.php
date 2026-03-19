<?php
include "config.php";

$id = $_GET["id"];

$result = $conn->query("SELECT * FROM clients WHERE id=$id");
$row = $result->fetch_assoc();


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    $sql = "UPDATE clients 
            SET name='$name', email='$email', phone='$phone'
            WHERE id=$id";

    if ($conn->query($sql)) {
        header("Location: dashboard.php");
    } else {
        echo "Error updating: " . $conn->error;
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
<h2>Edit Client</h2>

<form method="POST">
    <input type="text" name="name" value="<?php echo $row['name']; ?>" required>
    <input type="email" name="email" value="<?php echo $row['email']; ?>" required>
    <input type="text" name="phone" value="<?php echo $row['phone']; ?>" required>
    <button type="submit">Update</button>
</form>
    </div>
</body>
</html>
