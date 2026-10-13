<?php
// app/includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once dirname(__DIR__) . '/helpers/helpers.php';
$currentScript = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="COVID-19 Information and Awareness Website - STQA College Mini Project">
    <title><?php echo isset($pageTitle) ? escape($pageTitle) . ' - COVID-19 Portal' : 'COVID-19 Information & Awareness Portal'; ?></title>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo asset('css/style.css'); ?>">
</head>
<body>

    <!-- Header Navigation -->
    <header class="site-header">
        <div class="container navbar">
            <a href="<?php echo url('index.php'); ?>" class="logo-brand">
                <div class="logo-icon">🛡️</div>
                <span>CovidShield</span>
            </a>

            <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle navigation">
                <span>☰</span>
            </button>

            <nav class="nav-menu" id="navMenu">
                <a href="<?php echo url('index.php'); ?>" class="nav-link <?php echo ($currentScript == 'index.php') ? 'active' : ''; ?>">Home</a>
                <a href="<?php echo url('public/pages/covid/covid-info.php'); ?>" class="nav-link <?php echo ($currentScript == 'covid-info.php') ? 'active' : ''; ?>">COVID Information</a>
                <a href="<?php echo url('public/pages/covid/statistics.php'); ?>" class="nav-link <?php echo ($currentScript == 'statistics.php') ? 'active' : ''; ?>">COVID Statistics</a>
                <a href="<?php echo url('public/pages/covid/search.php'); ?>" class="nav-link <?php echo ($currentScript == 'search.php') ? 'active' : ''; ?>">Search</a>
                <a href="<?php echo url('public/pages/community/comments.php'); ?>" class="nav-link <?php echo ($currentScript == 'comments.php') ? 'active' : ''; ?>">Comments</a>
                <a href="<?php echo url('public/pages/contact.php'); ?>" class="nav-link <?php echo ($currentScript == 'contact.php') ? 'active' : ''; ?>">Contact</a>
                
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="<?php echo url('admin/dashboard.php'); ?>" class="nav-link" style="color: #ef4444; font-weight: 700;">⚙️ Admin</a>
                    <?php endif; ?>
                    <a href="<?php echo url('public/pages/user/dashboard.php'); ?>" class="nav-link <?php echo ($currentScript == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
                    <a href="<?php echo url('public/pages/auth/logout.php'); ?>" class="nav-link nav-btn">Logout (<?php echo escape($_SESSION['username'] ?? 'User'); ?>)</a>
                <?php else: ?>
                    <a href="<?php echo url('public/pages/auth/login.php'); ?>" class="nav-link <?php echo ($currentScript == 'login.php') ? 'active' : ''; ?>">Login</a>
                    <a href="<?php echo url('public/pages/auth/register.php'); ?>" class="nav-link nav-btn">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
