<?php

session_start();


/* =========================
   SESSION CHECK
========================= */

if (!isset($_SESSION["provider_id"])) {

    header("Location: Provider_login.php");
    exit();
}


/* =========================
   DATABASE CONNECTION
========================= */

require_once "../../Config/db.php";

$providerId = $_SESSION["provider_id"];


/* =========================
   DEFAULT VALUES
========================= */

$availabilityStatus = "Offline";

$workingFrom = "";
$workingTo = "";

$is24Hours = 0;

$statusErr = "";
$timeErr = "";
$dbErr = "";

$successMessage = "";


/* =========================
   LOAD CURRENT AVAILABILITY
========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        availability_status,
        working_from,
        working_to,
        is_24_hours
     FROM provider_availability
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
    $dbStatus,
    $dbWorkingFrom,
    $dbWorkingTo,
    $dbIs24Hours
);


if (mysqli_stmt_fetch($stmt)) {

    $availabilityStatus =
        $dbStatus;

    $workingFrom =
        $dbWorkingFrom;

    $workingTo =
        $dbWorkingTo;

    $is24Hours =
        $dbIs24Hours;
}

mysqli_stmt_close($stmt);


/* =========================
   UPDATE AVAILABILITY
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    /* -------------------------
       STATUS VALIDATION
    ------------------------- */

    if (empty($_POST["status"])) {

        $statusErr =
            "Please select your availability status.";

    } else {

        $availabilityStatus =
            $_POST["status"];


        $allowedStatuses = [
            "Available",
            "Busy",
            "Offline"
        ];


        if (
            !in_array(
                $availabilityStatus,
                $allowedStatuses,
                true
            )
        ) {

            $statusErr =
                "Invalid availability status.";
        }
    }


    /* -------------------------
       24 HOURS CHECKBOX
    ------------------------- */

    if (isset($_POST["fullDay"])) {

        $is24Hours = 1;

    } else {

        $is24Hours = 0;
    }


    /* -------------------------
       WORKING TIME VALIDATION
    ------------------------- */

    if ($is24Hours == 1) {

        $workingFrom = NULL;
        $workingTo = NULL;

    } else {

        if (
            empty($_POST["startTime"]) ||
            empty($_POST["endTime"])
        ) {

            $timeErr =
                "Please select both start time and end time.";

        } else {

            $workingFrom =
                $_POST["startTime"];

            $workingTo =
                $_POST["endTime"];


            if ($workingFrom == $workingTo) {

                $timeErr =
                    "Start time and end time cannot be the same.";
            }
        }
    }


    /* =========================
       SAVE TO DATABASE
    ========================= */

    if (
        $statusErr == "" &&
        $timeErr == ""
    ) {


        /* Check existing record */

        $stmt = mysqli_prepare(
            $conn,
            "SELECT availability_id
             FROM provider_availability
             WHERE provider_id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $providerId
        );

        mysqli_stmt_execute($stmt);

        mysqli_stmt_store_result($stmt);

        $availabilityExists =
            mysqli_stmt_num_rows($stmt);

        mysqli_stmt_close($stmt);


        /* =========================
           UPDATE EXISTING RECORD
        ========================= */

        if ($availabilityExists > 0) {

            $stmt = mysqli_prepare(
                $conn,
                "UPDATE provider_availability
                 SET
                    availability_status = ?,
                    working_from = ?,
                    working_to = ?,
                    is_24_hours = ?
                 WHERE provider_id = ?"
            );


            mysqli_stmt_bind_param(
                $stmt,
                "sssii",
                $availabilityStatus,
                $workingFrom,
                $workingTo,
                $is24Hours,
                $providerId
            );


            if (mysqli_stmt_execute($stmt)) {

                $successMessage =
                    "Availability updated successfully.";

            } else {

                $dbErr =
                    "Could not update availability.";
            }


            mysqli_stmt_close($stmt);

        }


        /* =========================
           INSERT NEW RECORD
        ========================= */

        else {

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO provider_availability
                (
                    provider_id,
                    availability_status,
                    working_from,
                    working_to,
                    is_24_hours
                )
                VALUES (?, ?, ?, ?, ?)"
            );


            mysqli_stmt_bind_param(
                $stmt,
                "isssi",
                $providerId,
                $availabilityStatus,
                $workingFrom,
                $workingTo,
                $is24Hours
            );


            if (mysqli_stmt_execute($stmt)) {

                $successMessage =
                    "Availability saved successfully.";

            } else {

                $dbErr =
                    "Could not save availability.";
            }


            mysqli_stmt_close($stmt);
        }
    }
}


/* =========================
   FORMAT VALUES FOR INPUTS
========================= */

$startTimeValue = "";
$endTimeValue = "";


if (
    $workingFrom != NULL &&
    $workingFrom != ""
) {

    $startTimeValue =
        substr(
            $workingFrom,
            0,
            5
        );
}


if (
    $workingTo != NULL &&
    $workingTo != ""
) {

    $endTimeValue =
        substr(
            $workingTo,
            0,
            5
        );
}


/* =========================
   FORMAT SUMMARY TIME
========================= */

