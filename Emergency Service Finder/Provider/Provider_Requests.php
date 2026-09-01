<?php

session_start();

if (!isset($_SESSION["provider_id"])) {
    header("Location: Provider_login.php");
    exit();
}

require_once "../../Config/db.php";

$providerId = $_SESSION["provider_id"];

$message = "";


/* =========================
   GET PROVIDER SERVICE TYPE
========================= */

$providerServiceType = "";

$stmt = mysqli_prepare(
    $conn,
    "SELECT service_type
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
    $providerServiceType
);

mysqli_stmt_fetch($stmt);

mysqli_stmt_close($stmt);


/* =========================
   STATUS HISTORY FUNCTION
========================= */

function saveStatusHistory(
    $conn,
    $requestId,
    $status,
    $providerId,
    $note
) {

    $type = "Provider";

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO request_status_history
        (
            request_id,
            status,
            updated_by_type,
            updated_by_id,
            note
        )
        VALUES (?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "issis",
        $requestId,
        $status,
        $type,
        $providerId,
        $note
    );

    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}


/* =========================
   REQUEST ACTIONS
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $requestId =
        (int)($_POST["request_id"] ?? 0);

    $action =
        $_POST["action"] ?? "";


    /* ACCEPT */

    if ($action == "accept") {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE emergency_requests
             SET
                status = 'Accepted',
                provider_id = ?
             WHERE request_id = ?
             AND status = 'Pending'
             AND
             (
                provider_id = ?
                OR
                (
                    provider_id IS NULL
                    AND service_type = ?
                )
             )"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iiis",
            $providerId,
            $requestId,
            $providerId,
            $providerServiceType
        );

        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {

            saveStatusHistory(
                $conn,
                $requestId,
                "Accepted",
                $providerId,
                "Provider accepted the emergency request"
            );

            $message =
                "Request accepted successfully.";
        }

        mysqli_stmt_close($stmt);
    }


    /* REJECT */

    elseif ($action == "reject") {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE emergency_requests
             SET status = 'Rejected'
             WHERE request_id = ?
             AND status = 'Pending'
             AND
             (
                provider_id = ?
                OR
                (
                    provider_id IS NULL
                    AND service_type = ?
                )
             )"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iis",
            $requestId,
            $providerId,
            $providerServiceType
        );

        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {

            saveStatusHistory(
                $conn,
                $requestId,
                "Rejected",
                $providerId,
                "Provider rejected the emergency request"
            );

            $message =
                "Request rejected.";
        }

        mysqli_stmt_close($stmt);
    }


    /* ON THE WAY */

    elseif ($action == "on_the_way") {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE emergency_requests
             SET status = 'On The Way'
             WHERE request_id = ?
             AND provider_id = ?
             AND status = 'Accepted'"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $requestId,
            $providerId
        );

        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {

            saveStatusHistory(
                $conn,
                $requestId,
                "On The Way",
                $providerId,
                "Provider is on the way"
            );

            $message =
                "Request status changed to On The Way.";
        }

        mysqli_stmt_close($stmt);
    }


    /* COMPLETE */

    elseif ($action == "complete") {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE emergency_requests
             SET status = 'Completed'
             WHERE request_id = ?
             AND provider_id = ?
             AND status = 'On The Way'"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $requestId,
            $providerId
        );

        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {

            saveStatusHistory(
                $conn,
                $requestId,
                "Completed",
                $providerId,
                "Emergency request completed"
            );

            $message =
                "Request completed successfully.";
        }

        mysqli_stmt_close($stmt);
    }
}


