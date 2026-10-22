<?php
if (session_status() === PHP_SESSION_NONE) {
    ini_set("session.use_strict_mode", "1");

    session_set_cookie_params([
        "httponly" => true,
        "samesite" => "Lax"
    ]);

    session_start();
}

function requireAdmin()
{
    if (empty($_SESSION["admin_id"])) {
        header("Location: login.php");
        exit;
    }
}
?>