<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

require_login();

$note_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch the note and verify ownership
$stmt = $pdo->prepare("SELECT * FROM notes WHERE id = ? AND user_id = ?");
$stmt->execute([$note_id, $_SESSION['user_id']]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$note) {
  $_SESSION['error'] = "Note not found or you don't have permission to edit it.";
  header("Location: dashboard.php");
  exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $tags = trim($_POST['tags'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if (empty($title) || empty($category) || empty($content)) {
    $error = "Title, category, and content are required.";
  } else {
    // Generate slug from title
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));

    // Check if slug exists (excluding current note)
    $checkSlug = $pdo->prepare("SELECT id FROM notes WHERE slug = ? AND id != ?");
    $checkSlug->execute([$slug, $note_id]);
    if ($checkSlug->fetch()) {
      $slug .= '-' . time();
    }

    // Handle PDF upload if new file is provided
    $attachment_path = $note['attachment_path'];
    if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
      $allowed = ['application/pdf'];
      $fileType = $_FILES['attachment']['type'];
      $fileSize = $_FILES['attachment']['size'];
      $maxSize = 10 * 1024 * 1024; // 10MB

      if (!in_array($fileType, $allowed)) {
        $error = "Only PDF files are allowed.";
      } elseif ($fileSize > $maxSize) {
        $error = "File size must not exceed 10MB.";
      } else {
        $uploadDir = "../uploads/notes/";
        if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['attachment']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['attachment']['tmp_name'], $targetPath)) {
          // Delete old file if exists
          if ($attachment_path && file_exists("../" . $attachment_path)) {
            unlink("../" . $attachment_path);
          }
          $attachment_path = "uploads/notes/" . $fileName;
        } else {
          $error = "Failed to upload file.";
        }
      }
    }

    if (!isset($error)) {
      $updateStmt = $pdo->prepare("
                UPDATE notes 
                SET title = ?, slug = ?, category = ?, tags = ?, content = ?, attachment_path = ?, status = 'pending'
                WHERE id = ? AND user_id = ?
            ");

      if ($updateStmt->execute([$title, $slug, $category, $tags, $content, $attachment_path, $note_id, $_SESSION['user_id']])) {
        $_SESSION['success'] = "Note updated successfully and resubmitted for approval.";
        header("Location: dashboard.php");
        exit;
      } else {
        $error = "Failed to update note.";
      }
    }
  }
}

require_once "../includes/header.php";
?>

<div class="card">
  <h2>✏️ Edit Study Material</h2>
  <p style="color: #6b7280; margin-bottom: 1.5rem;">
    Update your study material. Changes will be resubmitted for admin approval.
  </p>

  <?php if (isset($error)): ?>
    <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
      ⚠️ <?php echo htmlspecialchars($error); ?>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>📝 Title *</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($note['title']); ?>" required>

    <label>📂 Category *</label>
    <select name="category" required>
      <option value="">-- Select Category --</option>
      <option value="CDS" <?php echo $note['category'] === 'CDS' ? 'selected' : ''; ?>>CDS (Combined Defence Services)</option>
      <option value="AFCAT" <?php echo $note['category'] === 'AFCAT' ? 'selected' : ''; ?>>AFCAT (Air Force Common Admission Test)</option>
      <option value="NDA" <?php echo $note['category'] === 'NDA' ? 'selected' : ''; ?>>NDA (National Defence Academy)</option>
      <option value="Computer Science" <?php echo $note['category'] === 'Computer Science' ? 'selected' : ''; ?>>Computer Science</option>
      <option value="Programming" <?php echo $note['category'] === 'Programming' ? 'selected' : ''; ?>>Programming</option>
      <option value="Mathematics" <?php echo $note['category'] === 'Mathematics' ? 'selected' : ''; ?>>Mathematics</option>
      <option value="English" <?php echo $note['category'] === 'English' ? 'selected' : ''; ?>>English</option>
      <option value="General Knowledge" <?php echo $note['category'] === 'General Knowledge' ? 'selected' : ''; ?>>General Knowledge</option>
      <option value="Other" <?php echo $note['category'] === 'Other' ? 'selected' : ''; ?>>Other</option>
    </select>

    <label>🏷️ Tags (comma-separated)</label>
    <input type="text" name="tags" value="<?php echo htmlspecialchars($note['tags']); ?>" placeholder="e.g., algebra, trigonometry, formulas">

    <label>📄 Content *</label>
    <textarea name="content" rows="12" required><?php echo htmlspecialchars($note['content']); ?></textarea>

    <label>📎 PDF Attachment (optional - leave empty to keep current file)</label>
    <?php if ($note['attachment_path']): ?>
      <p style="color: #059669; font-size: 0.875rem; margin-bottom: 0.5rem;">
        ✅ Current file: <?php echo htmlspecialchars(basename($note['attachment_path'])); ?>
      </p>
    <?php endif; ?>
    <input type="file" name="attachment" accept=".pdf">
    <small style="color: #6b7280;">Only PDF files accepted. Max size: 10MB</small>

    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
      <button type="submit">💾 Update Material</button>
      <a href="dashboard.php" style="text-decoration: none;">
        <button type="button" class="btn-secondary">❌ Cancel</button>
      </a>
    </div>
  </form>
</div>

<?php require_once "../includes/footer.php"; ?>