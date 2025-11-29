<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
  echo "<div class='card'><p>Current affairs post not found.</p></div>";
  require_once "../includes/footer.php";
  exit;
}

$stmt = $pdo->prepare("
    SELECT c.*, u.name AS author 
    FROM current_affairs c 
    LEFT JOIN users u ON c.user_id = u.id 
    WHERE c.id = ? AND c.status = 'approved' 
    LIMIT 1
");
$stmt->execute([$id]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$post) {
  echo "<div class='card'><p>Current affairs post not found or not yet approved.</p></div>";
  require_once "../includes/footer.php";
  exit;
}
?>

<article class="card">
  <div style="margin-bottom: 1.5rem;">
    <h1 style="color: #1e40af; margin-bottom: 0.75rem; font-size: 2rem;">
      <?php echo htmlspecialchars($post['title']); ?>
    </h1>
    <p style="color: #6b7280; font-size: 0.875rem;">
      ✍️ By <?php echo htmlspecialchars($post['author'] ?? 'Admin'); ?>
      | 📅 <?php echo date('d M Y', strtotime($post['created_at'])); ?>
    </p>
  </div>

  <?php if (!empty($post['image_path'])): ?>
    <div style="margin: 1.5rem 0;">
      <img
        src="../uploads/current/<?php echo htmlspecialchars($post['image_path']); ?>"
        alt="<?php echo htmlspecialchars($post['title']); ?>"
        style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 12px; box-shadow: 0 4px 16px rgba(0,0,0,0.1);">
    </div>
  <?php endif; ?>

  <?php if (!empty($post['summary'])): ?>
    <div style="background: #eff6ff; padding: 1.25rem; border-left: 4px solid #3b82f6; border-radius: 8px; margin: 1.5rem 0;">
      <strong style="color: #1e40af; display: block; margin-bottom: 0.5rem;">📌 Summary</strong>
      <p style="color: #1f2937; line-height: 1.7; margin: 0;">
        <?php echo nl2br(htmlspecialchars($post['summary'])); ?>
      </p>
    </div>
  <?php endif; ?>

  <div class="note-content" style="margin-top: 1.5rem; color: #374151; line-height: 1.8; font-size: 1.05rem;">
    <?php echo nl2br(htmlspecialchars($post['content'])); ?>
  </div>

  <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #e5e7eb;">
    <a href="current_affairs.php" style="text-decoration: none;">
      <button type="button" class="btn-secondary">
        ← Back to Current Affairs
      </button>
    </a>
  </div>
</article>

<?php require_once "../includes/footer.php"; ?>