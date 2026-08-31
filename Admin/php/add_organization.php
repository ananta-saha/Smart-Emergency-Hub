<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();

}

include "db.php";

if (isset($_POST["add"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $type = $_POST["type"];
    $status = $_POST["status"];

    $sql = "INSERT INTO organizations
    (name,email,phone,address,type,status)
    VALUES
    ('$name','$email','$phone','$address','$type','$status')";

    if (mysqli_query($conn, $sql)) {

        header("Location: organizations.php");
        exit();

    }

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Add Organization</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="page organization-page">

<div class="form-box">

<h1>Add Organization</h1>

<p class="subtitle">
Register a new organization in Smart Emergency Hub
</p>

<form method="POST">

<div class="form-group">

<label>Organization Name</label>

<input
type="text"
name="name"
placeholder="Enter organization name"
required>

</div>

<div class="form-row">

<div class="form-group">

<label>Email Address</label>

<input
type="email"
name="email"
placeholder="organization@gmail.com"
required>

</div>

<div class="form-group">

<label>Phone Number</label>

<input
type="text"
name="phone"
placeholder="017XXXXXXXX"
required>

</div>

</div>

<div class="form-group">

<label>Address</label>

<input
type="text"
name="address"
placeholder="Enter organization address"
required>

</div>

<div class="form-group">

<label>Organization Type</label>

<select name="type" required>

<option value="">
Select organization type
</option>

<option>NGO</option>

<option>Charity Organization</option>

<option>Medical Organization</option>

<option>Emergency Support Organization</option>

</select>

</div>

<div class="form-group">

<label>Status</label>

<select name="status">

<option>Active</option>

<option>Pending</option>

</select>

</div>

<div class="action-buttons">

<button
type="submit"
name="add"
class="main-btn blue-btn">

Add Organization

</button>

<button
type="button"
class="back-btn"
onclick="window.location.href='organizations.php'">

Cancel

</button>

</div>

</form>

</div>

</div>

</body>

</html>