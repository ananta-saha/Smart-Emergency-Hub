<?php
session_start();

if (!isset($_SESSION["citizen_id"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Citizen Dashboard</title>

    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header class="navbar">
        <h2>🚑 Emergency Finder</h2>
        <nav>
            <a href="profile.php">Profile</a>
            <a href="../index.php">Logout</a>
        </nav>
    </header>

    <section class="dashboard">
        <section class="welcome">
            <h1>Welcome, Citizen!</h1>
            <p>How can we help you today?</p>
        </section>

        <section class="emergency">
            <div>
                <h2>🚨 Need Emergency Help?</h2>
                <p>
                    Find nearby emergency services and send a request.
                </p>
            </div>
            <a href="service.php" class="primary-btn">Find Emergency Help</a>
        </section>

        <h2 class="section-title">My Information</h2>

        <section class="feature">
            <a href="profile.php" class="feature-card">
                <p class="feature-icon">👤</p>
                <h3>My Profile</h3>
                <p>
                    Manage your personal information.
                </p>
            </a>

            <a href="wheelchair.php" class="feature-card">
                <p class="feature-icon">♿</p>
                <h3>Wheelchair</h3>
                <p>
                    Manage your wheelchair requirements.
                </p>
            </a>
        </section>
    </section>

    <footer>
        <p>
            © 2026 Emergency Finder - Citizen Portal.
        </p>
    </footer>

</body>

</html>

