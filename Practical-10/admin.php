<?php

require_once "auth.php";

requireAdmin();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>

<h1>Welcome to Admin Dashboard</h1>

<p>
    Hello Admin,
    <?php echo htmlspecialchars($_SESSION["username"]); ?>!
</p>

<p>You have administrator access.</p>

<a href="logout.php">Logout</a>

</body>
</html>