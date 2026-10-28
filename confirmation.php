<?php

session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: booking.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$car_id = $_POST['car_id'];
$rental_type = $_POST['rental_type'];
$booking_date = $_POST['booking_date'];
$duration = $_POST['duration'];
$customer_name = $_POST['customer_name'];
$mobile = $_POST['mobile'];
$total_amount = $_POST['total_amount'];
$payment_method = $_POST['payment_method'];


// Insert booking
$booking_query = "INSERT INTO bookings
                  (user_id, car_id, rental_type, booking_date,
                   duration, customer_name, mobile, total_amount, status)
                  VALUES
                  ($1,$2,$3,$4,$5,$6,$7,$8,'Confirmed')
                  RETURNING booking_id";

$booking_result = pg_query_params(
    $conn,
    $booking_query,
    array(
        $user_id,
        $car_id,
        $rental_type,
        $booking_date,
        $duration,
        $customer_name,
        $mobile,
        $total_amount
    )
);

if (!$booking_result) {
    die("Booking failed.");
}

$booking = pg_fetch_assoc($booking_result);
$booking_id = $booking['booking_id'];


// Insert payment
$payment_query = "INSERT INTO payments
                  (booking_id, payment_method, amount, payment_status)
                  VALUES ($1,$2,$3,'Paid')";

$payment_result = pg_query_params(
    $conn,
    $payment_query,
    array(
        $booking_id,
        $payment_method,
        $total_amount
    )
);

if (!$payment_result) {
    die("Payment failed.");
}


// Get car name
$car_query = "SELECT car_name
              FROM cars
              WHERE car_id = $1";

$car_result = pg_query_params(
    $conn,
    $car_query,
    array($car_id)
);

$car = pg_fetch_assoc($car_result);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Booking Receipt - DriveEase</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .receipt {
            width: 600px;
            max-width: 95%;
            margin: 50px auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0,0,0,0.15);
        }

        .receipt h1 {
            text-align: center;
            color: #087cf5;
        }

        .success {
            text-align: center;
            color: green;
            font-weight: bold;
        }

        .receipt-table {
            width: 100%;
            margin-top: 25px;
        }

        .receipt-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .receipt-table td:first-child {
            font-weight: bold;
        }

        .total {
            font-size: 20px;
            color: #087cf5;
            font-weight: bold;
        }

        .print-btn {
            text-align: center;
            margin-top: 25px;
        }

        @media print {

            .navbar,
            .print-btn {
                display: none;
            }

            .receipt {
                box-shadow: none;
                margin: 0 auto;
            }

        }

    </style>

</head>

<body>

<div class="navbar">

    <div class="logo">🚗 DriveEase</div>

    <a href="index.php">Home</a>
    <a href="cars.php">Cars</a>
    <a href="my_bookings.php">My Bookings</a>
    <a href="logout.php">Logout</a>

</div>


<div class="receipt">

    <h1>🚗 DriveEase</h1>

    <p class="success">
        ✓ Booking Confirmed Successfully
    </p>

    <table class="receipt-table">

        <tr>
            <td>Booking ID</td>
            <td><?php echo $booking_id; ?></td>
        </tr>

        <tr>
            <td>Customer Name</td>
            <td><?php echo $customer_name; ?></td>
        </tr>

        <tr>
            <td>Mobile</td>
            <td><?php echo $mobile; ?></td>
        </tr>

        <tr>
            <td>Car</td>
            <td><?php echo $car['car_name']; ?></td>
        </tr>

        <tr>
            <td>Rental Type</td>
            <td><?php echo $rental_type; ?></td>
        </tr>

        <tr>
            <td>Booking Date</td>
            <td><?php echo $booking_date; ?></td>
        </tr>

        <tr>
            <td>Duration</td>
            <td><?php echo $duration; ?></td>
        </tr>

        <tr>
            <td>Payment Method</td>
            <td><?php echo $payment_method; ?></td>
        </tr>

        <tr>
            <td>Payment Status</td>
            <td>Paid</td>
        </tr>

        <tr>
            <td>Booking Status</td>
            <td>Confirmed</td>
        </tr>

        <tr>
            <td>Total Amount</td>
            <td class="total">
                ₹<?php echo $total_amount; ?>
            </td>
        </tr>

    </table>


    <div class="print-btn">

        <button class="btn" onclick="window.print()">
            🖨 Print Receipt
        </button>

        <a href="my_bookings.php" class="btn">
            My Bookings
        </a>

    </div>

</div>

</body>

</html>