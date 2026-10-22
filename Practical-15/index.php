
<?php
require_once "auth.php";
require_once "db.php";

$events = $conn->query(
    "SELECT id, event_name, event_date, location
     FROM events ORDER BY event_date ASC LIMIT 5"
);

$announcements = $conn->query(
    "SELECT title, message, created_at
     FROM announcements ORDER BY created_at DESC LIMIT 5"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>StudentHub | Home</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="index.php">StudentHub</a>
    <button class="menu-toggle" id="menuToggle" type="button">Menu</button>
    <nav id="mainNav">
        <a href="index.php">Home</a>
        <a href="events.php">Events</a>
        <a href="announcements.php">Announcements</a>
        <?php if (isset($_SESSION["user_id"])): ?>
            <a href="dashboard.php">Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<main class="container">
    <section class="hero">
        <p class="eyebrow">YOUR CAMPUS, CONNECTED</p>
        <h1>Welcome to StudentHub</h1>
        <p>Access student information, discover campus events, and stay updated.</p>
        <a class="btn" href="<?= isset($_SESSION["user_id"]) ? "dashboard.php" : "login.php" ?>">
            Get Started
        </a>
    </section>

    <section class="section">
        <h2>Upcoming Events</h2>
        <div class="cards">
            <?php if ($events && $events->num_rows > 0): ?>
                <?php while ($event = $events->fetch_assoc()): ?>
                    <article class="card">
                        <h3><?= h($event["event_name"]) ?></h3>
                        <p><?= h($event["event_date"]) ?></p>
                        <p><?= h($event["location"]) ?></p>
                    </article>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No events are available.</p>
            <?php endif; ?>
        </div>
    </section>

    <section class="section">
        <h2>Latest Announcements</h2>
        <?php if ($announcements && $announcements->num_rows > 0): ?>
            <?php while ($item = $announcements->fetch_assoc()): ?>
                <article class="card announcement-card">
                    <h3><?= h($item["title"]) ?></h3>
                    <p><?= nl2br(h($item["message"])) ?></p>
                </article>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No announcements are available.</p>
        <?php endif; ?>
    </section>
</main>

<footer class="site-footer">© 2026 StudentHub</footer>
<script src="script.js"></script>
</body>
</html>
