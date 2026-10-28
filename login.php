<?php

session_start();

include "db.php";

$message = "";

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = $1";

    $result = pg_query_params(
        $conn,
        $query,
        array($email)
    );

    if (pg_num_rows($result) == 1) {

        $user = pg_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] =
                $user['user_id'];

            $_SESSION['user_name'] =
                $user['full_name'];

            $_SESSION['role'] =
                $user['role'];

           if ($user['role'] == 'admin') 
		{

         	 header("Location: admin.php");

	   } else {

    		header("Location: dashboard.php");

	}

exit();

        } else {

            $message = "Invalid password";

        }

    } else {

        $message = "User not found";

    }

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>DriveEase - Login</title>

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
    <a href="login.php">Login</a>
    <a href="register.php">Register</a>

</div>


<div class="form-box">

    <h1>Welcome Back!</h1>

    <p>Login to your DriveEase account</p>


    <?php if ($message != "") { ?>

        <p style="color:red;">
            <?php echo $message; ?>
        </p>

    <?php } ?>


    <form method="POST">

        <label>Email Address</label>

        <input type="email"
               name="email"
               placeholder="Enter your email"
               required>


        <label>Password</label>

        <input type="password"
               name="password"
               placeholder="Enter your password"
               required>


        <button class="btn"
                type="submit"
                name="login">

            Login

        </button>
<p>
    <a href="forgot_password.php">
        Forgot Password?
    </a>
</p>
    </form>


    <hr>

    <p>
        Don't have an account?

        <a href="register.php">
            Register Now
        </a>
    </p>

</div>


<footer>

    🚗 DriveEase

    <br><br>

    © 2026 DriveEase. All Rights Reserved.

</footer>

</body>

</html>