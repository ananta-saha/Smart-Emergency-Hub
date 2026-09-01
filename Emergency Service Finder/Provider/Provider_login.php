<?php

require_once "Provider_login_process.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Provider Login</title>


<link rel="stylesheet" href="../CSS/style.css">


</head>


<body>


<div class="login-page">


    <div class="login-box">


        <h1>
            Emergency Service Finder
        </h1>


        <p class="login-subtitle">
            Emergency Service Provider Login
        </p>



        <form
            method="post"
            action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
            novalidate
        >



            <label for="email">
                Email
            </label>


            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                value="<?php echo htmlspecialchars($email); ?>"
            >



            <?php if ($emailErr != ""): ?>

                <p class="form-error">
                    <?php echo $emailErr; ?>
                </p>

            <?php endif; ?>



            <label for="password">
                Password
            </label>


            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
            >



            <?php if ($passwordErr != ""): ?>

                <p class="form-error">
                    <?php echo $passwordErr; ?>
                </p>

            <?php endif; ?>



            <?php if ($loginErr != ""): ?>

                <p class="form-error">
                    <?php echo $loginErr; ?>
                </p>

            <?php endif; ?>



            <button type="submit">
                Login
            </button>



            <p class="signup-link">

                New Service Provider?

                <a href="Provider_signup.php">
                    Create Account
                </a>

            </p>



        </form>


    </div>


</div>


</body>

</html>