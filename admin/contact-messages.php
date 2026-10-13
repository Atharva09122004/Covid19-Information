<?php
/**
 * Module 10 — Admin Contact Messages Management
 * Project: COVID-19 Information and Awareness Website
 * 
 * Allows administrators to review messages submitted by site visitors and delete 
 * resolved entries using POST requests and prepared statements.
 */

require_once dirname(__DIR__) . '/app/includes/admin-auth.php';
require_once dirname(__DIR__) . '/app/config/database.php';

$errorMessage = "";
$successMessage = "";

// Handle Delete Contact Message (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete_message') {
    $msgId = isset($_POST['id']) ? (int)$_POST['id'] : 0;

    if ($msgId <= 0) {
        $errorMessage = "Invalid message ID.";
    } else {
        try {
            $pdo = getDBConnection();
            if ($pdo) {
                $stmt = $pdo->prepare("DELETE FROM contact_messages WHERE id = ?");
                $stmt->execute([$msgId]);

                if ($stmt->rowCount() > 0) {
                    $successMessage = "Contact message deleted successfully.";
                } else {
                    $errorMessage = "Message not found or already deleted.";
                }
            }
        } catch (PDOException $e) {
            error_log("Admin Delete Message Error: " . $e->getMessage());
            $errorMessage = "An error occurred while deleting the message.";
        }
    }
}

// Fetch all contact messages
$messagesList = [];
try {
    $pdo = getDBConnection();
    if ($pdo) {
        $stmt = $pdo->prepare("SELECT id, name, email, subject, message, created_at FROM contact_messages ORDER BY id DESC");
        $stmt->execute();
        $messagesList = $stmt->fetchAll();
    }
} catch (PDOException $e) {
    error_log("Admin Fetch Messages Error: " . $e->getMessage());
    $errorMessage = "Failed to retrieve contact messages.";
}

$pageTitle = "Contact Messages";
include 'includes/admin-header.php';
?>

<main class="container section">
    <div class="admin-page-wrapper">
        
        <div class="table-header-bar" style="margin-bottom: 25px; border-radius: var(--radius-md);">
            <div>
                <h1 style="font-size: 1.6rem; color: var(--secondary-color);">📬 Contact Messages</h1>
                <p style="color: var(--text-muted); font-size: 0.9rem;">Review messages submitted by visitors or delete resolved entries.</p>
            </div>
            <span class="count-badge"><?php echo count($messagesList); ?> Messages Total</span>
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
                            <th>Sender Name</th>
                            <th>Email Address</th>
                            <th>Subject</th>
                            <th>Message Content</th>
                            <th>Received At</th>
                            <th style="text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($messagesList)): ?>
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 30px;">No contact messages received yet.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($messagesList as $m): ?>
                                <tr>
                                    <td><strong>#<?php echo $m['id']; ?></strong></td>
                                    <td><strong><?php echo htmlspecialchars($m['name']); ?></strong></td>
                                    <td><a href="mailto:<?php echo htmlspecialchars($m['email']); ?>"><?php echo htmlspecialchars($m['email']); ?></a></td>
                                    <td><strong><?php echo htmlspecialchars($m['subject']); ?></strong></td>
                                    <td style="max-width: 350px; word-break: break-word;">
                                        <?php echo nl2br(htmlspecialchars($m['message'], ENT_QUOTES, 'UTF-8')); ?>
                                    </td>
                                    <td class="date-col"><?php echo htmlspecialchars(date('d-m-Y H:i', strtotime($m['created_at']))); ?></td>
                                    <td>
                                        <div style="display: flex; justify-content: center;">
                                            <form action="contact-messages.php" method="POST" onsubmit="return confirm('Delete message #<?php echo $m['id']; ?> from <?php echo htmlspecialchars(addslashes($m['name'])); ?>?');">
                                                <input type="hidden" name="action" value="delete_message">
                                                <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
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
