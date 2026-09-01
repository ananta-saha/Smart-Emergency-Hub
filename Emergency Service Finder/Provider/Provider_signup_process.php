<?php

require_once "../../Config/db.php";


if($_SERVER["REQUEST_METHOD"]=="POST"){


$name=$_POST["provider_name"];

$email=$_POST["email"];

$password=password_hash(
    $_POST["password"],
    PASSWORD_DEFAULT
);

$phone=$_POST["phone"];

$type=$_POST["service_type"];



$sql="
INSERT INTO service_providers
(
provider_name,
email,
password,
phone,
service_type,
status
)

VALUES
(
?,
?,
?,
?,
?,
'Pending'
)

";



$stmt=mysqli_prepare($conn,$sql);



mysqli_stmt_bind_param(
$stmt,
"sssss",
$name,
$email,
$password,
$phone,
$type
);



if(mysqli_stmt_execute($stmt)){


echo "

<script>

alert('Registration successful. Wait for admin approval.');

window.location='Provider_login.php';

</script>

";


}

else{


echo "Registration failed";

}



}

?>