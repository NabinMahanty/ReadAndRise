<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/header.php";

require_admin();

?>

<style>
  @media (max-width: 768px) {
    .admin-hero {
      padding: 1.5rem !important;
    }

    .admin-hero h1 {
      font-size: 1.5rem !important;
    }

    .admin-hero-content {
      flex-direction: column !important;
      text-align: center !important;
    }

    .metrics-grid {
      grid-template-columns: 1fr !important;
    }

    .moderation-grid {
      grid-template-columns: 1fr !important;
    }

    .content-stats-grid {
      grid-template-columns: 1fr !important;
    }

    .card {
      padding: 1.25rem !important;
    }
  }

  @media (min-width: 769px) and (max-width: 1024px) {
    .metrics-grid {
      grid-template-columns: repeat(2, 1fr) !important;
    }

    .moderation-grid {
      grid-template-columns: repeat(2, 1fr) !important;
    }

    .content-stats-grid {
      grid-template-columns: repeat(2, 1fr) !important;
    }
  }
</style>

<?php

// Get all statistics
$stats = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM notes WHERE status = 'pending') as pending_notes,
        (SELECT COUNT(*) FROM notes WHERE status = 'approved') as approved_notes,
        (SELECT COUNT(*) FROM notes WHERE status = 'rejected') as rejected_notes,
        (SELECT COUNT(*) FROM blogs WHERE status = 'pending') as pending_blogs,
        (SELECT COUNT(*) FROM blogs WHERE status = 'approved') as approved_blogs,
        (SELECT COUNT(*) FROM blogs WHERE status = 'rejected') as rejected_blogs,
        (SELECT COUNT(*) FROM current_affairs WHERE status = 'pending') as pending_current,
        (SELECT COUNT(*) FROM current_affairs WHERE status = 'approved') as approved_current,
        (SELECT COUNT(*) FROM current_affairs WHERE status = 'rejected') as rejected_current,
        (SELECT COUNT(*) FROM questions WHERE status = 'pending') as pending_questions,
        (SELECT COUNT(*) FROM questions WHERE status = 'approved') as approved_questions,
        (SELECT COUNT(*) FROM questions WHERE status = 'rejected') as rejected_questions,
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM users WHERE role = 'admin') as total_admins
")->fetch(PDO::FETCH_ASSOC);

$totalPending = $stats['pending_notes'] + $stats['pending_blogs'] + $stats['pending_current'] + $stats['pending_questions'];
$totalApproved = $stats['approved_notes'] + $stats['approved_blogs'] + $stats['approved_current'] + $stats['approved_questions'];
$totalRejected = $stats['rejected_notes'] + $stats['rejected_blogs'] + $stats['rejected_current'] + $stats['rejected_questions'];

