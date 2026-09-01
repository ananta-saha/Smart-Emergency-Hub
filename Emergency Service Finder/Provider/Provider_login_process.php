<?php

session_start();

require_once "../../Config/db.php";


// Default values
$email = "";
$password = "";

$emailErr = "";
$passwordErr = "";
$loginErr = "";



function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {


    // =========================
    // EMAIL VALIDATION
    // =========================

    if (empty($_POST["email"])) {

        $emailErr = "Enter your email address";

    } 
    else {

        $email = cleanInput($_POST["email"]);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $emailErr = "Enter a valid email address";

        }

    }



    // =========================
    // PASSWORD VALIDATION
    // =========================

    if (empty($_POST["password"])) {

        $passwordErr = "Enter your password";

    }
    else {

        $password = $_POST["password"];

    }




    // =========================
    // LOGIN CHECK
    // =========================

    if ($emailErr == "" && $passwordErr == "") {


        $stmt = mysqli_prepare(
            $conn,
            "SELECT 
                provider_id,
                provider_name,
                email,
                password,
                service_type,
                status
             FROM service_providers
             WHERE email = ?
             LIMIT 1"
        );



        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $email
        );



        mysqli_stmt_execute($stmt);



        $result = mysqli_stmt_get_result($stmt);



        if (mysqli_num_rows($result) == 1) {


            $provider = mysqli_fetch_assoc($result);



            // Check verification status

            if ($provider["status"] != "Verified") {


                $loginErr = 
                "Your provider account is not verified yet.";


            }


            // Check password

            elseif (
                password_verify(
                    $password,
                    $provider["password"]
                )
            ) {


                session_regenerate_id(true);



                $_SESSION["provider_id"] =
                    $provider["provider_id"];


                $_SESSION["provider_name"] =
                    $provider["provider_name"];


                $_SESSION["provider_email"] =
                    $provider["email"];


                $_SESSION["service_type"] =
                    $provider["service_type"];



                header(
                    "Location: Provider_Dashboard.php"
                );


                exit();


            }
            else {


                $loginErr =
                "Incorrect email or password.";


            }



        }
        else {


            $loginErr =
            "Incorrect email or password.";


        }



        mysqli_stmt_close($stmt);


    }


}



?>