<?php
/**
 * Admin Authentication Middleware (Module 9)
 * Project: COVID-19 Information and Awareness Website
 * 
 * Protects administrative pages by enforcing active session check 
 * and verifying that $_SESSION['role'] === 'admin'.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/helpers/helpers.php';

// Send HTTP no-cache headers on all administrative pages
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

// 1. Verify user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: " . url('public/pages/auth/login.php?error=auth_required'));
    exit;
}

// 2. Verify user role is 'admin'
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: " . url('public/pages/user/dashboard.php?error=admin_required'));
    exit;
}
?>
