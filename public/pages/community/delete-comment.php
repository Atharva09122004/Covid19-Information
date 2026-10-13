<?php
/**
 * Module 7 — Delete Comment
 * Project: COVID-19 Information and Awareness Website
 * 
 * Securely deletes a comment ONLY if it belongs to the authenticated user.
 * Security condition: DELETE FROM comments WHERE id = ? AND user_id = ?
 */

require_once dirname(__DIR__, 3) . '/app/includes/auth.php';
require_once dirname(__DIR__, 3) . '/app/config/database.php';

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$userId = (int)$_SESSION['user_id'];
$commentId = 0;

// Deletion must use POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = isset($_POST['id']) ? (int)$_POST['id'] : 0;
} else {
    // Also support GET for direct confirmation requests if provided cleanly
    $commentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
}

if ($commentId <= 0) {
    header("Location: " . url('public/pages/community/comments.php?error=invalid_comment'));
    exit;
}

try {
    $pdo = getDBConnection();
    if ($pdo) {
        // Enforce strict ownership check: WHERE id = ? AND user_id = ?
        $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ? AND user_id = ?");
        $stmt->execute([$commentId, $userId]);

        if ($stmt->rowCount() > 0) {
            header("Location: " . url('public/pages/community/comments.php?msg=comment_deleted'));
            exit;
        } else {
            // Deletion failed because comment does not exist or belongs to another user
            header("Location: " . url('public/pages/community/comments.php?error=unauthorized'));
            exit;
        }
    }
} catch (PDOException $e) {
    error_log("Delete Comment Error: " . $e->getMessage());
    header("Location: " . url('public/pages/community/comments.php?error=system_error'));
    exit;
}
?>
