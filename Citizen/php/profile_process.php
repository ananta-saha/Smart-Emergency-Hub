<?php
session_start();

require_once "../../Config/db.php";

$fnameErr = $emailErr = $phoneErr = $addressErr = "";

$dbErr = "";
$successMsg = "";

$fname = $email = $phone = $address = "";

if (!isset($_SESSION["citizen_id"])) {
    header("Location: login.php");
    exit();
}

$citizenId = $_SESSION["citizen_id"];
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
            $emailErr ="Enter a valid email address";
        }
    }

    if (empty($_POST["phone"])) {
        $phoneErr ="Enter your phone number";
    } else {
        $phone = cleanInput($_POST["phone"]);
        if (!preg_match("/^01[0-9]{9}$/", $phone)) {
            $phoneErr = "Enter a valid 11 digit phone number";
        }
    }

    if (empty($_POST["address"])) {
        $addressErr ="Enter your full address";
    } else {
        $address = cleanInput($_POST["address"]);
        if (strlen($address) < 8) {
            $addressErr ="Address must be at least 8 characters";
        }
    }

    $isValid = !$fnameErr && !$emailErr && !$phoneErr && !$addressErr;
    if ($isValid) {
        $checkStmt = mysqli_prepare(
            $conn,
            "SELECT citizen_id
             FROM citizens
             WHERE (email = ? OR phone = ?)
             AND citizen_id != ?"
        );

        mysqli_stmt_bind_param(
            $checkStmt,
            "ssi",
            $email,
            $phone,
            $citizenId
        );
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_store_result($checkStmt);
        if (mysqli_stmt_num_rows($checkStmt) > 0) {
            $dbErr ="Email or phone number is already used by another citizen.";
        } else {

            $stmt = mysqli_prepare(
                $conn,
                "UPDATE citizens
                 SET name = ?,
                     email = ?,
                     phone = ?,
                     address = ?,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE citizen_id = ?"
            );
            mysqli_stmt_bind_param(
                $stmt, "ssssi",
                $fname,
                $email,
                $phone,
                $address,
                $citizenId
            );

            if (mysqli_stmt_execute($stmt)) {
                $successMsg = "Profile updated successfully.";
                $_SESSION["citizen_name"] = $fname;
                $_SESSION["citizen_email"] = $email;
            } else {
                $dbErr = "Could not update profile: ". mysqli_stmt_error($stmt);
            }
            mysqli_stmt_close($stmt);
        }
        mysqli_stmt_close($checkStmt);
    }
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT name, email, phone, address
     FROM citizens
     WHERE citizen_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $citizenId
);

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1) {
    $citizen =mysqli_fetch_assoc($result);
    $fname = $citizen["name"];
    $email = $citizen["email"];
    $phone = $citizen["phone"];
    $address = $citizen["address"];
} else {
    $dbErr = "Citizen profile not found.";
}
mysqli_stmt_close($stmt);
?>