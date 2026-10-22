<?php
require_once "auth.php";
requireAdmin();
require_once "db.php";

$message = trim($_GET["message"] ?? "");

$stmt = $conn->prepare(
    "SELECT event_id, event_name, event_date,
            location, description, poster
     FROM events
     ORDER BY event_date DESC"
);

$events = [];

if ($stmt) {
    $stmt->execute();

    $stmt->bind_result(
        $eventId,
        $eventName,
        $eventDate,
        $location,
        $description,
        $poster
    );

    while ($stmt->fetch()) {
        $events[] = [
            "id" => $eventId,
            "name" => $eventName,
            "date" => $eventDate,
            "location" => $location,
            "description" => $description,
            "poster" => $poster
        ];
    }

    $stmt->close();
}

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<header class="topbar">
    <h2>Event Management - Admin Panel</h2>
    <div>
        Welcome, <?= h($_SESSION["admin_username"] ?? "Admin") ?>
        | <a href="logout.php">Logout</a>
    </div>
</header>

<main class="container">
    <h1>Manage Events</h1>

    <?php if ($message !== ""): ?>
        <p class="success"><?= h($message) ?></p>
    <?php endif; ?>

    <p>
        <a class="button" href="add_event.php">+ Add New Event</a>
    </p>

    <?php if (count($events) === 0): ?>
        <p>No events found. Add your first event.</p>
    <?php else: ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Poster</th>
                    <th>Event Name</th>
                    <th>Date</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
            <?php foreach ($events as $event): ?>
                <tr>
                    <td>
                        <?php
                        $posterFile = !empty($event["poster"])
                            ? basename($event["poster"])
                            : "";
                        ?>

                        <?php if (
                            $posterFile !== "" &&
                            is_file(__DIR__ . "/uploads/" . $posterFile)
                        ): ?>
                            <img
                                class="poster-thumb"
                                src="uploads/<?= h($posterFile) ?>"
                                alt="Event poster"
                            >
                        <?php else: ?>
                            No poster
                        <?php endif; ?>
                    </td>

                    <td><?= h($event["name"]) ?></td>
                    <td><?= h($event["date"]) ?></td>
                    <td><?= h($event["location"]) ?></td>
                    <td><?= h($event["description"]) ?></td>

                    <td>
                        <a
                            class="button small-button"
                            href="edit_event.php?id=<?= (int)$event["id"] ?>"
                        >Edit</a>

                        <form
                            method="post"
                            action="delete_event.php"
                            class="inline-form"
                            onsubmit="return confirm('Are you sure you want to delete this event?');"
                        >
                            <input
                                type="hidden"
                                name="event_id"
                                value="<?= (int)$event["id"] ?>"
                            >
                            <button class="danger small-button" type="submit">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <?php endif; ?>
</main>

</body>
</html>