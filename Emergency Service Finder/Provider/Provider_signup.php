<?php

require_once "Provider_signup_process.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Provider Signup</title>

<link rel="stylesheet" href="../CSS/style.css">

</head>


<body>


<div class="login-page">


    <div class="login-box">


        <h1>
            Emergency Service Finder
        </h1>


        <p class="login-subtitle">
            Emergency Service Provider Registration
        </p>



        <form method="post">


            <label>
                Provider Name
            </label>

            <input 
                type="text"
                name="provider_name"
                placeholder="Enter provider name"
            >



            <label>
                Email
            </label>

            <input 
                type="email"
                name="email"
                placeholder="Enter email"
            >



            <label>
                Password
            </label>

            <input 
                type="password"
                name="password"
                placeholder="Create password"
            >



            <label>
                Phone
            </label>

            <input 
                type="text"
                name="phone"
                placeholder="Enter phone number"
            >



            <label>
                Service Type
            </label>

            <select 
                name="service_type"
                style="width:100%;padding:12px;border-radius:5px;border:1px solid #ccc;"
            >

                <option>
                    Ambulance
                </option>

                <option>
                    Fire Service
                </option>

                <option>
                    Police
                </option>

                <option>
                    Hospital
                </option>

            </select>



            <button type="submit">
                Create Account
            </button>



        </form>



        <p style="margin-top:20px;text-align:center;">

            Already have an account?

            <a href="Provider_login.php">
                Login
            </a>

        </p>



    </div>


</div>


</body>

</html>