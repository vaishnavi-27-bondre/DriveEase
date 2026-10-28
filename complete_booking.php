<?php

session_start();
include "db.php";


// Only admin can access
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}


if (!isset($_GET['id'])) {
    header("Location: manage_bookings.php");
    exit();
}


$booking_id = $_GET['id'];


// Change Confirmed to Completed
$query = "
UPDATE bookings
SET status = 'Completed'
WHERE booking_id = $1
AND status = 'Confirmed'
";


$result = pg_query_params(
    $conn,
    $query,
    array($booking_id)
);
if ($result && pg_affected_rows($result) > 0) {

    $car_query = "UPDATE cars
                  SET status = 'Available'
                  WHERE car_id = (
                      SELECT car_id
                      FROM bookings
                      WHERE booking_id = $1
                  )";

    pg_query_params(
        $conn,
        $car_query,
        array($booking_id)
    );
}


header("Location: manage_bookings.php");
exit();

?>