<?php

session_start();
include "db.php";


// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}


// ==============================
// TOTAL USERS
// ==============================

$user_query = "
SELECT COUNT(*) AS total_users
FROM users
WHERE role = 'customer'
";

$user_result = pg_query($conn, $user_query);

$total_users = pg_fetch_assoc($user_result)['total_users'];


// ==============================
// TOTAL CARS
// ==============================

$car_query = "
SELECT COUNT(*) AS total_cars
FROM cars
";

$car_result = pg_query($conn, $car_query);

$total_cars = pg_fetch_assoc($car_result)['total_cars'];


// ==============================
// AVAILABLE CARS
// ==============================

$available_car_query = "
SELECT COUNT(*) AS available_cars
FROM cars
WHERE status = 'Available'
";

$available_car_result = pg_query($conn, $available_car_query);

$available_cars =
    pg_fetch_assoc($available_car_result)['available_cars'];


// ==============================
// TOTAL BOOKINGS
// Cancelled bookings excluded
// ==============================

$total_booking_query = "
SELECT COUNT(*) AS total_bookings
FROM bookings
WHERE status != 'Cancelled'
";

$total_booking_result =
    pg_query($conn, $total_booking_query);

$total_bookings =
    pg_fetch_assoc($total_booking_result)['total_bookings'];


// ==============================
// CONFIRMED BOOKINGS
// ==============================

$confirmed_query = "
SELECT COUNT(*) AS confirmed_bookings
FROM bookings
WHERE status = 'Confirmed'
";

$confirmed_result =
    pg_query($conn, $confirmed_query);

$confirmed_bookings =
    pg_fetch_assoc($confirmed_result)['confirmed_bookings'];


// ==============================
// COMPLETED BOOKINGS
// ==============================

$completed_query = "
SELECT COUNT(*) AS completed_bookings
FROM bookings
WHERE status = 'Completed'
";

$completed_result =
    pg_query($conn, $completed_query);

$completed_bookings =
    pg_fetch_assoc($completed_result)['completed_bookings'];


// ==============================
// CANCELLED BOOKINGS
// ==============================

$cancelled_query = "
SELECT COUNT(*) AS cancelled_bookings
FROM bookings
WHERE status = 'Cancelled'
";

$cancelled_result =
    pg_query($conn, $cancelled_query);

$cancelled_bookings =
    pg_fetch_assoc($cancelled_result)['cancelled_bookings'];


// ==============================
// TOTAL REVENUE
// Cancelled bookings excluded
// ==============================

$revenue_query = "
SELECT COALESCE(SUM(payments.amount), 0) AS total_revenue
FROM payments
JOIN bookings
ON payments.booking_id = bookings.booking_id
WHERE payments.payment_status = 'Paid'
AND bookings.status != 'Cancelled'
";

$revenue_result =
    pg_query($conn, $revenue_query);

$total_revenue =
    pg_fetch_assoc($revenue_result)['total_revenue'];


// ==============================
// RECENT BOOKINGS
// ==============================

$recent_query = "
SELECT
    bookings.booking_id,
    bookings.customer_name,
    cars.car_name,
    bookings.booking_date,
    bookings.total_amount,
    bookings.status
FROM bookings
JOIN cars
ON bookings.car_id = cars.car_id
ORDER BY bookings.booking_id DESC
LIMIT 5
";

