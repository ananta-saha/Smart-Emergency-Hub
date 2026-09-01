<?php

session_start();

include "db.php";

$message = "";

if (isset($_POST["login"])) {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM admins WHERE email='$email'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $admin = mysqli_fetch_assoc($result);

        if (password_verify($password, $admin["password"])) {

            $_SESSION["admin_id"] = $admin["id"];
            $_SESSION["admin_name"] = $admin["name"];

            header("Location: admin_dashboard.php");
            exit();

        } else {

            $message = "Wrong password";

        }

    } else {

        $message = "Admin account not found";

    }
}

?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Login</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="page">

<div class="login-box">

<div class="logo">
🚨
</div>

<h1>Admin Login</h1>

<p class="subtitle">
Smart Emergency Hub
</p>

<form method="POST">

<div class="form-group">

<label>Email Address</label>

<input
type="email"
name="email"
placeholder="admin@example.com"
required>

</div>

<div class="form-group">

<label>Password</label>

<input
type="password"
name="password"
placeholder="Enter password"
required>

</div>

<button
type="submit"
name="login"
class="main-btn blue-btn">

Login

</button>

</form>

<p class="link-text">

Don't have an account?

<a href="admin_registration.php">
Register
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