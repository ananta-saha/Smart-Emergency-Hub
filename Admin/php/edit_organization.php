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
    "SELECT * FROM organizations WHERE id=$id"
);

$row = mysqli_fetch_assoc($result);

if (isset($_POST["update"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $address = $_POST["address"];
    $type = $_POST["type"];
    $status = $_POST["status"];

    $sql = "UPDATE organizations SET
    name='$name',
    email='$email',
    phone='$phone',
    address='$address',
    type='$type',
    status='$status'
    WHERE id=$id";

    mysqli_query($conn, $sql);

    header("Location: organizations.php");
    exit();

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Edit Organization</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="page organization-page">

<div class="form-box">

<h1>Edit Organization</h1>

<p class="subtitle">
Update organization information
</p>

<form method="POST">

<div class="form-group">

<label>Organization Name</label>

<input
type="text"
name="name"
value="<?php echo $row["name"]; ?>"
required>

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

<label>Phone Number</label>

<input
type="text"
name="phone"
value="<?php echo $row["phone"]; ?>"
required>

</div>

</div>

<div class="form-group">

<label>Address</label>

<input
type="text"
name="address"
value="<?php echo $row["address"]; ?>"
required>

</div>

<div class="form-group">

<label>Organization Type</label>

<select name="type">

<option
<?php if ($row["type"] == "NGO") echo "selected"; ?>>
NGO
</option>

<option
<?php if ($row["type"] == "Charity Organization") echo "selected"; ?>>
Charity Organization
</option>

<option
<?php if ($row["type"] == "Medical Organization") echo "selected"; ?>>
Medical Organization
</option>

<option
<?php if ($row["type"] == "Emergency Support Organization") echo "selected"; ?>>
Emergency Support Organization
</option>

</select>

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
class="main-btn blue-btn">

Update Organization

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