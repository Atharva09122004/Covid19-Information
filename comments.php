<?php
/**
 * Module 7 — Comments and Feedback
 * Project: COVID-19 Information and Awareness Website
 * 
 * Allows users to view community feedback and permits authenticated users to 
 * post, edit, and delete their own comments safely.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'config/database.php';

$pageTitle = "Community Feedback";
$errorMessage = "";
$successMessage = "";
$commentInput = "";

$isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
$currentUserId = $isLoggedIn ? (int)$_SESSION['user_id'] : 0;

// Flash message handler
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'comment_added') {
        $successMessage = "Comment added successfully.";
    } elseif ($_GET['msg'] === 'comment_updated') {
        $successMessage = "Comment updated successfully.";
    } elseif ($_GET['msg'] === 'comment_deleted') {
        $successMessage = "Comment deleted successfully.";
    }
} elseif (isset($_GET['error'])) {
    if ($_GET['error'] === 'unauthorized') {
        $errorMessage = "You are not authorized to modify that comment.";
    } elseif ($_GET['error'] === 'invalid_comment') {
        $errorMessage = "Invalid comment selected.";
    }
}

// Handle New Comment Submission (Authenticated users only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_comment') {
    if (!$isLoggedIn) {
        header("Location: login.php?error=auth_required");
        exit;
    }

    $commentInput = trim($_POST['comment'] ?? '');

    // Server-side Comment Validation
    if (empty($commentInput) || strlen($commentInput) < 3) {
        $errorMessage = "Comment must contain at least 3 characters.";
    } elseif (strlen($commentInput) > 1000) {
        $errorMessage = "Comment cannot exceed 1000 characters.";
    } else {
        try {
            $pdo = getDBConnection();
            if ($pdo) {
                // Prepared statement inserting session user_id
                $stmt = $pdo->prepare("INSERT INTO comments (user_id, comment, created_at, updated_at) VALUES (?, ?, NOW(), NOW())");
                $stmt->execute([$currentUserId, $commentInput]);

                header("Location: comments.php?msg=comment_added");
                exit;
            } else {
                $errorMessage = "Database connection failed. Please try again.";
            }
        } catch (PDOException $e) {
            error_log("Add Comment Error: " . $e->getMessage());
            $errorMessage = "An error occurred while posting your comment.";
        }
    }
}

// Fetch existing comments from database
$comments = [];
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $query = "SELECT comments.id,
                         comments.comment,
                         comments.created_at,
                         comments.updated_at,
                         users.username,
                         users.name,
                         comments.user_id
                  FROM comments
                  JOIN users ON comments.user_id = users.id
                  ORDER BY comments.created_at DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute();
        $comments = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    error_log("Fetch Comments Error: " . $e->getMessage());
}

include 'includes/header.php';
?>

<main class="container section">
    <div class="comments-page-wrapper">
        
        <!-- Header Banner -->
        <div class="comments-header-card">
            <div class="comments-header-icon">💬</div>
            <div class="comments-header-content">
                <h1 class="comments-title">Community Feedback</h1>
                <p class="comments-subtitle">Share your thoughts or feedback about this COVID-19 information platform.</p>
            </div>
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

        <!-- Add Comment Form Card -->
        <div class="add-comment-card">
            <h3 class="card-section-heading">
                <span>💬 Leave a Comment</span>
                <?php if ($isLoggedIn): ?>
                    <span class="user-pill-tag">Posting as @<?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <?php endif; ?>
            </h3>

            <?php if ($isLoggedIn): ?>
                <form action="comments.php" method="POST" class="auth-form" id="commentForm" novalidate>
                    <input type="hidden" name="action" value="add_comment">
                    
                    <div class="form-group">
                        <label for="comment" class="form-label">Your Comment / Feedback <span class="required-star">*</span></label>
                        <textarea 
                            id="comment" 
                            name="comment" 
                            class="form-control" 
                            rows="4" 
                            placeholder="Write your feedback or questions here (3 to 1000 characters)..." 
                            required
                        ><?php echo htmlspecialchars($commentInput); ?></textarea>
                        <span class="error-msg" id="commentError">Comment must contain between 3 and 1000 characters.</span>
                        <div class="char-count-bar"><span id="charCounter">0</span> / 1000 characters</div>
                    </div>

                    <div class="form-actions-row">
                        <button type="submit" class="btn btn-primary">💬 Submit Comment</button>
                    </div>
                </form>
            <?php else: ?>
                <div class="guest-notice-box">
                    <p>🔒 <strong>Want to share your feedback?</strong> Please <a href="login.php">Login</a> or <a href="register.php">Register</a> to post comments.</p>
                </div>
            <?php endif; ?>
        </div>

        <!-- Comments Feed Section -->
        <div class="comments-feed-container">
            <h3 class="feed-title">
                Recent Community Comments 
                <span class="count-badge"><?php echo count($comments); ?></span>
            </h3>

            <?php if (empty($comments)): ?>
                <div class="empty-state-box">
                    <div class="empty-icon">💬</div>
                    <h3>No Comments Yet</h3>
                    <p>Be the first to share your thoughts and feedback on this portal!</p>
                </div>
            <?php else: ?>
                <div class="comments-list">
                    <?php foreach ($comments as $item): ?>
                        <div class="comment-card <?php echo ($isLoggedIn && $item['user_id'] == $currentUserId) ? 'own-comment-card' : ''; ?>" id="comment-<?php echo $item['id']; ?>">
                            <div class="comment-card-header">
                                <div class="comment-author-info">
                                    <div class="author-avatar">
                                        <?php echo strtoupper(substr($item['username'], 0, 1)); ?>
                                    </div>
                                    <div class="author-details">
                                        <h4 class="author-name"><?php echo htmlspecialchars($item['name']); ?></h4>
                                        <span class="author-username">@<?php echo htmlspecialchars($item['username']); ?></span>
                                    </div>
                                </div>
                                <div class="comment-meta">
                                    <span class="comment-date">
                                        🕒 <?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($item['created_at']))); ?>
                                        <?php if ($item['updated_at'] && strtotime($item['updated_at']) > strtotime($item['created_at']) + 5): ?>
                                            <em class="edited-tag">(Edited)</em>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>

                            <div class="comment-body">
                                <p><?php echo nl2br(htmlspecialchars($item['comment'], ENT_QUOTES, 'UTF-8')); ?></p>
                            </div>

                            <!-- Show Edit/Delete Controls ONLY for current user's comments -->
                            <?php if ($isLoggedIn && (int)$item['user_id'] === $currentUserId): ?>
                                <div class="comment-card-footer">
                                    <a href="edit-comment.php?id=<?php echo $item['id']; ?>" class="btn-sm btn-edit">✏️ Edit</a>
                                    <form action="delete-comment.php" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                                        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="btn-sm btn-delete">🗑️ Delete</button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>
