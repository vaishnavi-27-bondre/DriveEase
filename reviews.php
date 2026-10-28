<?php

session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$query = "
SELECT
    reviews.review_id,
    users.full_name,
    cars.car_name,
    reviews.rating,
    reviews.comment,
    reviews.created_at
FROM reviews
JOIN users
ON reviews.user_id = users.user_id
JOIN bookings
ON reviews.booking_id = bookings.booking_id
JOIN cars
ON bookings.car_id = cars.car_id
ORDER BY reviews.review_id DESC
";

$result = pg_query($conn, $query);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Customer Reviews - DriveEase</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        .reviews-container {
            width: 95%;
            max-width: 1100px;
            margin: 40px auto;
        }

        .review-card {
            background: white;
            padding: 25px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .rating {
            color: #f5a623;
            font-size: 20px;
            margin: 10px 0;
        }

        .customer {
            font-weight: bold;
            font-size: 18px;
        }

        .car {
            color: #087cf5;
            font-weight: bold;
        }

        .date {
            color: #777;
            font-size: 13px;
        }

    </style>

</head>

<body>

<div class="navbar">

    <div class="logo">
        🚗 DriveEase Admin
    </div>

    <a href="admin.php">Dashboard</a>
    <a href="manage_cars.php">Manage Cars</a>
    <a href="manage_bookings.php">Manage Bookings</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="reviews.php">Reviews</a>
    <a href="messages.php">Messages</a>
    <a href="logout.php">Logout</a>

</div>


<div class="reviews-container">

    <h1>Customer Reviews</h1>

    <br>

    <?php if (pg_num_rows($result) == 0) { ?>

        <div class="card">
            <h3>No reviews available.</h3>
        </div>

    <?php } ?>


    <?php while ($review = pg_fetch_assoc($result)) { ?>

        <div class="review-card">

            <div class="customer">
                <?php echo $review['full_name']; ?>
            </div>

            <div class="car">
                🚗 <?php echo $review['car_name']; ?>
            </div>

            <div class="rating">

                <?php

                for ($i = 1; $i <= 5; $i++) {

                    if ($i <= $review['rating']) {
                        echo "★";
                    } else {
                        echo "☆";
                    }

                }

                ?>

            </div>

            <p>
                <?php echo $review['comment']; ?>
            </p>

            <div class="date">

                <?php echo $review['created_at']; ?>

            </div>

        </div>

    <?php } ?>

</div>

</body>

</html>