<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="COVID-19 Information and Awareness Website - STQA College Mini Project">
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - COVID-19 Portal' : 'COVID-19 Information & Awareness Portal'; ?></title>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Header Navigation -->
    <header class="site-header">
        <div class="container navbar">
            <a href="index.php" class="logo-brand">
                <div class="logo-icon">🛡️</div>
                <span>CovidShield</span>
            </a>

            <button class="mobile-toggle" id="mobileToggle" aria-label="Toggle navigation">
                <span>☰</span>
            </button>

            <nav class="nav-menu" id="navMenu">
                <a href="index.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>">Home</a>
                <a href="covid-info.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'covid-info.php') ? 'active' : ''; ?>">COVID Information</a>
                <a href="statistics.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'statistics.php') ? 'active' : ''; ?>">COVID Statistics</a>
                <a href="search.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'search.php') ? 'active' : ''; ?>">Search</a>
                <a href="comments.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'comments.php') ? 'active' : ''; ?>">Comments</a>
                <a href="contact.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>">Contact</a>
                
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                        <a href="admin/dashboard.php" class="nav-link" style="color: #ef4444; font-weight: 700;">⚙️ Admin</a>
                    <?php endif; ?>
                    <a href="dashboard.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'active' : ''; ?>">Dashboard</a>
                    <a href="logout.php" class="nav-link nav-btn">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
                <?php else: ?>
                    <a href="login.php" class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'login.php') ? 'active' : ''; ?>">Login</a>
                    <a href="register.php" class="nav-link nav-btn">Register</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
