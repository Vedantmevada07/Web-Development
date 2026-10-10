<?php

require_once "auth.php";

requireLogin();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
</head>
<body>

<h1>Welcome to User Dashboard</h1>

<p>
    Hello,
    <?php echo htmlspecialchars($_SESSION["username"]); ?>!
</p>

<p>Your role is: User</p>

<a href="logout.php">Logout</a>

</body>
</html>