<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();

}

include "db.php";

$result = mysqli_query(
    $conn,
    "SELECT * FROM service_providers ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Service Providers</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="list-page">

<div class="list-container">

<div class="list-header">

<div>

<h1>
Service Provider Management
</h1>

<p>
Manage all registered service providers
</p>

</div>

<button
class="add-btn green-btn"
onclick="window.location.href='add_service_provider.php'">

+ Add Provider

</button>

</div>

<div class="table-box">

<table class="green-table">

<tr>

<th>ID</th>
<th>Name</th>
<th>Service</th>
<th>Email</th>
<th>Phone</th>
<th>Location</th>
<th>Status</th>
<th>Action</th>

</tr>

<?php

while ($row = mysqli_fetch_assoc($result)) {

?>

<tr>

<td>
<?php echo $row["id"]; ?>
</td>

<td>
<?php echo $row["name"]; ?>
</td>

<td>
<?php echo $row["service_type"]; ?>
</td>

<td>
<?php echo $row["email"]; ?>
</td>

<td>
<?php echo $row["phone"]; ?>
</td>

<td>
<?php echo $row["location"]; ?>
</td>

<td>

<span class="status
<?php

if ($row["status"] == "Active") {
    echo " active";
} elseif ($row["status"] == "Pending") {
    echo " pending";
} else {
    echo " rejected";
}

?>">

<?php echo $row["status"]; ?>

</span>

</td>

<td>

<button
class="edit-btn"
onclick="window.location.href='edit_service_provider.php?id=<?php echo $row["id"]; ?>'">

Edit

</button>

<button
class="delete-btn"
onclick="deleteProvider(<?php echo $row["id"]; ?>)">

Delete

</button>

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

</div>

<script>

function deleteProvider(id) {

    var answer = confirm(
        "Are you sure you want to delete this service provider?"
    );

    if (answer) {

        window.location.href =
        "delete_service_provider.php?id=" + id;

    }

}

</script>

</body>

</html>