<?php

session_start();

if (!isset($_SESSION["provider_id"])) {
    header("Location: Provider_login.php");
    exit();
}

require_once "../../Config/db.php";

$providerId =
    $_SESSION["provider_id"];

$message = "";
$errorMessage = "";


/* =========================
   LOAD PROVIDER
========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        provider_name,
        email,
        phone,
        service_type,
        address,
        status
     FROM service_providers
     WHERE provider_id = ?"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $providerId
);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result(
    $stmt,
    $providerName,
    $providerEmail,
    $providerPhone,
    $providerServiceType,
    $providerAddress,
    $providerStatus
);

mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);


/* =========================
   UPDATE PROFILE
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $providerName =
        trim($_POST["providerName"] ?? "");

    $providerEmail =
        trim($_POST["providerEmail"] ?? "");

    $providerPhone =
        trim($_POST["providerPhone"] ?? "");

    $providerServiceType =
        trim($_POST["providerServiceType"] ?? "");

    $providerAddress =
        trim($_POST["providerAddress"] ?? "");


    if (
        $providerName == "" ||
        $providerEmail == "" ||
        $providerPhone == "" ||
        $providerServiceType == "" ||
        $providerAddress == ""
    ) {

        $errorMessage =
            "Please fill in all profile information.";

    } elseif (
        !filter_var(
            $providerEmail,
            FILTER_VALIDATE_EMAIL
        )
    ) {

        $errorMessage =
            "Please enter a valid email address.";

    } elseif (
        !preg_match(
            "/^01[3-9][0-9]{8}$/",
            $providerPhone
        )
    ) {

        $errorMessage =
            "Enter a valid 11-digit phone number.";

    } else {


        /* Check duplicate email */

        $stmt = mysqli_prepare(
            $conn,
            "SELECT provider_id
             FROM service_providers
             WHERE email = ?
             AND provider_id != ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $providerEmail,
            $providerId
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_store_result($stmt);

        $duplicateEmail =
            mysqli_stmt_num_rows($stmt);

        mysqli_stmt_close($stmt);


        if ($duplicateEmail > 0) {

            $errorMessage =
                "This email is already being used.";

        } else {

            $stmt = mysqli_prepare(
                $conn,
                "UPDATE service_providers
                 SET
                    provider_name = ?,
                    email = ?,
                    phone = ?,
                    service_type = ?,
                    address = ?
                 WHERE provider_id = ?"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "sssssi",
                $providerName,
                $providerEmail,
                $providerPhone,
                $providerServiceType,
                $providerAddress,
                $providerId
            );

            if (mysqli_stmt_execute($stmt)) {

                $message =
                    "Profile updated successfully.";


                $_SESSION["provider_name"] =
                    $providerName;

                $_SESSION["provider_email"] =
                    $providerEmail;

                $_SESSION["service_type"] =
                    $providerServiceType;

            } else {

                $errorMessage =
                    "Could not update profile.";
            }

            mysqli_stmt_close($stmt);
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Provider Profile</title>

    <link
        rel="stylesheet"
        href="../CSS/style.css"
    >

</head>

<body>

<div class="dashboard-container">


    <aside class="sidebar">

        <h2>
            Emergency Service Finder
        </h2>

        <a href="Provider_Dashboard.php">
            Dashboard
        </a>

        <a href="Provider_Availability.php">
            Availability
        </a>

        <a href="Provider_Vehicles.php">
            Vehicles
        </a>

        <a href="Provider_Area.php">
            Service Area
        </a>

        <a href="Provider_Requests.php">
            Emergency Requests
        </a>

        <a
            href="Provider_Profile.php"
            class="active"
        >
            Profile
        </a>

        <a href="Provider_logout.php">
            Logout
        </a>

    </aside>


    <main class="main-content">

        <div class="top-section">

            <div>

                <h1>
                    Provider Profile
                </h1>

                <p>
                    View and update your service information.
                </p>

            </div>

        </div>


        <div class="section-box">

            <form method="post">

                <div class="profile-grid">


                    <div class="form-group">

                        <label for="providerName">
                            Provider Name
                        </label>

                        <input
                            type="text"
                            id="providerName"
                            name="providerName"
                            value="<?php echo htmlspecialchars($providerName); ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label for="providerEmail">
                            Email
                        </label>

                        <input
                            type="email"
                            id="providerEmail"
                            name="providerEmail"
                            value="<?php echo htmlspecialchars($providerEmail); ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label for="providerPhone">
                            Phone
                        </label>

                        <input
                            type="text"
                            id="providerPhone"
                            name="providerPhone"
                            value="<?php echo htmlspecialchars($providerPhone); ?>"
                        >

                    </div>


                    <div class="form-group">

                        <label for="providerServiceType">
                            Service Type
                        </label>

                        <select
                            id="providerServiceType"
                            name="providerServiceType"
                        >

                            <?php

                            $serviceTypes = [
                                "Ambulance",
                                "Fire Service",
                                "Police",
                                "Hospital",
                                "Blood Donor",
                                "Other"
                            ];

                            ?>

                            <?php foreach ($serviceTypes as $type): ?>

                                <option
                                    value="<?php echo $type; ?>"

                                    <?php
                                    if (
                                        $providerServiceType == $type
                                    ) {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    <?php echo $type; ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>

                </div>


                <div class="form-group profile-address">

                    <label for="providerAddress">
                        Address
                    </label>

                    <textarea
                        id="providerAddress"
                        name="providerAddress"
                        rows="4"
                    ><?php echo htmlspecialchars($providerAddress); ?></textarea>

                </div>


                <?php if ($errorMessage != ""): ?>

                    <p class="form-error">
                        <?php echo $errorMessage; ?>
                    </p>

                <?php endif; ?>


                <?php if ($message != ""): ?>

                    <p style="color:green; margin-top:15px; font-weight:bold;">
                        <?php echo $message; ?>
                    </p>

                <?php endif; ?>


                <button
                    type="submit"
                    class="primary-btn"
                >
                    Update Profile
                </button>

            </form>

        </div>


        <div class="section-box">

            <h2>
                Profile Summary
            </h2>

            <p>
                <strong>Name:</strong>
                <?php echo htmlspecialchars($providerName); ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?php echo htmlspecialchars($providerEmail); ?>
            </p>

            <p>
                <strong>Phone:</strong>
                <?php echo htmlspecialchars($providerPhone); ?>
            </p>

            <p>
                <strong>Service:</strong>
                <?php echo htmlspecialchars($providerServiceType); ?>
            </p>

            <p>
                <strong>Address:</strong>
                <?php echo htmlspecialchars($providerAddress); ?>
            </p>

            <p>
                <strong>Account Status:</strong>
                <?php echo htmlspecialchars($providerStatus); ?>
            </p>

        </div>

    </main>

</div>

</body>

</html>