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
    "DELETE FROM service_providers WHERE id=$id"
);

header("Location: service_providers.php");

exit();

?>