<?php
/**
 * Authentication Middleware (Module 5)
 * Project: COVID-19 Information and Awareness Website
 * 
 * Verifies active session state. Protects sensitive pages against unauthorized access.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/helpers/helpers.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    // Send HTTP no-cache headers to prevent browser caching of protected content
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // Redirect unauthorized user to login page
    header("Location: " . url('public/pages/auth/login.php?error=auth_required'));
    exit;
}
?>
