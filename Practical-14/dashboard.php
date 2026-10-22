
<?php
require_once "auth.php";
require_once "db.php";

requireLogin();

function getCount($conn, $table)
{
    $allowedTables = ["users", "students", "events", "registrations"];

    if (!in_array($table, $allowedTables, true)) {
        return 0;
    }

    $result = $conn->query("SELECT COUNT(*) AS total FROM " . $table);

    if ($result) {
        return (int) $result->fetch_assoc()["total"];
    }

    return 0;
}

$userCount = getCount($conn, "users");
$studentCount = getCount($conn, "students");
$eventCount = getCount($conn, "events");
$registrationCount = getCount($conn, "registrations");

$latest = $conn->query(
    "SELECT students.name AS student_name,
            events.event_name,
            registrations.registration_date
     FROM registrations
     INNER JOIN students ON registrations.student_id = students.id
     INNER JOIN events ON registrations.event_id = events.id
     ORDER BY registrations.registration_date DESC
     LIMIT 5"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - StudentHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="layout">
        <aside class="sidebar">
            <h2>StudentHub</h2>
            <p class="sidebar-user">
                <?= htmlspecialchars($_SESSION["username"]) ?>
                <br>
                <span><?= htmlspecialchars(ucfirst($_SESSION["role"])) ?></span>
            </p>

            <nav>
                <a class="active" href="dashboard.php">Dashboard</a>

                <?php if ($_SESSION["role"] === "admin"): ?>
                    <a href="users.php">Manage Users</a>
                    <a href="students.php">Manage Students</a>
                    <a href="events.php">Manage Events</a>
                <?php else: ?>
                    <a href="students.php">Student Information</a>
                    <a href="events.php">View Events</a>
                <?php endif; ?>

                <a href="logout.php">Logout</a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="topbar">
                <div>
                    <h1>Dashboard</h1>
                    <p>Welcome, <?= htmlspecialchars($_SESSION["username"]) ?>!</p>
                </div>
                <span class="role-label">
                    <?= htmlspecialchars(strtoupper($_SESSION["role"])) ?>
                </span>
            </header>

            <?php if ($_SESSION["role"] === "admin"): ?>
                <h2>Overview Statistics</h2>

                <section class="stats">
                    <div class="stat-card">
                        <p>Total Users</p>
                        <h2><?= $userCount ?></h2>
                    </div>

                    <div class="stat-card">
                        <p>Total Students</p>
                        <h2><?= $studentCount ?></h2>
                    </div>

                    <div class="stat-card">
                        <p>Total Events</p>
                        <h2><?= $eventCount ?></h2>
                    </div>

                    <div class="stat-card">
                        <p>Registrations</p>
                        <h2><?= $registrationCount ?></h2>
                    </div>
                </section>

                <section class="panel">
                    <h2>Latest Registrations</h2>

                    <div class="table-wrapper">
                        <table>
                            <thead>
                                <tr>
                                    <th>Student Name</th>
                                    <th>Event Name</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($latest && $latest->num_rows > 0): ?>
                                    <?php while ($row = $latest->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row["student_name"]) ?></td>
                                            <td><?= htmlspecialchars($row["event_name"]) ?></td>
                                            <td><?= htmlspecialchars($row["registration_date"]) ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="3">No registrations found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </section>
            <?php else: ?>
                <section class="panel">
                    <h2>Student Dashboard</h2>
                    <p>You are logged in as a student. Admin statistics and management options are restricted.</p>
                    <p>Use the menu to view student information and available events.</p>
                </section>
            <?php endif; ?>
        </main>
    </div>
</body>
</html>