if ($is24Hours == 1) {

    $summaryWorkingTime =
        "24 Hours";

} elseif (
    $workingFrom != NULL &&
    $workingFrom != "" &&
    $workingTo != NULL &&
    $workingTo != ""
) {

    $summaryWorkingTime =
        date(
            "h:i A",
            strtotime($workingFrom)
        )
        .
        " - "
        .
        date(
            "h:i A",
            strtotime($workingTo)
        );

} else {

    $summaryWorkingTime =
        "Not Set";
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

    <title>
        Availability Management
    </title>

    <link
        rel="stylesheet"
        href="../CSS/style.css"
    >

</head>


<body>


<div class="dashboard-container">


    <!-- =========================
         SIDEBAR
    ========================== -->

    <aside class="sidebar">


        <h2>
            Emergency Service Finder
        </h2>


        <a href="Provider_Dashboard.php">
            Dashboard
        </a>


        <a
            href="Provider_Availability.php"
            class="active"
        >
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


        <a href="Provider_Profile.php">
            Profile
        </a>


        <a href="Provider_logout.php">
            Logout
        </a>


    </aside>



    <!-- =========================
         MAIN CONTENT
    ========================== -->

    <main class="main-content">


        <!-- PAGE HEADER -->

        <div class="top-section">

            <div>

                <h1>
                    Availability Management
                </h1>

                <p>
                    Update your current service status
                    and working time.
                </p>

            </div>

        </div>



        <!-- =========================
             AVAILABILITY FORM
        ========================== -->

        <div class="section-box">


            <h2>
                Current Service Status
            </h2>


            <form
                method="post"
                action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                novalidate
            >


                <!-- STATUS OPTIONS -->

                <div class="status-options">


                    <label class="status-option">

                        <input
                            type="radio"
                            name="status"
                            value="Available"

                            <?php
                            if (
                                $availabilityStatus == "Available"
                            ) {
                                echo "checked";
                            }
                            ?>
                        >

                        Available

                    </label>



                    <label class="status-option">

                        <input
                            type="radio"
                            name="status"
                            value="Busy"

                            <?php
                            if (
                                $availabilityStatus == "Busy"
                            ) {
                                echo "checked";
                            }
                            ?>
                        >

                        Busy

                    </label>



                    <label class="status-option">

                        <input
                            type="radio"
                            name="status"
                            value="Offline"

                            <?php
                            if (
                                $availabilityStatus == "Offline"
                            ) {
                                echo "checked";
                            }
                            ?>
                        >

                        Offline

                    </label>


                </div>


                <?php if ($statusErr != ""): ?>

                    <p class="form-error">

                        <?php
                            echo htmlspecialchars(
                                $statusErr
                            );
                        ?>

                    </p>

                <?php endif; ?>



                <!-- =========================
                     WORKING TIME
                ========================== -->

                <h2 class="availability-title">
                    Working Time
                </h2>


                <div class="time-container">


                    <div class="form-group">


                        <label for="startTime">
                            Start Time
                        </label>


                        <input
                            type="time"
                            id="startTime"
                            name="startTime"

                            value="<?php
                                echo htmlspecialchars(
                                    $startTimeValue
                                );
                            ?>"

                            <?php
                            if ($is24Hours == 1) {
                                echo "disabled";
                            }
                            ?>
                        >


                    </div>



                    <div class="form-group">


                        <label for="endTime">
                            End Time
                        </label>


                        <input
                            type="time"
                            id="endTime"
                            name="endTime"

                            value="<?php
                                echo htmlspecialchars(
                                    $endTimeValue
                                );
                            ?>"

                            <?php
                            if ($is24Hours == 1) {
                                echo "disabled";
                            }
                            ?>
                        >


                    </div>


                </div>



                <!-- =========================
                     24 HOURS
                ========================== -->

                <div class="full-day-option">


                    <input
                        type="checkbox"
                        id="fullDay"
                        name="fullDay"
                        value="1"

                        <?php
                        if ($is24Hours == 1) {
                            echo "checked";
                        }
                        ?>
                    >


                    <label for="fullDay">
                        Available 24 Hours
                    </label>


                </div>



                <?php if ($timeErr != ""): ?>

                    <p class="form-error">

                        <?php
                            echo htmlspecialchars(
                                $timeErr
                            );
                        ?>

                    </p>

                <?php endif; ?>



                <?php if ($dbErr != ""): ?>

                    <p class="form-error">

                        <?php
                            echo htmlspecialchars(
                                $dbErr
                            );
                        ?>

                    </p>

                <?php endif; ?>



                <!-- SUCCESS MESSAGE -->

                <?php if ($successMessage != ""): ?>

                    <p
                        style="
                            color: green;
                            margin-top: 15px;
                            font-weight: bold;
                        "
                    >

                        <?php
                            echo htmlspecialchars(
                                $successMessage
                            );
                        ?>

                    </p>

                <?php endif; ?>



                <!-- UPDATE BUTTON -->

                <button
                    type="submit"
                    class="primary-btn"
                >
                    Update Availability
                </button>


            </form>


        </div>



        <!-- =========================
             CURRENT SUMMARY
        ========================== -->

        <div class="section-box availability-summary">


            <h2>
                Current Availability Summary
            </h2>


            <p>

                <strong>
                    Status:
                </strong>

                <?php
                    echo htmlspecialchars(
                        $availabilityStatus
                    );
                ?>

            </p>



            <p>

                <strong>
                    Working Time:
                </strong>

                <?php
                    echo htmlspecialchars(
                        $summaryWorkingTime
                    );
                ?>

            </p>


        </div>


    </main>


</div>



<!-- =========================
     24 HOURS JAVASCRIPT
========================== -->

<script>

const fullDay =
    document.getElementById("fullDay");

const startTime =
    document.getElementById("startTime");

const endTime =
    document.getElementById("endTime");


fullDay.addEventListener(
    "change",
    function () {


        if (fullDay.checked) {

            startTime.disabled = true;
            endTime.disabled = true;

            startTime.value = "";
            endTime.value = "";

        } else {

            startTime.disabled = false;
            endTime.disabled = false;
        }

    }
);

</script>


</body>

</html>