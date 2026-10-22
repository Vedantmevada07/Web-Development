
<?php
mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli("localhost", "root", "", "studenthub15");

if ($conn->connect_error) {
    http_response_code(500);
    exit("Database connection failed. Check MySQL and the studenthub15 database.");
}

$conn->set_charset("utf8mb4");
?>
