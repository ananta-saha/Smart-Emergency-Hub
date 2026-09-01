<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();

}

include "db.php";

$result = mysqli_query(
    $conn,
    "SELECT * FROM notifications ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Notifications</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="list-page">

<div class="list-container">

<div class="list-header">

<div>

<h1>
Notification Management
</h1>

<p>
Send and view all system notifications
</p>

</div>

<button
class="add-btn purple-btn"
onclick="window.location.href='send_notification.php'">

+ Send Notification

</button>

</div>


<?php

while ($row = mysqli_fetch_assoc($result)) {

?>

<div class="notification-item">

<h3>
<?php echo $row["subject"]; ?>
</h3>

<p>
<?php echo $row["message"]; ?>
</p>

<small>

To:
<?php echo $row["receiver"]; ?>

|

<?php echo $row["created_at"]; ?>

</small>

</div>

<?php

}

?>

</div>

</div>

</body>

</html>