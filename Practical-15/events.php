
<?php
require_once "auth.php";
require_once "db.php";
requireLogin();

$error = "";
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    requireAdmin();
    verifyCsrf();

    $name = trim($_POST["event_name"] ?? "");
    $date = trim($_POST["event_date"] ?? "");
    $location = trim($_POST["location"] ?? "");
    $description = trim($_POST["description"] ?? "");

    $validDate = DateTime::createFromFormat("!Y-m-d", $date);

    if ($name === "" || strlen($name) > 150 ||
        !$validDate || $validDate->format("Y-m-d") !== $date ||
        $location === "" || strlen($location) > 150) {
        $error = "Please enter a valid event name, date, and location.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO events (event_name, event_date, location, description)
             VALUES (?, ?, ?, ?)"
        );

        if ($stmt) {
            $stmt->bind_param("ssss", $name, $date, $location, $description);
            if ($stmt->execute()) {
                $message = "Event added successfully.";
            } else {
                $error = "Unable to save event.";
            }
            $stmt->close();
        } else {
            $error = "Unable to prepare event request.";
        }
    }
}

$events = $conn->query(
    "SELECT id, event_name, event_date, location, description
     FROM events ORDER BY event_date ASC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Events | StudentHub</title>
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
    <h1>Campus Events</h1>

    <?php if ($message): ?><p class="success"><?= h($message) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>

    <?php if ($_SESSION["role"] === "admin"): ?>
    <section class="card">
        <h2>Add Event</h2>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= h(csrfToken()) ?>">
            <label for="event_name">Event Name</label>
            <input id="event_name" name="event_name" maxlength="150" required>

            <label for="event_date">Event Date</label>
            <input id="event_date" name="event_date" type="date" required>

            <label for="location">Location</label>
            <input id="location" name="location" maxlength="150" required>

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="3"></textarea>

            <button class="btn" type="submit">Add Event</button>
        </form>
    </section>
    <?php endif; ?>

    <div class="cards">
    <?php if ($events && $events->num_rows): ?>
        <?php while ($event = $events->fetch_assoc()): ?>
            <article class="card">
                <h2><?= h($event["event_name"]) ?></h2>
                <p><strong>Date:</strong> <?= h($event["event_date"]) ?></p>
                <p><strong>Location:</strong> <?= h($event["location"]) ?></p>
                <p><?= nl2br(h($event["description"] ?? "")) ?></p>
                <?php if ($_SESSION["role"] === "student"): ?>
                    <form action="registrations.php" method="post">
                        <input type="hidden" name="csrf_token" value="<?= h(csrfToken()) ?>">
                        <input type="hidden" name="event_id" value="<?= (int)$event["id"] ?>">
                        <button class="btn" type="submit">Register for Event</button>
                    </form>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No events found.</p>
    <?php endif; ?>
    </div>
</main>
<script src="script.js"></script>
</body>
</html>
