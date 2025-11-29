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

?>

<style>
  @media (max-width: 768px) {
    .pending-header {
      padding: 1.5rem 1rem !important;
    }

    .pending-header h1 {
      font-size: 1.5rem !important;
    }

    .pending-header-content {
      flex-direction: column !important;
      align-items: flex-start !important;
    }

    .pending-card {
      padding: 1.25rem !important;
    }

    .pending-card-header {
      flex-direction: column !important;
      align-items: flex-start !important;
    }

    .pending-actions {
      flex-direction: column !important;
      width: 100%;
    }

    .pending-actions a {
      width: 100%;
    }

    .pending-actions button {
      width: 100%;
    }

    .pending-badges {
      flex-wrap: wrap !important;
      gap: 0.5rem !important;
    }
  }

  @media (min-width: 769px) and (max-width: 1024px) {
    .pending-header {
      padding: 2rem 1.5rem !important;
    }
  }
</style>

<?php

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

<!-- Header Section -->
<div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); padding: 2.5rem 2rem; border-radius: 16px; margin-bottom: 2rem; border: 1px solid rgba(148, 163, 184, 0.2);">
  <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 style="color: #f1f5f9; font-size: 2rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
        📝 Pending Question Papers
      </h1>
      <p style="color: #cbd5e1; font-size: 1rem;">
        Review Drive folders and moderate question paper submissions
      </p>
    </div>
    <a href="index.php" style="text-decoration: none;">
      <button style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 0.75rem 1.5rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600;">
        ← Back to Dashboard
      </button>
    </a>
  </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
  <div style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.3); color: #d1fae5; font-weight: 600;">
    ✅ <?php echo htmlspecialchars($_SESSION['success']);
      unset($_SESSION['success']); ?>
  </div>
<?php endif; ?>

<?php if (empty($pending)): ?>
  <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 3rem; text-align: center;">
    <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">✅</div>
    <h3 style="color: #f1f5f9; margin-bottom: 0.75rem; font-size: 1.5rem;">All Caught Up!</h3>
    <p style="color: #cbd5e1; font-size: 1.1rem;">No pending question submissions. All clear! 🎉</p>
  </div>
<?php else: ?>
  <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid rgba(167, 139, 250, 0.3);">
    <p style="color: #cbd5e1; font-size: 0.95rem; margin: 0;">
      📊 <strong style="color: #a78bfa;"><?php echo count($pending); ?></strong> question paper<?php echo count($pending) != 1 ? 's' : ''; ?> awaiting review. Check the Drive folder links before approving.
    </p>
  </div>

  <div style="display: grid; gap: 1rem;">
    <?php foreach ($pending as $item): ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1.75rem;">
        <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem; margin-bottom: 1rem;">
          <div style="flex: 1;">
            <h3 style="color: #f1f5f9; margin-bottom: 1rem; font-size: 1.25rem; font-weight: 600;">
              <?php echo htmlspecialchars($item['title'] ?: ($item['subject'] . ' - ' . $item['year'])); ?>
            </h3>

            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1rem;">
              <span style="background: rgba(59, 130, 246, 0.2); color: #60a5fa; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(59, 130, 246, 0.3);">
                📅 <?php echo htmlspecialchars($item['year']); ?>
              </span>
              <?php if ($item['subject']): ?>
                <span style="background: rgba(168, 85, 247, 0.2); color: #a78bfa; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(168, 85, 247, 0.3);">
                  📚 <?php echo htmlspecialchars($item['subject']); ?>
                </span>
              <?php endif; ?>
              <span style="background: rgba(251, 191, 36, 0.2); color: #fbbf24; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(251, 191, 36, 0.3);">
                📊 <?php echo htmlspecialchars($item['qtype']); ?>
              </span>
            </div>

            <div style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 1rem;">
              <strong style="color: #60a5fa;">✍️ By:</strong> <?php echo htmlspecialchars($item['author'] ?? 'Unknown'); ?>
              <span style="margin: 0 0.5rem;">|</span>
              <strong style="color: #60a5fa;">📅 Submitted:</strong> <?php echo date('d M Y, h:i A', strtotime($item['created_at'])); ?>
            </div>

            <?php if (!empty($item['description'])): ?>
              <div style="background: rgba(15, 23, 42, 0.5); padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid rgba(148, 163, 184, 0.2);">
                <p style="color: #cbd5e1; margin: 0; line-height: 1.6; font-size: 0.95rem;">
                  <?php echo nl2br(htmlspecialchars($item['description'])); ?>
                </p>
              </div>
            <?php endif; ?>

            <div style="background: rgba(59, 130, 246, 0.1); padding: 1rem; border-radius: 8px; border: 1px solid rgba(59, 130, 246, 0.3);">
              <strong style="color: #60a5fa; display: block; margin-bottom: 0.5rem;">🔗 Google Drive Link:</strong>
              <a href="<?php echo htmlspecialchars($item['drive_folder_link']); ?>" target="_blank" rel="noopener" style="color: #93c5fd; word-break: break-all; text-decoration: underline;">
                <?php echo htmlspecialchars($item['drive_folder_link']); ?>
              </a>
            </div>
          </div>
          <span style="background: rgba(251, 191, 36, 0.2); color: #fbbf24; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(251, 191, 36, 0.3);">
            ⏳ Pending
          </span>
        </div>

        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-top: 1rem;">
          <a href="<?php echo htmlspecialchars($item['drive_folder_link']); ?>" target="_blank" rel="noopener" style="text-decoration: none;">
            <button style="background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); padding: 0.625rem 1.25rem; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.875rem;">
              🔗 Open Folder
            </button>
          </a>
          <a href="questions_pending.php?action=approve&id=<?php echo $item['id']; ?>" style="text-decoration: none;">
            <button style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 0.625rem 1.25rem; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.875rem;">
              ✅ Approve
            </button>
          </a>
          <a href="questions_pending.php?action=reject&id=<?php echo $item['id']; ?>" style="text-decoration: none;">
            <button style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); padding: 0.625rem 1.25rem; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.875rem;">
              ❌ Reject
            </button>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . "/../includes/footer.php"; ?>