<?php
session_start();
include "db.php";

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

$query = "SELECT * FROM cars ORDER BY car_id";
$result = pg_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Cars - DriveEase</title>
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

<div class="container">

    <h1>Manage Cars</h1>

    <a href="add_car.php" class="btn">+ Add New Car</a>

    <br><br>

    <table>

        <tr>
            <th>ID</th>
            <th>Car</th>
            <th>Type</th>
            <th>Fuel</th>
            <th>Seats</th>
            <th>Transmission</th>
            <th>Price/Day</th>
            <th>Status</th>
            <th>Action</th>
        </tr>

        <?php
        while ($car = pg_fetch_assoc($result)) {
        ?>

        <tr>

            <td><?php echo $car['car_id']; ?></td>

            <td><?php echo htmlspecialchars($car['car_name']); ?></td>

            <td><?php echo htmlspecialchars($car['car_type']); ?></td>

            <td><?php echo htmlspecialchars($car['fuel_type']); ?></td>

            <td><?php echo $car['seats']; ?></td>

            <td><?php echo htmlspecialchars($car['transmission']); ?></td>

            <td>₹<?php echo $car['price_day']; ?></td>

            <td><?php echo htmlspecialchars($car['status']); ?></td>

            <td>
                <a href="edit_car.php?id=<?php echo $car['car_id']; ?>"
                   class="btn">Edit</a>

                <a href="delete_car.php?id=<?php echo $car['car_id']; ?>"
                   class="btn"
                   onclick="return confirm('Are you sure you want to delete this car?');">
                   Delete
                </a>
            </td>

        </tr>

        <?php
        }
        ?>

    </table>

</div>

</body>
</html>