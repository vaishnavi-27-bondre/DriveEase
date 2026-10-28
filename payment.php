<?php

session_start();
include "db.php";

/* Login Check */

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}


/* Get Booking Data */

if (
    !isset($_POST['car_id']) ||
    !isset($_POST['rental_type']) ||
    !isset($_POST['booking_date']) ||
    !isset($_POST['duration']) ||
    !isset($_POST['customer_name']) ||
    !isset($_POST['mobile'])
) {
    header("Location: booking.php");
    exit();
}


$car_id = $_POST['car_id'];
$rental_type = $_POST['rental_type'];
$booking_date = $_POST['booking_date'];
$duration = $_POST['duration'];
$customer_name = $_POST['customer_name'];
$mobile = $_POST['mobile'];


/* Date Validation */

if ($booking_date < date('Y-m-d')) {

    echo "<h2>Invalid Booking Date</h2>";
    echo "<p>You cannot book a car for a past date.</p>";
    echo '<a href="booking.php" class="btn">Go Back</a>';

    exit();
}


/* Duration Validation */

if ($duration <= 0) {

    echo "<h2>Invalid Duration</h2>";
    echo "<p>Duration must be at least 1.</p>";
    echo '<a href="booking.php" class="btn">Go Back</a>';

    exit();
}


/* Get Car Details */

$query = "SELECT * FROM cars WHERE car_id = $1";

$result = pg_query_params(
    $conn,
    $query,
    array($car_id)
);


if (pg_num_rows($result) == 0) {

    echo "Car not found.";
    exit();
}


$car = pg_fetch_assoc($result);


/* Check Car Availability */

if ($car['status'] != "Available") {

    echo "<h2>Car Not Available</h2>";
    echo "<p>This car is currently booked.</p>";
    echo '<a href="cars.php" class="btn">View Cars</a>';

    exit();
}


/* Double Booking Check */

$check_query = "SELECT * FROM bookings
                WHERE car_id = $1
                AND booking_date = $2
                AND status = 'Confirmed'";

$check_result = pg_query_params(
    $conn,
    $check_query,
    array($car_id, $booking_date)
);


if (pg_num_rows($check_result) > 0) {

    echo "<h2>Car Already Booked</h2>";
    echo "<p>This car is already booked for the selected date.</p>";
    echo '<a href="booking.php" class="btn">Choose Another Date</a>';

    exit();
}


/* Calculate Total */

