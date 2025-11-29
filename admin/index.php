<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/header.php";

require_admin();

// Get all statistics
$stats = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM notes WHERE status = 'pending') as pending_notes,
        (SELECT COUNT(*) FROM notes WHERE status = 'approved') as approved_notes,
        (SELECT COUNT(*) FROM blogs WHERE status = 'pending') as pending_blogs,
        (SELECT COUNT(*) FROM blogs WHERE status = 'approved') as approved_blogs,
        (SELECT COUNT(*) FROM current_affairs WHERE status = 'pending') as pending_current,
        (SELECT COUNT(*) FROM current_affairs WHERE status = 'approved') as approved_current,
        (SELECT COUNT(*) FROM questions WHERE status = 'pending') as pending_questions,
        (SELECT COUNT(*) FROM questions WHERE status = 'approved') as approved_questions,
        (SELECT COUNT(*) FROM users) as total_users
")->fetch(PDO::FETCH_ASSOC);

$totalPending = $stats['pending_notes'] + $stats['pending_blogs'] + $stats['pending_current'] + $stats['pending_questions'];
?>

<div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; border-left: 4px solid #fbbf24;">
  <h2 style="color: #fbbf24;">⚙️ Admin Command Center</h2>
  <p style="color: #cbd5e1;">Welcome back, <strong><?php echo htmlspecialchars($_SESSION['user_name']); ?></strong> (Administrator)</p>
  <p style="color: #94a3b8; font-size: 0.95rem; margin-top: 0.5rem;">
    You have <strong style="color: #fbbf24;"><?php echo $totalPending; ?></strong> items pending moderation.
  </p>
</div>

<!-- Statistics Overview -->
<div class="card" style="margin-top: 2rem;">
  <h3 style="color: #1e40af; margin-bottom: 1.5rem;">📊 Platform Statistics</h3>
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
    <div style="text-align: center; padding: 1.25rem; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px; border-left: 4px solid #3b82f6;">
      <div style="font-size: 2.5rem; font-weight: 800; color: #1e40af;"><?php echo $stats['approved_notes']; ?></div>
      <div style="font-size: 0.875rem; color: #1e3a8a; font-weight: 600; margin-top: 0.5rem;">Study Materials</div>
      <div style="font-size: 0.75rem; color: #3b82f6; margin-top: 0.25rem;"><?php echo $stats['pending_notes']; ?> pending</div>
    </div>
    <div style="text-align: center; padding: 1.25rem; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-radius: 12px; border-left: 4px solid #f59e0b;">
      <div style="font-size: 2.5rem; font-weight: 800; color: #92400e;"><?php echo $stats['approved_blogs']; ?></div>
      <div style="font-size: 0.875rem; color: #78350f; font-weight: 600; margin-top: 0.5rem;">Success Stories</div>
      <div style="font-size: 0.75rem; color: #f59e0b; margin-top: 0.25rem;"><?php echo $stats['pending_blogs']; ?> pending</div>
    </div>
    <div style="text-align: center; padding: 1.25rem; background: linear-gradient(135deg, #fef3c7 0%, #fcd34d 100%); border-radius: 12px; border-left: 4px solid #d97706;">
      <div style="font-size: 2.5rem; font-weight: 800; color: #92400e;"><?php echo $stats['approved_current']; ?></div>
      <div style="font-size: 0.875rem; color: #78350f; font-weight: 600; margin-top: 0.5rem;">Current Affairs</div>
      <div style="font-size: 0.75rem; color: #d97706; margin-top: 0.25rem;"><?php echo $stats['pending_current']; ?> pending</div>
    </div>
    <div style="text-align: center; padding: 1.25rem; background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); border-radius: 12px; border-left: 4px solid #6366f1;">
      <div style="font-size: 2.5rem; font-weight: 800; color: #3730a3;"><?php echo $stats['approved_questions']; ?></div>
      <div style="font-size: 0.875rem; color: #312e81; font-weight: 600; margin-top: 0.5rem;">Question Papers</div>
      <div style="font-size: 0.75rem; color: #6366f1; margin-top: 0.25rem;"><?php echo $stats['pending_questions']; ?> pending</div>
    </div>
    <div style="text-align: center; padding: 1.25rem; background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); border-radius: 12px; border-left: 4px solid #10b981;">
      <div style="font-size: 2.5rem; font-weight: 800; color: #065f46;"><?php echo $stats['total_users']; ?></div>
      <div style="font-size: 0.875rem; color: #064e3b; font-weight: 600; margin-top: 0.5rem;">Total Users</div>
      <div style="font-size: 0.75rem; color: #10b981; margin-top: 0.25rem;">Active Members</div>
    </div>
  </div>
</div>

