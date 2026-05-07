<?php
include "config.php";
session_start();
if (!isset($_SESSION["client_id"])) {
    header("location: client_login.php");
    exit();
}

$client_id = $_SESSION["client_id"];
$success   = "";
$error     = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email    = trim($_POST["email"]);
    $phone    = trim($_POST["phone"]);
    $new_pass = $_POST["new_password"];
    $confirm  = $_POST["confirm_password"];

    if ($new_pass && $new_pass !== $confirm) {
        $error = "Passwords do not match.";
    } elseif ($new_pass && strlen($new_pass) < 6) {
        $error = "Password must be at least 6 characters.";
    } else {
        if ($new_pass) {
            $hashed = password_hash($new_pass, PASSWORD_DEFAULT);
            $stmt   = $conn->prepare(
                "UPDATE clients SET email=?, phone=?, password=? WHERE id=?"
            );
            $stmt->bind_param("sssi", $email, $phone, $hashed, $client_id);
        } else {
            $stmt = $conn->prepare("UPDATE clients SET email=?, phone=? WHERE id=?");
            $stmt->bind_param("ssi", $email, $phone, $client_id);
        }

        $stmt->execute()
            ? $success = "Profile updated successfully!"
            : $error   = "Update failed. Please try again.";
    }
}

$stmt = $conn->prepare("SELECT username, email, phone FROM clients WHERE id=?");
$stmt->bind_param("i", $client_id);
$stmt->execute();
$client = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Dashboard </title>
    <link rel="stylesheet" href="server.css">
</head>
<body>
<div class="container">
    <a href="client_logout.php">Logout</a>

    <h2>Welcome, <?php echo htmlspecialchars($client["username"]); ?>!</h2>

    <?php if ($error): ?>
        <p style="color:#f07070;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if ($success): ?>
        <p style="color:#7ec87e;"><?php echo $success; ?></p>
    <?php endif; ?>

    <h3>My Profile</h3>
    <form method="POST">
        <label>Username</label>
        
        <input type="text"
               value="<?php echo htmlspecialchars($client["username"]); ?>"
               disabled
               style="opacity:0.5; cursor:not-allowed;">

        <label>Email</label>
        <input type="email" name="email"
               value="<?php echo htmlspecialchars($client["email"]); ?>"
               required>

        <label>Phone</label>
        <input type="text" name="phone"
               value="<?php echo htmlspecialchars($client["phone"]); ?>"
               required>

        <hr style="border-color:rgba(200,160,60,0.15); margin:6px 0;">
        <h3 style="margin-top:10px;">Change Password</h3>
        <p style="color:#a08060; font-size:13px; margin-bottom:8px;">
            Leave blank to keep your current password.
        </p>

        <label>New Password</label>
        <input type="password" name="new_password" placeholder="Min 6 characters">

        <label>Confirm New Password</label>
        <input type="password" name="confirm_password" placeholder="Repeat new password">

        <button type="submit">Save Changes</button>
    </form>
</div>
</body>
</html>