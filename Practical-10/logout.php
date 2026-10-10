<?php

require_once "auth.php";

// Remove the current remember-me token from the database
if (isset($_COOKIE["remember_me"])) {

    $parts = explode(":", $_COOKIE["remember_me"]);

    if (count($parts) === 2 &&
        preg_match('/^[a-f0-9]{24}$/', $parts[0])) {

        $selector = $parts[0];

        $stmt = $conn->prepare(
            "DELETE FROM remember_tokens WHERE selector = ?"
        );

        if ($stmt) {
            $stmt->bind_param("s", $selector);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Clear the remember-me cookie
$isHttps = !empty($_SERVER["HTTPS"])
    && $_SERVER["HTTPS"] !== "off";

setcookie("remember_me", "", [
    "expires" => time() - 3600,
    "path" => "/",
    "secure" => $isHttps,
    "httponly" => true,
    "samesite" => "Lax"
]);

// Destroy the session
$_SESSION = [];

if (ini_get("session.use_cookies")) {
    setcookie(session_name(), "", [
        "expires" => time() - 3600,
        "path" => "/",
        "secure" => $isHttps,
        "httponly" => true,
        "samesite" => "Lax"
    ]);
}

session_destroy();

header("Location: login.php?logout=1");
exit;

?>