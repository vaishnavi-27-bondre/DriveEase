<?php
include("db.php");

$message = "";

if (isset($_POST['submit'])) {

    $email = $_POST['email'];

    $query = "SELECT * FROM users WHERE email = $1";
    $result = pg_query_params($conn, $query, array($email));

    if (pg_num_rows($result) > 0) {

        // Generate OTP
        $otp = rand(100000, 999999);

        // OTP valid for 10 minutes
$insert = "INSERT INTO password_resets (email, otp, expires_at)
           VALUES ($1, $2, CURRENT_TIMESTAMP + INTERVAL '10 minutes')";

pg_query_params(
    $conn,
    $insert,
    array($email, $otp)
);
        // For project/demo purpose
        echo "<script>
                alert('Your OTP is: $otp');
                window.location='verify_otp.php?email=" . urlencode($email) . "';
              </script>";

    } else {
        $message = "Email not registered.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Forgot Password | DriveEase</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #020b14, #061b2e, #087cf5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 400px;
            background: white;
            padding: 40px;
            border-radius: 18px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.35);
            text-align: center;
        }

        .logo {
            font-size: 45px;
            margin-bottom: 8px;
        }

        .brand {
            font-size: 28px;
            font-weight: bold;
            color: #020b14;
            margin-bottom: 8px;
        }

        .brand span {
            color: #087cf5;
        }

        h2 {
            color: #222;
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .input-box {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        input:focus {
            border-color: #087cf5;
            box-shadow: 0 0 5px rgba(8,124,245,0.3);
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #087cf5;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #005fc7;
            transform: translateY(-1px);
        }

        .message {
            color: #d62828;
            margin-top: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        .back {
            display: inline-block;
            margin-top: 22px;
            color: #087cf5;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
        }

        .back:hover {
            text-decoration: underline;
        }

        .car-line {
            width: 60px;
            height: 4px;
            background: #087cf5;
            margin: 12px auto 20px;
            border-radius: 10px;
        }

        .footer-text {
            margin-top: 25px;
            font-size: 12px;
            color: #999;
        }

    </style>

</head>

<body>

    <div class="container">

        <div class="logo">🚗</div>

        <div class="brand">
            Drive<span>Ease</span>
        </div>

        <div class="car-line"></div>

        <h2>Forgot Password?</h2>

        <p class="subtitle">
            Enter your registered email address to reset your password.
        </p>

        <form method="POST">

            <div class="input-box">

                <label>Registered Email</label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email"
                    required
                >

            </div>

            <button type="submit" name="submit">
                Send OTP
            </button>

        </form>

        <?php if ($message != "") { ?>

            <p class="message">
                <?php echo $message; ?>
            </p>

        <?php } ?>

        <a href="login.php" class="back">
            ← Back to Login
        </a>

        <div class="footer-text">
            DriveEase Car Rental System
        </div>

    </div>

</body>

</html>