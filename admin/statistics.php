<?php
/**
 * Module 9 — Admin Statistics Management
 * Project: COVID-19 Information and Awareness Website
 * 
 * Allows administrators to Create, Read, Update, and Delete country COVID-19 
 * statistics with validation enforcing cases >= 0, recovered >= 0, deaths >= 0, 
 * and recovered + deaths <= cases.
 */

require_once '../includes/admin-auth.php';
require_once '../config/database.php';

$errorMessage = "";
$successMessage = "";

// Handle POST actions (Create / Edit / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    try {
        $pdo = getDBConnection();
        if ($pdo) {

            // CREATE STATISTICS
            if ($action === 'create_stat') {
                $country = trim($_POST['country'] ?? '');
                $cases = isset($_POST['cases']) ? (int)$_POST['cases'] : -1;
                $recovered = isset($_POST['recovered']) ? (int)$_POST['recovered'] : -1;
                $deaths = isset($_POST['deaths']) ? (int)$_POST['deaths'] : -1;

                if (empty($country)) {
                    $errorMessage = "Country name is required.";
                } elseif ($cases < 0 || $recovered < 0 || $deaths < 0) {
                    $errorMessage = "Numeric values (cases, recovered, deaths) must be greater than or equal to 0.";
                } elseif (($recovered + $deaths) > $cases) {
                    $errorMessage = "Invalid statistics: Total recovered + deaths (" . ($recovered + $deaths) . ") cannot exceed total cases (" . $cases . ").";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO statistics (country, cases, recovered, deaths, updated_at) VALUES (?, ?, ?, ?, NOW())");
                    $stmt->execute([$country, $cases, $recovered, $deaths]);
                    $successMessage = "Statistics record added successfully.";
                }
            }

            // EDIT STATISTICS
            elseif ($action === 'edit_stat') {
                $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
                $country = trim($_POST['country'] ?? '');
                $cases = isset($_POST['cases']) ? (int)$_POST['cases'] : -1;
                $recovered = isset($_POST['recovered']) ? (int)$_POST['recovered'] : -1;
                $deaths = isset($_POST['deaths']) ? (int)$_POST['deaths'] : -1;

                if ($id <= 0) {
                    $errorMessage = "Invalid statistics record ID.";
                } elseif (empty($country)) {
                    $errorMessage = "Country name is required.";
                } elseif ($cases < 0 || $recovered < 0 || $deaths < 0) {
                    $errorMessage = "Numeric values (cases, recovered, deaths) must be greater than or equal to 0.";
                } elseif (($recovered + $deaths) > $cases) {
                    $errorMessage = "Invalid statistics: Total recovered + deaths (" . ($recovered + $deaths) . ") cannot exceed total cases (" . $cases . ").";
                } else {
                    $stmt = $pdo->prepare("UPDATE statistics SET country = ?, cases = ?, recovered = ?, deaths = ?, updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$country, $cases, $recovered, $deaths, $id]);
                    $successMessage = "Statistics record updated successfully.";
                }
            }

            // DELETE STATISTICS
            elseif ($action === 'delete_stat') {
                $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
                if ($id <= 0) {
                    $errorMessage = "Invalid statistics record ID.";
                } else {
                    $stmt = $pdo->prepare("DELETE FROM statistics WHERE id = ?");
                    $stmt->execute([$id]);
                    if ($stmt->rowCount() > 0) {
                        $successMessage = "Statistics record deleted successfully.";
                    } else {
                        $errorMessage = "Record not found or already deleted.";
                    }
                }
            }

        }
    } catch (PDOException $e) {
        error_log("Admin Statistics Action Error: " . $e->getMessage());
        $errorMessage = "An error occurred while managing statistics.";
    }
}

// Fetch edit target record if edit_id param provided
$editRecord = null;
if (isset($_GET['edit_id'])) {
    $editId = (int)$_GET['edit_id'];
    try {
        $pdo = getDBConnection();
        if ($pdo && $editId > 0) {
            $stmt = $pdo->prepare("SELECT id, country, cases, recovered, deaths FROM statistics WHERE id = ? LIMIT 1");
            $stmt->execute([$editId]);
            $editRecord = $stmt->fetch();
        }
    } catch (PDOException $e) {
        error_log("Fetch Edit Stat Record Error: " . $e->getMessage());
    }
}

// Fetch all Statistics records
$statRecords = [];
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT id, country, cases, recovered, deaths, updated_at FROM statistics ORDER BY cases DESC");
        $stmt->execute();
        $statRecords = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    error_log("Fetch Statistics Error: " . $e->getMessage());
    $errorMessage = "Failed to load statistics records.";
}

$pageTitle = "Manage Statistics";
include 'includes/admin-header.php';
?>

