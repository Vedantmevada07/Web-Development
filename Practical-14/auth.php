
<?php
if (session_status() === PHP_SESSION_NONE) {
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

    if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "admin") {
        http_response_code(403);
        echo "<h2>Access Denied</h2>";
        echo "<p>This page is available to admin users only.</p>";
        echo '<p><a href="dashboard.php">Back to Dashboard</a></p>';
        exit;
    }
}
?>
