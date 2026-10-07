<?php
/**
 * Module 7 — Edit Comment
 * Project: COVID-19 Information and Awareness Website
 * 
 * Allows authenticated users to edit ONLY their own existing comments.
 * Security condition: WHERE id = ? AND user_id = ?
 */

require_once 'includes/auth.php';
require_once 'config/database.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$userId = (int)$_SESSION['user_id'];
$commentId = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
$errorMessage = "";
$commentRow = null;

if ($commentId <= 0) {
    header("Location: comments.php?error=invalid_comment");
    exit;
}

// Fetch comment strictly verifying ownership: WHERE id = ? AND user_id = ?
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT id, comment, user_id FROM comments WHERE id = ? AND user_id = ? LIMIT 1");
        $stmt->execute([$commentId, $userId]);
        $commentRow = $stmt->fetch();
    }
} catch (PDOException $e) {
    error_log("Edit Comment Fetch Error: " . $e->getMessage());
    $errorMessage = "System error fetching comment.";
}

// Authorization Check: If comment does not exist or belong to this user -> DENY ACCESS
if (!$commentRow) {
    header("Location: comments.php?error=unauthorized");
    exit;
}

$commentText = $commentRow['comment'];

// Handle POST Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentText = trim($_POST['comment'] ?? '');

    if (empty($commentText) || strlen($commentText) < 3) {
        $errorMessage = "Comment must contain at least 3 characters.";
    } elseif (strlen($commentText) > 1000) {
        $errorMessage = "Comment cannot exceed 1000 characters.";
    } else {
        try {
            if ($pdo) {
                // Prepared statement updating only if id AND user_id match
                $updateStmt = $pdo->prepare("UPDATE comments SET comment = ?, updated_at = NOW() WHERE id = ? AND user_id = ?");
                $updateStmt->execute([$commentText, $commentId, $userId]);

                header("Location: comments.php?msg=comment_updated");
                exit;
            } else {
                $errorMessage = "Database connection error.";
            }
        } catch (PDOException $e) {
            error_log("Edit Comment Update Error: " . $e->getMessage());
            $errorMessage = "An error occurred while updating your comment.";
        }
    }
}

$pageTitle = "Edit Comment";
include 'includes/header.php';
?>

<main class="container section">
    <div class="form-card-wrapper" style="max-width: 640px;">
        <div class="form-card-header">
            <div class="form-avatar-icon">✏️</div>
            <h1 class="form-card-title">Edit Your Comment</h1>
            <p class="form-card-subtitle">Modify your posted feedback below.</p>
        </div>

        <?php if (!empty($errorMessage)): ?>
            <div class="alert-box alert-danger" id="editCommentAlert">
                ⚠️ <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <form action="edit-comment.php?id=<?php echo $commentId; ?>" method="POST" class="auth-form" id="editCommentForm" novalidate>
            <input type="hidden" name="id" value="<?php echo $commentId; ?>">

            <div class="form-group">
                <label for="comment" class="form-label">Comment Text <span class="required-star">*</span></label>
                <textarea 
                    id="comment" 
                    name="comment" 
                    class="form-control" 
                    rows="5" 
                    required
                ><?php echo htmlspecialchars($commentText); ?></textarea>
                <span class="error-msg" id="commentError">Comment must contain between 3 and 1000 characters.</span>
                <div class="char-count-bar"><span id="charCounter"><?php echo strlen($commentText); ?></span> / 1000 characters</div>
            </div>

            <div class="form-actions-row" style="margin-top: 15px;">
                <button type="submit" class="btn btn-primary btn-full">Save Changes</button>
                <a href="comments.php" class="btn btn-outline" style="text-align: center; display: flex; align-items: center; justify-content: center;">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