<main class="container section">
    <div class="admin-page-wrapper">
        
        <div class="table-header-bar" style="margin-bottom: 25px; border-radius: var(--radius-md);">
            <div>
                <h1 style="font-size: 1.6rem; color: var(--secondary-color);">📊 COVID-19 Statistics Management</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Add, edit, or remove country infection statistics.</p>
            </div>
            <span class="count-badge"><?php echo count($statRecords); ?> Countries Tracked</span>
        </div>

        <?php if (!empty($successMessage)): ?>
            <div class="alert-box alert-success" style="margin-bottom: 25px;">
                ✅ <?php echo htmlspecialchars($successMessage); ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert-box alert-danger" style="margin-bottom: 25px;">
                ⚠️ <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <!-- Create / Edit Form Card -->
        <div class="add-comment-card" style="margin-bottom: 35px;">
            <h3 class="card-section-heading">
                <span><?php echo $editRecord ? '✏️ Edit Country Statistics' : '➕ Add New Country Statistics'; ?></span>
                <?php if ($editRecord): ?>
                    <a href="statistics.php" class="btn-sm btn-outline">Cancel Edit</a>
                <?php endif; ?>
            </h3>

            <form action="statistics.php" method="POST" class="auth-form" novalidate>
                <input type="hidden" name="action" value="<?php echo $editRecord ? 'edit_stat' : 'create_stat'; ?>">
                <?php if ($editRecord): ?>
                    <input type="hidden" name="id" value="<?php echo $editRecord['id']; ?>">
                <?php endif; ?>

                <div class="form-row-2col">
                    <div class="form-group">
                        <label for="country" class="form-label">Country Name <span class="required-star">*</span></label>
                        <input 
                            type="text" 
                            id="country" 
                            name="country" 
                            class="form-control" 
                            placeholder="Enter country name (e.g. India)" 
                            value="<?php echo htmlspecialchars($editRecord ? $editRecord['country'] : ''); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="cases" class="form-label">Total Confirmed Cases <span class="required-star">*</span></label>
                        <input 
                            type="number" 
                            id="cases" 
                            name="cases" 
                            class="form-control" 
                            placeholder="0" 
                            min="0"
                            value="<?php echo htmlspecialchars($editRecord ? $editRecord['cases'] : '0'); ?>" 
                            required
                        >
                    </div>
                </div>

                <div class="form-row-2col" style="margin-top: 15px;">
                    <div class="form-group">
                        <label for="recovered" class="form-label">Recovered Cases <span class="required-star">*</span></label>
                        <input 
                            type="number" 
                            id="recovered" 
                            name="recovered" 
                            class="form-control" 
                            placeholder="0" 
                            min="0"
                            value="<?php echo htmlspecialchars($editRecord ? $editRecord['recovered'] : '0'); ?>" 
                            required
                        >
                    </div>

                    <div class="form-group">
                        <label for="deaths" class="form-label">Total Deaths <span class="required-star">*</span></label>
                        <input 
                            type="number" 
                            id="deaths" 
                            name="deaths" 
                            class="form-control" 
                            placeholder="0" 
                            min="0"
                            value="<?php echo htmlspecialchars($editRecord ? $editRecord['deaths'] : '0'); ?>" 
                            required
                        >
                    </div>
                </div>

                <div class="form-actions-row" style="margin-top: 15px;">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $editRecord ? '💾 Update Statistics' : '➕ Add Statistics'; ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistics Table Card -->
        <div class="table-card-container">
            <div class="table-responsive">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Country</th>
                            <th>Confirmed Cases</th>
                            <th>Recovered</th>
                            <th>Deaths</th>
                            <th>Active Cases</th>
                            <th>Last Updated</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($statRecords)): ?>
                            <tr>
                                <td colspan="8" style="text-align: center; padding: 30px;">No statistics records found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($statRecords as $st): 
                                $active = max(0, $st['cases'] - $st['recovered'] - $st['deaths']);
                            ?>
                                <tr>
                                    <td><strong>#<?php echo $st['id']; ?></strong></td>
                                    <td><strong><?php echo htmlspecialchars($st['country']); ?></strong></td>
                                    <td class="cases-col"><?php echo number_format($st['cases']); ?></td>
                                    <td class="recovered-col"><?php echo number_format($st['recovered']); ?></td>
                                    <td class="deaths-col"><?php echo number_format($st['deaths']); ?></td>
                                    <td><span class="active-pill"><?php echo number_format($active); ?></span></td>
                                    <td class="date-col"><?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($st['updated_at']))); ?></td>
                                    <td>
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <a href="statistics.php?edit_id=<?php echo $st['id']; ?>" class="btn-sm btn-edit">✏️ Edit</a>
                                            
                                            <form action="statistics.php" method="POST" style="display: inline;" onsubmit="return confirm('Delete statistics for <?php echo htmlspecialchars(addslashes($st['country'])); ?>?');">
                                                <input type="hidden" name="action" value="delete_stat">
                                                <input type="hidden" name="id" value="<?php echo $st['id']; ?>">
                                                <button type="submit" class="btn-sm btn-delete">🗑️ Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

<?php include 'includes/admin-footer.php'; ?>
