<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

require_login();

$blog_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Verify ownership
$stmt = $pdo->prepare("SELECT id, title FROM blogs WHERE id = ? AND user_id = ?");
$stmt->execute([$blog_id, $_SESSION['user_id']]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
  $_SESSION['error'] = "Success story not found or you don't have permission to delete it.";
  header("Location: dashboard.php");
  exit;
}

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $deleteStmt = $pdo->prepare("DELETE FROM blogs WHERE id = ? AND user_id = ?");
  if ($deleteStmt->execute([$blog_id, $_SESSION['user_id']])) {
    $_SESSION['success'] = "Success story deleted successfully.";
  } else {
    $_SESSION['error'] = "Failed to delete success story.";
  }

  header("Location: dashboard.php");
  exit;
}

require_once "../includes/header.php";
?>

<div class="card" style="max-width: 600px; margin: 2rem auto;">
  <h2 style="color: #dc2626;">🗑️ Delete Success Story</h2>
  <p style="color: #6b7280; margin: 1rem 0;">
    Are you sure you want to delete this success story?
  </p>

  <div style="background: #fef3c7; padding: 1rem; border-radius: 8px; border-left: 4px solid #f59e0b; margin: 1.5rem 0;">
    <strong style="color: #92400e;">⚠️ Warning:</strong>
    <p style="color: #78350f; margin-top: 0.5rem; margin-bottom: 0;">
      This action cannot be undone. Your story will be permanently deleted from the platform.
    </p>
  </div>

  <div style="background: #f3f4f6; padding: 1rem; border-radius: 8px; margin: 1rem 0;">
    <strong style="color: #1f2937;">Story to be deleted:</strong>
    <p style="margin-top: 0.5rem; color: #4b5563;">
      <?php echo htmlspecialchars($blog['title']); ?>
    </p>
  </div>

  <form method="POST" style="margin-top: 1.5rem;">
    <div style="display: flex; gap: 1rem; justify-content: center;">
      <button type="submit" style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);">
        🗑️ Yes, Delete Permanently
      </button>
      <a href="dashboard.php" style="text-decoration: none;">
        <button type="button" class="btn-secondary">❌ Cancel</button>
      </a>
    </div>
  </form>
</div>

<?php require_once "../includes/footer.php"; ?>