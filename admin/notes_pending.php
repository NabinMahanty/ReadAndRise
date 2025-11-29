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

    .pending-meta {
      flex-direction: column !important;
      align-items: flex-start !important;
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

<!-- Header Section -->
<div class="pending-header" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); padding: 2.5rem 2rem; border-radius: 16px; margin-bottom: 2rem; border: 1px solid rgba(148, 163, 184, 0.2);">
  <div class="pending-header-content" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
    <div>
      <h1 style="color: #f1f5f9; font-size: 2rem; margin-bottom: 0.5rem; display: flex; align-items: center; gap: 0.75rem;">
        📚 Pending Study Materials
      </h1>
      <p style="color: #cbd5e1; font-size: 1rem;">
        Review and moderate submitted study materials
      </p>
    </div>
    <a href="index.php" style="text-decoration: none;">
      <button style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 0.75rem 1.5rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600;">
        ← Back to Dashboard
      </button>
    </a>
  </div>
</div>

<?php if (!empty($_GET['msg'])): ?>
  <div style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid rgba(16, 185, 129, 0.3); color: #d1fae5; font-weight: 600;">
    ✅ <?php echo htmlspecialchars($_GET['msg']); ?>
  </div>
<?php endif; ?>

<?php if (empty($pendingNotes)): ?>
  <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 3rem; text-align: center;">
    <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">✅</div>
    <h3 style="color: #f1f5f9; margin-bottom: 0.75rem; font-size: 1.5rem;">All Caught Up!</h3>
    <p style="color: #cbd5e1; font-size: 1.1rem;">No pending study materials. Great job keeping up with moderation! 🎉</p>
  </div>
<?php else: ?>
  <div style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); padding: 1.5rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid rgba(59, 130, 246, 0.3);">
    <p style="color: #cbd5e1; font-size: 0.95rem; margin: 0;">
      📊 <strong style="color: #60a5fa;"><?php echo count($pendingNotes); ?></strong> study material<?php echo count($pendingNotes) != 1 ? 's' : ''; ?> awaiting review. Review each item and choose <strong style="color: #34d399;">Approve</strong> or <strong style="color: #f87171;">Reject</strong>.
    </p>
  </div>

  <div style="display: grid; gap: 1rem;">
    <?php foreach ($pendingNotes as $note): ?>
      <div class="card pending-card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1.75rem;">
        <div class="pending-card-header" style="display: flex; justify-content: space-between; align-items: start; gap: 1rem; margin-bottom: 1rem;">
          <div style="flex: 1;">
            <h3 style="color: #f1f5f9; margin-bottom: 0.75rem; font-size: 1.25rem; font-weight: 600;">
              <?php echo htmlspecialchars($note['title']); ?>
            </h3>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.75rem;">
              <span style="background: rgba(168, 85, 247, 0.2); color: #a78bfa; padding: 0.375rem 0.875rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(168, 85, 247, 0.3);">
                📂 <?php echo htmlspecialchars($note['category']); ?>
              </span>
              <?php if (!empty($note['tags'])): ?>
                <span style="background: rgba(59, 130, 246, 0.2); color: #60a5fa; padding: 0.375rem 0.875rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(59, 130, 246, 0.3);">
                  🏷️ <?php echo htmlspecialchars($note['tags']); ?>
                </span>
              <?php endif; ?>
            </div>
            <div style="color: #94a3b8; font-size: 0.875rem;">
              <strong style="color: #60a5fa;">✍️ Author:</strong> <?php echo htmlspecialchars($note['author']); ?>
              <span style="margin: 0 0.5rem;">|</span>
              <strong style="color: #60a5fa;">📅 Submitted:</strong> <?php echo date('d M Y, H:i', strtotime($note['created_at'])); ?>
            </div>
          </div>
          <span style="background: rgba(251, 191, 36, 0.2); color: #fbbf24; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(251, 191, 36, 0.3);">
            ⏳ Pending
          </span>
        </div>

        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
          <a href="../public/note.php?slug=<?php echo urlencode($note['slug']); ?>&preview=1" target="_blank" style="text-decoration: none;">
            <button style="background: rgba(59, 130, 246, 0.2); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); padding: 0.625rem 1.25rem; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.875rem;">
              👁️ Preview Note
            </button>
          </a>
          <a href="notes_pending.php?action=approve&id=<?php echo (int)$note['id']; ?>" style="text-decoration: none;">
            <button style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); padding: 0.625rem 1.25rem; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.875rem;">
              ✅ Approve
            </button>
          </a>
          <a href="notes_pending.php?action=reject&id=<?php echo (int)$note['id']; ?>" style="text-decoration: none;">
            <button style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); padding: 0.625rem 1.25rem; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.875rem;">
              ❌ Reject
            </button>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>