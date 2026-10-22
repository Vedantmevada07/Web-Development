
<?php
mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli("localhost", "root", "", "practical14");

if ($conn->connect_error) {
    die("Database connection failed. Please check MySQL and database settings.");
}

$conn->set_charset("utf8mb4");
?>
