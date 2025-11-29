<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

require_admin();

// action - BEFORE including header
if (isset($_GET['action'], $_GET['id'])) {
  $action = $_GET['action'];
  $id     = (int) $_GET['id'];

  if ($id > 0 && in_array($action, ['approve', 'reject'], true)) {
    $newStatus = $action === 'approve' ? 'approved' : 'rejected';

    $stmt = $pdo->prepare("UPDATE blogs SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);

    $msg = "Story has been " . ($newStatus === 'approved' ? "approved" : "rejected") . ".";
    header("Location: blogs_pending.php?msg=" . urlencode($msg));
    exit;
  }
}

require_once "../includes/header.php";

$stmt = $pdo->query("
    SELECT b.id, b.title, b.slug, b.category, b.created_at, u.name AS author
    FROM blogs b
    JOIN users u ON b.user_id = u.id
    WHERE b.status = 'pending'
    ORDER BY b.created_at ASC
");
$pendingBlogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
  <h2>Pending Struggle Stories</h2>

  <?php if (!empty($_GET['msg'])): ?>
    <div class="alert-success">
      <?php echo htmlspecialchars($_GET['msg']); ?>
    </div>
  <?php endif; ?>

  <?php if (empty($pendingBlogs)): ?>
    <p>No pending stories. All clear ✅</p>
  <?php else: ?>
    <ul style="list-style:none; margin-top:10px;">
      <?php foreach ($pendingBlogs as $blog): ?>
        <li style="margin-bottom:12px; padding:10px 12px; background:#ffffff; border-radius:10px; box-shadow:0 1px 4px rgba(15,23,42,0.08);">
          <strong><?php echo htmlspecialchars($blog['title']); ?></strong><br>
          <small>
            Category: <?php echo htmlspecialchars($blog['category']); ?>
            | By: <?php echo htmlspecialchars($blog['author']); ?>
            | On: <?php echo htmlspecialchars($blog['created_at']); ?>
          </small>
          <br><br>
          <a href="../public/blog.php?slug=<?php echo urlencode($blog['slug']); ?>&preview=1" target="_blank" style="font-size:13px; color:#2563eb;">
            👁️ Preview Story (opens in new tab)
          </a>
          <br><br>
          <a href="blogs_pending.php?action=approve&id=<?php echo (int)$blog['id']; ?>">
            <button type="button">Approve</button>
          </a>
          <a href="blogs_pending.php?action=reject&id=<?php echo (int)$blog['id']; ?>">
            <button type="button" class="btn-secondary" style="margin-left:6px;">Reject</button>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php require_once "../includes/footer.php"; ?>