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
    "DELETE FROM organizations WHERE id=$id"
);

header("Location: organizations.php");

exit();

?>