// Get recent activities (latest 5 pending items)
$recentActivities = $pdo->query("
    (SELECT 'note' as type, title, created_at, status FROM notes WHERE status = 'pending' ORDER BY created_at DESC LIMIT 3)
    UNION
    (SELECT 'blog' as type, title, created_at, status FROM blogs WHERE status = 'pending' ORDER BY created_at DESC LIMIT 2)
    UNION
    (SELECT 'current' as type, title, created_at, status FROM current_affairs WHERE status = 'pending' ORDER BY created_at DESC LIMIT 2)
    UNION
    (SELECT 'question' as type, title, created_at, status FROM questions WHERE status = 'pending' ORDER BY created_at DESC LIMIT 2)
    ORDER BY created_at DESC
    LIMIT 8
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Admin Hero Header -->
<div class="admin-hero" style="background: linear-gradient(135deg, #7c2d12 0%, #92400e 50%, #b45309 100%); border-radius: 16px; padding: 2.5rem; margin-bottom: 2rem; border: 1px solid rgba(245, 158, 11, 0.3); position: relative; overflow: hidden;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23fff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

  <div style="position: relative; z-index: 1;">
    <div class="admin-hero-content" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
      <div>
        <div style="display: inline-block; background: rgba(255, 255, 255, 0.15); padding: 0.5rem 1.25rem; border-radius: 50px; border: 1px solid rgba(255, 255, 255, 0.3); margin-bottom: 1rem;">
          <span style="color: #fde68a; font-size: 0.875rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">⚙️ Admin Dashboard</span>
        </div>
        <h1 style="color: white; font-size: 2.25rem; margin-bottom: 0.5rem;">Welcome back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
        <p style="color: #fde68a; font-size: 1.05rem;">
          <?php if ($totalPending > 0): ?>
            You have <strong style="color: #fbbf24; font-size: 1.25rem;"><?php echo $totalPending; ?></strong> items awaiting your review
          <?php else: ?>
            All caught up! No pending items at the moment. 🎉
          <?php endif; ?>
        </p>
      </div>

      <div style="text-align: right;">
        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🎯</div>
        <div style="color: #fef3c7; font-size: 0.875rem;">Last login</div>
        <div style="color: white; font-weight: 600;"><?php echo date('d M Y, H:i'); ?></div>
      </div>
    </div>
  </div>
</div>

<!-- Key Metrics Overview -->
<div class="metrics-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
  <!-- Total Pending -->
  <div class="card" style="background: linear-gradient(135deg, #991b1b 0%, #b91c1c 100%); border: 1px solid rgba(239, 68, 68, 0.3); padding: 1.75rem; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -10px; right: -10px; font-size: 4rem; opacity: 0.1;">⏳</div>
    <div style="position: relative; z-index: 1;">
      <div style="color: #fecaca; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Pending Review</div>
      <div style="color: white; font-size: 3rem; font-weight: 800; line-height: 1;"><?php echo $totalPending; ?></div>
      <div style="color: #fecaca; font-size: 0.75rem; margin-top: 0.5rem;">Needs Attention</div>
    </div>
  </div>

  <!-- Total Approved -->
  <div class="card" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); border: 1px solid rgba(16, 185, 129, 0.3); padding: 1.75rem; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -10px; right: -10px; font-size: 4rem; opacity: 0.1;">✅</div>
    <div style="position: relative; z-index: 1;">
      <div style="color: #6ee7b7; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Approved</div>
      <div style="color: white; font-size: 3rem; font-weight: 800; line-height: 1;"><?php echo $totalApproved; ?></div>
      <div style="color: #6ee7b7; font-size: 0.75rem; margin-top: 0.5rem;">Total Approved</div>
    </div>
  </div>

  <!-- Total Rejected -->
  <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.3); padding: 1.75rem; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -10px; right: -10px; font-size: 4rem; opacity: 0.1;">❌</div>
    <div style="position: relative; z-index: 1;">
      <div style="color: #cbd5e1; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Rejected</div>
      <div style="color: white; font-size: 3rem; font-weight: 800; line-height: 1;"><?php echo $totalRejected; ?></div>
      <div style="color: #94a3b8; font-size: 0.75rem; margin-top: 0.5rem;">Total Rejected</div>
    </div>
  </div>

  <!-- Total Users -->
  <div class="card" style="background: linear-gradient(135deg, #1e40af 0%, #2563eb 100%); border: 1px solid rgba(59, 130, 246, 0.3); padding: 1.75rem; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -10px; right: -10px; font-size: 4rem; opacity: 0.1;">👥</div>
    <div style="position: relative; z-index: 1;">
      <div style="color: #93c5fd; font-size: 0.875rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Platform Users</div>
      <div style="color: white; font-size: 3rem; font-weight: 800; line-height: 1;"><?php echo $stats['total_users']; ?></div>
      <div style="color: #93c5fd; font-size: 0.75rem; margin-top: 0.5rem;"><?php echo $stats['total_admins']; ?> Admins</div>
    </div>
  </div>
</div>

