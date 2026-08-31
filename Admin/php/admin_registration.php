<?php

include "db.php";

$message = "";

if (isset($_POST["register"])) {

    $name = $_POST["name"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];

    if ($password != $confirm_password) {

        $message = "Password does not match";

    } else {

        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO admins
                (name,email,phone,password)
                VALUES
                ('$name','$email','$phone','$password')";

        if (mysqli_query($conn, $sql)) {

            header("Location: admin_login.php");
            exit();

        } else {

            $message = "Registration failed";

        }
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Registration</title>

    <link rel="stylesheet" href="style.css">

</head>

<body>

<div class="page">

<div class="form-box">

<h1>Registration Form</h1>

<p class="subtitle">
Fill out the form carefully for registration
</p>

<form method="POST">

<div class="form-group">

<label>Admin Name</label>

<input
type="text"
name="name"
placeholder="Enter full name"
required>

</div>

<div class="form-row">

<div class="form-group">

<label>Email Address</label>

<input
type="email"
name="email"
placeholder="admin@example.com"
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

<label>Password</label>

<input
type="password"
name="password"
placeholder="Enter password"
required>

</div>

<div class="form-group">

<label>Confirm Password</label>

<input
type="password"
name="confirm_password"
placeholder="Confirm password"
required>

</div>

</div>

<button
type="submit"
name="register"
class="main-btn green-btn">

Register

</button>

</form>

<p class="link-text">

Already have an account?

<a href="admin_login.php">
Login
</a>

</p>

<?php

if ($message != "") {
    echo "<p class='link-text'>$message</p>";
}

?>

</div>

</div>

</body>

</html>