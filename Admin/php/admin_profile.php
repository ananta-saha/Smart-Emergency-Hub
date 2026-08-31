<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();

}

include "db.php";

$id = $_SESSION["admin_id"];

$result = mysqli_query(
    $conn,
    "SELECT * FROM admins WHERE id=$id"
);

$admin = mysqli_fetch_assoc($result);

if (isset($_POST["update"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];

    mysqli_query(
        $conn,
        "UPDATE admins SET
        name='$name',
        email='$email',
        phone='$phone'
        WHERE id=$id"
    );

    $_SESSION["admin_name"] = $name;

    header("Location: admin_profile.php");

    exit();

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Profile</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="page">

<div class="form-box">

<h1>
Admin Profile
</h1>

<p class="subtitle">
View and update administrator information
</p>

<form method="POST">

<div class="form-group">

<label>Full Name</label>

<input
type="text"
name="name"
value="<?php echo $admin["name"]; ?>"
required>

</div>

<div class="form-row">

<div class="form-group">

<label>Email</label>

<input
type="email"
name="email"
value="<?php echo $admin["email"]; ?>"
required>

</div>

<div class="form-group">

<label>Phone Number</label>

<input
type="text"
name="phone"
value="<?php echo $admin["phone"]; ?>"
required>

</div>

</div>

<div class="form-group">

<label>Role</label>

<input
type="text"
value="Administrator"
readonly>

</div>

<div class="action-buttons">

<button
type="submit"
name="update"
class="main-btn blue-btn">

Update Profile

</button>

<button
type="button"
class="back-btn"
onclick="window.location.href='admin_dashboard.php'">

Back

</button>

</div>

</form>

</div>

</div>

</body>

</html>