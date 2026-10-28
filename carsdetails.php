<?php

include "db.php";

if (!isset($_GET['id'])) {
    die("Car not selected");
}

$car_id = $_GET['id'];

$query = "SELECT * FROM cars WHERE car_id = $1";

$result = pg_query_params(
    $conn,
    $query,
    array($car_id)
);

if (pg_num_rows($result) == 0) {
    die("Car not found");
}

$car = pg_fetch_assoc($result);

$review_query = "
SELECT 
    reviews.rating,
    reviews.comment,
    reviews.created_at,
    users.full_name
FROM reviews
JOIN bookings
ON reviews.booking_id = bookings.booking_id
JOIN users
ON reviews.user_id = users.user_id
WHERE bookings.car_id = $1
ORDER BY reviews.created_at DESC
";

$review_result = pg_query_params(
    $conn,
    $review_query,
    array($car_id)
);


?>
<!DOCTYPE html>
<html>

<head>

    <title>DriveEase - Car Details</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="navbar">

    <div class="logo">
        🚗 DriveEase
    </div>

    <a href="index.php">Home</a>
    <a href="cars.php">Cars</a>
    <a href="booking.php">Book Car</a>
    <a href="about.php">About Us</a>
    <a href="contact.php">Contact</a>
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>

</div>


<div class="container">

    <div class="card">

        <img src="images/<?php echo $car['image']; ?>"
             width="400"
             height="250"
             style="object-fit:contain;">

        <h1>
            <?php echo $car['car_name']; ?>
        </h1>

        <p>
            <b>Car Type:</b>
            <?php echo $car['car_type']; ?>
        </p>

        <p>
            <b>Fuel Type:</b>
            <?php echo $car['fuel_type']; ?>
        </p>

        <p>
            <b>Seating Capacity:</b>
            <?php echo $car['seats']; ?> Seats
        </p>

        <p>
            <b>Transmission:</b>
            <?php echo $car['transmission']; ?>
        </p>

        <p>
            <b>Availability:</b>

            <?php echo $car['status']; ?>

        </p>

        <hr>

        <h2 style="color:#087cf5;">
            Rental Prices
        </h2>

        <p>
            <b>Hour Wise:</b>
            ₹<?php echo $car['price_hour']; ?> / Hour
        </p>

        <p>
            <b>KM Wise:</b>
            ₹<?php echo $car['price_km']; ?> / KM
        </p>

        <p>
            <b>Day Wise:</b>
            ₹<?php echo $car['price_day']; ?> / Day
        </p>

        <br>

        <?php if ($car['status'] == 'Available') { ?>

            <a class="btn"
               href="booking.php?car_id=<?php echo $car['car_id']; ?>">

                Book This Car

            </a>

        <?php } ?>

    </div>
<hr>

<h2>Customer Reviews ⭐</h2>

<?php if (pg_num_rows($review_result) > 0) { ?>

    <?php while ($review = pg_fetch_assoc($review_result)) { ?>

        <div class="card">

            <h3>
                <?php echo htmlspecialchars($review['full_name']); ?>
            </h3>

            <p>
                <b>Rating:</b>
                ⭐ <?php echo $review['rating']; ?>/5
            </p>

            <p>
                <?php echo htmlspecialchars($review['comment']); ?>
            </p>

            <small>
                <?php echo $review['created_at']; ?>
            </small>

        </div>

        <br>

    <?php } ?>

<?php } else { ?>

    <p>No reviews yet for this car.</p>

<?php } ?>

</div>



<footer>

    🚗 DriveEase

    <br><br>

    © 2026 DriveEase. All Rights Reserved.

</footer>

</body>

</html>