<?php
/**
 * Module 5 — User Logout
 * Project: COVID-19 Information and Awareness Website
 * 
 * Safely terminates the PHP user session and clears all session cookies.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Unset all session variables
$_SESSION = array();

// 2. Destroy session cookie if set
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(), 
        '', 
        time() - 42000,
        $params["path"], 
        $params["domain"],
        $params["secure"], 
        $params["httponly"]
    );
}

// 3. Destroy session storage on server
session_destroy();

// 4. Send no-cache headers and redirect to login page
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

header("Location: login.php?logout=success");
exit;
?>
