<?php
include "config.php";
session_start();

if (isset($_SESSION["client_id"])) {
    header("location: client_dashboard.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, username, password FROM clients WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $client = $result->fetch_assoc();

        if (password_verify($password, $client["password"])) {
            $_SESSION["client_id"]       = $client["id"];
            $_SESSION["client_username"] = $client["username"];
            header("location: client_dashboard.php");
            exit();
        } else {
            $error = "Wrong password.";
        }
    } else {
        $error = "No account found with that email. <a href='client_register.php'>Register here</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Client Login – PUROVUE</title>
    <link rel="stylesheet" href="server.css">
</head>
<body>
<div class="container">
    <h2>Client Login – PUROVUE Essentials</h2>

    <?php if ($error): ?>
        <p style="color:#f07070;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" placeholder="your@email.com" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Your password" required>

        <button type="submit">Login</button>
    </form>

    <p style="color:#c8a050; margin-top:14px; font-size:14px;">
        Don't have an account? <a href="client_register.php">Register here</a>
    </p>
    <p style="color:#c8a050; font-size:14px;">
        Are you an admin? <a href="login.php">Admin Login</a>
    </p>
</div>
</body>
</html>