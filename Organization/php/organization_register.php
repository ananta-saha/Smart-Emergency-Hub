<?php 
require_once "register_process.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Emergency Help Hub - Registration</title>
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
                <h2>Organization Registration</h2>
                <?php if ($dbErr): ?><p><?= htmlspecialchars($dbErr) ?></p><?php endif; ?>
                <?php if ($successMsg): ?><p><?= htmlspecialchars($successMsg) ?></p><?php endif; ?>

                <form action="<?= htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <label for="orgName"></label>
                    <input type="text" id="orgName" name="orgName" placeholder="Organization Name" value="<?= htmlspecialchars($orgName) ?>">
                    <?php if ($orgNameErr): ?><p><?= htmlspecialchars($orgNameErr) ?></p><?php endif; ?>

                    <label for="regEmail"></label>
                    <input type="email" id="regEmail" name="regEmail" placeholder="Email" value="<?= htmlspecialchars($regEmail) ?>">
                    <?php if ($emailErr): ?><p><?= htmlspecialchars($emailErr) ?></p><?php endif; ?>

                    <label for="regPassword"></label>
                    <input type="password" id="regPassword" name="regPassword" placeholder="Password">
                    <?php if ($passwordErr): ?><p><?= htmlspecialchars($passwordErr) ?></p><?php endif; ?>

                    <label for="phone"></label>
                    <input type="text" id="phone" name="phone" placeholder="Phone Number" value="<?= htmlspecialchars($phone) ?>">
                    <?php if ($phoneErr): ?><p><?= htmlspecialchars($phoneErr) ?></p><?php endif; ?>

                    <label for="address"></label>
                    <input type="text" id="address" name="address" placeholder="Organization Address" value="<?= htmlspecialchars($address) ?>">
                    <?php if ($addressErr): ?><p><?= htmlspecialchars($addressErr) ?></p><?php endif; ?>

                    <button type="submit">Register</button>
                </form>
                <p>
                    Already have an account?
                    <a href="login.php">Login</a>
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