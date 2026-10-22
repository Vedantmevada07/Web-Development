
<?php
require_once "auth.php";
require_once "db.php";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $stmt = $conn->prepare(
        "SELECT id, username, password, role FROM users WHERE username = ?"
    );

    if ($stmt) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user["password"])) {
            session_regenerate_id(true);
            $_SESSION["user_id"] = (int)$user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];
            csrfToken();

            header("Location: dashboard.php");
            exit;
        }

        $error = "Invalid username or password.";
        $stmt->close();
    } else {
        $error = "Login is temporarily unavailable.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login | StudentHub</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
<main class="login-card">
    <h1>StudentHub</h1>
    <p>Sign in to continue</p>

    <?php if ($error !== ""): ?>
        <p class="error"><?= h($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <label for="username">Username</label>
        <input id="username" name="username" required maxlength="50">

        <label for="password">Password</label>
        <input id="password" type="password" name="password" required>

        <button class="btn" type="submit">Login</button>
    </form>

    <p>New student? <a href="register.php">Create an account</a></p>
    <p class="hint">Local demo: admin / password</p>
</main>
</body>
</html>
