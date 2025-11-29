<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/header.php";

require_login();
?>

<style>
  @media (max-width: 768px) {
    .welcome-card {
      padding: 1.5rem !important;
    }

    .welcome-card h2 {
      font-size: 1.5rem !important;
    }

    .action-buttons {
      flex-direction: column !important;
    }

    .action-buttons a {
      width: 100%;
    }

    .action-buttons button {
      width: 100%;
    }

    .stats-grid {
      grid-template-columns: 1fr !important;
    }

    .section-header {
      flex-direction: column !important;
      align-items: flex-start !important;
      gap: 0.5rem !important;
    }

    .card {
      padding: 1rem !important;
    }
  }

  @media (min-width: 769px) and (max-width: 1024px) {
    .stats-grid {
      grid-template-columns: repeat(2, 1fr) !important;
    }

    .welcome-card {
      padding: 2rem !important;
    }
  }
</style>

<?php

// fetch user notes
$stmtNotes = $pdo->prepare("
    SELECT id, title, category, status, created_at
    FROM notes
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmtNotes->execute([$_SESSION['user_id']]);
$myNotes = $stmtNotes->fetchAll(PDO::FETCH_ASSOC);

// fetch user blogs
$stmtBlogs = $pdo->prepare("
    SELECT id, title, category, status, created_at
    FROM blogs
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmtBlogs->execute([$_SESSION['user_id']]);
$myBlogs = $stmtBlogs->fetchAll(PDO::FETCH_ASSOC);

// fetch user current affairs
$stmtCurrent = $pdo->prepare("
    SELECT id, title, status, created_at
    FROM current_affairs
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmtCurrent->execute([$_SESSION['user_id']]);
$myCurrentAffairs = $stmtCurrent->fetchAll(PDO::FETCH_ASSOC);

// fetch user questions
$stmtQuestions = $pdo->prepare("
    SELECT id, title, year, subject, status, created_at
    FROM questions
    WHERE user_id = ?
    ORDER BY created_at DESC
");
$stmtQuestions->execute([$_SESSION['user_id']]);
$myQuestions = $stmtQuestions->fetchAll(PDO::FETCH_ASSOC);

function status_badge_class($status)
{
  switch ($status) {
    case 'approved':
      return 'status-badge status-approved';
    case 'rejected':
      return 'status-badge status-rejected';
    default:
      return 'status-badge status-pending';
  }
}
?>

<!-- Welcome Section -->
<div class="card welcome-card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 2.5rem;">
  <h2 style="color: #f1f5f9; margin-bottom: 1rem; font-size: 2rem;">👋 Welcome Back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
  <p style="font-size:1.1rem; color:#cbd5e1; line-height: 1.7;">
    This is your personal command center. Manage your study materials, track your contributions,
    and share your journey with fellow aspirants. Together, we build a stronger community.
  </p>

  <div class="action-buttons" style="display: flex; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap;">
    <a href="add_note.php" style="text-decoration: none;">
      <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 0.875rem 1.5rem; font-size: 0.95rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">📝 Upload Study Material</button>
    </a>
    <a href="add_blog.php" style="text-decoration: none;">
      <button type="button" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 0.875rem 1.5rem; font-size: 0.95rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);">✨ Share Success Story</button>
    </a>
    <a href="add_current.php" style="text-decoration: none;">
      <button type="button" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 0.875rem 1.5rem; font-size: 0.95rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">📰 Add Current Affairs</button>
    </a>
    <a href="add_question.php" style="text-decoration: none;">
      <button type="button" style="background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); padding: 0.875rem 1.5rem; font-size: 0.95rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);">📝 Submit Question Papers</button>
    </a>
  </div>
</div>

<!-- Statistics Overview -->
<div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); margin-top: 2rem; padding: 2.5rem;">
  <h2 style="color: #60a5fa; margin-bottom: 1.5rem; font-size: 1.75rem;">📊 Your Contribution Statistics</h2>
  <div class="stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
    <div style="text-align: center; padding: 1.5rem; background: rgba(59, 130, 246, 0.1); border-radius: 12px; border: 1px solid rgba(59, 130, 246, 0.3);">
      <div style="font-size: 2.5rem; font-weight: 800; color: #60a5fa;"><?php echo count($myNotes); ?></div>
      <div style="font-size: 0.95rem; color: #cbd5e1; margin-top: 0.5rem; font-weight: 600;">Study Materials</div>
    </div>
    <div style="text-align: center; padding: 1.5rem; background: rgba(139, 92, 246, 0.1); border-radius: 12px; border: 1px solid rgba(139, 92, 246, 0.3);">
      <div style="font-size: 2.5rem; font-weight: 800; color: #a78bfa;"><?php echo count($myBlogs); ?></div>
      <div style="font-size: 0.95rem; color: #cbd5e1; margin-top: 0.5rem; font-weight: 600;">Success Stories</div>
    </div>
    <div style="text-align: center; padding: 1.5rem; background: rgba(245, 158, 11, 0.1); border-radius: 12px; border: 1px solid rgba(245, 158, 11, 0.3);">
      <div style="font-size: 2.5rem; font-weight: 800; color: #fbbf24;"><?php echo count($myCurrentAffairs); ?></div>
      <div style="font-size: 0.95rem; color: #cbd5e1; margin-top: 0.5rem; font-weight: 600;">Current Affairs</div>
    </div>
    <div style="text-align: center; padding: 1.5rem; background: rgba(236, 72, 153, 0.1); border-radius: 12px; border: 1px solid rgba(236, 72, 153, 0.3);">
      <div style="font-size: 2.5rem; font-weight: 800; color: #ec4899;"><?php echo count($myQuestions); ?></div>
      <div style="font-size: 0.95rem; color: #cbd5e1; margin-top: 0.5rem; font-weight: 600;">Question Papers</div>
    </div>
  </div>
</div>

<div class="home-grid">
  <!-- LEFT: My Study Materials -->
  <section>
    <div class="section-header" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1rem;">
      <h2 style="color: #f1f5f9; margin: 0; font-size: 1.25rem;">📚 My Study Materials</h2>
      <a href="add_note.php" style="color: #60a5fa; font-weight: 600; text-decoration: none; font-size: 0.95rem;">+ Upload New Material</a>
    </div>

    <?php if (empty($myNotes)): ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 2rem; text-align: center;">
        <h3 style="margin-bottom: 1rem; color: #f1f5f9; font-size: 1.25rem;">🚀 Start Your Contribution</h3>
        <p style="font-size:1rem; color: #cbd5e1; line-height: 1.6;">You haven't uploaded any study materials yet. Be the change you wish to see—share your knowledge and help fellow aspirants succeed!</p>
        <a href="add_note.php">
          <button type="button" style="margin-top: 1.5rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 0.875rem 1.75rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">Upload Your First Material</button>
        </a>
      </div>
    <?php else: ?>
      <div style="display: grid; gap: 1rem;">
        <?php foreach ($myNotes as $note): ?>
          <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1.5rem;">
            <h3 style="color: #f1f5f9; margin-bottom: 0.75rem; font-size: 1.1rem;"><?php echo htmlspecialchars($note['title']); ?></h3>
            <div style="margin-bottom: 1rem;">
              <small style="color: #94a3b8; display: block; margin-bottom: 0.5rem;">
                <strong style="color: #a78bfa;">Category:</strong> <?php echo htmlspecialchars($note['category']); ?> |
                <strong style="color: #60a5fa;">Date:</strong> <?php echo date('d M Y', strtotime($note['created_at'])); ?>
              </small>
              <span style="padding: 0.375rem 0.875rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; <?php echo $note['status'] === 'approved' ? 'background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3);' : ($note['status'] === 'rejected' ? 'background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3);' : 'background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3);'); ?>">
                <?php echo ucfirst(htmlspecialchars($note['status'])); ?>
              </span>
            </div>
            <div style="display: flex; gap: 0.75rem;">
              <a href="edit_note.php?id=<?php echo $note['id']; ?>" style="text-decoration: none;">
                <button type="button" style="font-size: 0.875rem; padding: 0.5rem 1rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                  ✏️ Edit
                </button>
              </a>
              <a href="delete_note.php?id=<?php echo $note['id']; ?>" style="text-decoration: none;">
                <button type="button" style="font-size: 0.875rem; padding: 0.5rem 1rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                  🗑️ Delete
                </button>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <!-- RIGHT: My Success Stories -->
  <aside>
    <div class="section-header" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1rem;">
      <h2 style="color: #f1f5f9; margin: 0; font-size: 1.25rem;">✨ My Success Stories</h2>
      <a href="add_blog.php" style="color: #60a5fa; font-weight: 600; text-decoration: none; font-size: 0.95rem;">+ Share Story</a>
    </div>

    <?php if (empty($myBlogs)): ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 2rem; text-align: center;">
        <h4 style="margin-bottom: 1rem; color: #f1f5f9; font-size: 1.25rem;">💫 Inspire Others</h4>
        <p style="font-size:0.95rem; color: #cbd5e1; line-height: 1.6;">Your journey can inspire thousands. Share your preparation story, challenges overcome, and lessons learned to motivate fellow aspirants.</p>
        <a href="add_blog.php">
          <button type="button" style="margin-top: 1.5rem; background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); padding: 0.875rem 1.75rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);">Share Your Story</button>
        </a>
      </div>
    <?php else: ?>
      <div style="display: grid; gap: 1rem;">
        <?php foreach ($myBlogs as $blog): ?>
          <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1.5rem;">
            <h3 style="color: #f1f5f9; margin-bottom: 0.75rem; font-size: 1.1rem;"><?php echo htmlspecialchars($blog['title']); ?></h3>
            <div style="margin-bottom: 1rem;">
              <small style="color: #94a3b8; display: block; margin-bottom: 0.5rem;">
                <strong style="color: #a78bfa;">Category:</strong> <?php echo htmlspecialchars($blog['category']); ?> |
                <strong style="color: #60a5fa;">Date:</strong> <?php echo date('d M Y', strtotime($blog['created_at'])); ?>
              </small>
              <span style="padding: 0.375rem 0.875rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; <?php echo $blog['status'] === 'approved' ? 'background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3);' : ($blog['status'] === 'rejected' ? 'background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3);' : 'background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3);'); ?>">
                <?php echo ucfirst(htmlspecialchars($blog['status'])); ?>
              </span>
            </div>
            <div style="display: flex; gap: 0.75rem;">
              <a href="edit_blog.php?id=<?php echo $blog['id']; ?>" style="text-decoration: none;">
                <button type="button" style="font-size: 0.875rem; padding: 0.5rem 1rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                  ✏️ Edit
                </button>
              </a>
              <a href="delete_blog.php?id=<?php echo $blog['id']; ?>" style="text-decoration: none;">
                <button type="button" style="font-size: 0.875rem; padding: 0.5rem 1rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600;">
                  🗑️ Delete
                </button>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </aside>
</div>

<!-- Current Affairs Section -->
<div class="home-grid" style="margin-top: 2rem;">
  <section>
    <div class="section-header" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1rem;">
      <h2 style="color: #f1f5f9; margin: 0; font-size: 1.25rem;">📰 My Current Affairs</h2>
      <a href="add_current.php" style="color: #60a5fa; font-weight: 600; text-decoration: none; font-size: 0.95rem;">+ Add New</a>
    </div>

    <?php if (empty($myCurrentAffairs)): ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 2rem; text-align: center;">
        <h3 style="margin-bottom: 1rem; color: #f1f5f9; font-size: 1.25rem;">📰 Share Current Events</h3>
        <p style="font-size:1rem; color: #cbd5e1; line-height: 1.6;">Keep the community updated with important news and events relevant to exam preparation.</p>
        <a href="add_current.php">
          <button type="button" style="margin-top: 1.5rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding: 0.875rem 1.75rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);">Add Current Affairs</button>
        </a>
      </div>
    <?php else: ?>
      <div style="display: grid; gap: 1rem;">
        <?php foreach ($myCurrentAffairs as $current): ?>
          <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1.5rem;">
            <h3 style="color: #f1f5f9; margin-bottom: 0.75rem; font-size: 1.1rem;"><?php echo htmlspecialchars($current['title']); ?></h3>
            <div>
              <small style="color: #94a3b8; display: block; margin-bottom: 0.5rem;">
                <strong style="color: #60a5fa;">Date:</strong> <?php echo date('d M Y', strtotime($current['created_at'])); ?>
              </small>
              <span style="padding: 0.375rem 0.875rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; <?php echo $current['status'] === 'approved' ? 'background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3);' : ($current['status'] === 'rejected' ? 'background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3);' : 'background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3);'); ?>">
                <?php echo ucfirst(htmlspecialchars($current['status'])); ?>
              </span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </section>

  <!-- Question Papers Section -->
  <aside>
    <div class="section-header" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1rem 1.5rem; border-radius: 12px; margin-bottom: 1rem;">
      <h2 style="color: #f1f5f9; margin: 0; font-size: 1.25rem;">📝 My Question Papers</h2>
      <a href="add_question.php" style="color: #60a5fa; font-weight: 600; text-decoration: none; font-size: 0.95rem;">+ Submit New</a>
    </div>

    <?php if (empty($myQuestions)): ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 2rem; text-align: center;">
        <h4 style="margin-bottom: 1rem; color: #f1f5f9; font-size: 1.25rem;">📝 Share Resources</h4>
        <p style="font-size:0.95rem; color: #cbd5e1; line-height: 1.6;">Submit Google Drive folders containing previous year question papers to help others practice.</p>
        <a href="add_question.php">
          <button type="button" style="margin-top: 1.5rem; background: linear-gradient(135deg, #ec4899 0%, #db2777 100%); padding: 0.875rem 1.75rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(236, 72, 153, 0.3);">Submit Papers</button>
        </a>
      </div>
    <?php else: ?>
      <div style="display: grid; gap: 1rem;">
        <?php foreach ($myQuestions as $question): ?>
          <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1.5rem;">
            <h3 style="color: #f1f5f9; margin-bottom: 0.75rem; font-size: 1.1rem;"><?php echo htmlspecialchars($question['title']); ?></h3>
            <div>
              <small style="color: #94a3b8; display: block; margin-bottom: 0.5rem;">
                <?php if ($question['subject']): ?><strong style="color: #a78bfa;">Subject:</strong> <?php echo htmlspecialchars($question['subject']); ?> | <?php endif; ?>
                <strong style="color: #ec4899;">Year:</strong> <?php echo htmlspecialchars($question['year']); ?> |
                <strong style="color: #60a5fa;">Date:</strong> <?php echo date('d M Y', strtotime($question['created_at'])); ?>
              </small>
              <span style="padding: 0.375rem 0.875rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; <?php echo $question['status'] === 'approved' ? 'background: rgba(16, 185, 129, 0.2); color: #6ee7b7; border: 1px solid rgba(16, 185, 129, 0.3);' : ($question['status'] === 'rejected' ? 'background: rgba(239, 68, 68, 0.2); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3);' : 'background: rgba(251, 191, 36, 0.2); color: #fbbf24; border: 1px solid rgba(251, 191, 36, 0.3);'); ?>">
                <?php echo ucfirst(htmlspecialchars($question['status'])); ?>
              </span>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </aside>
</div>

<?php require_once "../includes/footer.php"; ?>