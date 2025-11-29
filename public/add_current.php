<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $summary = trim($_POST['summary'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if (empty($title) || empty($content)) {
    $error = "Title and content are required.";
  } else {
    $image_path = null;

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
      $allowed = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
      $fileType = $_FILES['image']['type'];
      $fileSize = $_FILES['image']['size'];
      $maxSize = 5 * 1024 * 1024; // 5MB

      if (!in_array($fileType, $allowed)) {
        $error = "Only JPG, PNG, and WebP images are allowed.";
      } elseif ($fileSize > $maxSize) {
        $error = "Image size must not exceed 5MB.";
      } else {
        $uploadDir = "../uploads/current/";
        if (!is_dir($uploadDir)) {
          mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
          $image_path = $fileName;
        } else {
          $error = "Failed to upload image.";
        }
      }
    }

    if (!isset($error)) {
      $stmt = $pdo->prepare("
                INSERT INTO current_affairs (user_id, title, summary, content, image_path, status)
                VALUES (?, ?, ?, ?, ?, 'pending')
            ");

      if ($stmt->execute([$_SESSION['user_id'], $title, $summary, $content, $image_path])) {
        $_SESSION['success'] = "Current affairs post submitted successfully. Awaiting admin approval.";
        header("Location: dashboard.php");
        exit;
      } else {
        $error = "Failed to submit current affairs post.";
      }
    }
  }
}

require_once "../includes/header.php";
?>

<div class="card">
  <h2>📰 Add Current Affairs</h2>
  <p style="color: #6b7280; margin-bottom: 1.5rem;">
    Share important current events, news, and updates relevant to exam preparation.
  </p>

  <?php if (isset($error)): ?>
    <div class="alert alert-error" style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
      ⚠️ <?php echo htmlspecialchars($error); ?>
    </div>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>📝 Title *</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($_POST['title'] ?? ''); ?>" required placeholder="e.g., New Defence Recruitment Policy 2025">

    <label>📄 Summary (Brief overview)</label>
    <textarea name="summary" rows="3" placeholder="A short summary of the news or event (150-200 characters)"><?php echo htmlspecialchars($_POST['summary'] ?? ''); ?></textarea>

    <label>📋 Full Content *</label>
    <textarea name="content" rows="10" required placeholder="Detailed information, key points, dates, sources, and relevance to exams..."><?php echo htmlspecialchars($_POST['content'] ?? ''); ?></textarea>

    <label>🖼️ Image (optional)</label>
    <input type="file" name="image" accept="image/jpeg,image/jpg,image/png,image/webp">
    <small style="color: #6b7280;">JPG, PNG, or WebP. Max size: 5MB</small>

    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
      <button type="submit">📤 Submit for Approval</button>
      <a href="dashboard.php" style="text-decoration: none;">
        <button type="button" class="btn-secondary">❌ Cancel</button>
      </a>
    </div>
  </form>
</div>

<?php require_once "../includes/footer.php"; ?>