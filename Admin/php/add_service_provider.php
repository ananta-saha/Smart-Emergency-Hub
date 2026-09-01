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
    $service_type = $_POST["service_type"];
    $location = $_POST["location"];
    $status = $_POST["status"];

    $sql = "INSERT INTO service_providers
    (name,email,phone,service_type,location,status)
    VALUES
    ('$name','$email','$phone','$service_type','$location','$status')";

    if (mysqli_query($conn, $sql)) {

        header("Location: service_providers.php");
        exit();

    }

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Add Service Provider</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="page provider-page">

<div class="form-box">

<h1>Add Service Provider</h1>

<p class="subtitle">
Register a new emergency service provider
</p>

<form method="POST">

<div class="form-row">

<div class="form-group">

<label>Full Name</label>

<input
type="text"
name="name"
placeholder="Enter full name"
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

<div class="form-row">

<div class="form-group">

<label>Email Address</label>

<input
type="email"
name="email"
placeholder="provider@gmail.com"
required>

</div>

<div class="form-group">

<label>Service Type</label>

<select name="service_type" required>

<option value="">
Select service
</option>

<option>Ambulance</option>

<option>Hospital</option>

<option>Fire Service</option>

<option>Police</option>

<option>Blood Donor</option>

</select>

</div>

</div>

<div class="form-group">

<label>Location</label>

<input
type="text"
name="location"
placeholder="Enter service provider location"
required>

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
class="main-btn green-btn">

Add Provider

</button>

<button
type="button"
class="back-btn"
onclick="window.location.href='service_providers.php'">

Cancel

</button>

</div>

</form>

</div>

</div>

</body>

</html>