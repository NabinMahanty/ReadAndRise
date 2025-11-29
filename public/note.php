<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

$slug = trim($_GET['slug'] ?? '');
$preview = isset($_GET['preview']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if ($slug === '') {
  echo "<div class='card'><p>Note not found.</p></div>";
  require_once "../includes/footer.php";
  exit;
}

// If preview mode (admin only), allow viewing pending notes
$statusCondition = $preview ? "" : "AND n.status = 'approved'";

$stmt = $pdo->prepare("
    SELECT n.title, n.category, n.tags, n.content, n.attachment_path, n.created_at, n.status, u.name AS author
    FROM notes n
    JOIN users u ON n.user_id = u.id
    WHERE n.slug = ? $statusCondition
    LIMIT 1
");
$stmt->execute([$slug]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$note) {
  echo "<div class='card'><p>Note not found or not approved yet.</p></div>";
  require_once "../includes/footer.php";
  exit;
}
?>

<?php if ($preview && $note['status'] === 'pending'): ?>
  <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
    <strong style="color: #92400e;">⚠️ ADMIN PREVIEW MODE</strong>
    <p style="color: #78350f; margin: 0.5rem 0 0 0;">
      This note is in <strong>pending</strong> status. Only admins can see this preview.
    </p>
  </div>
<?php endif; ?>

<article class="card">
  <h2><?php echo htmlspecialchars($note['title']); ?></h2>
  <p>
    <small>
      Category: <?php echo htmlspecialchars($note['category']); ?>
      | By: <?php echo htmlspecialchars($note['author']); ?>
      | On: <?php echo htmlspecialchars($note['created_at']); ?>
    </small>
  </p>

  <?php
  // Top Ad block yahan
  // <!-- Google AdSense -->
  ?>

  <div class="note-content">
    <?php
    // content already HTML/text hai, isliye direct echo:
    echo nl2br(htmlspecialchars($note['content']));
    ?>
  </div>

  <?php if (!empty($note['attachment_path'])): ?>
    <h3>Attached PDF</h3>
    <iframe
      src="/readandrise/uploads/notes/<?php echo htmlspecialchars($note['attachment_path']); ?>#toolbar=0"
      width="100%"
      height="600px"
      style="border: 1px solid #ccc; border-radius:10px;">
    </iframe>
  <?php endif; ?>

  <?php
  // Bottom Ad block yahan
  // <!-- Google AdSense -->
  ?>

  <?php if (!empty($note['tags'])): ?>
    <p><small>Tags: <?php echo htmlspecialchars($note['tags']); ?></small></p>
  <?php endif; ?>
</article>

<?php require_once "../includes/footer.php"; ?>