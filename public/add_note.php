<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/header.php";

require_login(); // ensure user logged in

$errors = [];
$title = "";
$category = "";
$tags = "";
$content = "";

function make_slug($string)
{
  $slug = strtolower(trim($string));
  $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
  $slug = trim($slug, '-');
  return $slug;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title    = trim($_POST['title'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $tags     = trim($_POST['tags'] ?? '');
  $content  = trim($_POST['content'] ?? '');

  if ($title === '') {
    $errors[] = "Title is required.";
  }
  if ($category === '') {
    $errors[] = "Category is required.";
  }
  if ($content === '') {
    $errors[] = "Content cannot be empty.";
  }

  $attachment_path = null;

  if (!empty($_FILES['note_file']['name'])) {
    $file = $_FILES['note_file'];

    // validation
    if ($file['error'] === 0) {
      $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
      $fileSize = $file['size'];
      $maxSize = 10 * 1024 * 1024; // 10MB in bytes

      if ($ext !== 'pdf') {
        $errors[] = "Only PDF files are allowed.";
      } elseif ($fileSize > $maxSize) {
        $errors[] = "PDF file size must not exceed 10MB. Your file is " . round($fileSize / 1024 / 1024, 2) . "MB.";
      } else {
        // unique filename
        $newName = 'note_' . time() . '_' . rand(1000, 9999) . '.pdf';
        $uploadPath = "../uploads/notes/" . $newName;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
          $attachment_path = $newName;
        } else {
          $errors[] = "Failed to upload PDF.";
        }
      }
    }
  }

  if (empty($errors)) {
    $slug = make_slug($title);

    // unique slug banana
    $base_slug = $slug;
    $i = 1;
    while (true) {
      $stmt = $pdo->prepare("SELECT id FROM notes WHERE slug = ?");
      $stmt->execute([$slug]);
      if ($stmt->fetch()) {
        $slug = $base_slug . '-' . $i;
        $i++;
      } else {
        break;
      }
    }

    $stmt = $pdo->prepare("
    INSERT INTO notes (user_id, title, slug, category, tags, content, attachment_path, status)
    VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
");
    $stmt->execute([
      $_SESSION['user_id'],
      $title,
      $slug,
      $category,
      $tags,
      $content,
      $attachment_path
    ]);

    // Display success message
    $success_msg = "✅ Excellent! Your material has been submitted successfully and is pending admin approval. You'll be notified once it's published. Thank you for contributing to our community!";
    // Reset form
    $title = $category = $tags = $content = "";
  }
}
?>

<h2>📝 Upload Study Material</h2>

<div class="alert-info" style="margin-bottom: 1.5rem;">
  <strong>🎯 Guidelines for Quality Contributions:</strong>
  <ul style="margin-top: 0.5rem; padding-left: 1.25rem;">
    <li>Provide clear, descriptive titles</li>
    <li>Categorize accurately for easy discovery</li>
    <li>Use relevant tags (comma-separated)</li>
    <li>Upload PDF files for supplementary content (optional)</li>
    <li>Your material will be reviewed before publication</li>
  </ul>
</div>

<?php if (!empty($errors)): ?>
  <div style="color:red;">
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?php echo htmlspecialchars($e); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if (!empty($success_msg)): ?>
  <p style="color:green;"><?php echo htmlspecialchars($success_msg); ?></p>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
  <label>
    📝 Title of Study Material:<br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" placeholder="e.g., Complete Mathematics Notes for CDS Exam" style="width:100%;">
  </label><br><br>

  <label>
    📂 Category (Exam/Subject):<br>
    <input type="text" name="category" value="<?php echo htmlspecialchars($category); ?>" placeholder="e.g., CDS, AFCAT, NDA, Programming, MCA" style="width:100%;">
  </label><br><br>

  <label>
    🏷️ Tags (comma-separated for better discoverability):<br>
    <input type="text" name="tags" value="<?php echo htmlspecialchars($tags); ?>" placeholder="e.g., mathematics, algebra, geometry, defense exams" style="width:100%;">
  </label><br><br>

  <label>
    📄 Upload PDF Document (Optional):<br>
    <input type="file" name="note_file" accept="application/pdf">
    <small style="color: #6b7280; display: block; margin-top: 0.25rem;">Only PDF files accepted. Max size: 10MB recommended</small>
  </label>
  <br><br>

  <label>
    ✍️ Content (Your detailed notes):<br>
    <textarea name="content" rows="12" placeholder="Share your comprehensive notes, key concepts, formulas, tips, and strategies..." style="width:100%;"><?php echo htmlspecialchars($content); ?></textarea>
  </label><br><br>

  <button type="submit">🚀 Submit for Review</button>
  <a href="dashboard.php" style="margin-left: 1rem;">
    <button type="button" class="btn-secondary">Cancel</button>
  </a>
</form>

<?php require_once "../includes/footer.php"; ?>