<?php

session_start();

if (!isset($_SESSION["provider_id"])) {
    header("Location: Provider_login.php");
    exit();
}

require_once "../../Config/db.php";

$providerId = $_SESSION["provider_id"];

$message = "";
$errorMessage = "";


/* =========================
   ADD / UPDATE VEHICLE
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"] ?? "";


    if ($action == "save") {

        $vehicleType =
            trim($_POST["vehicleType"] ?? "");

        $totalRaw =
            $_POST["totalVehicles"] ?? "";

        $availableRaw =
            $_POST["availableVehicles"] ?? "";


        if (
            $vehicleType == "" ||
            $totalRaw == "" ||
            $availableRaw == ""
        ) {

            $errorMessage =
                "Please fill in all vehicle information.";

        } else {

            $totalVehicles = (int)$totalRaw;
            $availableVehicles = (int)$availableRaw;


            if ($totalVehicles < 1) {

                $errorMessage =
                    "Total vehicles must be at least 1.";

            } elseif ($availableVehicles < 0) {

                $errorMessage =
                    "Available vehicles cannot be negative.";

            } elseif (
                $availableVehicles > $totalVehicles
            ) {

                $errorMessage =
                    "Available vehicles cannot be greater than total vehicles.";

            } else {


                /* Check existing vehicle type */

                $stmt = mysqli_prepare(
                    $conn,
                    "SELECT vehicle_id
                     FROM provider_vehicles
                     WHERE provider_id = ?
                     AND vehicle_type = ?"
                );

                mysqli_stmt_bind_param(
                    $stmt,
                    "is",
                    $providerId,
                    $vehicleType
                );

                mysqli_stmt_execute($stmt);
                mysqli_stmt_store_result($stmt);

                $exists =
                    mysqli_stmt_num_rows($stmt);

                mysqli_stmt_close($stmt);


                if ($exists > 0) {

                    $stmt = mysqli_prepare(
                        $conn,
                        "UPDATE provider_vehicles
                         SET
                            total_vehicles = ?,
                            available_vehicles = ?
                         WHERE provider_id = ?
                         AND vehicle_type = ?"
                    );

                    mysqli_stmt_bind_param(
                        $stmt,
                        "iiis",
                        $totalVehicles,
                        $availableVehicles,
                        $providerId,
                        $vehicleType
                    );

                    mysqli_stmt_execute($stmt);

                    $message =
                        "Vehicle information updated successfully.";

                    mysqli_stmt_close($stmt);

                } else {

                    $stmt = mysqli_prepare(
                        $conn,
                        "INSERT INTO provider_vehicles
                        (
                            provider_id,
                            vehicle_type,
                            total_vehicles,
                            available_vehicles
                        )
                        VALUES (?, ?, ?, ?)"
                    );

                    mysqli_stmt_bind_param(
                        $stmt,
                        "isii",
                        $providerId,
                        $vehicleType,
                        $totalVehicles,
                        $availableVehicles
                    );

                    mysqli_stmt_execute($stmt);

                    $message =
                        "Vehicle added successfully.";

                    mysqli_stmt_close($stmt);
                }
            }
        }
    }


    /* =========================
       DELETE VEHICLE
    ========================= */

    if ($action == "delete") {

        $vehicleId =
            (int)($_POST["vehicle_id"] ?? 0);

        $stmt = mysqli_prepare(
            $conn,
            "DELETE FROM provider_vehicles
             WHERE vehicle_id = ?
             AND provider_id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "ii",
            $vehicleId,
            $providerId
        );

        mysqli_stmt_execute($stmt);

        $message =
            "Vehicle deleted successfully.";

        mysqli_stmt_close($stmt);
    }
}


