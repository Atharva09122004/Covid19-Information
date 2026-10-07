<?php
/**
 * Admin Shared Header Navigation (Module 9)
 * Project: COVID-19 Information and Awareness Website
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - Admin Portal' : 'Admin Portal - COVID-19 Website'; ?></title>
    
    <!-- Site Stylesheet -->
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>

    <!-- Site Header Navigation -->
    <header class="site-header admin-site-header">
        <div class="container navbar">
            <a href="dashboard.php" class="logo-brand">
                <div class="logo-icon" style="background: linear-gradient(135deg, #ef4444, #3b82f6);">⚙️</div>
                <span>CovidShield <small style="font-size: 0.75rem; color: #ef4444; font-weight: 800; text-transform: uppercase; margin-left: 4px;">Admin</small></span>
            </a>

            <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle navigation">
                <span>☰</span>
            </button>

            <nav class="nav-menu" id="navMenu">
                <a href="../index.php" class="nav-link">🌐 Main Website</a>
                <a href="dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
                <a href="users.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'users.php') ? 'active' : ''; ?>">Users</a>
                <a href="covid-info.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'covid-info.php') ? 'active' : ''; ?>">COVID Info</a>
                <a href="statistics.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'statistics.php') ? 'active' : ''; ?>">Statistics</a>
                <a href="comments.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'comments.php') ? 'active' : ''; ?>">Comments</a>
                <a href="contact-messages.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact-messages.php') ? 'active' : ''; ?>">Messages</a>
                <a href="../logout.php" class="nav-link nav-btn" style="background: #ef4444;">Logout (<?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?>)</a>
            </nav>
        </div>
    </header>
