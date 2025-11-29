<?php
require_once __DIR__ . "/../includes/db.php";
require_once __DIR__ . "/../includes/auth.php";

require_admin();

// Handle actions: approve / reject
if (isset($_GET['action'], $_GET['id'])) {
  $action = $_GET['action'];
  $id = (int) $_GET['id'];

  if ($id > 0 && in_array($action, ['approve', 'reject'], true)) {
    $newStatus = $action === 'approve' ? 'approved' : 'rejected';
    $stmt = $pdo->prepare("UPDATE questions SET status = ? WHERE id = ?");
    $stmt->execute([$newStatus, $id]);

    $_SESSION['success'] = "Question folder has been " . ($newStatus === 'approved' ? "approved" : "rejected") . ".";
    header("Location: questions_pending.php");
    exit;
  }
}

require_once __DIR__ . "/../includes/header.php";

// Fetch pending question folder submissions
$stmt = $pdo->query("
    SELECT q.id, q.title, q.year, q.subject, q.qtype, q.description, q.drive_folder_link, q.created_at, u.name AS author
    FROM questions q
    LEFT JOIN users u ON q.user_id = u.id
    WHERE q.status = 'pending'
    ORDER BY q.created_at ASC
");
$pending = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card">
  <h2 style="color: #1e40af;">📝 Pending Question Folders</h2>

  <?php if (isset($_SESSION['success'])): ?>
    <div style="background: #d1fae5; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
      ✅ <?php echo htmlspecialchars($_SESSION['success']);
        unset($_SESSION['success']); ?>
    </div>
  <?php endif; ?>

  <?php if (empty($pending)): ?>
    <p style="color: #059669; font-size: 1.1rem;">✅ No pending question submissions. All clear!</p>
  <?php else: ?>
    <p style="color: #374151; margin-bottom: 1.5rem;">
      Review the Drive folder link, check contents in new tab, then Approve or Reject.
    </p>

    <div class="notes-list">
      <?php foreach ($pending as $item): ?>
        <div class="card" style="border-left: 4px solid #fbbf24;">
          <h3 style="margin-bottom: 0.75rem; color: #1e40af;">
            <?php echo htmlspecialchars($item['title'] ?: ($item['subject'] . ' - ' . $item['year'])); ?>
          </h3>

          <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.75rem;">
            <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
              📅 <?php echo htmlspecialchars($item['year']); ?>
            </span>
            <?php if ($item['subject']): ?>
              <span style="background: #e0e7ff; color: #4338ca; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
                📚 <?php echo htmlspecialchars($item['subject']); ?>
              </span>
            <?php endif; ?>
            <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
              📊 <?php echo htmlspecialchars($item['qtype']); ?>
            </span>
          </div>

          <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 0.75rem;">
            ✍️ By <?php echo htmlspecialchars($item['author'] ?? 'Unknown'); ?>
            | 📅 <?php echo date('d M Y, h:i A', strtotime($item['created_at'])); ?>
          </p>

          <?php if (!empty($item['description'])): ?>
            <div style="background: #f3f4f6; padding: 0.75rem; border-radius: 6px; margin: 0.75rem 0;">
              <p style="color: #374151; margin: 0; line-height: 1.6;">
                <?php echo nl2br(htmlspecialchars($item['description'])); ?>
              </p>
            </div>
          <?php endif; ?>

          <div style="background: #eff6ff; padding: 0.75rem; border-radius: 6px; margin: 0.75rem 0;">
            <strong style="color: #1e40af;">🔗 Google Drive Link:</strong>
            <br>
            <a href="<?php echo htmlspecialchars($item['drive_folder_link']); ?>" target="_blank" rel="noopener" style="color: #2563eb; word-break: break-all;">
              <?php echo htmlspecialchars($item['drive_folder_link']); ?>
            </a>
          </div>

          <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
            <a href="questions_pending.php?action=approve&id=<?php echo $item['id']; ?>">
              <button type="button" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                ✅ Approve
              </button>
            </a>
            <a href="questions_pending.php?action=reject&id=<?php echo $item['id']; ?>">
              <button type="button" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                ❌ Reject
              </button>
            </a>
            <a href="<?php echo htmlspecialchars($item['drive_folder_link']); ?>" target="_blank" rel="noopener">
              <button type="button" class="btn-secondary">
                🔗 Open Folder
              </button>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>