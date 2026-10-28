<?php

session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: manage_cars.php");
    exit();
}

$car_id = $_GET['id'];

/* Check whether this car has bookings */

$check_query = "SELECT COUNT(*) FROM bookings WHERE car_id = $1";

$check_result = pg_query_params(
    $conn,
    $check_query,
    [$car_id]
);

$booking_count = pg_fetch_result($check_result, 0, 0);


/* Do not delete if bookings exist */

if ($booking_count > 0) {

    echo "<h2>Cannot Delete This Car</h2>";
    echo "<p>This car has existing bookings.</p>";
    echo "<a href='manage_cars.php'>Back to Manage Cars</a>";

    exit();
}


/* Delete car */

$query = "DELETE FROM cars WHERE car_id = $1";

$result = pg_query_params(
    $conn,
    $query,
    [$car_id]
);

if ($result) {

    header("Location: manage_cars.php");
    exit();

} else {

    echo "Error deleting car.";

}

?>