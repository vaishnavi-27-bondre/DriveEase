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

$query = "SELECT * FROM cars WHERE car_id = $1";
$result = pg_query_params($conn, $query, [$car_id]);

if (!$result || pg_num_rows($result) == 0) {
    echo "Car not found.";
    exit();
}

$car = pg_fetch_assoc($result);


if (isset($_POST['update'])) {

    $car_name = $_POST['car_name'];
    $car_type = $_POST['car_type'];
    $fuel_type = $_POST['fuel_type'];
    $seats = $_POST['seats'];
    $transmission = $_POST['transmission'];
    $price_hour = $_POST['price_hour'];
    $price_km = $_POST['price_km'];
    $price_day = $_POST['price_day'];
    $image = $_POST['image'];
    $status = $_POST['status'];

    $query = "UPDATE cars SET
              car_name = $1,
              car_type = $2,
              fuel_type = $3,
              seats = $4,
              transmission = $5,
              price_hour = $6,
              price_km = $7,
              price_day = $8,
              image = $9,
              status = $10
              WHERE car_id = $11";

    $result = pg_query_params(
        $conn,
        $query,
        [
            $car_name,
            $car_type,
            $fuel_type,
            $seats,
            $transmission,
            $price_hour,
            $price_km,
            $price_day,
            $image,
            $status,
            $car_id
        ]
    );

    if ($result) {
        header("Location: manage_cars.php");
        exit();
    } else {
        echo "Error updating car.";
    }
}

?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Car - DriveEase</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<div class="navbar">

    <div class="logo">🚗 DriveEase Admin</div>

    <a href="admin.php">Dashboard</a>
    <a href="manage_cars.php">Cars</a>
    <a href="add_car.php">Add Car</a>
    <a href="manage_bookings.php">Bookings</a>
    <a href="manage_users.php">Users</a>
    <a href="messages.php">Messages</a>
    <a href="logout.php">Logout</a>

</div>


<div class="form-box">

    <h2>Edit Car</h2>

    <form method="POST">

        <label>Car Name</label>
        <input type="text"
               name="car_name"
               value="<?php echo htmlspecialchars($car['car_name']); ?>"
               required>


        <label>Car Type</label>
        <input type="text"
               name="car_type"
               value="<?php echo htmlspecialchars($car['car_type']); ?>"
               required>


        <label>Fuel Type</label>
        <input type="text"
               name="fuel_type"
               value="<?php echo htmlspecialchars($car['fuel_type']); ?>"
               required>


        <label>Seats</label>
        <input type="number"
               name="seats"
               value="<?php echo $car['seats']; ?>"
               required>


        <label>Transmission</label>

        <select name="transmission">

            <option value="Manual"
                <?php if ($car['transmission'] == 'Manual') echo 'selected'; ?>>
                Manual
            </option>

            <option value="Automatic"
                <?php if ($car['transmission'] == 'Automatic') echo 'selected'; ?>>
                Automatic
            </option>

        </select>


        <label>Price Per Hour</label>

        <input type="number"
               name="price_hour"
               value="<?php echo $car['price_hour']; ?>"
               required>


        <label>Price Per KM</label>

        <input type="number"
               name="price_km"
               value="<?php echo $car['price_km']; ?>"
               required>


        <label>Price Per Day</label>

        <input type="number"
               name="price_day"
               value="<?php echo $car['price_day']; ?>"
               required>


        <label>Image</label>

        <input type="text"
               name="image"
               value="<?php echo htmlspecialchars($car['image']); ?>"
               required>


        <label>Status</label>

        <select name="status">

            <option value="Available"
                <?php if ($car['status'] == 'Available') echo 'selected'; ?>>
                Available
            </option>

            <option value="Booked"
                <?php if ($car['status'] == 'Booked') echo 'selected'; ?>>
                Booked
            </option>

        </select>


        <br><br>

        <button type="submit" name="update" class="btn">
            Update Car
        </button>

        <a href="manage_cars.php" class="btn">
            Cancel
        </a>

    </form>

</div>

</body>

</html>