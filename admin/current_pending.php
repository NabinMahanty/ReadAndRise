<?php
// admin/current_pending.php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";

require_admin();

// handle actions: approve / reject
if (isset($_GET['action'], $_GET['id'])) {
  $action = $_GET['action'];
  $id = (int) $_GET['id'];

  if ($id > 0 && in_array($action, ['approve', 'reject'], true)) {
    $newStatus = $action === 'approve' ? 'approved' : 'rejected';
    $stmt = $pdo->prepare("UPDATE current_affairs SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);

    $msg = "Current affairs post has been " . ($newStatus === 'approved' ? "approved" : "rejected") . ".";
    header("Location: current_pending.php?msg=" . urlencode($msg));
    exit;
  }
}

require_once __DIR__ . "/../includes/header.php";

// fetch pending current affairs
$stmt = $pdo->query("
    SELECT c.id, c.title, c.summary, c.image_path, c.created_at, u.name AS author
    FROM current_affairs c
    LEFT JOIN users u ON c.user_id = u.id
    WHERE c.status = 'pending'
    ORDER BY c.created_at ASC
");
$pending = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
  <h2>Pending Current Affairs</h2>

  <?php if (!empty($_GET['msg'])): ?>
    <div class="alert-success"><?php echo htmlspecialchars($_GET['msg']); ?></div>
  <?php endif; ?>

  <?php if (empty($pending)): ?>
    <p>No pending current affairs posts. All clear ✅</p>
  <?php else: ?>
    <p style="color:#374151;">Review each post, preview the content/image, then Approve or Reject.</p>
    <ul style="list-style:none; margin-top:12px;">
      <?php foreach ($pending as $item): ?>
        <li style="margin-bottom:14px; padding:12px; background:#fff; border-radius:10px; box-shadow:0 1px 6px rgba(15,23,42,0.06);">
          <strong><?php echo htmlspecialchars($item['title']); ?></strong>
          <br>
          <small>
            By: <?php echo htmlspecialchars($item['author'] ?? 'Admin'); ?> |
            On: <?php echo htmlspecialchars($item['created_at']); ?>
          </small>

          <?php if (!empty($item['summary'])): ?>
            <div style="margin-top:8px; color:#374151;"><?php echo nl2br(htmlspecialchars($item['summary'])); ?></div>
          <?php endif; ?>

          <?php if (!empty($item['image_path'])): ?>
            <div style="margin-top:8px;">
              <img src="/ReadAndRise/uploads/current/<?php echo htmlspecialchars($item['image_path']); ?>" alt="" style="max-width:180px;border-radius:6px;box-shadow:0 2px 8px rgba(0,0,0,0.08);">
            </div>
          <?php endif; ?>

          <div style="margin-top:10px;">
            <a href="current_pending.php?action=approve&id=<?php echo (int)$item['id']; ?>">
              <button type="button">Approve</button>
            </a>
            <a href="current_pending.php?action=reject&id=<?php echo (int)$item['id']; ?>">
              <button type="button" class="btn-secondary" style="margin-left:8px;">Reject</button>
            </a>
            <a href="/ReadAndRise/public/current.php?id=<?php echo (int)$item['id']; ?>" target="_blank" style="margin-left:12px; color:#2563eb;">Preview public page</a>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>