<div class="home-grid" style="margin-top: 2rem;">
  <section>
    <div class="card" style="border-left: 4px solid #3b82f6; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="color: #1e40af; margin: 0;">📚 Study Materials</h3>
        <?php if ($stats['pending_notes'] > 0): ?>
          <span style="background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
            <?php echo $stats['pending_notes']; ?> NEW
          </span>
        <?php endif; ?>
      </div>
      <p style="font-size: 2.5rem; font-weight: bold; color: #3b82f6; margin: 1rem 0;"><?php echo $stats['pending_notes']; ?></p>
      <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1rem;">
        <?php echo $stats['approved_notes']; ?> approved • <?php echo ($stats['pending_notes'] + $stats['approved_notes']); ?> total
      </p>
      <a href="notes_pending.php" class="btn" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); width: 100%;">
        <?php echo $stats['pending_notes'] > 0 ? 'Review Now →' : 'View All →'; ?>
      </a>
    </div>
  </section>

  <section>
    <div class="card" style="border-left: 4px solid #f59e0b; background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="color: #92400e; margin: 0;">✨ Success Stories</h3>
        <?php if ($stats['pending_blogs'] > 0): ?>
          <span style="background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
            <?php echo $stats['pending_blogs']; ?> NEW
          </span>
        <?php endif; ?>
      </div>
      <p style="font-size: 2.5rem; font-weight: bold; color: #f59e0b; margin: 1rem 0;"><?php echo $stats['pending_blogs']; ?></p>
      <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1rem;">
        <?php echo $stats['approved_blogs']; ?> approved • <?php echo ($stats['pending_blogs'] + $stats['approved_blogs']); ?> total
      </p>
      <a href="blogs_pending.php" class="btn" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); width: 100%;">
        <?php echo $stats['pending_blogs'] > 0 ? 'Review Now →' : 'View All →'; ?>
      </a>
    </div>
  </section>

  <section>
    <div class="card" style="border-left: 4px solid #d97706; background: linear-gradient(135deg, #fffbeb 0%, #fcd34d 100%);">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="color: #92400e; margin: 0;">📰 Current Affairs</h3>
        <?php if ($stats['pending_current'] > 0): ?>
          <span style="background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
            <?php echo $stats['pending_current']; ?> NEW
          </span>
        <?php endif; ?>
      </div>
      <p style="font-size: 2.5rem; font-weight: bold; color: #d97706; margin: 1rem 0;"><?php echo $stats['pending_current']; ?></p>
      <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1rem;">
        <?php echo $stats['approved_current']; ?> approved • <?php echo ($stats['pending_current'] + $stats['approved_current']); ?> total
      </p>
      <a href="current_pending.php" class="btn" style="background: linear-gradient(135deg, #d97706 0%, #b45309 100%); width: 100%;">
        <?php echo $stats['pending_current'] > 0 ? 'Review Now →' : 'View All →'; ?>
      </a>
    </div>
  </section>

  <section>
    <div class="card" style="border-left: 4px solid #6366f1; background: linear-gradient(135deg, #f5f3ff 0%, #e0e7ff 100%);">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <h3 style="color: #3730a3; margin: 0;">📝 Question Papers</h3>
        <?php if ($stats['pending_questions'] > 0): ?>
          <span style="background: #ef4444; color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700;">
            <?php echo $stats['pending_questions']; ?> NEW
          </span>
        <?php endif; ?>
      </div>
      <p style="font-size: 2.5rem; font-weight: bold; color: #6366f1; margin: 1rem 0;"><?php echo $stats['pending_questions']; ?></p>
      <p style="color: #64748b; font-size: 0.875rem; margin-bottom: 1rem;">
        <?php echo $stats['approved_questions']; ?> approved • <?php echo ($stats['pending_questions'] + $stats['approved_questions']); ?> total
      </p>
      <a href="questions_pending.php" class="btn" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); width: 100%;">
        <?php echo $stats['pending_questions'] > 0 ? 'Review Now →' : 'View All →'; ?>
      </a>
    </div>
  </section>
</div>

<div class="card" style="margin-top: 2rem; border-left: 4px solid #10b981; background: linear-gradient(135deg, #f0fdf4 0%, #d1fae5 100%);">
  <h3 style="color: #065f46;">📋 Moderation Guidelines</h3>
  <p style="font-size: 0.95rem; color: #047857; margin-bottom: 1rem;">
    Approve only content that meets these criteria:
  </p>
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1rem;">
    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
      <span style="font-size: 1.5rem;">✅</span>
      <div>
        <strong style="color: #065f46;">Relevant & Useful</strong>
        <p style="font-size: 0.875rem; color: #047857; margin: 0;">Content must be helpful for exam preparation</p>
      </div>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
      <span style="font-size: 1.5rem;">✅</span>
      <div>
        <strong style="color: #065f46;">Professional Language</strong>
        <p style="font-size: 0.875rem; color: #047857; margin: 0;">No abusive, vulgar, or offensive content</p>
      </div>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
      <span style="font-size: 1.5rem;">✅</span>
      <div>
        <strong style="color: #065f46;">No Spam</strong>
        <p style="font-size: 0.875rem; color: #047857; margin: 0;">Reject promotional content or external links (except Drive)</p>
      </div>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
      <span style="font-size: 1.5rem;">✅</span>
      <div>
        <strong style="color: #065f46;">Well Formatted</strong>
        <p style="font-size: 0.875rem; color: #047857; margin: 0;">Properly structured and readable content</p>
      </div>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
      <span style="font-size: 1.5rem;">✅</span>
      <div>
        <strong style="color: #065f46;">Accurate Information</strong>
        <p style="font-size: 0.875rem; color: #047857; margin: 0;">Verify facts for current affairs and educational content</p>
      </div>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
      <span style="font-size: 1.5rem;">✅</span>
      <div>
        <strong style="color: #065f46;">Valid Links</strong>
        <p style="font-size: 0.875rem; color: #047857; margin: 0;">Ensure Google Drive links are accessible</p>
      </div>
    </div>
  </div>
</div>

<?php require_once "../includes/footer.php"; ?>