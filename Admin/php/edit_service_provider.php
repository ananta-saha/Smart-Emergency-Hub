<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();

}

include "db.php";

$id = $_GET["id"];

$result = mysqli_query(
    $conn,
    "SELECT * FROM service_providers WHERE id=$id"
);

$row = mysqli_fetch_assoc($result);

if (isset($_POST["update"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $service_type = $_POST["service_type"];
    $location = $_POST["location"];
    $status = $_POST["status"];

    $sql = "UPDATE service_providers SET
    name='$name',
    email='$email',
    phone='$phone',
    service_type='$service_type',
    location='$location',
    status='$status'
    WHERE id=$id";

    mysqli_query($conn, $sql);

    header("Location: service_providers.php");

    exit();

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Service Provider</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="page provider-page">

<div class="form-box">

<h1>Edit Service Provider</h1>

<p class="subtitle">
Update service provider information
</p>

<form method="POST">

<div class="form-row">

<div class="form-group">

<label>Full Name</label>

<input
type="text"
name="name"
value="<?php echo $row["name"]; ?>"
required>

</div>

<div class="form-group">

<label>Phone Number</label>

<input
type="text"
name="phone"
value="<?php echo $row["phone"]; ?>"
required>

</div>

</div>

<div class="form-row">

<div class="form-group">

<label>Email Address</label>

<input
type="email"
name="email"
value="<?php echo $row["email"]; ?>"
required>

</div>

<div class="form-group">

<label>Service Type</label>

<select name="service_type">

<option
<?php if ($row["service_type"] == "Ambulance") echo "selected"; ?>>
Ambulance
</option>

<option
<?php if ($row["service_type"] == "Hospital") echo "selected"; ?>>
Hospital
</option>

<option
<?php if ($row["service_type"] == "Fire Service") echo "selected"; ?>>
Fire Service
</option>

<option
<?php if ($row["service_type"] == "Police") echo "selected"; ?>>
Police
</option>

<option
<?php if ($row["service_type"] == "Blood Donor") echo "selected"; ?>>
Blood Donor
</option>

</select>

</div>

</div>

<div class="form-group">

<label>Location</label>

<input
type="text"
name="location"
value="<?php echo $row["location"]; ?>"
required>

</div>

<div class="form-group">

<label>Status</label>

<select name="status">

<option
<?php if ($row["status"] == "Active") echo "selected"; ?>>
Active
</option>

<option
<?php if ($row["status"] == "Pending") echo "selected"; ?>>
Pending
</option>

<option
<?php if ($row["status"] == "Rejected") echo "selected"; ?>>
Rejected
</option>

</select>

</div>

<div class="action-buttons">

<button
type="submit"
name="update"
class="main-btn green-btn">

Update Provider

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