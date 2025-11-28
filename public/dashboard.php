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
      <button type="button">📝 Upload New Study Material</button>
    </a>
    <a href="add_blog.php" style="text-decoration: none;">
      <button type="button" class="btn-secondary">✨ Share Your Success Story</button>
    </a>
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
              <?php if ($note['status'] === 'rejected'): ?>
                <small style="color:#b91c1c; font-size:0.875rem; margin-left:6px; display: block; margin-top: 0.25rem;">
                  ⚠️ Review feedback and resubmit (editing feature launching soon)
                </small>
              <?php endif; ?>
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
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
</div>

<?php require_once "../includes/footer.php"; ?>