<?php
 require_once "login_process.php"; 
 ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Emergency Help Hub - Login</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <header>
        <h1>🚨 Emergency Help Hub</h1>
    </header>
    <main>
        <section class="auth-page">
            <div class="auth-box">
                <h1>🚨 Emergency Help Hub</h1>
                <h2>Organization Login</h2>
                <?php if ($loginErr): ?><p><?= htmlspecialchars($loginErr) ?></p><?php endif; ?>
                <?php if ($dbErr): ?><p><?= htmlspecialchars($dbErr) ?></p><?php endif; ?>

                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <label for="loginEmail"></label>
                    <input type="text" id="loginEmail" name="loginEmail" placeholder="Enter Address or mobile number" value="<?= htmlspecialchars($email) ?>">

                    <?php if ($emailErr): ?><p><?= htmlspecialchars($emailErr) ?></p> <?php endif; ?>

                    <label for="loginPassword"></label>
                    <input type="password" id="loginPassword" name="loginPassword" placeholder="Enter Password">

                    <?php if ($passwordErr): ?><p><?= htmlspecialchars($passwordErr) ?></p><?php endif; ?>

                    <button type="submit">Login</button>
                </form>
                <p>
                    Don't have an account?<a href="organization_register.php">Register</a>
                </p>
            </div>
        </section>
    </main>

    <footer>
        <p>
            © 2026 Emergency Help Hub |
            Organization Management
        </p>
    </footer>
</body>

</html>

