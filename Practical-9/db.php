<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "user_registration";

$conn = new mysqli(
    $servername,
    $username,
    $password,
    $database
);

if ($conn->connect_error) {
    die("Database connection failed.");
}

$conn->set_charset("utf8mb4");

?>