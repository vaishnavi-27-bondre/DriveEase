<?php 

include "db.php"; 

$message = ""; 

if (isset($_POST['register'])) { 

    $name = $_POST['full_name']; 
    $email = $_POST['email']; 
    $mobile = $_POST['mobile']; 

    $password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password']; 

    // Check password and confirm password
    if ($password != $confirm_password) { 

        $message = "Passwords do not match"; 

    } else { 

        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT); 

        $query = "INSERT INTO users 
                  (full_name, email, mobile, password) 
                  VALUES ($1, $2, $3, $4)"; 

        $result = pg_query_params( 
            $conn, 
            $query, 
            array( 
                $name, 
                $email, 
                $mobile, 
                $hashed_password 
            ) 
        ); 

        if ($result) { 

            $message = "Registration successful! You can login now."; 

        } else { 

            $message = "Registration failed. Email may already exist."; 

        } 

    } 

} 

?> 

<!DOCTYPE html> 
<html> 

<head> 

    <title>DriveEase - Register</title> 

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

    <h1>Create Account</h1> 

    <p>Register with DriveEase</p> 

    <?php if ($message != "") { ?> 

        <p style="color:#087cf5;"> 
            <?php echo $message; ?> 
        </p> 

    <?php } ?> 


    <form method="POST"> 

        <label>Full Name</label> 

        <input type="text" 
               name="full_name" 
               placeholder="Enter your full name" 
               required> 


        <label>Email Address</label> 

        <input type="email" 
               name="email" 
               placeholder="Enter your email" 
               required> 


        <label>Mobile Number</label> 

        <input type="tel" 
               name="mobile" 
               placeholder="Enter your mobile number" 
               required> 


        <label>Password</label> 

        <input type="password" 
               name="password" 
               placeholder="Create password" 
               required> 


        <label>Confirm Password</label> 

        <input type="password" 
               name="confirm_password" 
               placeholder="Confirm your password" 
               required> 


        <label> 

            <input type="checkbox" 
                   name="terms" 
                   required 
                   style="width:auto;"> 

            I agree to the Terms and Conditions 

        </label> 

        <br><br> 


        <button class="btn" 
                type="submit" 
                name="register"> 

            Create Account 

        </button> 

    </form> 


    <hr> 

    <p> 
        Already have an account? 

        <a href="login.php"> 
            Login Here 
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