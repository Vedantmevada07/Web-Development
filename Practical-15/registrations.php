
<?php
require_once "auth.php";
require_once "db.php";
requireLogin();

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    verifyCsrf();

    if ($_SESSION["role"] !== "student") {
        http_response_code(403);
        exit("Only student accounts can register for events.");
    }

    $eventId = filter_var($_POST["event_id"] ?? null, FILTER_VALIDATE_INT);

    $userStmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $userStmt->bind_param("i", $_SESSION["user_id"]);
    $userStmt->execute();
    $user = $userStmt->get_result()->fetch_assoc();
    $userStmt->close();

    if (!$eventId || !$user) {
        $error = "Invalid event or user account.";
    } else {
        $studentStmt = $conn->prepare(
            "SELECT id FROM students WHERE email = ?"
        );
        $studentStmt->bind_param("s", $user["email"]);
        $studentStmt->execute();
        $student = $studentStmt->get_result()->fetch_assoc();
        $studentStmt->close();

        if (!$student) {
            $error = "No student record matches your account email. Ask an administrator to add your student record.";
        } else {
            $stmt = $conn->prepare(
                "INSERT INTO registrations (student_id, event_id)
                 SELECT ?, id FROM events WHERE id = ?"
            );
            $stmt->bind_param("ii", $student["id"], $eventId);

            if ($stmt->execute() && $stmt->affected_rows === 1) {
                $message = "Registration completed successfully.";
            } elseif ($stmt->errno === 1062) {
                $error = "You have already registered for this event.";
            } else {
                $error = "Registration failed. Please check that the event exists.";
            }
            $stmt->close();
        }
    }
}

if ($_SESSION["role"] === "admin") {
    $result = $conn->query(
        "SELECT s.name, s.email, e.event_name, r.registration_date
         FROM registrations r
         JOIN students s ON s.id = r.student_id
         JOIN events e ON e.id = r.event_id
         ORDER BY r.registration_date DESC"
    );
} else {
    $userStmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $userStmt->bind_param("i", $_SESSION["user_id"]);
    $userStmt->execute();
    $user = $userStmt->get_result()->fetch_assoc();
    $userStmt->close();

    $result = false;
    if ($user) {
        $stmt = $conn->prepare(
            "SELECT s.name, s.email, e.event_name, r.registration_date
             FROM registrations r
             JOIN students s ON s.id = r.student_id
             JOIN events e ON e.id = r.event_id
             WHERE s.email = ?
             ORDER BY r.registration_date DESC"
        );
        $stmt->bind_param("s", $user["email"]);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Registrations | StudentHub</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="index.php">StudentHub</a>
    <button class="menu-toggle" id="menuToggle" type="button">Menu</button>
    <nav id="mainNav">
        <a href="dashboard.php">Dashboard</a>
        <a href="students.php">Students</a>
        <a href="events.php">Events</a>
        <a href="registrations.php">Registrations</a>
        <a href="announcements.php">Announcements</a>
        <?php if ($_SESSION["role"] === "admin"): ?><a href="users.php">Manage Users</a><?php endif; ?>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="container section">
    <h1>Event Registrations</h1>
    <?php if ($message): ?><p class="success"><?= h($message) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Student</th><th>Email</th><th>Event</th><th>Date</th></tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= h($row["name"]) ?></td>
                        <td><?= h($row["email"]) ?></td>
                        <td><?= h($row["event_name"]) ?></td>
                        <td><?= h($row["registration_date"]) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="4">No registrations found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<script src="script.js"></script>
</body>
</html>
