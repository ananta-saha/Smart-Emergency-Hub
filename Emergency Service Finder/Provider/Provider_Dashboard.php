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
   PROVIDER INFORMATION
========================= */

$providerName = "Provider";
$serviceType = "Not Set";

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        provider_name,
        service_type
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
    $serviceType
);

mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);


/* =========================
   PROVIDER AVAILABILITY
========================= */

$availabilityStatus = "Offline";
$workingTime = "Not Set";

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
    $availabilityStatus,
    $workingFrom,
    $workingTo,
    $is24Hours
);

if (mysqli_stmt_fetch($stmt)) {

    if ($is24Hours == 1) {

        $workingTime = "24 Hours";

    } else {

        if ($workingFrom != NULL && $workingTo != NULL) {

            $workingTime =
                date("h:i A", strtotime($workingFrom))
                . " - " .
                date("h:i A", strtotime($workingTo));

        } else {

            $workingTime = "Not Set";
        }
    }
}

mysqli_stmt_close($stmt);


/* =========================
   AVAILABLE VEHICLES
========================= */

$availableVehicles = 0;

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        COALESCE(SUM(available_vehicles), 0)
     FROM provider_vehicles
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
    $availableVehicles
);

mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);


/* =========================
   SERVICE AREA
========================= */

$baseArea = "Not Set";
$serviceRange = 0;

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        base_area,
        service_range_km
     FROM service_areas
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
    $baseArea,
    $serviceRange
);

mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);


/* =========================
   PENDING REQUESTS
========================= */

$pendingRequests = 0;

$stmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(*)
     FROM emergency_requests
     WHERE provider_id = ?
     AND status = 'Pending'"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $providerId
);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result(
    $stmt,
    $pendingRequests
);

mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);


/* =========================
   TODAY'S REQUESTS
========================= */

$todayRequests = 0;

$stmt = mysqli_prepare(
    $conn,
    "SELECT COUNT(*)
     FROM emergency_requests
     WHERE provider_id = ?
     AND DATE(request_time) = CURDATE()"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $providerId
);

mysqli_stmt_execute($stmt);

mysqli_stmt_bind_result(
    $stmt,
    $todayRequests
);

mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);


/* =========================
   RECENT REQUESTS
========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        request_id,
        location,
        request_time,
        status
     FROM emergency_requests
     WHERE provider_id = ?
     ORDER BY request_time DESC
     LIMIT 3"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $providerId
);

mysqli_stmt_execute($stmt);

$recentRequests =
    mysqli_stmt_get_result($stmt);

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Provider Dashboard</title>

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


        <a
            href="Provider_Dashboard.php"
            class="active"
        >
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


        <!-- TOP SECTION -->

        <div class="top-section">


            <div>

                <h1>
                    Provider Dashboard
                </h1>


                <p>

                    Welcome,

                    <?php
                        echo htmlspecialchars(
                            $providerName
                        );
                    ?>

                </p>

            </div>


            <div class="status-badge">

                <?php
                    echo htmlspecialchars(
                        $availabilityStatus
                    );
                ?>

            </div>


        </div>



        <!-- =========================
             DASHBOARD CARDS
        ========================== -->

        <div class="dashboard-cards">


            <div class="dashboard-card">

                <h3>
                    Pending Requests
                </h3>

                <p>
                    <?php echo $pendingRequests; ?>
                </p>

            </div>



            <div class="dashboard-card">

                <h3>
                    Available Vehicles
                </h3>

                <p>
                    <?php echo $availableVehicles; ?>
                </p>

            </div>



            <div class="dashboard-card">

                <h3>
                    Service Range
                </h3>

                <p>

                    <?php
                        echo $serviceRange;
                    ?>

                    KM

                </p>

            </div>



            <div class="dashboard-card">

                <h3>
                    Today's Requests
                </h3>

                <p>
                    <?php echo $todayRequests; ?>
                </p>

            </div>


        </div>



        <!-- =========================
             SERVICE INFORMATION
        ========================== -->

        <div class="section-box">


            <h2>
                Service Information
            </h2>


            <div class="service-info">


                <p>

                    <strong>
                        Service Type:
                    </strong>

                    <?php
                        echo htmlspecialchars(
                            $serviceType
                        );
                    ?>

                </p>



                <p>

                    <strong>
                        Base Area:
                    </strong>

                    <?php
                        echo htmlspecialchars(
                            $baseArea
                        );
                    ?>

                </p>



                <p>

                    <strong>
                        Working Time:
                    </strong>

                    <?php
                        echo htmlspecialchars(
                            $workingTime
                        );
                    ?>

                </p>



                <p>

                    <strong>
                        Current Status:
                    </strong>

                    <?php
                        echo htmlspecialchars(
                            $availabilityStatus
                        );
                    ?>

                </p>


            </div>


        </div>



        <!-- =========================
             RECENT REQUESTS
        ========================== -->

        <div class="section-box">


            <h2>
                Recent Emergency Requests
            </h2>


            <table>


                <thead>

                    <tr>

                        <th>
                            Request ID
                        </th>

                        <th>
                            Location
                        </th>

                        <th>
                            Time
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>



                <tbody>


                <?php if (mysqli_num_rows($recentRequests) > 0): ?>


                    <?php while ($request = mysqli_fetch_assoc($recentRequests)): ?>


                        <?php

                        $statusClass = "";


                        if ($request["status"] == "Pending") {

                            $statusClass = "pending";

                        } elseif ($request["status"] == "Accepted") {

                            $statusClass = "accepted";

                        } elseif ($request["status"] == "Completed") {

                            $statusClass = "completed";

                        } elseif ($request["status"] == "On The Way") {

                            $statusClass = "status-way";

                        } elseif (
                            $request["status"] == "Rejected" ||
                            $request["status"] == "Cancelled"
                        ) {

                            $statusClass = "status-rejected";
                        }

                        ?>


                        <tr>


                            <td>

                                #
                                <?php
                                    echo $request[
                                        "request_id"
                                    ];
                                ?>

                            </td>



                            <td>

                                <?php
                                    echo htmlspecialchars(
                                        $request["location"]
                                    );
                                ?>

                            </td>



                            <td>

                                <?php
                                    echo date(
                                        "h:i A",
                                        strtotime(
                                            $request["request_time"]
                                        )
                                    );
                                ?>

                            </td>



                            <td>

                                <span
                                    class="<?php echo $statusClass; ?>"
                                >

                                    <?php
                                        echo htmlspecialchars(
                                            $request["status"]
                                        );
                                    ?>

                                </span>

                            </td>


                        </tr>


                    <?php endwhile; ?>


                <?php else: ?>


                    <tr>

                        <td
                            colspan="4"
                            style="text-align: center;"
                        >
                            No emergency requests found.
                        </td>

                    </tr>


                <?php endif; ?>


                </tbody>


            </table>


        </div>


    </main>


</div>


<?php

mysqli_stmt_close($stmt);

?>


</body>

</html>