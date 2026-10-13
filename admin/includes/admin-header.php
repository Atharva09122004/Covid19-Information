<?php
/**
 * Admin Shared Header Navigation (Module 9)
 * Project: COVID-19 Information and Awareness Website
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__, 2) . '/app/helpers/helpers.php';
$currentScript = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? escape($pageTitle) . ' - Admin Portal' : 'Admin Portal - COVID-19 Website'; ?></title>
    
    <!-- Site Stylesheet -->
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
</head>
<body>

    <!-- Site Header Navigation -->
    <header class="site-header admin-site-header">
        <div class="container navbar">
            <a href="<?php echo url('admin/dashboard.php'); ?>" class="logo-brand">
                <div class="logo-icon" style="background: linear-gradient(135deg, #ef4444, #3b82f6);">⚙️</div>
                <span>CovidShield <small style="font-size: 0.75rem; color: #ef4444; font-weight: 800; text-transform: uppercase; margin-left: 4px;">Admin</small></span>
            </a>

            <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle navigation">
                <span>☰</span>
            </button>

            <nav class="nav-menu" id="navMenu">
                <a href="<?php echo url('index.php'); ?>" class="nav-link">🌐 Main Website</a>
                <a href="<?php echo url('admin/dashboard.php'); ?>" class="nav-link <?php echo ($currentScript == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
                <a href="<?php echo url('admin/users.php'); ?>" class="nav-link <?php echo ($currentScript == 'users.php') ? 'active' : ''; ?>">Users</a>
                <a href="<?php echo url('admin/covid-info.php'); ?>" class="nav-link <?php echo ($currentScript == 'covid-info.php') ? 'active' : ''; ?>">COVID Info</a>
                <a href="<?php echo url('admin/statistics.php'); ?>" class="nav-link <?php echo ($currentScript == 'statistics.php') ? 'active' : ''; ?>">Statistics</a>
                <a href="<?php echo url('admin/comments.php'); ?>" class="nav-link <?php echo ($currentScript == 'comments.php') ? 'active' : ''; ?>">Comments</a>
                <a href="<?php echo url('admin/contact-messages.php'); ?>" class="nav-link <?php echo ($currentScript == 'contact-messages.php') ? 'active' : ''; ?>">Messages</a>
                <a href="<?php echo url('public/pages/auth/logout.php'); ?>" class="nav-link nav-btn" style="background: #ef4444;">Logout (<?php echo escape($_SESSION['username'] ?? 'Admin'); ?>)</a>
            </nav>
        </div>
    </header>
