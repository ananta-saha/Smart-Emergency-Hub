<?php

require_once "../../Config/db.php";

$orgNameErr = $emailErr = $passwordErr = $phoneErr = $addressErr = "";
$dbErr = "";

$successMsg = "";
$orgName = $regEmail = $phone = $address = $password = "";

$isValid = false;

function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data))
    );
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["orgName"])) {
        $orgNameErr = "Enter organization name";
    } else {
        $orgName = cleanInput($_POST["orgName"]);
        if (!preg_match("/^[a-zA-Z0-9&.,' -]+$/", $orgName)) {
            $orgNameErr ="Use letters, numbers, spaces, hyphens and common symbols only";
        }
    }

    if (empty($_POST["regEmail"])) {
        $emailErr = "Enter email address";
    } else {
        $regEmail = cleanInput($_POST["regEmail"]);
        if (!filter_var($regEmail, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Enter a valid email address";
        }
    }

    if (empty($_POST["regPassword"])) {
        $passwordErr = "Enter a password";
    } else {
        $password = $_POST["regPassword"];
        if (strlen($password) < 8) {
            $passwordErr =
                "Password must be at least 8 characters";
        } elseif (!preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)) {
            $passwordErr =
                "Password must contain at least one letter and one number";
        }
    }

    if (empty($_POST["phone"])) {
        $phoneErr = "Enter phone number";
    } else {
        $phone = cleanInput($_POST["phone"]);
        if (!preg_match("/^01[0-9]{9}$/", $phone)) {
            $phoneErr ="Enter a valid 11 digit phone number";
        }
    }

    if (empty($_POST["address"])) {
        $addressErr = "Enter organization address";
    } else {
        $address = cleanInput($_POST["address"]);
        if (strlen($address) < 8) {
            $addressErr ="Address must be at least 8 characters";
        }
    }

    $isValid = !$orgNameErr && !$emailErr && !$passwordErr && !$phoneErr && !$addressErr;

    if ($isValid) {
        $passwordHash = password_hash($password,PASSWORD_DEFAULT);
        $checkStmt = mysqli_prepare(
            $conn,
            "SELECT email FROM organizations
             WHERE email = ? OR phone = ?"
        );

        mysqli_stmt_bind_param(
            $checkStmt, "ss",
            $regEmail,
            $phone
        );
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);

        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $dbErr ="Email or phone number is already registered.";
            $isValid = false;
        } else {

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO organizations
                (org_name, email, password, phone, address, status)
                VALUES (?, ?, ?, ?, ?, 'Pending')"
            );
            mysqli_stmt_bind_param(
                $stmt, "sssss",
                $orgName,
                $regEmail,
                $passwordHash,
                $phone,
                $address
            );

            if (mysqli_stmt_execute($stmt)) {
                $organizationId =mysqli_insert_id($conn);
                $message = "New organization registration request from " .
                    $orgName . ". Please review and approve.";
                $title = "New Organization Registration";
                $notificationStmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO notifications
                    (admin_id, title, message, target_type, status)
                    VALUES (NULL, ?, ?, 'Organization', 'Active')"
                );

                if ($notificationStmt) {
                   mysqli_stmt_bind_param(
                    $notificationStmt, "ss", 
                    $title,
                    $message
                    );
                    mysqli_stmt_execute(
                        $notificationStmt
                    );
                    mysqli_stmt_close(
                        $notificationStmt
                    );
                }

                $successMsg = 
                    "Registration submitted successfully. " .
                    "Your organization is waiting for Admin approval. " .
                    "You can login after Admin approves your registration.";

                $orgName = "";
                $regEmail = "";
                $phone = "";
                $address = "";
            } else {
                $dbErr = "Could not save registration: " .mysqli_stmt_error($stmt);
                $isValid = false;
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($checkStmt);
    }
}

?>