<?php

session_start();

require_once "../../Config/db.php";


/* ---------- Check Login ---------- */

if (!isset($_SESSION["org_id"])) {

    header("Location: login.php");
    exit();
}

$org_id = $_SESSION["org_id"];


/* ---------- Variables ---------- */

$orgNameErr = $emailErr = $phoneErr = $addressErr = "";
$dbErr = "";
$successMsg = "";

$orgName = $email = $phone = $address = "";

$isValid = false;


/* ---------- Clean Input Function ---------- */

function cleanInput($data)
{
    return htmlspecialchars(
        stripslashes(
            trim($data)
        )
    );
}


/* ---------- Load Organization Profile ---------- */

$stmt = mysqli_prepare(
    $conn,
    "SELECT org_name, email, phone, address, status
     FROM organizations
     WHERE org_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $org_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);


if (mysqli_num_rows($result) == 1) {

    $organization = mysqli_fetch_assoc($result);

    $orgName = $organization["org_name"];
    $email = $organization["email"];
    $phone = $organization["phone"];
    $address = $organization["address"];

} else {

    $dbErr = "Organization profile not found.";
}

mysqli_stmt_close($stmt);


/* ---------- Update Profile ---------- */

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    /* ---------- Organization Name ---------- */

    if (empty($_POST["profileName"])) {

        $orgNameErr = "Enter organization name";

    } else {

        $orgName = cleanInput($_POST["profileName"]);

        if (!preg_match("/^[a-zA-Z0-9&.,' -]+$/", $orgName)) {

            $orgNameErr =
                "Use letters, numbers, spaces, hyphens and common symbols only";
        }
    }


    /* ---------- Email ---------- */

    if (empty($_POST["profileEmail"])) {

        $emailErr = "Enter email address";

    } else {

        $email = cleanInput($_POST["profileEmail"]);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

            $emailErr = "Enter a valid email address";
        }
    }


    /* ---------- Phone ---------- */

    if (empty($_POST["profilePhone"])) {

        $phoneErr = "Enter phone number";

    } else {

        $phone = cleanInput($_POST["profilePhone"]);

        if (!preg_match("/^01[0-9]{9}$/", $phone)) {

            $phoneErr =
                "Enter a valid 11 digit phone number";
        }
    }


    /* ---------- Address ---------- */

    if (empty($_POST["profileAddress"])) {

        $addressErr = "Enter organization address";

    } else {

        $address = cleanInput($_POST["profileAddress"]);

        if (strlen($address) < 8) {

            $addressErr =
                "Address must be at least 8 characters";
        }
    }


    /* ---------- Check Validation ---------- */

    $isValid =
        !$orgNameErr
        && !$emailErr
        && !$phoneErr
        && !$addressErr;


    /* ---------- Update Database ---------- */

    if ($isValid) {


        /* Check duplicate email or phone */

        $checkStmt = mysqli_prepare(
            $conn,
            "SELECT org_id
             FROM organizations
             WHERE (email = ? OR phone = ?)
             AND org_id != ?
             LIMIT 1"
        );

        mysqli_stmt_bind_param(
            $checkStmt,
            "ssi",
            $email,
            $phone,
            $org_id
        );

        mysqli_stmt_execute($checkStmt);

        mysqli_stmt_store_result($checkStmt);


        if (mysqli_stmt_num_rows($checkStmt) > 0) {

            $dbErr =
                "Email or phone number is already registered by another organization.";

            $isValid = false;

        } else {


            /* Update existing organization */

            $updateStmt = mysqli_prepare(
                $conn,
                "UPDATE organizations
                 SET org_name = ?,
                     email = ?,
                     phone = ?,
                     address = ?,
                     status = 'Pending'
                 WHERE org_id = ?"
            );


            mysqli_stmt_bind_param(
                $updateStmt,
                "ssssi",
                $orgName,
                $email,
                $phone,
                $address,
                $org_id
            );


            if (mysqli_stmt_execute($updateStmt)) {

                $successMsg =
                    "Profile updated successfully. "
                    . "Your changes are waiting for Admin approval.";


                /* Update session */

                $_SESSION["org_name"] = $orgName;
                $_SESSION["email"] = $email;


                /* ---------- Send Notification to Admin ---------- */

                $title = "Organization Profile Update";

                $message =
                    $orgName .
                    " has updated their organization profile. "
                    . "Please review and approve the changes.";


                $notificationStmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO notifications
                    (admin_id, title, message, target_type, status)
                    VALUES (NULL, ?, ?, 'Organizations', 'Active')"
                );


                if ($notificationStmt) {

                    mysqli_stmt_bind_param(
                        $notificationStmt,
                        "ss",
                        $title,
                        $message
                    );

                    mysqli_stmt_execute($notificationStmt);

                    mysqli_stmt_close($notificationStmt);
                }

            } else {

                $dbErr =
                    "Could not update profile: "
                    . mysqli_stmt_error($updateStmt);

                $isValid = false;
            }


            mysqli_stmt_close($updateStmt);
        }


        mysqli_stmt_close($checkStmt);
    }
}

?>