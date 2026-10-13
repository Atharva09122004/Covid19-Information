<?php
/**
 * Module 9 — Admin Dashboard
 * Project: COVID-19 Information and Awareness Website
 * 
 * Provides summary statistics, system metrics, and quick management links 
 * for administrators.
 */

require_once dirname(__DIR__) . '/app/includes/admin-auth.php';
require_once dirname(__DIR__) . '/app/config/database.php';

$totalUsers = 0;
$totalInfo = 0;
$totalStats = 0;
$totalComments = 0;
$totalMessages = 0;
$errorMessage = "";

try {
    $pdo = getDBConnection();
    if ($pdo) {
        $totalUsers = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $totalInfo = (int)$pdo->query("SELECT COUNT(*) FROM covid_info")->fetchColumn();
        $totalStats = (int)$pdo->query("SELECT COUNT(*) FROM statistics")->fetchColumn();
        $totalComments = (int)$pdo->query("SELECT COUNT(*) FROM comments")->fetchColumn();
        $totalMessages = (int)$pdo->query("SELECT COUNT(*) FROM contact_messages")->fetchColumn();
    }
} catch (PDOException $e) {
    error_log("Admin Dashboard Metrics Error: " . $e->getMessage());
    $errorMessage = "Failed to load dashboard metrics from database.";
}

$pageTitle = "Admin Dashboard";
include 'includes/admin-header.php';
?>

<main class="container section">
    <div class="admin-dashboard-wrapper">
        
        <!-- Welcome Banner -->
        <div class="dashboard-welcome-card admin-welcome-hero">
            <div class="user-avatar-circle" style="background: linear-gradient(135deg, #ef4444, #3b82f6);">⚙️</div>
            <div class="welcome-text-wrap">
                <span class="dashboard-badge" style="color: #ef4444;">ADMINISTRATIVE PORTAL</span>
                <h1 class="welcome-heading">Welcome, <?php echo htmlspecialchars($_SESSION['name'] ?? $_SESSION['username']); ?></h1>
                <p class="dashboard-subtitle">System oversight and management dashboard for CovidShield.</p>
            </div>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert-box alert-danger" style="margin-bottom: 25px;">
                ⚠️ <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- System Metrics Grid Cards -->
        <div class="admin-metrics-grid">
            
            <div class="admin-stat-card card-users">
                <div class="stat-icon">👥</div>
                <div class="stat-info">
                    <span class="stat-title">Total Users</span>
                    <span class="stat-value"><?php echo number_format($totalUsers); ?></span>
                </div>
            </div>

            <div class="admin-stat-card card-info">
                <div class="stat-icon">📚</div>
                <div class="stat-info">
                    <span class="stat-title">COVID Info Records</span>
                    <span class="stat-value"><?php echo number_format($totalInfo); ?></span>
                </div>
            </div>

            <div class="admin-stat-card card-stats">
                <div class="stat-icon">📊</div>
                <div class="stat-info">
                    <span class="stat-title">Statistics Records</span>
                    <span class="stat-value"><?php echo number_format($totalStats); ?></span>
                </div>
            </div>

            <div class="admin-stat-card card-comments">
                <div class="stat-icon">💬</div>
                <div class="stat-info">
                    <span class="stat-title">Comments</span>
                    <span class="stat-value"><?php echo number_format($totalComments); ?></span>
                </div>
            </div>

            <div class="admin-stat-card card-messages">
                <div class="stat-icon">📬</div>
                <div class="stat-info">
                    <span class="stat-title">Contact Messages</span>
                    <span class="stat-value"><?php echo number_format($totalMessages); ?></span>
                </div>
            </div>

        </div>

        <!-- Quick Actions Navigation Section -->
        <div class="admin-section-box">
            <h2 class="admin-box-title">Quick Management Actions</h2>
            
            <div class="quick-actions-grid">
                
                <a href="users.php" class="quick-action-card">
                    <div class="action-icon">👥</div>
                    <div class="action-content">
                        <h3>Manage Users</h3>
                        <p>View registered accounts, assign admin roles, or delete users.</p>
                    </div>
                    <span class="btn btn-primary btn-sm">Open Users →</span>
                </a>

                <a href="covid-info.php" class="quick-action-card">
                    <div class="action-icon">📚</div>
                    <div class="action-content">
                        <h3>COVID Information</h3>
                        <p>Create, update, or remove educational articles and guidelines.</p>
                    </div>
                    <span class="btn btn-primary btn-sm">Manage Info →</span>
                </a>

                <a href="statistics.php" class="quick-action-card">
                    <div class="action-icon">📊</div>
                    <div class="action-content">
                        <h3>COVID Statistics</h3>
                        <p>Manage country statistics (cases, recovered, deaths).</p>
                    </div>
                    <span class="btn btn-primary btn-sm">Manage Stats →</span>
                </a>

                <a href="comments.php" class="quick-action-card">
                    <div class="action-icon">💬</div>
                    <div class="action-content">
                        <h3>Manage Comments</h3>
                        <p>Review and moderate community feedback or delete comments.</p>
                    </div>
                    <span class="btn btn-primary btn-sm">View Comments →</span>
                </a>

                <a href="contact-messages.php" class="quick-action-card">
                    <div class="action-icon">📬</div>
                    <div class="action-content">
                        <h3>Contact Messages</h3>
                        <p>Review messages submitted by website visitors.</p>
                    </div>
                    <span class="btn btn-primary btn-sm">View Messages →</span>
                </a>

            </div>
        </div>

    </div>
</main>

<?php include 'includes/admin-footer.php'; ?>
