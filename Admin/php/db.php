<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "smart_emergency_hub"
);

if (!$conn) {
    die("Database connection failed");
}

?>