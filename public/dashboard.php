<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/header.php";

require_login();

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

<div class="card" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-left: 4px solid #3b82f6;">
  <h2 style="color: #1e40af;">👋 Welcome Back, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h2>
  <p style="font-size:1rem; color:#1f2937; line-height: 1.7;">
    This is your personal command center. Manage your study materials, track your contributions,
    and share your journey with fellow aspirants. Together, we build a stronger community.
  </p>

  <div style="display: flex; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap;">
    <a href="add_note.php" style="text-decoration: none;">
      <button type="button">📝 Upload Study Material</button>
    </a>
    <a href="add_blog.php" style="text-decoration: none;">
      <button type="button" class="btn-secondary">✨ Share Success Story</button>
    </a>
    <a href="add_current.php" style="text-decoration: none;">
      <button type="button" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">📰 Add Current Affairs</button>
    </a>
    <a href="add_question.php" style="text-decoration: none;">
      <button type="button" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">📝 Submit Question Papers</button>
    </a>
  </div>
</div>

<!-- Statistics Overview -->
<div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; margin-top: 2rem;">
  <h2 style="color: #fbbf24; margin-bottom: 1rem;">📊 Your Contribution Statistics</h2>
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem;">
    <div style="text-align: center; padding: 1rem; background: rgba(255,255,255,0.1); border-radius: 8px;">
      <div style="font-size: 2rem; font-weight: 800; color: #60a5fa;"><?php echo count($myNotes); ?></div>
      <div style="font-size: 0.875rem; color: #cbd5e1; margin-top: 0.25rem;">Study Materials</div>
    </div>
    <div style="text-align: center; padding: 1rem; background: rgba(255,255,255,0.1); border-radius: 8px;">
      <div style="font-size: 2rem; font-weight: 800; color: #34d399;"><?php echo count($myBlogs); ?></div>
      <div style="font-size: 0.875rem; color: #cbd5e1; margin-top: 0.25rem;">Success Stories</div>
    </div>
    <div style="text-align: center; padding: 1rem; background: rgba(255,255,255,0.1); border-radius: 8px;">
      <div style="font-size: 2rem; font-weight: 800; color: #fbbf24;"><?php echo count($myCurrentAffairs); ?></div>
      <div style="font-size: 0.875rem; color: #cbd5e1; margin-top: 0.25rem;">Current Affairs</div>
    </div>
    <div style="text-align: center; padding: 1rem; background: rgba(255,255,255,0.1); border-radius: 8px;">
      <div style="font-size: 2rem; font-weight: 800; color: #a78bfa;"><?php echo count($myQuestions); ?></div>
      <div style="font-size: 0.875rem; color: #cbd5e1; margin-top: 0.25rem;">Question Papers</div>
    </div>
  </div>
</div>

