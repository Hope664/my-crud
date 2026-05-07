<?php
include "config.php";
session_start();


if (isset($_SESSION["client_id"])) {
    header("location: client_dashboard.php");
    exit();
}

$error   = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $email    = trim($_POST["email"]);
    $phone    = trim($_POST["phone"]);
    $password = $_POST["password"];
    $confirm  = $_POST["confirm_password"];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
    
        $chk = $conn->prepare("SELECT id FROM clients WHERE username = ?");
        $chk->bind_param("s", $username);
        $chk->execute();
        $chk->get_result()->num_rows > 0 ? $error = "Username already taken." : null;

        // Check email taken
        if (!$error) {
            $chkE = $conn->prepare("SELECT id FROM clients WHERE email = ?");
            $chkE->bind_param("s", $email);
            $chkE->execute();
            $chkE->get_result()->num_rows > 0 ? $error = "Email already registered." : null;
        }

        if (!$error) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare(
                "INSERT INTO clients (username, email, phone, password, role) VALUES (?, ?, ?, ?, 'client')"
            );
            $stmt->bind_param("ssss", $username, $email, $phone, $hashed);
            $stmt->execute()
                ? $success = "Account created! You can now log in."
                : $error   = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="server.css">
</head>
<body>
<div class="container">
    <h2>Register</h2>

    <?php if ($error): ?>
        <p style="color:#f07070;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:#7ec87e;"><?php echo $success; ?> <a href="client_login.php">Login here</a></p>
    <?php endif; ?>

    <form method="POST">
        <label>Username</label>
        <input type="text" name="username" placeholder="Your name" required>

        <label>Email</label>
        <input type="email" name="email" placeholder="your@email.com" required>

        <label>Phone</label>
        <input type="text" name="phone" placeholder="Phone number" required>

        <label>Password</label>
        <input type="password" name="password" placeholder="Min 6 characters" required>

        <label>Confirm Password</label>
        <input type="password" name="confirm_password" placeholder="Repeat password" required>

        <button type="submit">Create Account</button>
    </form>

    <p style="color:#c8a050; margin-top:14px; font-size:14px;">
        Already have an account? <a href="client_login.php">Login here</a>
    </p>
</div>
</body>
</html>