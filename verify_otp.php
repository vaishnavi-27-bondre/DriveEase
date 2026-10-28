<?php
include("db.php");

$email = $_GET['email'] ?? "";
$message = "";

if (isset($_POST['verify'])) {

    $email = $_POST['email'];
    $otp = $_POST['otp'];

    $query = "SELECT * FROM password_resets
              WHERE email = $1
              AND otp = $2
              AND expires_at > CURRENT_TIMESTAMP
              ORDER BY id DESC
              LIMIT 1";

    $result = pg_query_params(
    $conn,
    $query,
    array($email, (string)$otp)
);

    if (pg_num_rows($result) > 0) {

        // OTP correct
        header("Location: reset_password.php?email=" . urlencode($email));
        exit();

    } else {

        $message = "Invalid or expired OTP.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Verify OTP | DriveEase</title>

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
        }

        .brand {
            font-size: 28px;
            font-weight: bold;
            color: #020b14;
            margin-top: 5px;
        }

        .brand span {
            color: #087cf5;
        }

        .line {
            width: 60px;
            height: 4px;
            background: #087cf5;
            margin: 12px auto 20px;
            border-radius: 10px;
        }

        h2 {
            color: #222;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #777;
            font-size: 14px;
            margin-bottom: 25px;
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
        }

        button:hover {
            background: #005fc7;
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
            font-weight: bold;
            font-size: 14px;
        }

        .footer {
            margin-top: 25px;
            font-size: 12px;
            color: #999;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="logo">🔐</div>

    <div class="brand">
        Drive<span>Ease</span>
    </div>

    <div class="line"></div>

    <h2>Verify OTP</h2>

    <p class="subtitle">
        Enter the OTP sent to your registered email.
    </p>

    <form method="POST">

        <input type="hidden" name="email"
               value="<?php echo htmlspecialchars($email); ?>">

        <div class="input-box">

            <label>Enter OTP</label>

            <input
                type="text"
                name="otp"
                maxlength="6"
                placeholder="Enter 6-digit OTP"
                required
            >

        </div>

        <button type="submit" name="verify">
            Verify OTP
        </button>

    </form>

    <?php if ($message != "") { ?>

        <p class="message">
            <?php echo $message; ?>
        </p>

    <?php } ?>

    <a href="forgot_password.php" class="back">
        ← Back
    </a>

    <div class="footer">
        DriveEase Car Rental System
    </div>

</div>

</body>

</html>