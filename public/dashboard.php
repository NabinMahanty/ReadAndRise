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

<div class="card">
  <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
  <p style="font-size:14px; color:#4b5563;">
    Yahan se aap apne notes aur apni struggle/journey stories manage kar sakte hain.
  </p>

  <ul style="padding-left:18px; font-size:14px; margin-top:8px;">
    <li>➤ <a href="add_note.php">Add new note</a></li>
    <li>➤ <a href="add_blog.php">Share your struggle / journey</a></li>
  </ul>
</div>

<div class="home-grid">
  <!-- LEFT: My Notes -->
  <section>
    <div class="section-header">
      <h2>My Notes</h2>
      <a href="add_note.php">+ Add Note</a>
    </div>

    <?php if (empty($myNotes)): ?>
      <div class="card">
        <p style="font-size:14px;">You haven't added any notes yet.</p>
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
                <small style="color:#b91c1c; font-size:12px; margin-left:6px;">
                  (You can edit & resubmit later – feature coming soon)
                </small>
              <?php endif; ?>
            </li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php endif; ?>
  </section>

  <!-- RIGHT: My Stories -->
  <aside>
    <div class="section-header">
      <h2>My Stories</h2>
      <a href="add_blog.php">+ Add Story</a>
    </div>

    <?php if (empty($myBlogs)): ?>
      <div class="card">
        <p style="font-size:14px;">You haven't shared any stories yet.</p>
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