<?php

require_once "db.php";

$sql = "SELECT id, username, email, created_at
        FROM users
        ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Registered Users</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eef3f8;
            padding: 25px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
        }

        h1 {
            text-align: center;
            color: #2471a3;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #cccccc;
            padding: 10px;
            text-align: left;
        }

        th {
            background: #2471a3;
            color: white;
        }

        a {
            display: inline-block;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Registered Users</h1>

    <?php if ($result === false): ?>

        <p>Unable to load registered users.</p>

    <?php elseif ($result->num_rows > 0): ?>

        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Registration Date</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>

                <tr>
                    <td><?php echo htmlspecialchars((string)$row["id"]); ?></td>
                    <td><?php echo htmlspecialchars($row["username"]); ?></td>
                    <td><?php echo htmlspecialchars($row["email"]); ?></td>
                    <td><?php echo htmlspecialchars($row["created_at"]); ?></td>
                </tr>

            <?php endwhile; ?>

        </table>

    <?php else: ?>

        <p>No users have registered yet.</p>

    <?php endif; ?>

    <a href="register.php">Back to Registration</a>

</div>

</body>
</html>