/* =========================
   LOAD VEHICLES
========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        vehicle_id,
        vehicle_type,
        total_vehicles,
        available_vehicles
     FROM provider_vehicles
     WHERE provider_id = ?
     ORDER BY vehicle_id DESC"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $providerId
);

mysqli_stmt_execute($stmt);

$vehicles =
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

    <title>Provider Vehicles</title>

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

        <a
            href="Provider_Vehicles.php"
            class="active"
        >
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


    <main class="main-content">

        <div class="top-section">

            <div>

                <h1>
                    Vehicle Management
                </h1>

                <p>
                    Add and manage your emergency vehicles.
                </p>

            </div>

        </div>


        <div class="section-box">

            <h2>
                Add / Update Vehicle
            </h2>


            <form method="post">

                <input
                    type="hidden"
                    name="action"
                    value="save"
                >


                <div class="vehicle-form-grid">

                    <div class="form-group">

                        <label for="vehicleType">
                            Vehicle Type
                        </label>

                        <select
                            id="vehicleType"
                            name="vehicleType"
                        >

                            <option value="">
                                Select Vehicle
                            </option>

                            <option value="Basic Ambulance">
                                Basic Ambulance
                            </option>

                            <option value="ICU Ambulance">
                                ICU Ambulance
                            </option>

                            <option value="Cardiac Ambulance">
                                Cardiac Ambulance
                            </option>

                            <option value="Freezer Ambulance">
                                Freezer Ambulance
                            </option>

                            <option value="Fire Engine">
                                Fire Engine
                            </option>

                            <option value="Rescue Vehicle">
                                Rescue Vehicle
                            </option>

                            <option value="Police Van">
                                Police Van
                            </option>

                            <option value="Patrol Car">
                                Patrol Car
                            </option>

                        </select>

                    </div>


                    <div class="form-group">

                        <label for="totalVehicles">
                            Total Vehicles
                        </label>

                        <input
                            type="number"
                            id="totalVehicles"
                            name="totalVehicles"
                            min="1"
                        >

                    </div>


                    <div class="form-group">

                        <label for="availableVehicles">
                            Available Vehicles
                        </label>

                        <input
                            type="number"
                            id="availableVehicles"
                            name="availableVehicles"
                            min="0"
                        >

                    </div>

                </div>


                <?php if ($errorMessage != ""): ?>

                    <p class="form-error">
                        <?php echo $errorMessage; ?>
                    </p>

                <?php endif; ?>


                <?php if ($message != ""): ?>

                    <p style="color: green; margin-top: 15px; font-weight: bold;">
                        <?php echo $message; ?>
                    </p>

                <?php endif; ?>


                <button
                    type="submit"
                    class="primary-btn"
                >
                    Save Vehicle
                </button>

            </form>

        </div>


        <div class="section-box">

            <h2>
                Vehicle List
            </h2>


            <table>

                <thead>

                    <tr>

                        <th>
                            Vehicle Type
                        </th>

                        <th>
                            Total
                        </th>

                        <th>
                            Available
                        </th>

                        <th>
                            Action
                        </th>

                    </tr>

                </thead>


                <tbody>

                <?php if (mysqli_num_rows($vehicles) > 0): ?>

                    <?php while ($vehicle = mysqli_fetch_assoc($vehicles)): ?>

                        <tr>

                            <td>
                                <?php
                                    echo htmlspecialchars(
                                        $vehicle["vehicle_type"]
                                    );
                                ?>
                            </td>

                            <td>
                                <?php
                                    echo $vehicle["total_vehicles"];
                                ?>
                            </td>

                            <td>
                                <?php
                                    echo $vehicle["available_vehicles"];
                                ?>
                            </td>

                            <td>

                                <form
                                    method="post"
                                    style="display:inline;"
                                    onsubmit="return confirm('Delete this vehicle?');"
                                >

                                    <input
                                        type="hidden"
                                        name="action"
                                        value="delete"
                                    >

                                    <input
                                        type="hidden"
                                        name="vehicle_id"
                                        value="<?php echo $vehicle["vehicle_id"]; ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="delete-btn"
                                    >
                                        Delete
                                    </button>

                                </form>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                <?php else: ?>

                    <tr>

                        <td
                            colspan="4"
                            style="text-align:center;"
                        >
                            No vehicles found.
                        </td>

                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>

    </main>

</div>

<?php mysqli_stmt_close($stmt); ?>

</body>

</html>