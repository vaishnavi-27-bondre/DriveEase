<?php

session_start();

include "db.php";


if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit();

}


$user_id = $_SESSION['user_id'];


$query = "
SELECT
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
";


$result = pg_query_params(
    $conn,
    $query,
    array($user_id)
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>My Bookings - DriveEase</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="navbar">

    <div class="logo">
        🚗 DriveEase
    </div>

    <a href="index.php">Home</a>
    <a href="cars.php">Cars</a>
    <a href="booking.php">Book Car</a>
    <a href="my_bookings.php">My Bookings</a>
    <a href="logout.php">Logout</a>

</div>


<div class="container">

    <h1>My Bookings</h1>


    <table>

        <tr>

            <th>Booking ID</th>

            <th>Car</th>

            <th>Rental Type</th>

            <th>Date</th>

            <th>Duration</th>

            <th>Amount</th>

            <th>Status</th>
	    
		<th>Action</th>


        </tr>


        <?php while ($booking =
            pg_fetch_assoc($result)) { ?>

        <tr>

            <td>
                <?php echo $booking['booking_id']; ?>
            </td>

            <td>
                <?php echo $booking['car_name']; ?>
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
                <?php echo $booking['status']; ?>
            </td>
		<td>

    <?php if ($booking['status'] == 'Confirmed') { ?>

        <a href="cancel_booking.php?id=<?php echo $booking['booking_id']; ?>"
           class="btn"
           onclick="return confirm('Are you sure you want to cancel this booking?');">
           Cancel Booking
        </a>

    <?php } elseif ($booking['status'] == 'Completed') { ?>

        <a href="review.php?id=<?php echo $booking['booking_id']; ?>"
           class="btn">
           ⭐ Give Review
        </a>

    <?php } else { ?>

        <span>No Action</span>

    <?php } ?>

</td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>

</html>