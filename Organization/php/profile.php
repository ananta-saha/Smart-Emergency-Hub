<?php
require_once "profile_process.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Organization Profile</title>

    <link rel="stylesheet" href="../css/style.css">

</head>


<body>


    <!-- ---------- Header ---------- -->

    <header>

        <h1>🚨 Emergency Help Hub</h1>

        <div>

            Organization

            <button onclick="location.href='logout.php'">
                Logout
            </button>

        </div>

    </header>


    <!-- ---------- Navigation ---------- -->

    <nav>

        <a href="dashboard.php">🏠 Dashboard</a>

        <a href="providers.php">👥 Providers</a>

        <a href="services.php">🚑 Services</a>

        <a href="requests.php">🚨 Requests</a>

        <a href="funds.php">💰 Funds</a>

        <a href="reviews.php">⭐ Reviews</a>

        <a href="reports.php">📊 Reports</a>

        <a href="profile.php">👤 Profile</a>

    </nav>


    <!-- ---------- Main ---------- -->

    <main>

        <h2>Organization Profile</h2>


        <!-- Database Error -->

        <?php if ($dbErr): ?>

            <p>
                <?= htmlspecialchars($dbErr) ?>
            </p>

        <?php endif; ?>


        <!-- Success Message -->

        <?php if ($successMsg): ?>

            <p>
                <?= htmlspecialchars($successMsg) ?>
            </p>

        <?php endif; ?>


        <div class="box">


            <form
                method="POST"
                action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                novalidate
            >


                <!-- Organization Name -->

                <label for="profileName">
                    Organization Name
                </label>

                <input
                    type="text"
                    id="profileName"
                    name="profileName"
                    placeholder="Organization Name"
                    value="<?= htmlspecialchars($orgName) ?>"
                >

                <?php if ($orgNameErr): ?>

                    <p>
                        <?= htmlspecialchars($orgNameErr) ?>
                    </p>

                <?php endif; ?>


                <!-- Email -->

                <label for="profileEmail">
                    Email
                </label>

                <input
                    type="email"
                    id="profileEmail"
                    name="profileEmail"
                    placeholder="Email"
                    value="<?= htmlspecialchars($email) ?>"
                >

                <?php if ($emailErr): ?>

                    <p>
                        <?= htmlspecialchars($emailErr) ?>
                    </p>

                <?php endif; ?>


                <!-- Phone -->

                <label for="profilePhone">
                    Phone Number
                </label>

                <input
                    type="text"
                    id="profilePhone"
                    name="profilePhone"
                    placeholder="Phone Number"
                    value="<?= htmlspecialchars($phone) ?>"
                >

                <?php if ($phoneErr): ?>

                    <p>
                        <?= htmlspecialchars($phoneErr) ?>
                    </p>

                <?php endif; ?>


                <!-- Address -->

                <label for="profileAddress">
                    Address
                </label>

                <textarea
                    id="profileAddress"
                    name="profileAddress"
                    placeholder="Organization Address"
                ><?= htmlspecialchars($address) ?></textarea>

                <?php if ($addressErr): ?>

                    <p>
                        <?= htmlspecialchars($addressErr) ?>
                    </p>

                <?php endif; ?>


                <!-- Update Button -->

                <button type="submit">
                    Update Profile
                </button>


            </form>

        </div>

    </main>


    <!-- ---------- Footer ---------- -->

    <footer>

        <p>
            © 2026 Emergency Help Hub |
            Organization Management
        </p>

    </footer>


</body>

</html>