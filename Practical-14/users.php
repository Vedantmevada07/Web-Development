
<?php
require_once "auth.php";
require_once "db.php";
requireAdmin();

$result = $conn->query(
    "SELECT id, username, role, created_at FROM users ORDER BY id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - StudentHub</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="layout">
    <aside class="sidebar">
        <h2>StudentHub</h2>
        <p class="sidebar-user">
            <?= htmlspecialchars($_SESSION["username"]) ?><br>
            <span>Administrator</span>
        </p>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a class="active" href="users.php">Manage Users</a>
            <a href="students.php">Manage Students</a>
            <a href="events.php">Manage Events</a>
            <a href="logout.php">Logout</a>
        </nav>
    </aside>

    <main class="main-content">
        <header class="topbar">
            <div>
                <h1>Manage Users</h1>
                <p>Only administrators can access this page.</p>
            </div>
        </header>

        <section class="panel">
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= (int) $row["id"] ?></td>
                                <td><?= htmlspecialchars($row["username"]) ?></td>
                                <td>
                                    <span class="role-label">
                                        <?= htmlspecialchars(ucfirst($row["role"])) ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row["created_at"]) ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4">No users found.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</div>
</body>
</html>
