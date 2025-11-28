<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

$slug = trim($_GET['slug'] ?? '');

if ($slug === '') {
  echo "<p>Note not found.</p>";
  require_once "../includes/footer.php";
  exit;
}

$stmt = $pdo->prepare("
    SELECT n.title, n.category, n.tags, n.content, n.created_at, u.name AS author
    FROM notes n
    JOIN users u ON n.user_id = u.id
    WHERE n.slug = ? AND n.status = 'approved'
    LIMIT 1
");
$stmt->execute([$slug]);
$note = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$note) {
  echo "<p>Note not found or not approved yet.</p>";
  require_once "../includes/footer.php";
  exit;
}
?>

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

  <?php
  // Bottom Ad block yahan
  // <!-- Google AdSense -->
  ?>

  <?php if (!empty($note['tags'])): ?>
    <p><small>Tags: <?php echo htmlspecialchars($note['tags']); ?></small></p>
  <?php endif; ?>
</article>

<?php require_once "../includes/footer.php"; ?>