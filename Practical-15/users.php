
<?php
require_once "auth.php";
require_once "db.php";
requireAdmin();

$result = $conn->query(
    "SELECT id, username, email, role, created_at
     FROM users ORDER BY id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Manage Users | StudentHub</title>
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
        <a href="users.php">Manage Users</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="container section">
    <h1>Manage Users</h1>
    <p>This page is restricted to administrators.</p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Created</th></tr>
            </thead>
            <tbody>
            <?php if ($result && $result->num_rows): ?>
                <?php while ($user = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$user["id"] ?></td>
                        <td><?= h($user["username"]) ?></td>
                        <td><?= h($user["email"]) ?></td>
                        <td><?= h($user["role"]) ?></td>
                        <td><?= h($user["created_at"]) ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No users found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<script src="script.js"></script>
</body>
</html>
