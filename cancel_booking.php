<?php

session_start();
include "db.php";

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: my_bookings.php");
    exit();
}

$booking_id = $_GET['id'];
$user_id = $_SESSION['user_id'];


// Cancel booking
$query = "UPDATE bookings
          SET status = 'Cancelled'
          WHERE booking_id = $1
          AND user_id = $2
          AND status = 'Confirmed'";

$result = pg_query_params(
    $conn,
    $query,
    array($booking_id, $user_id)
);


// If booking cancelled successfully
if ($result && pg_affected_rows($result) > 0) {

    // Cancel payment
    $payment_query = "UPDATE payments
                      SET payment_status = 'Cancelled'
                      WHERE booking_id = $1";

    pg_query_params(
        $conn,
        $payment_query,
        array($booking_id)
    );

    header("Location: my_bookings.php");
    exit();

} else {

    echo "Booking cancellation failed.";

}

?>