<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();

}

include "db.php";

$result = mysqli_query(
    $conn,
    "SELECT * FROM organizations ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Organizations</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="list-page">

<div class="list-container">

<div class="list-header">

<div>

<h1>
Organization Management
</h1>

<p>
View and manage all organizations
</p>

</div>

<button
class="add-btn blue-btn"
onclick="window.location.href='add_organization.php'">

+ Add Organization

</button>

</div>


<div class="table-box">

<table class="blue-table">

<tr>

<th>ID</th>
<th>Organization</th>
<th>Email</th>
<th>Phone</th>
<th>Type</th>
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
<?php echo $row["email"]; ?>
</td>

<td>
<?php echo $row["phone"]; ?>
</td>

<td>
<?php echo $row["type"]; ?>
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
onclick="window.location.href='edit_organization.php?id=<?php echo $row["id"]; ?>'">

Edit

</button>

<button
class="delete-btn"
onclick="deleteOrganization(<?php echo $row["id"]; ?>)">

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

function deleteOrganization(id) {

    var answer = confirm(
        "Are you sure you want to delete this organization?"
    );

    if (answer) {

        window.location.href =
        "delete_organization.php?id=" + id;

    }

}

</script>

</body>

</html>