<!-- Main Content Grid -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">

  <!-- Left Column: Moderation Tasks -->
  <div>
    <!-- Pending Moderation Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">

      <!-- Study Materials -->
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(59, 130, 246, 0.3); border-left: 4px solid #3b82f6; padding: 1.75rem;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
          <div>
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📚</div>
            <h3 style="color: #dbeafe; font-size: 1.1rem; margin: 0;">Study Materials</h3>
          </div>
          <?php if ($stats['pending_notes'] > 0): ?>
            <span style="background: #ef4444; color: white; padding: 0.375rem 0.875rem; border-radius: 50px; font-size: 0.875rem; font-weight: 700; animation: pulse 2s infinite;">
              <?php echo $stats['pending_notes']; ?>
            </span>
          <?php endif; ?>
        </div>

        <div style="margin-bottom: 1.25rem;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Approved</span>
            <span style="color: #34d399; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['approved_notes']; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Pending</span>
            <span style="color: #fbbf24; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['pending_notes']; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Rejected</span>
            <span style="color: #f87171; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['rejected_notes']; ?></span>
          </div>
        </div>

        <a href="notes_pending.php">
          <button style="width: 100%; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; padding: 0.75rem; border-radius: 8px; font-weight: 600;">
            <?php echo $stats['pending_notes'] > 0 ? 'Review Now →' : 'View All →'; ?>
          </button>
        </a>
      </div>

      <!-- Success Stories -->
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(16, 185, 129, 0.3); border-left: 4px solid #10b981; padding: 1.75rem;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
          <div>
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">✨</div>
            <h3 style="color: #d1fae5; font-size: 1.1rem; margin: 0;">Success Stories</h3>
          </div>
          <?php if ($stats['pending_blogs'] > 0): ?>
            <span style="background: #ef4444; color: white; padding: 0.375rem 0.875rem; border-radius: 50px; font-size: 0.875rem; font-weight: 700; animation: pulse 2s infinite;">
              <?php echo $stats['pending_blogs']; ?>
            </span>
          <?php endif; ?>
        </div>

        <div style="margin-bottom: 1.25rem;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Approved</span>
            <span style="color: #34d399; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['approved_blogs']; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Pending</span>
            <span style="color: #fbbf24; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['pending_blogs']; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Rejected</span>
            <span style="color: #f87171; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['rejected_blogs']; ?></span>
          </div>
        </div>

        <a href="blogs_pending.php">
          <button style="width: 100%; background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 0.75rem; border-radius: 8px; font-weight: 600;">
            <?php echo $stats['pending_blogs'] > 0 ? 'Review Now →' : 'View All →'; ?>
          </button>
        </a>
      </div>

      <!-- Current Affairs -->
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(245, 158, 11, 0.3); border-left: 4px solid #f59e0b; padding: 1.75rem;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
          <div>
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📰</div>
            <h3 style="color: #fde68a; font-size: 1.1rem; margin: 0;">Current Affairs</h3>
          </div>
          <?php if ($stats['pending_current'] > 0): ?>
            <span style="background: #ef4444; color: white; padding: 0.375rem 0.875rem; border-radius: 50px; font-size: 0.875rem; font-weight: 700; animation: pulse 2s infinite;">
              <?php echo $stats['pending_current']; ?>
            </span>
          <?php endif; ?>
        </div>

        <div style="margin-bottom: 1.25rem;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Approved</span>
            <span style="color: #34d399; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['approved_current']; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Pending</span>
            <span style="color: #fbbf24; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['pending_current']; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Rejected</span>
            <span style="color: #f87171; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['rejected_current']; ?></span>
          </div>
        </div>

        <a href="current_pending.php">
          <button style="width: 100%; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; padding: 0.75rem; border-radius: 8px; font-weight: 600;">
            <?php echo $stats['pending_current'] > 0 ? 'Review Now →' : 'View All →'; ?>
          </button>
        </a>
      </div>

      <!-- Question Papers -->
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(167, 139, 250, 0.3); border-left: 4px solid #a78bfa; padding: 1.75rem;">
        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
          <div>
            <div style="font-size: 2rem; margin-bottom: 0.5rem;">📝</div>
            <h3 style="color: #ede9fe; font-size: 1.1rem; margin: 0;">Question Papers</h3>
          </div>
          <?php if ($stats['pending_questions'] > 0): ?>
            <span style="background: #ef4444; color: white; padding: 0.375rem 0.875rem; border-radius: 50px; font-size: 0.875rem; font-weight: 700; animation: pulse 2s infinite;">
              <?php echo $stats['pending_questions']; ?>
            </span>
          <?php endif; ?>
        </div>

        <div style="margin-bottom: 1.25rem;">
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Approved</span>
            <span style="color: #34d399; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['approved_questions']; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Pending</span>
            <span style="color: #fbbf24; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['pending_questions']; ?></span>
          </div>
          <div style="display: flex; justify-content: space-between;">
            <span style="color: #94a3b8; font-size: 0.875rem;">Rejected</span>
            <span style="color: #f87171; font-weight: 600; font-size: 0.875rem;"><?php echo $stats['rejected_questions']; ?></span>
          </div>
        </div>

        <a href="questions_pending.php">
          <button style="width: 100%; background: linear-gradient(135deg, #a78bfa 0%, #8b5cf6 100%); color: white; padding: 0.75rem; border-radius: 8px; font-weight: 600;">
            <?php echo $stats['pending_questions'] > 0 ? 'Review Now →' : 'View All →'; ?>
          </button>
        </a>
      </div>
    </div>

    <!-- Recent Activity -->
    <?php if (!empty($recentActivities)): ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2);">
        <h3 style="color: #f1f5f9; font-size: 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
          📊 Recent Submissions
        </h3>

        <div style="display: grid; gap: 0.75rem;">
          <?php foreach ($recentActivities as $activity):
            $typeColors = [
              'note' => ['bg' => 'rgba(59, 130, 246, 0.1)', 'border' => 'rgba(59, 130, 246, 0.3)', 'text' => '#93c5fd', 'icon' => '📚'],
              'blog' => ['bg' => 'rgba(16, 185, 129, 0.1)', 'border' => 'rgba(16, 185, 129, 0.3)', 'text' => '#6ee7b7', 'icon' => '✨'],
              'current' => ['bg' => 'rgba(245, 158, 11, 0.1)', 'border' => 'rgba(245, 158, 11, 0.3)', 'text' => '#fde68a', 'icon' => '📰'],
              'question' => ['bg' => 'rgba(167, 139, 250, 0.1)', 'border' => 'rgba(167, 139, 250, 0.3)', 'text' => '#c4b5fd', 'icon' => '📝']
            ];
            $color = $typeColors[$activity['type']];
          ?>
            <div style="background: <?php echo $color['bg']; ?>; padding: 1rem; border-radius: 8px; border: 1px solid <?php echo $color['border']; ?>;">
              <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
                <div style="flex: 1;">
                  <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                    <span style="font-size: 1.25rem;"><?php echo $color['icon']; ?></span>
                    <span style="color: <?php echo $color['text']; ?>; font-size: 0.75rem; text-transform: uppercase; font-weight: 600;">
                      <?php echo ucfirst($activity['type']); ?>
                    </span>
                  </div>
                  <div style="color: #f1f5f9; font-weight: 600; font-size: 0.95rem; margin-bottom: 0.25rem;">
                    <?php echo htmlspecialchars($activity['title']); ?>
                  </div>
                  <div style="color: #94a3b8; font-size: 0.75rem;">
                    <?php echo date('d M Y, H:i', strtotime($activity['created_at'])); ?>
                  </div>
                </div>
                <span style="background: rgba(251, 191, 36, 0.2); color: #fbbf24; padding: 0.25rem 0.625rem; border-radius: 50px; font-size: 0.75rem; font-weight: 600;">
                  Pending
                </span>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Right Column: Guidelines & Quick Links -->
  <div>
    <!-- Moderation Guidelines -->
    <div class="card" style="background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); border: 1px solid rgba(16, 185, 129, 0.3);">
      <h3 style="color: #d1fae5; font-size: 1.1rem; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
        📋 Moderation Guidelines
      </h3>

      <div style="display: grid; gap: 0.875rem;">
        <div style="background: rgba(255, 255, 255, 0.05); padding: 0.875rem; border-radius: 8px;">
          <div style="display: flex; gap: 0.625rem; align-items: start;">
            <span style="color: #34d399; font-size: 1.25rem;">✅</span>
            <div>
              <div style="color: #d1fae5; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem;">Relevant & Useful</div>
              <div style="color: #a7f3d0; font-size: 0.8rem; line-height: 1.4;">Content must be helpful for exam preparation</div>
            </div>
          </div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.05); padding: 0.875rem; border-radius: 8px;">
          <div style="display: flex; gap: 0.625rem; align-items: start;">
            <span style="color: #34d399; font-size: 1.25rem;">✅</span>
            <div>
              <div style="color: #d1fae5; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem;">Professional Language</div>
              <div style="color: #a7f3d0; font-size: 0.8rem; line-height: 1.4;">No abusive, vulgar, or offensive content</div>
            </div>
          </div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.05); padding: 0.875rem; border-radius: 8px;">
          <div style="display: flex; gap: 0.625rem; align-items: start;">
            <span style="color: #34d399; font-size: 1.25rem;">✅</span>
            <div>
              <div style="color: #d1fae5; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem;">No Spam</div>
              <div style="color: #a7f3d0; font-size: 0.8rem; line-height: 1.4;">Reject promotional content or external links</div>
            </div>
          </div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.05); padding: 0.875rem; border-radius: 8px;">
          <div style="display: flex; gap: 0.625rem; align-items: start;">
            <span style="color: #34d399; font-size: 1.25rem;">✅</span>
            <div>
              <div style="color: #d1fae5; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem;">Well Formatted</div>
              <div style="color: #a7f3d0; font-size: 0.8rem; line-height: 1.4;">Properly structured and readable content</div>
            </div>
          </div>
        </div>

        <div style="background: rgba(255, 255, 255, 0.05); padding: 0.875rem; border-radius: 8px;">
          <div style="display: flex; gap: 0.625rem; align-items: start;">
            <span style="color: #34d399; font-size: 1.25rem;">✅</span>
            <div>
              <div style="color: #d1fae5; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.25rem;">Accurate Information</div>
              <div style="color: #a7f3d0; font-size: 0.8rem; line-height: 1.4;">Verify facts for educational content</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Admin Actions -->
    <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); margin-top: 1.5rem;">
      <h3 style="color: #f1f5f9; font-size: 1.1rem; margin-bottom: 1rem;">⚡ Quick Actions</h3>

      <div style="display: grid; gap: 0.75rem;">
        <a href="/readandrise/public/add_current.php" style="text-decoration: none;">
          <div style="background: rgba(245, 158, 11, 0.1); padding: 0.875rem; border-radius: 8px; border: 1px solid rgba(245, 158, 11, 0.2); color: #fde68a; transition: all 0.3s ease; cursor: pointer;">
            <strong>📰 Add Current Affairs</strong>
          </div>
        </a>

        <a href="/readandrise/public/index.php" style="text-decoration: none;">
          <div style="background: rgba(59, 130, 246, 0.1); padding: 0.875rem; border-radius: 8px; border: 1px solid rgba(59, 130, 246, 0.2); color: #93c5fd; transition: all 0.3s ease; cursor: pointer;">
            <strong>🏠 View Public Site</strong>
          </div>
        </a>

        <a href="/readandrise/public/dashboard.php" style="text-decoration: none;">
          <div style="background: rgba(167, 139, 250, 0.1); padding: 0.875rem; border-radius: 8px; border: 1px solid rgba(167, 139, 250, 0.2); color: #c4b5fd; transition: all 0.3s ease; cursor: pointer;">
            <strong>📊 My Dashboard</strong>
          </div>
        </a>
      </div>
    </div>

    <!-- Admin Stats -->
    <div class="card" style="background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%); border: 1px solid rgba(59, 130, 246, 0.3); margin-top: 1.5rem;">
      <h3 style="color: #dbeafe; font-size: 1.1rem; margin-bottom: 1rem;">📈 Today's Overview</h3>

      <div style="display: grid; gap: 0.75rem;">
        <div style="display: flex; justify-content: space-between; padding: 0.625rem; background: rgba(255, 255, 255, 0.05); border-radius: 6px;">
          <span style="color: #bfdbfe; font-size: 0.875rem;">Total Content</span>
          <span style="color: white; font-weight: 700;"><?php echo $totalApproved + $totalPending + $totalRejected; ?></span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 0.625rem; background: rgba(255, 255, 255, 0.05); border-radius: 6px;">
          <span style="color: #bfdbfe; font-size: 0.875rem;">Approval Rate</span>
          <span style="color: #34d399; font-weight: 700;">
            <?php
            $total = $totalApproved + $totalRejected;
            echo $total > 0 ? round(($totalApproved / $total) * 100) . '%' : 'N/A';
            ?>
          </span>
        </div>
        <div style="display: flex; justify-content: space-between; padding: 0.625rem; background: rgba(255, 255, 255, 0.05); border-radius: 6px;">
          <span style="color: #bfdbfe; font-size: 0.875rem;">Active Users</span>
          <span style="color: white; font-weight: 700;"><?php echo $stats['total_users']; ?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Animation for pulse effect -->
<style>
  @keyframes pulse {

    0%,
    100% {
      transform: scale(1);
      opacity: 1;
    }

    50% {
      transform: scale(1.05);
      opacity: 0.9;
    }
  }
</style>

<?php require_once "../includes/footer.php"; ?>