<?php
require_once "auth.php";
requireAdmin();
require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$id = filter_input(INPUT_POST, "event_id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    header(
        "Location: index.php?message=" .
        urlencode("Invalid event ID.")
    );
    exit;
}

// Find the poster associated with this event.
$stmt = $conn->prepare(
    "SELECT poster FROM events WHERE event_id = ?"
);
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($poster);

$found = $stmt->fetch();
$stmt->close();

if (!$found) {
    header(
        "Location: index.php?message=" .
        urlencode("Event not found.")
    );
    exit;
}

// Delete the event using a prepared statement.
$stmt = $conn->prepare(
    "DELETE FROM events WHERE event_id = ?"
);
$stmt->bind_param("i", $id);

if ($stmt->execute() && $stmt->affected_rows > 0) {
    $stmt->close();

    // Remove the associated poster from the uploads folder.
    if (
        !empty($poster) &&
        is_file(__DIR__ . "/uploads/" . basename($poster))
    ) {
        unlink(__DIR__ . "/uploads/" . basename($poster));
    }

    header(
        "Location: index.php?message=" .
        urlencode("Event deleted successfully.")
    );
    exit;
}

$stmt->close();

header(
    "Location: index.php?message=" .
    urlencode("Failed to delete event.")
);
exit;
?>