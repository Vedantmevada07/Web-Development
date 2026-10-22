<?php
require_once "db.php";
require_once "auth.php";

if (!empty($_SESSION["admin_id"])) {
    header("Location: index.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare(
        "SELECT admin_id, username, password
         FROM admins WHERE username = ?"
    );

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($adminId, $dbUsername, $passwordHash);

        if ($stmt->fetch() && password_verify($password, $passwordHash)) {
            session_regenerate_id(true);

            $_SESSION["admin_id"] = $adminId;
            $_SESSION["admin_username"] = $dbUsername;

            $stmt->close();
            header("Location: index.php");
            exit;
        }

        $stmt->close();
        $error = "Invalid username or password.";
    } else {
        $error = "Unable to process login.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="form-card">
    <h1>Admin Login</h1>

    <?php if ($error !== ""): ?>
        <p class="error">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form method="post">
        <label>Username</label>
        <input type="text" name="username" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <p class="small">Demo: admin / password</p>
</div>

</body>
</html>