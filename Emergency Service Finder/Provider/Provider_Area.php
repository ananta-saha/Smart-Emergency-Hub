<?php

session_start();

if (!isset($_SESSION["provider_id"])) {
    header("Location: Provider_login.php");
    exit();
}

require_once "../../Config/db.php";

$providerId = $_SESSION["provider_id"];

$baseArea = "";
$serviceRange = "";
$coveredAreas = [];

$message = "";
$errorMessage = "";


$allowedAreas = [
    "Dhanmondi",
    "Kalabagan",
    "Mohammadpur",
    "Farmgate",
    "Mirpur",
    "Uttara",
    "Gulshan",
    "Banani"
];


/* =========================
   LOAD CURRENT AREA
========================= */

$stmt = mysqli_prepare(
    $conn,
    "SELECT
        base_area,
        service_range_km,
        covered_areas
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
    $dbBaseArea,
    $dbRange,
    $dbCoveredAreas
);

if (mysqli_stmt_fetch($stmt)) {

    $baseArea = $dbBaseArea;
    $serviceRange = $dbRange;

    $coveredAreas =
        array_map(
            "trim",
            explode(",", $dbCoveredAreas)
        );
}

mysqli_stmt_close($stmt);


/* =========================
   SAVE SERVICE AREA
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $baseArea =
        trim($_POST["baseArea"] ?? "");

    $serviceRange =
        (int)($_POST["serviceRange"] ?? 0);

    $coveredAreas =
        $_POST["coveredArea"] ?? [];


    $coveredAreas =
        array_values(
            array_intersect(
                $coveredAreas,
                $allowedAreas
            )
        );


    if ($baseArea == "") {

        $errorMessage =
            "Please select a base area.";

    } elseif ($serviceRange < 1) {

        $errorMessage =
            "Please enter a valid service range.";

    } elseif (count($coveredAreas) == 0) {

        $errorMessage =
            "Please select at least one covered area.";

    } else {

        $coveredText =
            implode(", ", $coveredAreas);


        /* Check existing record */

        $stmt = mysqli_prepare(
            $conn,
            "SELECT area_id
             FROM service_areas
             WHERE provider_id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $providerId
        );

        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        $exists =
            mysqli_stmt_num_rows($stmt);

        mysqli_stmt_close($stmt);


        if ($exists > 0) {

            $stmt = mysqli_prepare(
                $conn,
                "UPDATE service_areas
                 SET
                    base_area = ?,
                    service_range_km = ?,
                    covered_areas = ?
                 WHERE provider_id = ?"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "sisi",
                $baseArea,
                $serviceRange,
                $coveredText,
                $providerId
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);


        } else {

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO service_areas
                (
                    provider_id,
                    base_area,
                    service_range_km,
                    covered_areas
                )
                VALUES (?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "isis",
                $providerId,
                $baseArea,
                $serviceRange,
                $coveredText
            );

            mysqli_stmt_execute($stmt);

            mysqli_stmt_close($stmt);
        }


        $message =
            "Service area updated successfully.";
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

    <title>Service Area</title>

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

        <a
            href="Provider_Area.php"
            class="active"
        >
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
                    Service Area
                </h1>

                <p>
                    Manage your emergency service coverage area.
                </p>

            </div>

        </div>


        <div class="section-box">

            <form method="post">


                <div class="area-form-grid">


                    <div class="form-group">

                        <label for="baseArea">
                            Base Area
                        </label>

                        <select
                            id="baseArea"
                            name="baseArea"
                        >

                            <option value="">
                                Select Base Area
                            </option>

                            <?php foreach ($allowedAreas as $area): ?>

                                <option
                                    value="<?php echo $area; ?>"
                                    <?php
                                    if ($baseArea == $area) {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    <?php echo $area; ?>
                                </option>

                            <?php endforeach; ?>

                        </select>

                    </div>


                    <div class="form-group">

                        <label for="serviceRange">
                            Service Range (KM)
                        </label>

                        <input
                            type="number"
                            id="serviceRange"
                            name="serviceRange"
                            min="1"
                            value="<?php echo $serviceRange; ?>"
                        >

                    </div>

                </div>


                <h3 class="covered-title">
                    Covered Areas
                </h3>


                <div class="covered-areas">

                    <?php foreach ($allowedAreas as $area): ?>

                        <label>

                            <input
                                type="checkbox"
                                name="coveredArea[]"
                                value="<?php echo $area; ?>"

                                <?php
                                if (
                                    in_array(
                                        $area,
                                        $coveredAreas
                                    )
                                ) {
                                    echo "checked";
                                }
                                ?>
                            >

                            <?php echo $area; ?>

                        </label>

                    <?php endforeach; ?>

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
                    Save Service Area
                </button>

            </form>

        </div>


        <div class="section-box">

            <h2>
                Current Service Area
            </h2>

            <p>
                <strong>Base Area:</strong>

                <?php
                    echo $baseArea != ""
                        ? htmlspecialchars($baseArea)
                        : "Not Set";
                ?>
            </p>


            <p>
                <strong>Service Range:</strong>

                <?php
                    echo $serviceRange != ""
                        ? $serviceRange . " KM"
                        : "Not Set";
                ?>
            </p>


            <p>
                <strong>Covered Areas:</strong>

                <?php
                    echo count($coveredAreas) > 0
                        ? htmlspecialchars(
                            implode(", ", $coveredAreas)
                        )
                        : "Not Set";
                ?>
            </p>

        </div>

    </main>

</div>

</body>

</html>