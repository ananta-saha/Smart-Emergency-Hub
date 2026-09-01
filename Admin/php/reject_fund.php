<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();

}

include "db.php";

$id = $_GET["id"];

mysqli_query(
    $conn,
    "UPDATE funds SET status='Rejected' WHERE id=$id"
);

header("Location: fund_review.php");

exit();

?>