
<?php
require_once "auth.php";
require_once "db.php";
requireLogin();

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    requireAdmin();
    verifyCsrf();

    $title = trim($_POST["title"] ?? "");
    $message = trim($_POST["message"] ?? "");

    if ($title === "" || strlen($title) > 150 || $message === "") {
        $error = "Enter a title and announcement message.";
    } else {
        $stmt = $conn->prepare(
            "INSERT INTO announcements (title, message) VALUES (?, ?)"
        );
        $stmt->bind_param("ss", $title, $message);

        if (!$stmt->execute()) {
            $error = "Unable to save announcement.";
        }
        $stmt->close();
    }
}

$result = $conn->query(
    "SELECT title, message, created_at FROM announcements
     ORDER BY created_at DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Announcements | StudentHub</title>
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
    <h1>Announcements</h1>
    <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>

    <?php if ($_SESSION["role"] === "admin"): ?>
    <section class="card">
        <h2>Publish Announcement</h2>
        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= h(csrfToken()) ?>">
            <label for="title">Title</label>
            <input id="title" name="title" maxlength="150" required>
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="4" required></textarea>
            <button class="btn" type="submit">Publish</button>
        </form>
    </section>
    <?php endif; ?>

    <div class="cards">
    <?php if ($result && $result->num_rows): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <article class="card">
                <h2><?= h($row["title"]) ?></h2>
                <p><?= nl2br(h($row["message"])) ?></p>
                <small><?= h($row["created_at"]) ?></small>
            </article>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No announcements available.</p>
    <?php endif; ?>
    </div>
</main>
<script src="script.js"></script>
</body>
</html>
