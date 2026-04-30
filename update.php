<?php
include "config.php";
session_start();

// Only admin can edit from this page
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit();
}

if (!isset($_GET["id"])) {
    header("Location: dashboard.php");
    exit();
}

$id   = intval($_GET["id"]);
$stmt = $conn->prepare("SELECT * FROM clients WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

if (!$row) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]); // was wrongly 'name' before
    $email    = trim($_POST["email"]);
    $phone    = trim($_POST["phone"]);

    $stmt = $conn->prepare(
        "UPDATE clients SET username=?, email=?, phone=? WHERE id=?"
    );
    $stmt->bind_param("sssi", $username, $email, $phone, $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
        exit();
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
    <title>Edit Client – PUROVUE</title>
    <link rel="stylesheet" href="server.css">
</head>
<body>
<div class="container">
    <h2>Edit Client</h2>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username"
               value="<?php echo htmlspecialchars($row['username']); ?>" required>

        <label>Email</label>
        <input type="email" name="email"
               value="<?php echo htmlspecialchars($row['email']); ?>" required>

        <label>Phone</label>
        <input type="text" name="phone"
               value="<?php echo htmlspecialchars($row['phone']); ?>" required>

        <button type="submit">Update Client</button>
    </form>

    <p style="margin-top:14px;">
        <a href="dashboard.php">← Back to Dashboard</a>
    </p>
</div>
</body>
</html>