<?php
mysqli_report(MYSQLI_REPORT_OFF);
$conn = new mysqli("localhost", "root", "", "student_management");

if ($conn->connect_error) {
    die("Database connection failed. Check that MySQL is running and db.php credentials are correct.");
}
$conn->set_charset("utf8mb4");
?>
