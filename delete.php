<?php
include "config.php";

$id = $_GET["id"];

$sql = "DELETE FROM clients WHERE id=$id";

if ($conn->query($sql)) {
    header("Location: dashboard.php");
} else {
    echo "Error deleting: " . $conn->error;
}
?>