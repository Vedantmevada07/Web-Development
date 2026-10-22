<?php
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php?type=error&message=" . urlencode("Invalid request. Use the Delete button."));
    exit;
}
$id = filter_input(INPUT_POST, "student_id", FILTER_VALIDATE_INT);
if (!$id || $id < 1) {
    header("Location: index.php?type=error&message=" . urlencode("Invalid student ID."));
    exit;
}

$stmt = $conn->prepare("DELETE FROM students WHERE student_id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    if ($stmt->affected_rows === 1) {
        $message = "Student deleted successfully.";
        $type = "success";
    } else {
        $message = "Failure: Student record was not found.";
        $type = "error";
    }
} else {
    $message = "Failure: Student could not be deleted.";
    $type = "error";
}
$stmt->close();
header("Location: index.php?type=" . $type . "&message=" . urlencode($message));
exit;
?>