/* =========================
   LOAD REQUESTS
========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT 
        er.request_id,
        c.name AS citizen_name,
        er.emergency_type,
        er.location,
        er.request_time,
        er.status,
        er.provider_id
     FROM emergency_requests er
     JOIN citizens c
        ON er.citizen_id = c.citizen_id
     WHERE 
        er.provider_id = ?
        OR
        (
            er.provider_id IS NULL
            AND er.service_type = ?
            AND er.status = 'Pending'
        )
     ORDER BY er.request_time DESC"
);

mysqli_stmt_bind_param(
    $stmt,
    "is",
    $providerId,
    $providerServiceType
);

mysqli_stmt_execute($stmt);

$requests =
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

    <title>Emergency Requests</title>

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

        <a
            href="Provider_Requests.php"
            class="active"
        >
            Emergency Requests
        </a>

        <a href="Provider_Profile.php">
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
                    Emergency Requests
                </h1>

                <p>
                    View and manage incoming requests.
                </p>

            </div>

        </div>


        <?php if ($message != ""): ?>

            <div class="section-box">

                <p style="color:green; font-weight:bold;">
                    <?php echo $message; ?>
                </p>

            </div>

        <?php endif; ?>


        <div class="section-box">

            <div class="request-filter">

                <label for="requestFilter">
                    Filter Requests:
                </label>

                <select id="requestFilter">

                    <option value="All">
                        All
                    </option>

                    <option value="Pending">
                        Pending
                    </option>

                    <option value="Accepted">
                        Accepted
                    </option>

                    <option value="On The Way">
                        On The Way
                    </option>

                    <option value="Completed">
                        Completed
                    </option>

                    <option value="Rejected">
                        Rejected
                    </option>

                </select>

            </div>

        </div>


        <div class="section-box">

            <h2>
                Request List
            </h2>

            <table id="requestTable">

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Citizen</th>
                        <th>Emergency</th>
                        <th>Location</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>

                <?php if (mysqli_num_rows($requests) > 0): ?>

                    <?php while ($request = mysqli_fetch_assoc($requests)): ?>

                        <tr
                            data-status="<?php echo htmlspecialchars($request["status"]); ?>"
                        >

                            <td>
                                #<?php echo $request["request_id"]; ?>
                            </td>

                            <td>
                                <?php
                                    echo htmlspecialchars(
                                        $request["citizen_name"]
                                    );
                                ?>
                            </td>

                            <td>
                                <?php
                                    echo htmlspecialchars(
                                        $request["emergency_type"]
                                    );
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

                                <strong>
                                    <?php
                                        echo htmlspecialchars(
                                            $request["status"]
                                        );
                                    ?>
                                </strong>

                            </td>


                            <td>

                            <?php if ($request["status"] == "Pending"): ?>

                                <form
                                    method="post"
                                    style="display:inline;"
                                >

                                    <input
                                        type="hidden"
                                        name="request_id"
                                        value="<?php echo $request["request_id"]; ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="accept"
                                    >

                                    <button
                                        type="submit"
                                        class="accept-btn"
                                    >
                                        Accept
                                    </button>

                                </form>


                                <form
                                    method="post"
                                    style="display:inline;"
                                >

                                    <input
                                        type="hidden"
                                        name="request_id"
                                        value="<?php echo $request["request_id"]; ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="reject"
                                    >

                                    <button
                                        type="submit"
                                        class="reject-btn"
                                    >
                                        Reject
                                    </button>

                                </form>


                            <?php elseif ($request["status"] == "Accepted"): ?>

                                <form method="post">

                                    <input
                                        type="hidden"
                                        name="request_id"
                                        value="<?php echo $request["request_id"]; ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="on_the_way"
                                    >

                                    <button
                                        type="submit"
                                        class="way-btn"
                                    >
                                        On The Way
                                    </button>

                                </form>


                            <?php elseif ($request["status"] == "On The Way"): ?>

                                <form method="post">

                                    <input
                                        type="hidden"
                                        name="request_id"
                                        value="<?php echo $request["request_id"]; ?>"
                                    >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="complete"
                                    >

                                    <button
                                        type="submit"
                                        class="complete-btn"
                                    >
                                        Complete
                                    </button>

                                </form>


                            <?php else: ?>

                                No Action

                            <?php endif; ?>

                            </td>

                        </tr>

                    <?php endwhile; ?>


                <?php else: ?>

                    <tr>

                        <td
                            colspan="7"
                            style="text-align:center;"
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


<script>

const requestFilter =
    document.getElementById("requestFilter");

requestFilter.addEventListener(
    "change",
    function () {

        const selected =
            requestFilter.value;

        const rows =
            document.querySelectorAll(
                "#requestTable tbody tr[data-status]"
            );

        rows.forEach(
            function (row) {

                const status =
                    row.getAttribute(
                        "data-status"
                    );

                if (
                    selected === "All" ||
                    selected === status
                ) {

                    row.style.display = "";

                } else {

                    row.style.display = "none";
                }
            }
        );
    }
);

</script>

<?php mysqli_stmt_close($stmt); ?>

</body>

</html>