<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";

require_admin();

// action handle (approve / reject) - BEFORE including header
if (isset($_GET['action'], $_GET['id'])) {
  $action = $_GET['action'];
  $id     = (int) $_GET['id'];

  if ($id > 0 && in_array($action, ['approve', 'reject'], true)) {
    $newStatus = $action === 'approve' ? 'approved' : 'rejected';

    $stmt = $pdo->prepare("UPDATE notes SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);

    $msg = "Note has been " . ($newStatus === 'approved' ? "approved" : "rejected") . ".";
    // redirect to avoid repeat on refresh
    header("Location: notes_pending.php?msg=" . urlencode($msg));
    exit;
  }
}

require_once "../includes/header.php";

// fetch all pending notes
$stmt = $pdo->query("
    SELECT n.id, n.title, n.slug, n.category, n.tags, n.created_at, u.name AS author
    FROM notes n
    JOIN users u ON n.user_id = u.id
    WHERE n.status = 'pending'
    ORDER BY n.created_at ASC
");
$pendingNotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
  <h2>Pending Notes</h2>

  <?php if (!empty($_GET['msg'])): ?>
    <div class="alert-success">
      <?php echo htmlspecialchars($_GET['msg']); ?>
    </div>
  <?php endif; ?>

  <?php if (empty($pendingNotes)): ?>
    <p>No pending notes. All clear ✅</p>
  <?php else: ?>
    <p style="font-size:14px; color:#4b5563;">
      Review each note and choose <strong>Approve</strong> or <strong>Reject</strong>.
    </p>

    <ul style="list-style:none; margin-top:10px;">
      <?php foreach ($pendingNotes as $note): ?>
        <li style="margin-bottom:12px; padding:10px 12px; background:#ffffff; border-radius:10px; box-shadow:0 1px 4px rgba(15,23,42,0.08);">
          <strong><?php echo htmlspecialchars($note['title']); ?></strong><br>
          <small>
            Category: <?php echo htmlspecialchars($note['category']); ?>
            <?php if (!empty($note['tags'])): ?>
              | Tags: <?php echo htmlspecialchars($note['tags']); ?>
            <?php endif; ?>
            | By: <?php echo htmlspecialchars($note['author']); ?>
            | On: <?php echo htmlspecialchars($note['created_at']); ?>
          </small>
          <br><br>
          <a href="../public/note.php?slug=<?php echo urlencode($note['slug']); ?>&preview=1" target="_blank" style="font-size:13px; color:#2563eb;">
            👁️ Preview Note (opens in new tab)
          </a>
          <br><br>
          <a href="notes_pending.php?action=approve&id=<?php echo (int)$note['id']; ?>">
            <button type="button">Approve</button>
          </a>
          <a href="notes_pending.php?action=reject&id=<?php echo (int)$note['id']; ?>">
            <button type="button" class="btn-secondary" style="margin-left:6px;">Reject</button>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php require_once "../includes/footer.php"; ?>