<?php
include "config.php";
session_start();

// Only admin can delete
if (!isset($_SESSION["user_id"])) {
    header("location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $id   = intval($_GET["id"]); // Safely cast to integer
    $stmt = $conn->prepare("DELETE FROM clients WHERE id=?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php");
    } else {
        echo "Error deleting: " . $conn->error;
    }
} else {
    header("Location: dashboard.php");
}
?>