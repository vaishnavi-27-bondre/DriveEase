<?php
include("db.php");

$email = $_GET['email'] ?? "";
$message = "";
$success = "";

if (isset($_POST['reset'])) {

    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password != $confirm_password) {

        $message = "Passwords do not match.";

    } else {

        // Update password
        // Update password
$hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

$query = "UPDATE users
          SET password = $1
          WHERE email = $2";

$result = pg_query_params(
    $conn,
    $query,
    array($hashed_password, $email)
);

        if ($result) {

            // Delete used OTP
            $delete = "DELETE FROM password_resets WHERE email = $1";
            pg_query_params($conn, $delete, array($email));

            $success = "Password reset successfully!";

        } else {

            $message = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Reset Password | DriveEase</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(
                135deg,
                #020b14,
                #061b2e,
                #087cf5
            );

            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            width: 400px;
            background: white;
            padding: 40px;
            border-radius: 18px;

            box-shadow:
                0 15px 40px rgba(0,0,0,0.35);

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
            margin-bottom: 18px;
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

            box-shadow:
                0 0 5px rgba(8,124,245,0.3);
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

        .success {
            color: #16803c;
            margin-top: 15px;
            font-size: 15px;
            font-weight: bold;
        }

        .login {
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

    <div class="logo">🔑</div>

    <div class="brand">
        Drive<span>Ease</span>
    </div>

    <div class="line"></div>

    <h2>Reset Password</h2>

    <p class="subtitle">
        Create a new password for your account.
    </p>

    <?php if ($success == "") { ?>

    <form method="POST">

        <input type="hidden"
               name="email"
               value="<?php echo htmlspecialchars($email); ?>">

        <div class="input-box">

            <label>New Password</label>

            <input
                type="password"
                name="new_password"
                placeholder="Enter new password"
                required
            >

        </div>

        <div class="input-box">

            <label>Confirm Password</label>

            <input
                type="password"
                name="confirm_password"
                placeholder="Confirm new password"
                required
            >

        </div>

        <button type="submit" name="reset">
            Reset Password
        </button>

    </form>

    <?php } ?>

    <?php if ($message != "") { ?>

        <p class="message">
            <?php echo $message; ?>
        </p>

    <?php } ?>

    <?php if ($success != "") { ?>

        <p class="success">
            <?php echo $success; ?>
        </p>

        <a href="login.php" class="login">
            Go to Login →
        </a>

    <?php } ?>

    <div class="footer">
        DriveEase Car Rental System
    </div>

</div>

</body>

</html>