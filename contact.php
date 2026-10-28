<?php

include "db.php";

$message = "";

if (isset($_POST['send'])) {

    $name = $_POST['name'];

    $email = $_POST['email'];

    $subject = $_POST['subject'];

    $msg = $_POST['message'];


    $query = "INSERT INTO contact_messages
              (name, email, subject, message)
              VALUES ($1,$2,$3,$4)";


    $result = pg_query_params(
        $conn,
        $query,
        array(
            $name,
            $email,
            $subject,
            $msg
        )
    );


    if ($result) {

        $message = "Message sent successfully!";

    } else {

        $message = "Message could not be sent.";

    }

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>DriveEase - Contact Us</title>

    <link rel="stylesheet"
          href="css/style.css">

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

    <div class="card">

        <h1>Contact Us</h1>

        <p>
            We are here to help you.
        </p>

        <h2>Get In Touch</h2>

        <p>
            📍 Pune, Maharashtra, India
        </p>

        <p>
            📞 +91 98765 43210
        </p>

        <p>
            ✉️ info@driveease.com
        </p>

    </div>


    <div class="form-box">

        <h2>Send Us a Message</h2>


        <?php if ($message != "") { ?>

            <p style="color:green;">
                <?php echo $message; ?>
            </p>

        <?php } ?>


        <form method="POST">


            <label>Your Name</label>

            <input type="text"
                   name="name"
                   placeholder="Enter your name"
                   required>


            <label>Email Address</label>

            <input type="email"
                   name="email"
                   placeholder="Enter your email"
                   required>


            <label>Subject</label>

            <input type="text"
                   name="subject"
                   placeholder="Enter subject"
                   required>


            <label>Message</label>

            <textarea name="message"
                      rows="5"
                      placeholder="Write your message"
                      required></textarea>


            <button class="btn"
                    type="submit"
                    name="send">

                Send Message

            </button>

        </form>

    </div>

</div>


<footer>

    🚗 DriveEase

    <br><br>

    © 2026 DriveEase. All Rights Reserved.

</footer>

</body>

</html>