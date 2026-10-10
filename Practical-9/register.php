<?php

require_once "db.php";

$message = "";
$messageType = "";

$username = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get and clean form inputs
    $username = trim($_POST["username"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirmPassword = $_POST["confirm_password"] ?? "";

    // Backend validation
    if ($username === "" || $email === "" ||
        $password === "" || $confirmPassword === "") {

        $message = "Please fill in all fields.";
        $messageType = "error";

    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {

        $message = "Username must be 3-20 characters and contain only letters, numbers, or underscores.";
        $messageType = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $messageType = "error";

    } elseif (strlen($password) < 8) {

        $message = "Password must be at least 8 characters long.";
        $messageType = "error";

    } elseif ($password !== $confirmPassword) {

        $message = "Passwords do not match.";
        $messageType = "error";

    } else {

        // Check whether username or email already exists
        $checkSql = "SELECT id FROM users
                     WHERE username = ? OR email = ?";

        $checkStmt = $conn->prepare($checkSql);

        if (!$checkStmt) {
            $message = "Something went wrong. Please try again.";
            $messageType = "error";
        } else {

            $checkStmt->bind_param("ss", $username, $email);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {

                $message = "Username or email is already registered.";
                $messageType = "error";

            } else {

                // Hash the password before storing it
                $hashedPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                // Insert the new user using a prepared statement
                $insertSql = "INSERT INTO users
                              (username, email, password)
                              VALUES (?, ?, ?)";

                $insertStmt = $conn->prepare($insertSql);

                if (!$insertStmt) {

                    $message = "Registration failed. Please try again.";
                    $messageType = "error";

                } else {

                    $insertStmt->bind_param(
                        "sss",
                        $username,
                        $email,
                        $hashedPassword
                    );

                    if ($insertStmt->execute()) {

                        $message = "Registration successful!";
                        $messageType = "success";

                        // Clear the form after success
                        $username = "";
                        $email = "";

                    } else {

                        // Also handle duplicates if two requests
                        // try to register the same details together
                        if ($insertStmt->errno === 1062) {
                            $message = "Username or email is already registered.";
                        } else {
                            $message = "Registration failed. Please try again.";
                        }

                        $messageType = "error";
                    }

                    $insertStmt->close();
                }
            }

            $checkStmt->close();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>User Registration</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #eef3f8;
            margin: 0;
            padding: 30px 15px;
        }

        .container {
            max-width: 450px;
            margin: auto;
            padding: 25px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 3px 12px #cccccc;
        }

        h1 {
            text-align: center;
            color: #2471a3;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 11px;
            margin-top: 6px;
            border: 1px solid #bbbbbb;
            border-radius: 5px;
        }

        button {
            width: 100%;
            margin-top: 22px;
            padding: 12px;
            border: none;
            border-radius: 5px;
            background: #2471a3;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #1b4f72;
        }

        .message {
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
            overflow-wrap: anywhere;
        }

        .success {
            color: #155724;
            background: #d4edda;
        }

        .error {
            color: #721c24;
            background: #f8d7da;
        }

        .link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Create Account</h1>

    <?php if ($message !== ""): ?>
        <div class="message <?php echo htmlspecialchars($messageType); ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="register.php">

        <label for="username">Username</label>
        <input
            type="text"
            id="username"
            name="username"
            minlength="3"
            maxlength="20"
            pattern="[a-zA-Z0-9_]{3,20}"
            title="Use 3-20 letters, numbers, or underscores."
            value="<?php echo htmlspecialchars($username); ?>"
            required
        >

        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            maxlength="100"
            value="<?php echo htmlspecialchars($email); ?>"
            required
        >

        <label for="password">Password</label>
        <input
            type="password"
            id="password"
            name="password"
            minlength="8"
            autocomplete="new-password"
            required
        >

        <label for="confirm_password">Confirm Password</label>
        <input
            type="password"
            id="confirm_password"
            name="confirm_password"
            minlength="8"
            autocomplete="new-password"
            required
        >

        <button type="submit">Register</button>

    </form>

    <div class="link">
        <a href="users.php">View Registered Users</a>
    </div>

</div>

</body>
</html>