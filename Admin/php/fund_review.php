<?php

session_start();

if (!isset($_SESSION["admin_id"])) {

    header("Location: admin_login.php");
    exit();

}

include "db.php";

$result = mysqli_query(
    $conn,
    "SELECT * FROM funds ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

<title>Fund Review</title>

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="list-page">

<div class="list-container">

<div class="list-header">

<div>

<h1>
Fund Review
</h1>

<p>
Review organization fund requests
</p>

</div>

</div>

<div class="table-box">

<table class="orange-table">

<tr>

<th>ID</th>
<th>Organization</th>
<th>Amount</th>
<th>Purpose</th>
<th>Date</th>
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
<?php echo $row["organization"]; ?>
</td>

<td>
৳<?php echo $row["amount"]; ?>
</td>

<td>
<?php echo $row["purpose"]; ?>
</td>

<td>
<?php echo $row["request_date"]; ?>
</td>

<td>

<span class="status
<?php

if ($row["status"] == "Approved") {
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

<?php

if ($row["status"] == "Pending") {

?>

<button
class="approve-btn"
onclick="window.location.href='approve_fund.php?id=<?php echo $row["id"]; ?>'">

Approve

</button>

<button
class="reject-btn"
onclick="window.location.href='reject_fund.php?id=<?php echo $row["id"]; ?>'">

Reject

</button>

<?php

}

?>

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

</div>

</body>

</html>