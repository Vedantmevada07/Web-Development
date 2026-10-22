
<?php
require_once "auth.php";
require_once "db.php";
requireLogin();

$result = $conn->query(
    "SELECT id, name, email, course FROM students ORDER BY id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - StudentHub</title>
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
                <a class="active" href="students.php">Manage Students</a>
                <a href="events.php">Manage Events</a>
            <?php else: ?>
                <a class="active" href="students.php">Student Information</a>
                <a href="events.php">View Events</a>
            <?php endif; ?>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div>
                <h1>Students</h1>
                <p>Student information stored in the database</p>
            </div>
        </header>

        <section class="panel">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Course</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int) $row["id"] ?></td>
                                <td><?= htmlspecialchars($row["name"]) ?></td>
                                <td><?= htmlspecialchars($row["email"]) ?></td>
                                <td><?= htmlspecialchars($row["course"]) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No students found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>
