<?php

session_start();

include "db.php";


if (!isset($_SESSION['user_id']) ||
    $_SESSION['role'] != 'admin') {

    die("Access Denied");

}


$result = pg_query(
    $conn,
    "SELECT * FROM contact_messages
     ORDER BY message_id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Messages - DriveEase</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="navbar">

    <div class="logo">
        🚗 DriveEase Admin
    </div>

    <a href="admin.php">
        Dashboard
    </a>

    <a href="manage_users.php">
        Users
    </a>

    <a href="manage_bookings.php">
        Bookings
    </a>

    <a href="messages.php">
        Messages
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>


<div class="container">

    <h1>Contact Messages</h1>


    <table>

        <tr>

            <th>ID</th>

            <th>Name</th>

            <th>Email</th>

            <th>Subject</th>

            <th>Message</th>

            <th>Date</th>

        </tr>


        <?php while ($msg =
            pg_fetch_assoc($result)) { ?>

        <tr>

            <td>
                <?php echo $msg['message_id']; ?>
            </td>

            <td>
                <?php echo $msg['name']; ?>
            </td>

            <td>
                <?php echo $msg['email']; ?>
            </td>

            <td>
                <?php echo $msg['subject']; ?>
            </td>

            <td>
                <?php echo $msg['message']; ?>
            </td>

            <td>
                <?php echo $msg['created_at']; ?>
            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>

</html>