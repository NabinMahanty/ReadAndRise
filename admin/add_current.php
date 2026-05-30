<?php
// admin/add_current.php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../includes/csrf.php";

require_admin();

$errors = [];
$title = "";
$summary = "";
$content = "";
$image_name = null;

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
  $title = trim($_POST['title'] ?? '');
  $summary = trim($_POST['summary'] ?? '');
  $content = trim($_POST['content'] ?? '');

  if ($title === '') $errors[] = "Title is required.";
  if ($content === '') $errors[] = "Content is required.";

  // handle image upload (optional)
  if (!empty($_FILES['image']['name'])) {
    $file = $_FILES['image'];

    if ($file['error'] === 0) {
      $fileSize = $file['size'];
      $maxSize = 2 * 1024 * 1024; // 2MB limit

      if ($fileSize > $maxSize) {
        $errors[] = "Image must be under 2MB.";
      } else {
        // Validate MIME
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (!array_key_exists($mime, $allowed)) {
          $errors[] = "Only JPG/PNG/WEBP images are allowed.";
        } else {
          $ext = $allowed[$mime];
          $newName = 'ca_' . time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
          $uploadDir = __DIR__ . '/../uploads/current/';
          if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
          }
          $uploadPath = $uploadDir . $newName;

          if (!is_uploaded_file($file['tmp_name']) || !move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $errors[] = "Failed to upload image.";
          } else {
            // store filename-only; templates will prefix uploads/current/
            $image_name = $newName;
          }
        }
      }
    } else {
      $errors[] = "Error uploading image.";
    }
  }

  if (empty($errors)) {
    // since admin is creating, publish directly as approved
    $stmt = $pdo->prepare("
            INSERT INTO current_affairs (user_id, title, summary, content, image_path, status)
            VALUES (?, ?, ?, ?, ?, 'approved')
        ");
    $stmt->execute([$_SESSION['user_id'], $title, $summary, $content, $image_name]);

    $success_msg = "Current Affairs post published successfully.";
    // reset form variables
    $title = $summary = $content = "";
    $image_name = null;
  }
}
?>

<div class="card">
  <h2>Add Current Affairs (Admin)</h2>

  <?php if (!empty($errors)): ?>
    <div class="alert-error">
      <ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>
    </div>
  <?php endif; ?>

  <?php if (!empty($success_msg)): ?>
    <div class="alert-success"><?php echo htmlspecialchars($success_msg); ?></div>
  <?php endif; ?>

  <form method="post" enctype="multipart/form-data" class="card">
    <label>
      Title:<br>
      <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
    </label>

    <label>
      Short summary (optional):<br>
      <input type="text" name="summary" value="<?php echo htmlspecialchars($summary); ?>">
    </label>

    <label>
      Content (full):<br>
      <textarea name="content" rows="8"><?php echo htmlspecialchars($content); ?></textarea>
    </label>

    <label>
      Image (optional, JPG/PNG/WEBP, &lt;=2MB):<br>
      <input type="file" name="image" accept="image/*">
    </label>

    <div style="margin-top:10px;">
      <?php echo csrf_field(); ?>
      <button type="submit">Publish Current Affairs</button>
    </div>
  </form>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>