<?php

session_start();

include "db.php";


if (!isset($_SESSION['user_id']) ||
    $_SESSION['role'] != 'admin') {

    die("Access Denied");

}


$message = "";


if (isset($_POST['add'])) {

    $name = $_POST['car_name'];

    $type = $_POST['car_type'];

    $fuel = $_POST['fuel_type'];

    $seats = $_POST['seats'];

    $transmission = $_POST['transmission'];

    $price_hour = $_POST['price_hour'];

    $price_km = $_POST['price_km'];

    $price_day = $_POST['price_day'];

    $image = $_POST['image'];


    $query = "INSERT INTO cars
    (car_name, car_type, fuel_type, seats,
    transmission, price_hour, price_km,
    price_day, image)
    VALUES
    ($1,$2,$3,$4,$5,$6,$7,$8,$9)";


    $result = pg_query_params(
        $conn,
        $query,
        array(
            $name,
            $type,
            $fuel,
            $seats,
            $transmission,
            $price_hour,
            $price_km,
            $price_day,
            $image
        )
    );


    if ($result) {

        $message = "Car added successfully!";

    } else {

        $message = "Car could not be added.";

    }

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Add Car</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="navbar">

    <div class="logo">
        🚗 DriveEase Admin
    </div>

    <a href="admin.php">Dashboard</a>

    <a href="cars.php">Cars</a>

    <a href="manage_bookings.php">
        Bookings
    </a>

    <a href="logout.php">Logout</a>

</div>


<div class="form-box">

    <h1>Add New Car</h1>


    <?php if ($message != "") { ?>

        <p style="color:green;">
            <?php echo $message; ?>
        </p>

    <?php } ?>


    <form method="POST">


        <label>Car Name</label>

        <input type="text"
               name="car_name"
               required>


        <label>Car Type</label>

        <input type="text"
               name="car_type"
               required>


        <label>Fuel Type</label>

        <input type="text"
               name="fuel_type"
               required>


        <label>Seats</label>

        <input type="number"
               name="seats"
               required>


        <label>Transmission</label>

        <input type="text"
               name="transmission"
               required>


        <label>Price Per Hour</label>

        <input type="number"
               name="price_hour"
               required>


        <label>Price Per KM</label>

        <input type="number"
               name="price_km"
               required>


        <label>Price Per Day</label>

        <input type="number"
               name="price_day"
               required>


        <label>Image File Name</label>

        <input type="text"
               name="image"
               placeholder="example.jpg"
               required>


        <button class="btn"
                type="submit"
                name="add">

            Add Car

        </button>


    </form>

</div>

</body>

</html>