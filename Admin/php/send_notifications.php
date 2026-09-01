<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();

}

include "db.php";

if (isset($_POST["send"])) {

    $receiver = $_POST["receiver"];
    $subject = $_POST["subject"];
    $message = $_POST["message"];

    $sql = "INSERT INTO notifications
    (receiver,subject,message)
    VALUES
    ('$receiver','$subject','$message')";

    mysqli_query($conn, $sql);

    header("Location: notifications.php");

    exit();

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Send Notification</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="page">

<div class="form-box">

<h1>Send Notification</h1>

<p class="subtitle">
Send notification to system users
</p>

<form method="POST">

<div class="form-group">

<label>Receiver</label>

<select name="receiver">

<option>All</option>

<option>Organization</option>

<option>Service Provider</option>

</select>

</div>

<div class="form-group">

<label>Subject</label>

<input
type="text"
name="subject"
placeholder="Enter notification subject"
required>

</div>

<div class="form-group">

<label>Message</label>

<textarea
name="message"
placeholder="Write notification message"
required></textarea>

</div>

<div class="action-buttons">

<button
type="submit"
name="send"
class="main-btn purple-btn">

Send Notification

</button>

<button
type="button"
class="back-btn"
onclick="window.location.href='notifications.php'">

Cancel

</button>

</div>

</form>

</div>

</div>

</body>

</html>