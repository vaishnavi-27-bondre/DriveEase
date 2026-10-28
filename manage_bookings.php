<?php

session_start();
include "db.php";


// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}


// Get all bookings
$query = "
SELECT
    bookings.booking_id,
    bookings.customer_name,
    bookings.mobile,
    cars.car_name,
    bookings.booking_date,
    bookings.duration,
    bookings.total_amount,
    bookings.status
FROM bookings
JOIN cars
ON bookings.car_id = cars.car_id
ORDER BY bookings.booking_id DESC
";

$result = pg_query($conn, $query);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Bookings - DriveEase</title>

    <link rel="stylesheet" href="CSS/style.css">

    <style>

        .admin-container {
            width: 95%;
            max-width: 1200px;
            margin: 40px auto;
        }

        .admin-container h1 {
            margin-bottom: 25px;
        }

        .booking-table {
            overflow-x: auto;
        }

        .booking-table table {
            min-width: 1000px;
        }

        .status-confirmed {
            color: green;
            font-weight: bold;
        }

        .status-cancelled {
            color: red;
            font-weight: bold;
        }

        .status-completed {
            color: #087cf5;
            font-weight: bold;
        }

        .complete-btn {
            background-color: green;
            color: white;
            padding: 8px 12px;
            border-radius: 5px;
            text-decoration: none;
        }

        .complete-btn:hover {
            background-color: darkgreen;
        }

        .no-action {
            color: #777;
        }

    </style>

</head>

<body>


<!-- Navbar -->

<div class="navbar">

    <div class="logo">
        🚗 DriveEase Admin
    </div>

    <a href="admin.php">Dashboard</a>

    <a href="manage_cars.php">Manage Cars</a>

    <a href="manage_bookings.php">Manage Bookings</a>

    <a href="manage_users.php">Manage Users</a>

    <a href="messages.php">Messages</a>

    <a href="logout.php">Logout</a>

</div>


<div class="admin-container">

    <h1>Manage Bookings</h1>


    <div class="booking-table">

        <table>

            <tr>

                <th>Booking ID</th>

                <th>Customer</th>

                <th>Mobile</th>

                <th>Car</th>

                <th>Date</th>

                <th>Duration</th>

                <th>Amount</th>

                <th>Status</th>

                <th>Action</th>

            </tr>


            <?php while ($booking = pg_fetch_assoc($result)) { ?>

            <tr>

                <td>
                    <?php echo $booking['booking_id']; ?>
                </td>

                <td>
                    <?php echo $booking['customer_name']; ?>
                </td>

                <td>
                    <?php echo $booking['mobile']; ?>
                </td>

                <td>
                    <?php echo $booking['car_name']; ?>
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

                    <?php if ($booking['status'] == 'Confirmed') { ?>

                        <span class="status-confirmed">
                            Confirmed
                        </span>

                    <?php } elseif ($booking['status'] == 'Cancelled') { ?>

                        <span class="status-cancelled">
                            Cancelled
                        </span>

                    <?php } elseif ($booking['status'] == 'Completed') { ?>

                        <span class="status-completed">
                            Completed
                        </span>

                    <?php } ?>

                </td>


                <td>

                    <?php if ($booking['status'] == 'Confirmed') { ?>

                        <a
                            href="complete_booking.php?id=<?php echo $booking['booking_id']; ?>"
                            class="complete-btn"
                            onclick="return confirm('Mark this booking as completed?');"
                        >
                            Mark Completed
                        </a>

                    <?php } else { ?>

                        <span class="no-action">
                            No Action
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