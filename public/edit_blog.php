<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

require_login();

$blog_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the blog and verify ownership
$stmt = $pdo->prepare("SELECT * FROM blogs WHERE id = ? AND user_id = ?");
$stmt->execute([$blog_id, $_SESSION['user_id']]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
  $_SESSION['error'] = "Success story not found or you don't have permission to edit it.";
  header("Location: dashboard.php");
  exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if (empty($title) || empty($category) || empty($content)) {
    $error = "All fields are required.";
  } else {
    // Generate slug from title
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

    // Check if slug exists (excluding current blog)
    $checkSlug = $pdo->prepare("SELECT id FROM blogs WHERE slug = ? AND id != ?");
    $checkSlug->execute([$slug, $blog_id]);
    if ($checkSlug->fetch()) {
      $slug .= '-' . time();
    }

    $updateStmt = $pdo->prepare("
            UPDATE blogs 
            SET title = ?, slug = ?, category = ?, content = ?, status = 'pending'
            WHERE id = ? AND user_id = ?
        ");

    if ($updateStmt->execute([$title, $slug, $category, $content, $blog_id, $_SESSION['user_id']])) {
      $_SESSION['success'] = "Success story updated and resubmitted for approval.";
      header("Location: dashboard.php");
      exit;
    } else {
      $error = "Failed to update success story.";
    }
  }
}

require_once "../includes/header.php";
?>

<div class="card">
  <h2>✏️ Edit Success Story</h2>
  <p style="color: #6b7280; margin-bottom: 1.5rem;">
    Update your success story. Changes will be resubmitted for admin approval.
  </p>

  <?php if (isset($error)): ?>
    <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
      ⚠️ <?php echo htmlspecialchars($error); ?>
    </div>
  <?php endif; ?>

  <form method="POST">
    <label>📝 Title *</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($blog['title']); ?>" required>

    <label>📂 Category *</label>
    <select name="category" required>
      <option value="">-- Select Category --</option>
      <option value="CDS Success" <?php echo $blog['category'] === 'CDS Success' ? 'selected' : ''; ?>>CDS Success</option>
      <option value="AFCAT Success" <?php echo $blog['category'] === 'AFCAT Success' ? 'selected' : ''; ?>>AFCAT Success</option>
      <option value="NDA Success" <?php echo $blog['category'] === 'NDA Success' ? 'selected' : ''; ?>>NDA Success</option>
      <option value="Preparation Journey" <?php echo $blog['category'] === 'Preparation Journey' ? 'selected' : ''; ?>>Preparation Journey</option>
      <option value="Motivation" <?php echo $blog['category'] === 'Motivation' ? 'selected' : ''; ?>>Motivation</option>
      <option value="Tips & Strategy" <?php echo $blog['category'] === 'Tips & Strategy' ? 'selected' : ''; ?>>Tips & Strategy</option>
      <option value="Other" <?php echo $blog['category'] === 'Other' ? 'selected' : ''; ?>>Other</option>
    </select>

    <label>📄 Your Story *</label>
    <textarea name="content" rows="15" required><?php echo htmlspecialchars($blog['content']); ?></textarea>
    <small style="color: #6b7280;">Share your preparation journey, challenges, strategies, and what helped you succeed.</small>

    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
      <button type="submit">💾 Update Story</button>
      <a href="dashboard.php" style="text-decoration: none;">
        <button type="button" class="btn-secondary">❌ Cancel</button>
      </a>
    </div>
  </form>
</div>

<?php require_once "../includes/footer.php"; ?>