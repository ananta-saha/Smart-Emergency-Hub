<?php
require_once "../../Config/db.php";

$fnameErr = $emailErr = $phoneErr = $passErr = $cpassErr = $addressErr = "";
$dbErr = "";

$fname = $email = $phone = $address = "";

$isValid = false;

function cleanInput($data)
{
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["fname"])) {
        $fnameErr = "Enter your full name";
    } else {
        $fname = cleanInput($_POST["fname"]);
        if (!preg_match("/^[a-zA-Z-' ]+$/", $fname)) {
            $fnameErr = "Use letters, spaces, hyphens and apostrophes only";
        } 
    }

    if (empty($_POST["email"])) {
        $emailErr = "Enter your email address";
    } else {
        $email = cleanInput($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Enter a valid email address";
        }
    }

    if (empty($_POST["phone"])) {
        $phoneErr = "Enter your phone number";
    } else {
        $phone = cleanInput($_POST["phone"]);
        if (!preg_match("/^01[0-9]{9}$/", $phone)) {
            $phoneErr = "Enter a valid 11 digit phone number";
        }
    }

    if (empty($_POST["pass"])) {
        $passErr = "Enter a password";
    } else {
        $password = $_POST["pass"];
        if (strlen($password) < 8) {
            $passErr = "Password must be at least 8 characters";
        } elseif (!preg_match("/[A-Za-z]/", $password) || !preg_match("/[0-9]/", $password)
        ) {
            $passErr = "Password must contain at least one letter and one number";
        }
    }

    if (empty($_POST["cpass"])) {
        $cpassErr = "Confirm your password";
    } else {
        $confirmPassword = $_POST["cpass"];
        if ($password !== $confirmPassword) {
            $cpassErr = "Passwords do not match";
        }
    }

    if (empty($_POST["address"])) {
        $addressErr = "Enter your full address";
    } else {
        $address = cleanInput($_POST["address"]);
        if (strlen($address) < 8) {
            $addressErr = "Address must be at least 5 characters";
        }
    }

    $isValid = !$fnameErr && !$emailErr && !$phoneErr && !$passErr && !$cpassErr && !$addressErr;

    if ($isValid) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $checkStmt = mysqli_prepare(
            $conn,
            "SELECT email FROM citizens WHERE email = ? OR phone = ?"
        );
        mysqli_stmt_bind_param(
            $checkStmt, "ss", $email, $phone
        );
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $dbErr = "Email or phone number is already registered.";
            $isValid = false;
        } else {
            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO citizens
                (name, email, phone, password, address, status)
                VALUES (?, ?, ?, ?, ?, 'Active')"
            );
            mysqli_stmt_bind_param(
                $stmt, "sssss",
                $fname, $email, $phone, $passwordHash, $address
            );

            if (!mysqli_stmt_execute($stmt)) {
                $dbErr = "Could not save registration: " .
                         mysqli_stmt_error($stmt);
                $isValid = false;
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($checkStmt);
    }
}
?>