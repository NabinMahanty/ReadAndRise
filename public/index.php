<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

// latest approved notes (limit 5)
$stmt = $pdo->query("
    SELECT n.title, n.slug, n.category, n.created_at, u.name AS author
    FROM notes n
    JOIN users u ON n.user_id = u.id
    WHERE n.status = 'approved'
    ORDER BY n.created_at DESC
    LIMIT 5
");
$latestNotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="hero">
  <div class="hero-tagline">Read • Learn • Rise</div>
  <h2 class="hero-title">Free Notes & Real Student Stories at One Place.</h2>
  <p class="hero-subtitle">
    Read exam notes, share your own notes, and post your struggle/journey as a blog.
    100% free to read – supported by ads, not paid courses.
  </p>

  <div class="hero-buttons">
    <a href="notes.php">
      <button type="button">Browse Notes</button>
    </a>

    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="dashboard.php">
        <button type="button" class="btn-secondary">Go to your Dashboard</button>
      </a>
    <?php else: ?>
      <a href="login.php">
        <button type="button" class="btn-secondary">Login / Register to Share</button>
      </a>
    <?php endif; ?>
  </div>
</div>

<div class="home-grid">

  <!-- LEFT: Latest Notes -->
  <section>
    <div class="section-header">
      <h2>Latest Notes</h2>
      <a href="notes.php">View all</a>
    </div>

    <div class="notes-list">
      <?php if (empty($latestNotes)): ?>
        <div class="card">
          <p>No notes approved yet. Be the first to upload from your dashboard!</p>
        </div>
      <?php else: ?>
        <ul>
          <?php foreach ($latestNotes as $note): ?>
            <li>
              <a href="note.php?slug=<?php echo urlencode($note['slug']); ?>">
                <?php echo htmlspecialchars($note['title']); ?>
              </a>
              <br>
              <small>
                Category: <?php echo htmlspecialchars($note['category']); ?>
                | By: <?php echo htmlspecialchars($note['author']); ?>
                | On: <?php echo htmlspecialchars($note['created_at']); ?>
              </small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div class="card" style="margin-top: 14px;">
      <h3 style="margin-bottom: 6px;">Why ReadAndRise.in?</h3>
      <ul style="padding-left: 18px; font-size: 14px; color:#4b5563;">
        <li>• Free notes for exams like CDS, AFCAT, MCA, Programming, etc.</li>
        <li>• Real students share their preparation journey & struggles.</li>
        <li>• No paid course pressure – just pure community-driven help.</li>
        <li>• You can contribute your own notes and stories.</li>
      </ul>
    </div>
  </section>

  <!-- RIGHT: Quick Links / Future Struggle Stories -->
  <aside>
    <div class="quick-card">
      <h3>Start Contributing</h3>
      <p style="font-size: 14px;">
        Logged-in users can upload their own notes and share their personal struggle stories.
      </p>

      <ul>
        <?php if (!empty($_SESSION['user_id'])): ?>
          <li>➤ <a href="add_note.php">Upload a new note</a></li>
          <li>➤ <a href="dashboard.php">Go to your dashboard</a></li>
          <li>➤ Struggle story upload page: <em>coming soon</em></li>
        <?php else: ?>
          <li>➤ <a href="register.php">Create your free account</a></li>
          <li>➤ <a href="login.php">Login to upload notes</a></li>
          <li>➤ Reading is always free, even without login.</li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="card" style="margin-top: 14px;">
      <h3 style="font-size: 16px; margin-bottom: 6px;">Upcoming Feature</h3>
      <p style="font-size: 14px; color:#4b5563;">
        “Struggle Stories” section jahan students apni journey likhenge –
        how they managed studies, failure, family pressure, jobs, etc.
      </p>
    </div>
  </aside>

</div>

<?php require_once "../includes/footer.php"; ?>