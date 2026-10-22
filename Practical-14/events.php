
<?php
require_once "auth.php";
require_once "db.php";
requireLogin();

$result = $conn->query(
    "SELECT id, event_name, event_date, location FROM events ORDER BY event_date DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - StudentHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h2>StudentHub</h2>
        <p class="sidebar-user">
            <?= htmlspecialchars($_SESSION["username"]) ?><br>
            <span><?= htmlspecialchars(ucfirst($_SESSION["role"])) ?></span>
        </p>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <?php if ($_SESSION["role"] === "admin"): ?>
                <a href="users.php">Manage Users</a>
                <a href="students.php">Manage Students</a>
                <a class="active" href="events.php">Manage Events</a>
            <?php else: ?>
                <a href="students.php">Student Information</a>
                <a class="active" href="events.php">View Events</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div>
                <h1>Events</h1>
                <p>Events available in StudentHub</p>
            </div>
        </header>

        <section class="panel">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Event Name</th>
                            <th>Event Date</th>
                            <th>Location</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int) $row["id"] ?></td>
                                <td><?= htmlspecialchars($row["event_name"]) ?></td>
                                <td><?= htmlspecialchars($row["event_date"]) ?></td>
                                <td><?= htmlspecialchars($row["location"]) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No events found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>
