
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        "httponly" => true,
        "secure" => !empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off",
        "samesite" => "Lax"
    ]);
    session_start();
}

function requireLogin()
{
    if (!isset($_SESSION["user_id"])) {
        header("Location: login.php");
        exit;
    }
}

function requireAdmin()
{
    requireLogin();

    if (($_SESSION["role"] ?? "") !== "admin") {
        http_response_code(403);
        exit("403 Forbidden: Admin access required.");
    }
}

function h($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}

function csrfToken()
{
    if (empty($_SESSION["csrf_token"])) {
        $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
    }
    return $_SESSION["csrf_token"];
}

function verifyCsrf()
{
    $sent = $_SERVER["HTTP_X_CSRF_TOKEN"] ?? $_POST["csrf_token"] ?? "";
    $saved = $_SESSION["csrf_token"] ?? "";

    if ($saved === "" || !is_string($sent) ||
        !hash_equals($saved, $sent)) {
        http_response_code(403);
        exit("Invalid security token. Reload the page and try again.");
    }
}
?>