if ($rental_type == "Day") {

    $total_amount = $car['price_day'] * $duration;

} elseif ($rental_type == "Hour") {

    $total_amount = $car['price_hour'] * $duration;

} elseif ($rental_type == "KM") {

    $total_amount = $car['price_km'] * $duration;

} else {

    echo "Invalid rental type.";
    exit();
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>DriveEase - Payment</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .payment-box {
            max-width: 700px;
            margin: 40px auto;
        }

        .summary {
            background: #f5f7fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .summary p {
            margin: 10px 0;
        }

        .payment-method {
            margin-top: 20px;
        }

        .payment-method label {
            display: block;
            padding: 12px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            cursor: pointer;
        }

        .payment-section {
            display: none;
            margin-top: 20px;
            padding: 20px;
            background: #f5f7fa;
            border-radius: 8px;
        }

        .qr-code {
            width: 220px;
            height: 220px;
            display: block;
            margin: 15px auto;
        }

        .terms {
            margin-top: 20px;
        }

        .terms input {
            width: auto;
        }

    </style>

</head>

<body>


<div class="navbar">

    <div class="logo">
        🚗 DriveEase
    </div>

    <a href="index.php">Home</a>
    <a href="cars.php">Cars</a>
    <a href="my_bookings.php">My Bookings</a>
    <a href="logout.php">Logout</a>

</div>


<div class="container">

    <div class="card payment-box">

        <h1>💳 Payment Details</h1>

        <p>Complete your payment to confirm your booking.</p>


        <!-- BOOKING SUMMARY -->

        <div class="summary">

            <h2>Booking Summary</h2>

            <p>
                <b>Car:</b>
                <?php echo htmlspecialchars($car['car_name']); ?>
            </p>

            <p>
                <b>Rental Type:</b>
                <?php echo htmlspecialchars($rental_type); ?>
            </p>

            <p>
                <b>Booking Date:</b>
                <?php echo htmlspecialchars($booking_date); ?>
            </p>

            <p>
                <b>Duration:</b>
                <?php echo htmlspecialchars($duration); ?>
            </p>

            <p>
                <b>Customer Name:</b>
                <?php echo htmlspecialchars($customer_name); ?>
            </p>

            <p>
                <b>Mobile:</b>
                <?php echo htmlspecialchars($mobile); ?>
            </p>

            <hr>

            <h2 class="price">
                Total Amount:
                ₹<?php echo number_format($total_amount, 2); ?>
            </h2>

        </div>


        <!-- PAYMENT FORM -->

        <form method="POST" action="confirmation.php">

            <input type="hidden"
                   name="car_id"
                   value="<?php echo $car_id; ?>">

            <input type="hidden"
                   name="rental_type"
                   value="<?php echo htmlspecialchars($rental_type); ?>">

            <input type="hidden"
                   name="booking_date"
                   value="<?php echo htmlspecialchars($booking_date); ?>">

            <input type="hidden"
                   name="duration"
                   value="<?php echo $duration; ?>">

            <input type="hidden"
                   name="customer_name"
                   value="<?php echo htmlspecialchars($customer_name); ?>">

            <input type="hidden"
                   name="mobile"
                   value="<?php echo htmlspecialchars($mobile); ?>">

            <input type="hidden"
                   name="total_amount"
                   value="<?php echo $total_amount; ?>">


            <div class="payment-method">

                <h2>Choose Payment Method</h2>


                <!-- UPI -->

                <label>

                    <input type="radio"
                           name="payment_method"
                           value="UPI"
                           onclick="showPayment('upi')"
                           required>

                    📱 UPI Payment

                </label>


                <!-- CARD -->

                <label>

                    <input type="radio"
                           name="payment_method"
                           value="Card"
                           onclick="showPayment('card')">

                    💳 Debit / Credit Card

                </label>


                <!-- CASH -->

                <label>

                    <input type="radio"
                           name="payment_method"
                           value="Cash"
                           onclick="showPayment('cash')">

                    💵 Cash Payment

                </label>


                <!-- UPI SECTION -->

                <div id="upi"
                     class="payment-section">

                    <h3>📱 Scan & Pay using UPI</h3>

                    <img src="images/upi_qr.jpeg"
                         class="qr-code"
                         alt="UPI QR Code">

                    <p style="text-align:center;">

                        <b>UPI ID:</b>
                        driveease@upi

                    </p>

                    <p style="text-align:center;">

                        Scan the QR code and pay

                        <b>
                            ₹<?php echo number_format($total_amount, 2); ?>
                        </b>

                    </p>

                </div>


                <!-- CARD SECTION -->

                <div id="card"
                     class="payment-section">

                    <h3>💳 Card Details</h3>

                    <label>Card Number</label>

                    <input type="text"
                           name="card_number"
                           placeholder="Enter 16 digit card number"
                           maxlength="16">

                    <label>Card Holder Name</label>

                    <input type="text"
                           name="card_holder"
                           placeholder="Enter card holder name">

                    <label>Expiry Date</label>

                    <input type="month"
                           name="expiry_date">

                    <label>CVV</label>

                    <input type="password"
                           name="cvv"
                           placeholder="Enter CVV"
                           maxlength="3">

                </div>


                <!-- CASH SECTION -->

                <div id="cash"
                     class="payment-section">

                    <h3>💵 Cash Payment</h3>

                    <p>
                        You can pay the amount at the time of car pickup.
                    </p>

                    <p>
                        Amount:
                        <b>
                            ₹<?php echo number_format($total_amount, 2); ?>
                        </b>
                    </p>

                </div>


            </div>


            <!-- SECURITY -->

            <div class="card">

                🔒 <b>Secure Payment</b>

                <p>
                    Your booking information is securely processed by DriveEase.
                </p>

            </div>


            <!-- TERMS -->

            <div class="terms">

                <label>

                    <input type="checkbox"
                           required>

                    I agree to the booking terms and conditions.

                </label>

            </div>


            <br>


            <button type="submit"
                    class="btn">

                ✅ Confirm Payment

            </button>


            <a href="booking.php"
               class="btn">

                Cancel

            </a>

        </form>

    </div>

</div>


<footer>

    🚗 DriveEase - Car Rental System

    <br><br>

    © 2026 DriveEase. All Rights Reserved.

</footer>


<script>

function showPayment(type) {

    document.getElementById("upi").style.display = "none";

    document.getElementById("card").style.display = "none";

    document.getElementById("cash").style.display = "none";


    document.getElementById(type).style.display = "block";

}

</script>


</body>

</html>