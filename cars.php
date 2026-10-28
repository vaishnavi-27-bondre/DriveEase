<?php

include "db.php";

$search = "";
$type = "";
$fuel = "";
$transmission = "";

if (isset($_GET['search'])) {
    $search = $_GET['search'];
}

if (isset($_GET['type'])) {
    $type = $_GET['type'];
}

if (isset($_GET['fuel'])) {
    $fuel = $_GET['fuel'];
}

if (isset($_GET['transmission'])) {
    $transmission = $_GET['transmission'];
}

$query = "SELECT 
            cars.*,
            COALESCE(AVG(reviews.rating), 0) AS average_rating,
            COUNT(reviews.review_id) AS review_count
          FROM cars
          LEFT JOIN bookings
          ON cars.car_id = bookings.car_id
          LEFT JOIN reviews
          ON bookings.booking_id = reviews.booking_id
          WHERE 1=1";

$params = array();
$count = 0;

if ($search != "") {
    $count++;
    $query .= " AND car_name ILIKE $" . $count;
    $params[] = "%" . $search . "%";
}

if ($type != "") {
    $count++;
    $query .= " AND car_type = $" . $count;
    $params[] = $type;
}

if ($fuel != "") {
    $count++;
    $query .= " AND fuel_type = $" . $count;
    $params[] = $fuel;
}

if ($transmission != "") {
    $count++;
    $query .= " AND transmission = $" . $count;
    $params[] = $transmission;
}

$query .= " GROUP BY cars.car_id ORDER BY cars.car_id";

$result = pg_query_params($conn, $query, $params);

?>

<!DOCTYPE html>
<html>

<head>

    <title>DriveEase - Available Cars</title>

    <link rel="stylesheet" href="css
/style.css">

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

    <h1>Available Cars</h1>

    <p>Search and choose the perfect car for your journey.</p>


    <!-- SEARCH AND FILTER -->

    <div class="card">

        <h2>Search Cars</h2>

        <form method="GET">

            <label>Search Car</label>

            <input type="text"
                   name="search"
                   placeholder="Enter car name"
                   value="<?php echo htmlspecialchars($search); ?>">


            <label>Car Type</label>

            <select name="type">

                <option value="">All Types</option>

                <option value="Hatchback"
                    <?php if ($type == "Hatchback") echo "selected"; ?>>
                    Hatchback
                </option>

                <option value="Sedan"
                    <?php if ($type == "Sedan") echo "selected"; ?>>
                    Sedan
                </option>

                <option value="SUV"
                    <?php if ($type == "SUV") echo "selected"; ?>>
                    SUV
                </option>

            </select>


            <label>Fuel Type</label>

            <select name="fuel">

                <option value="">All Fuel Types</option>

                <option value="Petrol"
                    <?php if ($fuel == "Petrol") echo "selected"; ?>>
                    Petrol
                </option>

                <option value="Diesel"
                    <?php if ($fuel == "Diesel") echo "selected"; ?>>
                    Diesel
                </option>

                <option value="Electric"
                    <?php if ($fuel == "Electric") echo "selected"; ?>>
                    Electric
                </option>

            </select>


            <label>Transmission</label>

            <select name="transmission">

                <option value="">All Transmissions</option>

                <option value="Manual"
                    <?php if ($transmission == "Manual") echo "selected"; ?>>
                    Manual
                </option>

                <option value="Automatic"
                    <?php if ($transmission == "Automatic") echo "selected"; ?>>
                    Automatic
                </option>

            </select>

            <br><br>

            <button class="btn" type="submit">
                🔍 Search
            </button>

            <a href="cars.php" class="btn">
                Reset
            </a>

        </form>

    </div>


    <br>


    <!-- CAR LIST -->

    <div class="car-grid">

        <?php

        if (pg_num_rows($result) > 0) {

            while ($car = pg_fetch_assoc($result)) {

        ?>

        <div class="car-card">

            <img src="images/<?php echo $car['image']; ?>">


            <h2>
                <?php echo htmlspecialchars($car['car_name']); ?>
            </h2>
	<p>
    <b>Rating:</b>

    <?php if ($car['review_count'] > 0) { ?>

        <span class="price">
            ⭐ <?php echo number_format($car['average_rating'], 1); ?>/5
        </span>

        <small>
            (<?php echo $car['review_count']; ?> reviews)
        </small>

    <?php } else { ?>

        <span>
            No reviews yet
        </span>

    <?php } ?>
</p>


            <p>
                <b>Type:</b>
                <?php echo htmlspecialchars($car['car_type']); ?>
            </p>


            <p>
                <b>Fuel:</b>
                <?php echo htmlspecialchars($car['fuel_type']); ?>
            </p>


            <p>
                <b>Seats:</b>
                <?php echo $car['seats']; ?>
            </p>


            <p>
                <b>Transmission:</b>
                <?php echo htmlspecialchars($car['transmission']); ?>
            </p>


            <p>

                <b>Price/Day:</b>

                <span class="price">

                    ₹<?php echo $car['price_day']; ?>

                </span>

            </p>


            <p>

                <b>Status:</b>

                <?php

                if ($car['status'] == "Available") {

                ?>

                    <span class="available">
                        🟢 Available
                    </span>

                <?php

                } else {

                ?>

                    <span class="booked">
                        🔴 <?php echo $car['status']; ?>
                    </span>

                <?php

                }

                ?>

            </p>


            <a class="btn"
               href="carsdetails.php?id=<?php echo $car['car_id']; ?>">

                View Details

            </a>


            <?php

            if ($car['status'] == "Available") {

            ?>

                <a class="btn"
                   href="booking.php?car_id=<?php echo $car['car_id']; ?>">

                    Book Now

                </a>

            <?php

            }

            ?>

        </div>

        <?php

            }

        } else {

        ?>

            <div class="card">

                <h2>No Cars Found</h2>

                <p>
                    Sorry, no cars match your search.
                </p>

                <a href="cars.php" class="btn">
                    View All Cars
                </a>

            </div>

        <?php

        }

        ?>

    </div>

</div>


<footer>

    🚗 DriveEase - Car Rental System

    <br><br>

    © 2026 DriveEase. All Rights Reserved.

</footer>

</body>

</html>