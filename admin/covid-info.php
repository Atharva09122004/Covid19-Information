<?php
/**
 * Module 9 — Admin COVID Information CRUD Management
 * Project: COVID-19 Information and Awareness Website
 * 
 * Allows administrators to Create, Read, Update, and Delete COVID-19 educational 
 * articles and guidelines across allowed categories.
 */

require_once '../includes/admin-auth.php';
require_once '../config/database.php';

$errorMessage = "";
$successMessage = "";

$allowedCategories = ['Overview', 'Symptoms', 'Prevention', 'Safety', 'Vaccination', 'Treatment'];

// Handle POST actions (Create / Edit / Delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    try {
        $pdo = getDBConnection();
        if ($pdo) {

            // CREATE COVID INFO
            if ($action === 'create_info') {
                $title = trim($_POST['title'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $category = trim($_POST['category'] ?? '');

                if (empty($title)) {
                    $errorMessage = "Title is required.";
                } elseif (strlen($title) > 255) {
                    $errorMessage = "Title cannot exceed 255 characters.";
                } elseif (empty($description)) {
                    $errorMessage = "Description is required.";
                } elseif (empty($category) || !in_array($category, $allowedCategories, true)) {
                    $errorMessage = "Please select a valid category.";
                } else {
                    $stmt = $pdo->prepare("INSERT INTO covid_info (title, description, category, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())");
                    $stmt->execute([$title, $description, $category]);
                    $successMessage = "COVID Information article created successfully.";
                }
            }

            // EDIT COVID INFO
            elseif ($action === 'edit_info') {
                $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
                $title = trim($_POST['title'] ?? '');
                $description = trim($_POST['description'] ?? '');
                $category = trim($_POST['category'] ?? '');

                if ($id <= 0) {
                    $errorMessage = "Invalid article ID.";
                } elseif (empty($title)) {
                    $errorMessage = "Title is required.";
                } elseif (strlen($title) > 255) {
                    $errorMessage = "Title cannot exceed 255 characters.";
                } elseif (empty($description)) {
                    $errorMessage = "Description is required.";
                } elseif (empty($category) || !in_array($category, $allowedCategories, true)) {
                    $errorMessage = "Please select a valid category.";
                } else {
                    $stmt = $pdo->prepare("UPDATE covid_info SET title = ?, description = ?, category = ?, updated_at = NOW() WHERE id = ?");
                    $stmt->execute([$title, $description, $category, $id]);
                    $successMessage = "COVID Information article updated successfully.";
                }
            }

            // DELETE COVID INFO
            elseif ($action === 'delete_info') {
                $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
                if ($id <= 0) {
                    $errorMessage = "Invalid article ID.";
                } else {
                    $stmt = $pdo->prepare("DELETE FROM covid_info WHERE id = ?");
                    $stmt->execute([$id]);
                    if ($stmt->rowCount() > 0) {
                        $successMessage = "COVID Information article deleted successfully.";
                    } else {
                        $errorMessage = "Article not found or already deleted.";
                    }
                }
            }

        }
    } catch (PDOException $e) {
        error_log("Admin COVID Info Action Error: " . $e->getMessage());
        $errorMessage = "An error occurred while managing COVID information.";
    }
}

// Fetch edit target record if edit_id param provided
$editRecord = null;
if (isset($_GET['edit_id'])) {
    $editId = (int)$_GET['edit_id'];
    try {
        $pdo = getDBConnection();
        if ($pdo && $editId > 0) {
            $stmt = $pdo->prepare("SELECT id, title, description, category FROM covid_info WHERE id = ? LIMIT 1");
            $stmt->execute([$editId]);
            $editRecord = $stmt->fetch();
        }
    } catch (PDOException $e) {
        error_log("Fetch Edit Record Error: " . $e->getMessage());
    }
}

// Fetch all COVID Info records
$infoRecords = [];
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT id, title, description, category, created_at, updated_at FROM covid_info ORDER BY id DESC");
        $stmt->execute();
        $infoRecords = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    error_log("Fetch COVID Info Error: " . $e->getMessage());
    $errorMessage = "Failed to load COVID information records.";
}

