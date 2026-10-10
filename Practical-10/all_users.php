<?php
require_once "auth.php";

// Only admins can view all accounts
requireAdmin();

$sql = "SELECT id, username, email, role, created_at
        FROM users
        ORDER BY id ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users and Admins</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: #f2f6fc;
        }

        h1 {
            text-align: center;
            color: #174a7e;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #2478b5;
            color: white;
        }

        tr:nth-child(even) {
            background: #eaf2f8;
        }

        a {
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1>All Registered Users and Admins</h1>

<table>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Registration Date</th>
    </tr>

    <?php if ($result && $result->num_rows > 0) { ?>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo htmlspecialchars($row["id"]); ?></td>
                <td><?php echo htmlspecialchars($row["username"]); ?></td>
                <td><?php echo htmlspecialchars($row["email"]); ?></td>
                <td><?php echo htmlspecialchars($row["role"]); ?></td>
                <td><?php echo htmlspecialchars($row["created_at"]); ?></td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="5">No registered accounts found.</td>
        </tr>
    <?php } ?>
</table>

<a href="admin.php">Back to Admin Dashboard</a> |
<a href="logout.php">Logout</a>

</body>
</html>
