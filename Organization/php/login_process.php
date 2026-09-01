<?php
session_start();
require_once "../../Config/db.php";

$emailErr = $passwordErr = $dbErr = $loginErr = "";
$email = $password = "";
$isValid = false;

function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Email / Phone validation
    if (empty($_POST["loginEmail"])) {

        $emailErr = "Enter your email or phone";

    } else {

        $email = cleanInput($_POST["loginEmail"]);

        if (
            !filter_var($email, FILTER_VALIDATE_EMAIL) &&
            !preg_match("/^01[0-9]{9}$/", $email)
        ) {
            $emailErr = "Enter a valid email or 11 digit phone number";
        }
    }


    // Password validation
    if (empty($_POST["loginPassword"])) {

        $passwordErr = "Enter your password";

    } else {

        $password = $_POST["loginPassword"];

        if (strlen($password) < 8) {

            $passwordErr = "Password must be at least 8 characters";

        } elseif (
            !preg_match("/[A-Za-z]/", $password) ||
            !preg_match("/[0-9]/", $password)
        ) {

            $passwordErr =
                "Password must contain at least one letter and one number";
        }
    }


    $isValid = !$emailErr && !$passwordErr;


    // Login
    if ($isValid) {

        $stmt = mysqli_prepare(
            $conn,
            "SELECT org_id, org_name, email, password, phone, address, status
             FROM organizations
             WHERE email = ? OR phone = ?
             LIMIT 1"
        );


        if (!$stmt) {

            $dbErr = "Could not process login: " . mysqli_error($conn);

        } else {

            mysqli_stmt_bind_param(
                $stmt,
                "ss",
                $email,
                $email
            );


            if (mysqli_stmt_execute($stmt)) {

                $result = mysqli_stmt_get_result($stmt);


                if (mysqli_num_rows($result) == 0) {

                    $loginErr =
                        "You are not registered. Please register first.";

                } else {

                    $organization = mysqli_fetch_assoc($result);


                    // Only Pending organization needs Admin approval
                    if ($organization["status"] == "Pending") {

                        $loginErr =
                            "Your organization registration is waiting for Admin approval.";

                    }

                    // Rejected organization cannot login
                    elseif ($organization["status"] == "Rejected") {

                        $loginErr =
                            "Your organization registration was rejected by Admin.";

                    }

                    // Inactive organization cannot login
                    elseif ($organization["status"] == "Inactive") {

                        $loginErr =
                            "Your organization account is inactive.";

                    }

                    // Approved organization can login
                    elseif (
                        $organization["status"] == "Approved" &&
                        password_verify(
                            $password,
                            $organization["password"]
                        )
                    ) {

                        $_SESSION["organization"] =
                            $organization["org_id"];

                        header("Location: dashboard.php");
                        exit();

                    } else {

                        $loginErr = "Incorrect password.";
                    }
                }

            } else {

                $dbErr =
                    "Could not process login: " .
                    mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>