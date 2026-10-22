
<?php
require_once "auth.php";
require_once "db.php";
requireLogin();

$stats = [];

if ($_SESSION["role"] === "admin") {
    foreach (["users", "students", "events", "registrations"] as $table) {
        $result = $conn->query("SELECT COUNT(*) AS total FROM `$table`");
        $stats[$table] = $result ? (int)$result->fetch_assoc()["total"] : 0;
    }

    $latest = $conn->query(
        "SELECT s.name, e.event_name, r.registration_date
         FROM registrations r
         JOIN students s ON s.id = r.student_id
         JOIN events e ON e.id = r.event_id
         ORDER BY r.registration_date DESC LIMIT 5"
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard | StudentHub</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="index.php">StudentHub</a>
    <button class="menu-toggle" id="menuToggle" type="button">Menu</button>
    <nav id="mainNav">
        <a href="index.php">Home</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="students.php">Students</a>
        <a href="events.php">Events</a>
        <a href="registrations.php">Registrations</a>
        <a href="announcements.php">Announcements</a>
        <?php if ($_SESSION["role"] === "admin"): ?>
            <a href="users.php">Manage Users</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="container">
    <section class="section">
        <p class="eyebrow"><?= h(strtoupper($_SESSION["role"])) ?> PORTAL</p>
        <h1>Welcome, <?= h($_SESSION["username"]) ?>!</h1>

        <?php if ($_SESSION["role"] === "admin"): ?>
            <p>Overview of the StudentHub portal.</p>

            <div class="stats-grid">
                <article class="stat-card">
                    <span>Total Users</span>
                    <strong><?= $stats["users"] ?></strong>
                </article>
                <article class="stat-card">
                    <span>Total Students</span>
                    <strong><?= $stats["students"] ?></strong>
                </article>
                <article class="stat-card">
                    <span>Total Events</span>
                    <strong><?= $stats["events"] ?></strong>
                </article>
                <article class="stat-card">
                    <span>Registrations</span>
                    <strong><?= $stats["registrations"] ?></strong>
                </article>
            </div>

            <h2>Latest Registrations</h2>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Student</th>
                            <th>Event</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($latest && $latest->num_rows): ?>
                        <?php while ($row = $latest->fetch_assoc()): ?>
                            <tr>
                                <td><?= h($row["name"]) ?></td>
                                <td><?= h($row["event_name"]) ?></td>
                                <td><?= h($row["registration_date"]) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="3">No registrations yet.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <article class="card">
                <h2>Student Dashboard</h2>
                <p>Use the navigation menu to browse students, events, and announcements.</p>
                <a class="btn" href="events.php">Explore Events</a>
            </article>
        <?php endif; ?>
    </section>
</main>
<script src="script.js"></script>
</body>
</html>
