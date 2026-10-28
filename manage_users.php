<?php

session_start();

include "db.php";


if (!isset($_SESSION['user_id']) ||
    $_SESSION['role'] != 'admin') {

    die("Access Denied");

}


$result = pg_query(
    $conn,
    "SELECT user_id, full_name, email,
            mobile, role, created_at
     FROM users
     ORDER BY user_id DESC"
);

?>

<!DOCTYPE html>
<html>

<head>

    <title>Manage Users</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<div class="navbar">

    <div class="logo">
        🚗 DriveEase Admin
    </div>

    <a href="admin.php">Dashboard</a>

    <a href="manage_users.php">
        Users
    </a>

    <a href="manage_bookings.php">
        Bookings
    </a>

    <a href="logout.php">
        Logout
    </a>

</div>


<div class="container">

    <h1>Registered Users</h1>


    <table>

        <tr>

            <th>ID</th>

            <th>Name</th>

            <th>Email</th>

            <th>Mobile</th>

            <th>Role</th>

            <th>Created</th>

        </tr>


        <?php while ($user =
            pg_fetch_assoc($result)) { ?>

        <tr>

            <td>
                <?php echo $user['user_id']; ?>
            </td>

            <td>
                <?php echo $user['full_name']; ?>
            </td>

            <td>
                <?php echo $user['email']; ?>
            </td>

            <td>
                <?php echo $user['mobile']; ?>
            </td>

            <td>
                <?php echo $user['role']; ?>
            </td>

            <td>
                <?php echo $user['created_at']; ?>
            </td>

        </tr>

        <?php } ?>

    </table>

</div>

</body>

</html>