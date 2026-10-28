<?php

$host = "localhost";
$port = "5432";
$dbname = "DriveEase";
$user = "postgres";
$password = "akki0121";

$conn = pg_connect(
    "host=$host port=$port dbname=$dbname user=$user password=$password"
);

if (!$conn) {
    die("Database connection failed");
}

?>