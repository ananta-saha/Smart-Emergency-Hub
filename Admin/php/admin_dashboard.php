<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();

}

?>

<!DOCTYPE html>
<html>

<head>

<title>Admin Dashboard</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="dashboard">

<div class="dashboard-container">

<div class="dashboard-header">

<h1>
Smart Emergency Hub
</h1>

<p>
Welcome, <?php echo $_SESSION["admin_name"]; ?>
</p>

</div>

<div class="dashboard-grid">


<div class="dashboard-card card-blue">

<div class="card-icon">
🏢
</div>

<h2>
Organizations
</h2>

<p>
Manage registered organizations
</p>

<button
class="main-btn blue-btn"
onclick="window.location.href='organizations.php'">

Manage

</button>

</div>


<div class="dashboard-card card-green">

<div class="card-icon">
🚑
</div>

<h2>
Service Providers
</h2>

<p>
Manage emergency service providers
</p>

<button
class="main-btn green-btn"
onclick="window.location.href='service_providers.php'">

Manage

</button>

</div>


<div class="dashboard-card card-purple">

<div class="card-icon">
🔔
</div>

<h2>
Notifications
</h2>

<p>
Send and view notifications
</p>

<button
class="main-btn purple-btn"
onclick="window.location.href='notifications.php'">

Manage

</button>

</div>


<div class="dashboard-card card-orange">

<div class="card-icon">
💰
</div>

<h2>
Fund Review
</h2>

<p>
Review organization fund requests
</p>

<button
class="main-btn orange-btn"
onclick="window.location.href='fund_review.php'">

Review

</button>

</div>


<div class="dashboard-card card-yellow">

<div class="card-icon">
👤
</div>

<h2>
Admin Profile
</h2>

<p>
View and update admin information
</p>

<button
class="main-btn orange-btn"
onclick="window.location.href='admin_profile.php'">

Profile

</button>

</div>


<div class="dashboard-card card-red">

<div class="card-icon">
🚪
</div>

<h2>
Logout
</h2>

<p>
Logout from admin panel
</p>

<button
class="main-btn red-btn"
onclick="window.location.href='admin_logout.php'">

Logout

</button>

</div>


</div>

</div>

</div>

</body>

</html>