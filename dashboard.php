<?php

session_start();

include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

/* Total bookings */

$query = "SELECT COUNT(*) FROM bookings
          WHERE user_id = $1
          AND status != 'Cancelled'";
$result = pg_query_params(
    $conn,
    $query,
    array($user_id)
);

$total_bookings = pg_fetch_result($result, 0, 0);


/* Confirmed bookings */

$query = "SELECT COUNT(*) FROM bookings
          WHERE user_id = $1
          AND status = 'Confirmed'";

$result = pg_query_params(
    $conn,
    $query,
    array($user_id)
);

$confirmed_bookings = pg_fetch_result($result, 0, 0);


/* Total amount */

$query = "SELECT COALESCE(SUM(total_amount),0)
          FROM bookings
          WHERE user_id = $1 AND status !='Cancelled'";

$result = pg_query_params(
    $conn,
    $query,
    array($user_id)
);

$total_amount = pg_fetch_result($result, 0, 0);

/* Completed bookings */

$query = "SELECT COUNT(*) FROM bookings
          WHERE user_id = $1
          AND status = 'Completed'";

$result = pg_query_params(
    $conn,
    $query,
    array($user_id)
);

$completed_bookings = pg_fetch_result($result, 0, 0);


/* Cancelled bookings */

$query = "SELECT COUNT(*) FROM bookings
          WHERE user_id = $1
          AND status = 'Cancelled'";

$result = pg_query_params(
    $conn,
    $query,
    array($user_id)
);

$cancelled_bookings = pg_fetch_result($result, 0, 0);


/* Recent bookings */

$query = "SELECT
          bookings.booking_id,
          cars.car_name,
          bookings.rental_type,
          bookings.booking_date,
          bookings.duration,
          bookings.total_amount,
          bookings.status
          FROM bookings
          JOIN cars
          ON bookings.car_id = cars.car_id
          WHERE bookings.user_id = $1
          ORDER BY bookings.booking_id DESC
          LIMIT 5";

$result = pg_query_params(
    $conn,
    $query,
    array($user_id)
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>DriveEase - Dashboard</title>

    <link rel="stylesheet"
          href="CSS/style.css">

</head>

<body>


<!-- NAVBAR -->

<div class="navbar">

    <div class="logo">
        🚗 DriveEase
    </div>

    <a href="index.php">Home</a>

    <a href="cars.php">Cars</a>

    <a href="booking.php">Book Car</a>

    <a href="dashboard.php">
        Dashboard
    </a>

    <a href="my_bookings.php">
        My Bookings
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>


<div class="container">


    <!-- WELCOME -->

    <div class="card">

        <h1>
            Welcome,
            <?php echo htmlspecialchars($user_name); ?> 👋
        </h1>

        <p>
            Manage your car rentals from your dashboard.
        </p>

        <br>

        <a href="cars.php" class="btn">
            🚗 Book a Car
        </a>

    </div>


    <br>


    <!-- STATISTICS -->

    <div class="car-grid">


        <div class="card">

            <h2>Total Bookings</h2>

            <h1>
                <?php echo $total_bookings; ?>
            </h1>

        </div>


        <div class="card">

            <h2>Confirmed</h2>

            <h1 class="available">
                <?php echo $confirmed_bookings; ?>
            </h1>

        </div>
	
	<div class="card">

    <h2>Completed</h2>

    <h1 class="price">
        <?php echo $completed_bookings; ?>
    </h1>

</div>


<div class="card">

    <h2>Cancelled</h2>

    <h1 class="booked">
        <?php echo $cancelled_bookings; ?>
    </h1>

</div>


        <div class="card">

            <h2>Total Spent</h2>

            <h1 class="price">
                ₹<?php echo $total_amount; ?>
            </h1>

        </div>


    </div>


    <br>


    <!-- RECENT BOOKINGS -->

    <div class="card">

        <h2>Recent Bookings</h2>

        <br>

        <table>

            <tr>

                <th>Booking ID</th>

                <th>Car</th>

                <th>Rental Type</th>

                <th>Date</th>

                <th>Duration</th>

                <th>Amount</th>

                <th>Status</th>

            </tr>


            <?php

            if (pg_num_rows($result) > 0) {

                while ($booking = pg_fetch_assoc($result)) {

            ?>

            <tr>

                <td>
                    <?php echo $booking['booking_id']; ?>
                </td>

                <td>
                    <?php echo htmlspecialchars(
                        $booking['car_name']
                    ); ?>
                </td>

                <td>
                    <?php echo $booking['rental_type']; ?>
                </td>

                <td>
                    <?php echo $booking['booking_date']; ?>
                </td>

                <td>
                    <?php echo $booking['duration']; ?>
                </td>

                <td>
                    ₹<?php echo $booking['total_amount']; ?>
                </td>

                <td>

                    <?php

                    if ($booking['status'] == "Confirmed") {

                    ?>

                        <span class="available">
                            🟢 Confirmed
                        </span>

                    <?php

                    } elseif ($booking['status'] == "Cancelled") {

                    ?>

                        <span class="booked">
                            🔴 Cancelled
                        </span>

                    <?php

                    } else {

                    ?>

                        <?php echo $booking['status']; ?>

                    <?php

                    }

                    ?>

                </td>

            </tr>

            <?php

                }

            } else {

            ?>

            <tr>

                <td colspan="7">

                    No bookings found.

                </td>

            </tr>

            <?php

            }

            ?>

        </table>

        <br>

        <a href="my_bookings.php"
           class="btn">

            View All Bookings

        </a>

    </div>


    <br>


    <!-- QUICK LINKS -->

    <h2>Quick Actions</h2>

    <div class="car-grid">


        <div class="card">

            <h2>🚗 Browse Cars</h2>

            <p>
                Find the perfect car for your journey.
            </p>

            <a href="cars.php"
               class="btn">

                View Cars

            </a>

        </div>


        <div class="card">

            <h2>📋 My Bookings</h2>

            <p>
                View all your previous bookings.
            </p>

            <a href="my_bookings.php"
               class="btn">

                View Bookings

            </a>

        </div>


        <div class="card">

            <h2>📞 Contact Us</h2>

            <p>
                Need help? Contact our support team.
            </p>

            <a href="contact.php"
               class="btn">

                Contact Us

            </a>

        </div>


    </div>


</div>


<footer>

    🚗 DriveEase - Car Rental System

    <br><br>

    Your trusted online car rental platform.

    <br><br>

    © 2026 DriveEase. All Rights Reserved.

</footer>


</body>

</html>