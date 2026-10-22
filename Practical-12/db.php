<?php
mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "event_management"
);

if ($conn->connect_error) {
    die("Database connection failed. Start MySQL in XAMPP.");
}

$conn->set_charset("utf8mb4");
?>