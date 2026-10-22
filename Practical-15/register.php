
<?php
require_once "auth.php";
require_once "db.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (strlen($username) < 3 || strlen($username) > 50) {
        $error = "Username must contain 3 to 50 characters.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL) ||
              strlen($email) > 150) {
        $error = "Enter a valid email address.";
    } elseif (strlen($password) < 8) {
        $error = "Password must contain at least 8 characters.";
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare(
            "INSERT INTO users (username, email, password, role)
             VALUES (?, ?, ?, 'student')"
        );

        if ($stmt) {
            $stmt->bind_param("sss", $username, $email, $hash);

            if ($stmt->execute()) {
                $message = "Registration successful. You can now log in.";
            } else {
                $error = $stmt->errno === 1062
                    ? "Username or email already exists."
                    : "Registration failed.";
            }

            $stmt->close();
        } else {
            $error = "Unable to prepare registration.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Register | StudentHub</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="login-page">
<main class="login-card">
    <h1>Create Account</h1>

    <?php if ($message): ?><p class="success"><?= h($message) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= h($error) ?></p><?php endif; ?>

    <form method="post">
        <label for="username">Username</label>
        <input id="username" name="username" required minlength="3" maxlength="50">

        <label for="email">Email</label>
        <input id="email" type="email" name="email" required maxlength="150">

        <label for="password">Password (minimum 8 characters)</label>
        <input id="password" type="password" name="password" required minlength="8">

        <button class="btn" type="submit">Register</button>
    </form>

    <p><a href="login.php">Back to login</a></p>
</main>
</body>
</html>
