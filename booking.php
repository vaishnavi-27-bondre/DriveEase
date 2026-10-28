<?php

session_start();

include "db.php";


if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");

    exit();

}


$selected_car = "";

if (isset($_GET['car_id'])) {

    $selected_car = $_GET['car_id'];

}


$result = pg_query(
    $conn,
    "SELECT * FROM cars WHERE status='Available'"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>DriveEase - Book Car</title>

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
    <a href="my_bookings.php">My Bookings</a>
    <a href="logout.php">Logout</a>

</div>


<div class="form-box">

    <h1>Book Your Car</h1>

    <p>Choose your car and rental option</p>


    <form action="payment.php"
          method="POST">


        <label>Select Car</label>

<input type="text"
       id="car_search"
       placeholder="Type car name..."
       autocomplete="off"
       required>

<input type="hidden"
       name="car_id"
       id="car_id">

<div id="car_suggestions"></div>

        <label>Rental Type</label>

        <select name="rental_type" required>

            <option value="">
                Select Rental Type
            </option>

            <option value="Day">
                Day Wise
            </option>

            <option value="Hour">
                Hour Wise
            </option>

            <option value="KM">
                KM Wise
            </option>

        </select>


        <label>Booking Date</label>

        <input type="date"
       name="booking_date"
       min="<?php echo date('Y-m-d'); ?>"
       required>


        <label>Rental Duration</label>

        <input type="number"
               name="duration"
               min="1"
               placeholder="Enter duration"
               required>


        <label>Customer Name</label>

        <input type="text"
               name="customer_name"
               value="<?php
               echo $_SESSION['user_name'];
               ?>"
               required>


        <label>Mobile Number</label>

        <input type="tel"
               name="mobile"
               placeholder="Enter mobile number"
               required>


        <button class="btn"
                type="submit"
                name="continue_payment">

            Continue to Payment

        </button>

    </form>

</div>


<footer>

    🚗 DriveEase

    <br><br>

    © 2026 DriveEase. All Rights Reserved.

</footer>

<script>

const carInput = document.getElementById("car_search");
const carIdInput = document.getElementById("car_id");
const suggestions = document.getElementById("car_suggestions");

const cars = [
<?php

pg_result_seek($result, 0);

while ($car = pg_fetch_assoc($result)) {

    echo "{id: " . $car['car_id'] .
         ", name: '" .
         addslashes($car['car_name']) .
         "'},";

}

?>
];

carInput.addEventListener("input", function () {

    const search = this.value.toLowerCase().trim();

    suggestions.innerHTML = "";

    if (search === "") {
        return;
    }

    const filteredCars = cars.filter(car =>
        car.name.toLowerCase().includes(search)
    );

    filteredCars.forEach(car => {

        const div = document.createElement("div");

        div.textContent = car.name;

        div.style.padding = "10px";
        div.style.cursor = "pointer";
        div.style.background = "white";
        div.style.border = "1px solid #ddd";

        div.onclick = function () {

            carInput.value = car.name;
            carIdInput.value = car.id;
            suggestions.innerHTML = "";

        };

        suggestions.appendChild(div);

    });

});

</script>

</body>
</html>