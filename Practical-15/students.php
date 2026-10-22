
<?php
require_once "auth.php";
require_once "db.php";
requireLogin();

$result = $conn->query(
    "SELECT id, name, email, course, semester
     FROM students ORDER BY id DESC"
);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Students | StudentHub</title>
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
        <?php if ($_SESSION["role"] === "admin"): ?>
            <a href="users.php">Manage Users</a>
        <?php endif; ?>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<main class="container section">
    <h1>Students</h1>
    <p>Student records stored in the database.</p>

    <?php if ($_SESSION["role"] === "admin"): ?>
        <section class="card">
            <h2>Add Student</h2>
            <form id="studentForm">
                <input type="hidden" name="csrf_token"
                       value="<?= h(csrfToken()) ?>">
                <label for="name">Full Name</label>
                <input id="name" name="name" maxlength="100" required>

                <label for="email">Email</label>
                <input id="email" name="email" type="email"
                       maxlength="150" required>

                <label for="course">Course</label>
                <input id="course" name="course" maxlength="100" required>

                <label for="semester">Semester</label>
                <input id="semester" name="semester" type="number"
                       min="1" max="12" value="1" required>

                <button class="btn" type="submit">Add Student</button>
            </form>
            <p id="ajaxMessage" role="status"></p>
        </section>
    <?php endif; ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th><th>Name</th><th>Email</th>
                    <th>Course</th><th>Semester</th>
                </tr>
            </thead>
            <tbody id="studentRows">
            <?php if ($result && $result->num_rows): ?>
                <?php while ($s = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= (int)$s["id"] ?></td>
                        <td><?= h($s["name"]) ?></td>
                        <td><?= h($s["email"]) ?></td>
                        <td><?= h($s["course"]) ?></td>
                        <td><?= (int)$s["semester"] ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No students found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
<script src="script.js"></script>
</body>
</html>
