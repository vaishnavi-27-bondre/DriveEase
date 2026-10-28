<?php

session_start();
include "db.php";


// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


// Check booking ID
if (!isset($_GET['id'])) {
    header("Location: my_bookings.php");
    exit();
}

$booking_id = $_GET['id'];


// Get completed booking
$query = "
SELECT
    bookings.booking_id,
    bookings.car_id,
    cars.car_name,
    bookings.booking_date,
    bookings.status
FROM bookings
JOIN cars
ON bookings.car_id = cars.car_id
WHERE bookings.booking_id = $1
AND bookings.user_id = $2
AND bookings.status = 'Completed'
";

$result = pg_query_params(
    $conn,
    $query,
    array($booking_id, $user_id)
);


// Check booking
if (pg_num_rows($result) == 0) {
    echo "<h2 style='text-align:center;'>Review is available only for completed bookings.</h2>";
    exit();
}

$booking = pg_fetch_assoc($result);


// Check whether review already exists
$check_query = "
SELECT review_id
FROM reviews
WHERE booking_id = $1
AND user_id = $2
";

$check_result = pg_query_params(
    $conn,
    $check_query,
    array($booking_id, $user_id)
);

if (pg_num_rows($check_result) > 0) {
    echo "<h2 style='text-align:center;'>You have already submitted a review.</h2>";
    exit();
}


// Submit review
if (isset($_POST['submit_review'])) {

    $rating = $_POST['rating'];
    $comment = $_POST['comment'];


    $insert_query = "
    INSERT INTO reviews
    (user_id, booking_id, rating, comment)
    VALUES ($1, $2, $3, $4)
    ";

    $insert_result = pg_query_params(
        $conn,
        $insert_query,
        array(
            $user_id,
            $booking_id,
            $rating,
            $comment
        )
    );


    if ($insert_result) {

        echo "<script>
                alert('Thank you for your review!');
                window.location='my_bookings.php';
              </script>";

        exit();

    } else {

        echo "<p style='color:red;text-align:center;'>
                Review submission failed.
              </p>";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Give Review - DriveEase</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .review-box {
            width: 500px;
            max-width: 95%;
            margin: 60px auto;
            background: white;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.1);
        }

        .review-box h1 {
            text-align: center;
            color: #087cf5;
        }

        .car-name {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .review-box label {
            font-weight: bold;
        }

        .review-box select,
        .review-box textarea {
            width: 100%;
            padding: 12px;
            margin-top: 8px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .review-box textarea {
            height: 120px;
            resize: none;
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


<div class="review-box">

    <h1>⭐ Give Your Review</h1>

    <p class="car-name">
        <?php echo $booking['car_name']; ?>
    </p>


    <form method="POST">


        <label>Rating</label>

        <select name="rating" required>

            <option value="">
                Select Rating
            </option>

            <option value="5">
                ⭐⭐⭐⭐⭐ Excellent
            </option>

            <option value="4">
                ⭐⭐⭐⭐ Very Good
            </option>

            <option value="3">
                ⭐⭐⭐ Good
            </option>

            <option value="2">
                ⭐⭐ Average
            </option>

            <option value="1">
                ⭐ Poor
            </option>

        </select>


        <label>Comment</label>

        <textarea
            name="comment"
            placeholder="Write your experience..."
            required
        ></textarea>


        <button
            type="submit"
            name="submit_review"
            class="btn"
        >
            Submit Review
        </button>


    </form>

</div>


</body>

</html>