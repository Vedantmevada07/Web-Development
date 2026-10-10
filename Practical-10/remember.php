<?php

require_once "auth.php";

// Check whether a remember-me cookie exists
if (!isset($_COOKIE["remember_me"])) {
    header("Location: login.php");
    exit;
}

$parts = explode(":", $_COOKIE["remember_me"]);

// Validate the cookie format
if (
    count($parts) !== 2 ||
    !preg_match('/^[a-f0-9]{24}$/', $parts[0]) ||
    !preg_match('/^[a-f0-9]{64}$/', $parts[1])
) {
    header("Location: logout.php");
    exit;
}

$selector = $parts[0];
$validator = $parts[1];

// Find the token in the database
$stmt = $conn->prepare(
    "SELECT token_hash
     FROM remember_tokens
     WHERE selector = ? AND expires_at > NOW()"
);

if (!$stmt) {
    exit("Unable to verify remember-me token.");
}

$stmt->bind_param("s", $selector);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {

    $stmt->bind_result($tokenHash);
    $stmt->fetch();

    // Compare the provided token with the stored hash
    if (hash_equals($tokenHash, hash("sha256", $validator))) {

        echo "Remember-me token is valid.";

    } else {

        echo "Invalid remember-me token.";
    }

} else {

    echo "Token expired or not found.";
}

$stmt->close();

?>