<?php

require_once __DIR__ . "/db.php";

$timeout = 900; // 15 minutes

$isHttps = !empty($_SERVER["HTTPS"])
    && $_SERVER["HTTPS"] !== "off";

ini_set("session.use_strict_mode", "1");

session_set_cookie_params([
    "lifetime" => 0,
    "path" => "/",
    "secure" => $isHttps,
    "httponly" => true,
    "samesite" => "Lax"
]);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Check session timeout
if (isset($_SESSION["user_id"])) {

    if (isset($_SESSION["last_activity"]) &&
        time() - $_SESSION["last_activity"] > $timeout) {

        $_SESSION = [];
        session_destroy();

        header("Location: login.php?timeout=1");
        exit;
    }

    $_SESSION["last_activity"] = time();
}

// Restore login from remember-me cookie
if (!isset($_SESSION["user_id"]) &&
    isset($_COOKIE["remember_me"])) {

    $parts = explode(":", $_COOKIE["remember_me"]);

    if (count($parts) === 2 &&
        preg_match('/^[a-f0-9]{24}$/', $parts[0]) &&
        preg_match('/^[a-f0-9]{64}$/', $parts[1])) {

        $selector = $parts[0];
        $validator = $parts[1];

        $stmt = $conn->prepare(
            "SELECT rt.user_id, rt.token_hash, u.username, u.role
             FROM remember_tokens rt
             JOIN users u ON u.id = rt.user_id
             WHERE rt.selector = ? AND rt.expires_at > NOW()"
        );

        if ($stmt) {
            $stmt->bind_param("s", $selector);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows === 1) {

                $stmt->bind_result(
                    $userId,
                    $tokenHash,
                    $username,
                    $role
                );

                $stmt->fetch();

                if (hash_equals(
                    $tokenHash,
                    hash("sha256", $validator)
                )) {
                    session_regenerate_id(true);

                    $_SESSION["user_id"] = $userId;
                    $_SESSION["username"] = $username;
                    $_SESSION["role"] = $role;
                    $_SESSION["last_activity"] = time();
                }
            }

            $stmt->close();
        }
    }

    // Invalid or expired cookies must not be retained
    if (!isset($_SESSION["user_id"])) {
        setcookie("remember_me", "", [
            "expires" => time() - 3600,
            "path" => "/",
            "secure" => $isHttps,
            "httponly" => true,
            "samesite" => "Lax"
        ]);
    }
}

function requireLogin() {
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
}

function requireAdmin() {
    requireLogin();

    if ($_SESSION["role"] !== "admin") {
        http_response_code(403);
        exit("Access denied. Admins only.");
    }
}

?>