$pageTitle = "Manage COVID Information";
include 'includes/admin-header.php';
?>

<main class="container section">
    <div class="admin-page-wrapper">
        
        <div class="table-header-bar" style="margin-bottom: 25px; border-radius: var(--radius-md);">
            <div>
                <h1 style="font-size: 1.6rem; color: var(--secondary-color);">📚 COVID-19 Information Management</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Add, edit, or delete educational health articles and preventive advice.</p>
            </div>
            <span class="count-badge"><?php echo count($infoRecords); ?> Articles Total</span>
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
                <span><?php echo $editRecord ? '✏️ Edit COVID Information Article' : '➕ Add New COVID Information Article'; ?></span>
                <?php if ($editRecord): ?>
                    <a href="covid-info.php" class="btn-sm btn-outline">Cancel Edit</a>
                <?php endif; ?>
            </h3>

            <form action="covid-info.php" method="POST" class="auth-form" novalidate>
                <input type="hidden" name="action" value="<?php echo $editRecord ? 'edit_info' : 'create_info'; ?>">
                <?php if ($editRecord): ?>
                    <input type="hidden" name="id" value="<?php echo $editRecord['id']; ?>">
                <?php endif; ?>

                <div class="form-row-2col">
                    <div class="form-group">
                        <label for="title" class="form-label">Article Title <span class="required-star">*</span></label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            class="form-control" 
                            placeholder="Enter article title" 
                            value="<?php echo htmlspecialchars($editRecord ? $editRecord['title'] : ''); ?>" 
                            required
                            maxlength="255"
                        >
                    </div>

                    <div class="form-group">
                        <label for="category" class="form-label">Category <span class="required-star">*</span></label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="">-- Select Category --</option>
                            <?php foreach ($allowedCategories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($editRecord && $editRecord['category'] === $cat) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label for="description" class="form-label">Article Description / Content <span class="required-star">*</span></label>
                    <textarea 
                        id="description" 
                        name="description" 
                        class="form-control" 
                        rows="4" 
                        placeholder="Write detailed health information..." 
                        required
                    ><?php echo htmlspecialchars($editRecord ? $editRecord['description'] : ''); ?></textarea>
                </div>

                <div class="form-actions-row" style="margin-top: 15px;">
                    <button type="submit" class="btn btn-primary">
                        <?php echo $editRecord ? '💾 Update Article' : '➕ Create Article'; ?>
                    </button>
                </div>
            </form>
        </div>

        <!-- Articles Table Card -->
        <div class="table-card-container">
            <div class="table-responsive">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Description Snippet</th>
                            <th>Last Updated</th>
                            <th style="text-align: center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($infoRecords)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px;">No COVID information articles found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($infoRecords as $rec): ?>
                                <tr>
                                    <td><strong>#<?php echo $rec['id']; ?></strong></td>
                                    <td><strong><?php echo htmlspecialchars($rec['title']); ?></strong></td>
                                    <td>
                                        <span class="card-tag tag-<?php echo strtolower($rec['category']); ?>">
                                            <?php echo htmlspecialchars($rec['category']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars(substr($rec['description'], 0, 90)) . (strlen($rec['description']) > 90 ? '...' : ''); ?></td>
                                    <td class="date-col"><?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($rec['updated_at']))); ?></td>
                                    <td>
                                        <div style="display: flex; gap: 8px; justify-content: center;">
                                            <a href="covid-info.php?edit_id=<?php echo $rec['id']; ?>" class="btn-sm btn-edit">✏️ Edit</a>
                                            
                                            <form action="covid-info.php" method="POST" style="display: inline;" onsubmit="return confirm('Delete article: <?php echo htmlspecialchars(addslashes($rec['title'])); ?>?');">
                                                <input type="hidden" name="action" value="delete_info">
                                                <input type="hidden" name="id" value="<?php echo $rec['id']; ?>">
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
