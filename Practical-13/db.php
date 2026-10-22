
<?php
mysqli_report(MYSQLI_REPORT_OFF);

$conn = new mysqli("localhost", "root", "", "practical13");

if ($conn->connect_error) {
    http_response_code(500);
    header("Content-Type: application/json; charset=UTF-8");

    echo json_encode([
        "success" => false,
        "message" => "Database connection failed. Check that MySQL is running and the database practical13 exists."
    ]);
    exit;
}

$conn->set_charset("utf8mb4");
?>
