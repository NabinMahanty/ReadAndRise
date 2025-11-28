<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/header.php";

require_admin();

// pending notes count
$stmt = $pdo->query("SELECT COUNT(*) AS c FROM notes WHERE status = 'pending'");
$pendingNotes = $stmt->fetch(PDO::FETCH_ASSOC)['c'] ?? 0;

// blogs table abhi nahi hai, to yahan 0 rakhenge (baad me replace karenge)
$pendingBlogs = 0;
?>

<div class="card">
  <h2>Admin Panel</h2>
  <p>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?> (Admin)</p>
</div>

<div class="home-grid">
  <section>
    <div class="card">
      <h3>Moderation</h3>
      <ul style="padding-left:18px; font-size:14px; color:#4b5563;">
        <li>
          • Pending Notes:
          <strong><?php echo (int)$pendingNotes; ?></strong>
          – <a href="notes_pending.php">Review now</a>
        </li>
        <li>
          • Pending Struggle Stories:
          <strong><?php echo (int)$pendingBlogs; ?></strong>
          – (coming soon)
        </li>
      </ul>
    </div>
  </section>

  <aside>
    <div class="quick-card">
      <h3>Quick Info</h3>
      <p style="font-size:14px;">
        Approve sirf wahi notes / stories jo:
      </p>
      <ul>
        <li>➤ Useful for students</li>
        <li>➤ No abusive / vulgar language</li>
        <li>➤ No direct promotion / spam</li>
      </ul>
    </div>
  </aside>
</div>

<?php require_once "../includes/footer.php"; ?>