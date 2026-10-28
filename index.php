<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>

    <title>DriveEase - Car Rental System</title>

    <link rel="stylesheet" href="CSS/style.css">

</head>

<body>

<!-- NAVBAR -->

<div class="navbar">

     <div class="logo">
 <img src="images/logo.jpeg"
             alt="DriveEase Logo">

         </div>
    <div class="nav-links">

        <a href="index.php">Home</a>

        <a href="cars.php">Cars</a>

        <a href="about.php">About</a>

        <a href="contact.php">Contact</a>

        <?php
        if (isset($_SESSION['user_id'])) {
        ?>

            <a href="dashboard.php">Dashboard</a>

            <a href="my_bookings.php">My Bookings</a>

            <a href="logout.php">Logout</a>

        <?php
        } else {
        ?>

            <a href="login.php">Login</a>

            <a href="register.php">Register</a>

        <?php
        }
        ?>

    </div>

</div>
<!-- HERO -->

<div class="container">

    <div class="card">

        <div style="display:flex; align-items:center; gap:40px;">

            <div style="flex:1;">

                <h1 style="font-size:42px;">
                    Find The Perfect
                </h1>

                <h1 style="font-size:42px; color:#087cf5;">
                    Car For Your Journey
                </h1>

                <p style="color:black; font-size:18px; line-height:1.6;">
                    Your trusted online car rental platform.
                    <br>
                    Rent your favourite car easily,
                    quickly and affordably.
                </p>

                <a href="cars.php" class="btn">
                    View Available Cars
                </a>

            </div>

            <div style="flex:1;">

                <img src="images/swift.jpeg"
                     width="100%"
                     height="300"
                     style="object-fit:contain;">

            </div>

        </div>

    </div>

</div>


<!-- SERVICES -->

<div class="container">

    <h2>Our Services</h2>

    <div class="car-grid">

        <div class="card">
            <h1 style="color:#087cf5;">🚗</h1>
            <h3>Wide Range of Cars</h3>
            <p>Choose from a variety of vehicles.</p>
        </div>

        <div class="card">
            <h1 style="color:#087cf5;">💰</h1>
            <h3>Affordable Prices</h3>
            <p>Get the best rates for rentals.</p>
        </div>

        <div class="card">
            <h1 style="color:#087cf5;">📋</h1>
            <h3>Easy Booking</h3>
            <p>Book your car easily.</p>
        </div>

        <div class="card">
            <h1 style="color:#087cf5;">🎧</h1>
            <h3>24/7 Support</h3>
            <p>Customer support anytime.</p>
        </div>

    </div>

</div>


<!-- WHY CHOOSE US -->

<div class="container">

    <div class="card">

        <h2>Why Choose DriveEase?</h2>

        <p>✔ Affordable Prices</p>
        <p>✔ Easy Booking</p>
        <p>✔ Wide Range of Cars</p>
        <p>✔ Flexible Rental Options</p>
        <p>✔ Customer Support</p>

    </div>

</div>


<!-- FOOTER -->

<footer>

    🚗 DriveEase - Car Rental System

    <br><br>

    Your trusted online car rental platform.

    <br><br>

    © 2026 DriveEase. All Rights Reserved.

</footer>

</body>

</html>