$recent_result =
    pg_query($conn, $recent_query);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Dashboard - DriveEase</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .admin-container {
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
        }

        .admin-container h1 {
            margin-bottom: 30px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 40px;
        }

        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .stat-card h3 {
            margin-top: 0;
            color: #555;
        }

        .stat-card p {
            font-size: 28px;
            font-weight: bold;
            color: #087cf5;
        }

        .section {
            background: white;
            padding: 25px;
            margin-bottom: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .status-confirmed {
            color: green;
            font-weight: bold;
        }

        .status-completed {
            color: #087cf5;
            font-weight: bold;
        }

        .status-cancelled {
            color: red;
            font-weight: bold;
        }

        .actions {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        @media (max-width: 900px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

        }

        @media (max-width: 600px) {

            .stats {
                grid-template-columns: 1fr;
            }

        }

    </style>

</head>


<body>


<!-- NAVBAR -->

<div class="navbar">

    <div class="logo">
        🚗 DriveEase Admin
    </div>

    <a href="admin.php">Dashboard</a>

    <a href="manage_cars.php">
        Manage Cars
    </a>

    <a href="manage_bookings.php">
        Manage Bookings
    </a>

    <a href="manage_users.php">
        Manage Users
    </a>

    <a href="messages.php">
        Messages
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>


<div class="admin-container">

    <h1>Admin Dashboard</h1>


    <!-- STATISTICS -->

    <div class="stats">


        <div class="stat-card">

            <h3>Total Users</h3>

            <p>
                <?php echo $total_users; ?>
            </p>

        </div>


        <div class="stat-card">

            <h3>Total Cars</h3>

            <p>
                <?php echo $total_cars; ?>
            </p>

        </div>


        <div class="stat-card">

            <h3>Available Cars</h3>

            <p>
                <?php echo $available_cars; ?>
            </p>

        </div>


        <div class="stat-card">

            <h3>Total Bookings</h3>

            <p>
                <?php echo $total_bookings; ?>
            </p>

        </div>


        <div class="stat-card">

            <h3>Confirmed Bookings</h3>

            <p>
                <?php echo $confirmed_bookings; ?>
            </p>

        </div>


        <div class="stat-card">

            <h3>Completed Bookings</h3>

            <p>
                <?php echo $completed_bookings; ?>
            </p>

        </div>


        <div class="stat-card">

            <h3>Cancelled Bookings</h3>

            <p>
                <?php echo $cancelled_bookings; ?>
            </p>

        </div>


        <div class="stat-card">

            <h3>Total Revenue</h3>

            <p>
                ₹<?php echo $total_revenue; ?>
            </p>

        </div>


    </div>


    <!-- QUICK ACTIONS -->

    <div class="section">

        <h2>Quick Actions</h2>

        <br>

        <div class="actions">

            <a href="add_car.php" class="btn">
                Add Car
            </a>

            <a href="manage_cars.php" class="btn">
                Manage Cars
            </a>

            <a href="manage_bookings.php" class="btn">
                Manage Bookings
            </a>

            <a href="manage_users.php" class="btn">
                Manage Users
            </a>

            <a href="messages.php" class="btn">
                View Messages
            </a>
		<a href="reviews.php" class="btn">
                Customer Reviews
            </a>


        </div>

    </div>


    <!-- RECENT BOOKINGS -->

    <div class="section">

        <h2>Recent Bookings</h2>

        <br>

        <table>

            <tr>

                <th>Booking ID</th>

                <th>Customer</th>

                <th>Car</th>

                <th>Date</th>

                <th>Amount</th>

                <th>Status</th>

            </tr>


            <?php while ($booking =
                pg_fetch_assoc($recent_result)) { ?>

            <tr>

                <td>
                    <?php echo $booking['booking_id']; ?>
                </td>

                <td>
                    <?php echo $booking['customer_name']; ?>
                </td>

                <td>
                    <?php echo $booking['car_name']; ?>
                </td>

                <td>
                    <?php echo $booking['booking_date']; ?>
                </td>

                <td>
                    ₹<?php echo $booking['total_amount']; ?>
                </td>

                <td>

                    <?php if ($booking['status'] == 'Confirmed') { ?>

                        <span class="status-confirmed">
                            Confirmed
                        </span>

                    <?php } elseif ($booking['status'] == 'Completed') { ?>

                        <span class="status-completed">
                            Completed
                        </span>

                    <?php } elseif ($booking['status'] == 'Cancelled') { ?>

                        <span class="status-cancelled">
                            Cancelled
                        </span>

                    <?php } ?>

                </td>

            </tr>

            <?php } ?>

        </table>

    </div>


</div>


</body>

</html>