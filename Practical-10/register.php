<?php

require_once "db.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $message = "Username must be 3-20 letters, numbers, or underscores.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Enter a valid email address.";

    } elseif (strlen($password) < 8) {
        $message = "Password must be at least 8 characters.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id FROM users WHERE username = ? OR email = ?"
        );

        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $message = "Username or email already exists.";

        } else {
            $stmt->close();

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (username, email, password, role)
                 VALUES (?, ?, ?, 'user')"
            );

            $stmt->bind_param("sss", $username, $email, $hash);

            if ($stmt->execute()) {
                $message = "Registration successful. You can now log in.";
            } else {
                $message = "Registration failed. Please try again.";
            }
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>
<body align="center">

<h2>Create Account</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" minlength="3"
           maxlength="20" required><br><br>

    <label>Email:</label>
    <input type="email" name="email" required><br><br>

    <label>Password:</label>
    <input type="password" name="password"
           minlength="8" required><br><br>

    <button type="submit">Register</button>
</form>

<p><a href="login.php">Go to Login</a></p>

</body>
</html>