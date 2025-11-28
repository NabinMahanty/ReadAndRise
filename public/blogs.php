<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

$stmt = $pdo->query("
    SELECT b.title, b.slug, b.category, b.created_at, u.name AS author
    FROM blogs b
    JOIN users u ON b.user_id = u.id
    WHERE b.status = 'approved'
    ORDER BY b.created_at DESC
");
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>Student Struggle & Journey Stories</h2>

<div class="blogs-list">
  <?php if (empty($blogs)): ?>
    <div class="card">
      <p>No stories published yet. Share your journey from your dashboard!</p>
    </div>
  <?php else: ?>
    <ul>
      <?php foreach ($blogs as $blog): ?>
        <li>
          <a href="blog.php?slug=<?php echo urlencode($blog['slug']); ?>">
            <?php echo htmlspecialchars($blog['title']); ?>
          </a>
          <br>
          <small>
            Category: <?php echo htmlspecialchars($blog['category']); ?>
            | By: <?php echo htmlspecialchars($blog['author']); ?>
            | On: <?php echo htmlspecialchars($blog['created_at']); ?>
          </small>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php require_once "../includes/footer.php"; ?>