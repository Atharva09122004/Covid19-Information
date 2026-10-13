<?php
/**
 * Module 9 — Admin Comment Moderation Management
 * Project: COVID-19 Information and Awareness Website
 * 
 * Allows administrators to review all community comments and delete 
 * inappropriate feedback using POST requests and prepared statements.
 */

require_once dirname(__DIR__) . '/app/includes/admin-auth.php';
require_once dirname(__DIR__) . '/app/config/database.php';

$errorMessage = "";
$successMessage = "";

// Handle Delete Comment (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_comment') {
    $commentId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($commentId <= 0) {
        $errorMessage = "Invalid comment ID.";
    } else {
        try {
            $pdo = getDBConnection();
            if ($pdo) {
                $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
                $stmt->execute([$commentId]);

                if ($stmt->rowCount() > 0) {
                    $successMessage = "Comment deleted successfully.";
                } else {
                    $errorMessage = "Comment not found or already deleted.";
                }
            }
        } catch (PDOException $e) {
            error_log("Admin Delete Comment Error: " . $e->getMessage());
            $errorMessage = "An error occurred while deleting the comment.";
        }
    }
}

// Fetch all comments with user details
$commentsList = [];
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $sql = "SELECT comments.id, comments.comment, comments.created_at, comments.user_id,
                       users.username, users.name, users.email
                FROM comments
                JOIN users ON comments.user_id = users.id
                ORDER BY comments.created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $commentsList = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    error_log("Admin Fetch Comments Error: " . $e->getMessage());
    $errorMessage = "Failed to retrieve comments.";
}

$pageTitle = "Comment Moderation";
include 'includes/admin-header.php';
?>

<main class="container section">
    <div class="admin-page-wrapper">
        
        <div class="table-header-bar" style="margin-bottom: 25px; border-radius: var(--radius-md);">
            <div>
                <h1 style="font-size: 1.6rem; color: var(--secondary-color);">💬 Comment Moderation</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Review community comments and delete inappropriate content.</p>
            </div>
            <span class="count-badge"><?php echo count($commentsList); ?> Comments Total</span>
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

        <div class="table-card-container">
            <div class="table-responsive">
                <table class="stats-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Author</th>
                            <th>Username</th>
                            <th>Comment Content</th>
                            <th>Posted At</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($commentsList)): ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 30px;">No community comments found.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($commentsList as $c): ?>
                                <tr>
                                    <td><strong>#<?php echo $c['id']; ?></strong></td>
                                    <td><?php echo htmlspecialchars($c['name']); ?></td>
                                    <td><code>@<?php echo htmlspecialchars($c['username']); ?></code></td>
                                    <td style="max-width: 400px; word-break: break-word;">
                                        <?php echo nl2br(htmlspecialchars($c['comment'], ENT_QUOTES, 'UTF-8')); ?>
                                    </td>
                                    <td class="date-col"><?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($c['created_at']))); ?></td>
                                    <td>
                                        <div style="display: flex; justify-content: center;">
                                            <form action="comments.php" method="POST" onsubmit="return confirm('Are you sure you want to delete comment #<?php echo $c['id']; ?>?');">
                                                <input type="hidden" name="action" value="delete_comment">
                                                <input type="hidden" name="id" value="<?php echo $c['id']; ?>">
                                                <button type="submit" class="btn-sm btn-delete">🗑️ Delete Comment</button>
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