<div class="home-grid">
  <!-- LEFT: My Study Materials -->
  <section>
    <div class="section-header">
      <h2>📚 My Study Materials</h2>
      <a href="add_note.php">+ Upload New Material</a>
    </div>

    <?php if (empty($myNotes)): ?>
      <div class="card">
        <h3 style="margin-bottom: 0.5rem;">🚀 Start Your Contribution</h3>
        <p style="font-size:1rem;">You haven't uploaded any study materials yet. Be the change you wish to see—share your knowledge and help fellow aspirants succeed!</p>
        <a href="add_note.php">
          <button type="button" style="margin-top: 1rem;">Upload Your First Material</button>
        </a>
      </div>
    <?php else: ?>
      <div class="notes-list">
        <ul>
          <?php foreach ($myNotes as $note): ?>
            <li>
              <strong><?php echo htmlspecialchars($note['title']); ?></strong>
              <br>
              <small>
                Category: <?php echo htmlspecialchars($note['category']); ?>
                | On: <?php echo htmlspecialchars($note['created_at']); ?>
              </small>
              <br>
              <span class="<?php echo status_badge_class($note['status']); ?>">
                <?php echo ucfirst(htmlspecialchars($note['status'])); ?>
              </span>
              <div style="margin-top: 0.5rem;">
                <a href="edit_note.php?id=<?php echo $note['id']; ?>" style="text-decoration: none;">
                  <button type="button" style="font-size: 0.875rem; padding: 0.4rem 0.8rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    ✏️ Edit
                  </button>
                </a>
                <a href="delete_note.php?id=<?php echo $note['id']; ?>" style="text-decoration: none;">
                  <button type="button" style="font-size: 0.875rem; padding: 0.4rem 0.8rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    🗑️ Delete
                  </button>
                </a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </section>

  <!-- RIGHT: My Success Stories -->
  <aside>
    <div class="section-header">
      <h2>✨ My Success Stories</h2>
      <a href="add_blog.php">+ Share Story</a>
    </div>

    <?php if (empty($myBlogs)): ?>
      <div class="card">
        <h4 style="margin-bottom: 0.5rem;">💫 Inspire Others</h4>
        <p style="font-size:0.95rem;">Your journey can inspire thousands. Share your preparation story, challenges overcome, and lessons learned to motivate fellow aspirants.</p>
        <a href="add_blog.php">
          <button type="button" class="btn-secondary" style="margin-top: 1rem;">Share Your Story</button>
        </a>
      </div>
    <?php else: ?>
      <div class="blogs-list">
        <ul>
          <?php foreach ($myBlogs as $blog): ?>
            <li>
              <strong><?php echo htmlspecialchars($blog['title']); ?></strong>
              <br>
              <small>
                Category: <?php echo htmlspecialchars($blog['category']); ?>
                | On: <?php echo htmlspecialchars($blog['created_at']); ?>
              </small>
              <br>
              <span class="<?php echo status_badge_class($blog['status']); ?>">
                <?php echo ucfirst(htmlspecialchars($blog['status'])); ?>
              </span>
              <div style="margin-top: 0.5rem;">
                <a href="edit_blog.php?id=<?php echo $blog['id']; ?>" style="text-decoration: none;">
                  <button type="button" style="font-size: 0.875rem; padding: 0.4rem 0.8rem; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                    ✏️ Edit
                  </button>
                </a>
                <a href="delete_blog.php?id=<?php echo $blog['id']; ?>" style="text-decoration: none;">
                  <button type="button" style="font-size: 0.875rem; padding: 0.4rem 0.8rem; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    🗑️ Delete
                  </button>
                </a>
              </div>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>

<!-- Current Affairs Section -->
<div class="home-grid" style="margin-top: 2rem;">
  <section>
    <div class="section-header">
      <h2>📰 My Current Affairs</h2>
      <a href="add_current.php">+ Add New</a>
    </div>

    <?php if (empty($myCurrentAffairs)): ?>
      <div class="card">
        <h3 style="margin-bottom: 0.5rem;">📰 Share Current Events</h3>
        <p style="font-size:1rem;">Keep the community updated with important news and events relevant to exam preparation.</p>
        <a href="add_current.php">
          <button type="button" style="margin-top: 1rem; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">Add Current Affairs</button>
        </a>
      </div>
    <?php else: ?>
      <div class="notes-list">
        <ul>
          <?php foreach ($myCurrentAffairs as $current): ?>
            <li>
              <strong><?php echo htmlspecialchars($current['title']); ?></strong>
              <br>
              <small>
                On: <?php echo htmlspecialchars($current['created_at']); ?>
              </small>
              <br>
              <span class="<?php echo status_badge_class($current['status']); ?>">
                <?php echo ucfirst(htmlspecialchars($current['status'])); ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </section>

  <!-- Question Papers Section -->
  <aside>
    <div class="section-header">
      <h2>📝 My Question Papers</h2>
      <a href="add_question.php">+ Submit New</a>
    </div>

    <?php if (empty($myQuestions)): ?>
      <div class="card">
        <h4 style="margin-bottom: 0.5rem;">📝 Share Resources</h4>
        <p style="font-size:0.95rem;">Submit Google Drive folders containing previous year question papers to help others practice.</p>
        <a href="add_question.php">
          <button type="button" class="btn-secondary" style="margin-top: 1rem; background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;">Submit Papers</button>
        </a>
      </div>
    <?php else: ?>
      <div class="blogs-list">
        <ul>
          <?php foreach ($myQuestions as $question): ?>
            <li>
              <strong><?php echo htmlspecialchars($question['title']); ?></strong>
              <br>
              <small>
                <?php if ($question['subject']): ?>Subject: <?php echo htmlspecialchars($question['subject']); ?> | <?php endif; ?>
              Year: <?php echo htmlspecialchars($question['year']); ?>
              | On: <?php echo htmlspecialchars($question['created_at']); ?>
              </small>
              <br>
              <span class="<?php echo status_badge_class($question['status']); ?>">
                <?php echo ucfirst(htmlspecialchars($question['status'])); ?>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>

<?php require_once "../includes/footer.php"; ?>