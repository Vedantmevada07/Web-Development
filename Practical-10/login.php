<?php

require_once "auth.php";

if (isset($_SESSION["user_id"])) {
    if ($_SESSION["role"] === "admin") {
        header("Location: admin.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}

$message = "";

if (isset($_GET["timeout"])) {
    $message = "Your session expired. Please log in again.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $remember = isset($_POST["remember"]);

    $stmt = $conn->prepare(
        "SELECT id, username, password, role
         FROM users WHERE username = ?"
    );

    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {

        $stmt->bind_result($id, $dbUsername, $dbPassword, $role);
        $stmt->fetch();

        if (password_verify($password, $dbPassword)) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $id;
            $_SESSION["username"] = $dbUsername;
            $_SESSION["role"] = $role;
            $_SESSION["last_activity"] = time();

            if ($remember) {

                // Create a random selector and validator
                $selector = bin2hex(random_bytes(12));
                $validator = bin2hex(random_bytes(32));
                $tokenHash = hash("sha256", $validator);
                $expires = date("Y-m-d H:i:s", time() + (30 * 86400));

                $tokenStmt = $conn->prepare(
                    "INSERT INTO remember_tokens
                     (user_id, selector, token_hash, expires_at)
                     VALUES (?, ?, ?, ?)"
                );

                $tokenStmt->bind_param(
                    "isss",
                    $id,
                    $selector,
                    $tokenHash,
                    $expires
                );

                if ($tokenStmt->execute()) {

                    $isHttps = !empty($_SERVER["HTTPS"])
                        && $_SERVER["HTTPS"] !== "off";

                    setcookie("remember_me", $selector . ":" . $validator, [
                        "expires" => time() + (30 * 86400),
                        "path" => "/",
                        "secure" => $isHttps,
                        "httponly" => true,
                        "samesite" => "Lax"
                    ]);
                }

                $tokenStmt->close();
            }

            $stmt->close();

            if ($role === "admin") {
                header("Location: admin.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;

        } else {
            $message = "Invalid username or password.";
        }

    } else {
        $message = "Invalid username or password.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Login</title>
</head>
<body>

<h2>User Login</h2>

<p><?php echo htmlspecialchars($message); ?></p>

<form method="POST">
    <label>Username:</label>
    <input type="text" name="username" required><br><br>

    <label>Password:</label>
    <input type="password" name="password" required><br><br>

    <label>
        <input type="checkbox" name="remember" value="1">
        Remember me for 30 days
    </label><br><br>

    <button type="submit">Login</button>
</form>

<p><a href="register.php">Create an account</a></p>

</body>
</html>