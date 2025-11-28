<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

$slug = trim($_GET['slug'] ?? '');

if ($slug === '') {
  echo "<p>Story not found.</p>";
  require_once "../includes/footer.php";
  exit;
}

$stmt = $pdo->prepare("
    SELECT b.title, b.category, b.content, b.created_at, u.name AS author
    FROM blogs b
    JOIN users u ON b.user_id = u.id
    WHERE b.slug = ? AND b.status = 'approved'
    LIMIT 1
");
$stmt->execute([$slug]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
  echo "<p>Story not found or not approved yet.</p>";
  require_once "../includes/footer.php";
  exit;
}
?>

<article class="card">
  <h2><?php echo htmlspecialchars($blog['title']); ?></h2>
  <p>
    <small>
      Category: <?php echo htmlspecialchars($blog['category']); ?>
      | By: <?php echo htmlspecialchars($blog['author']); ?>
      | On: <?php echo htmlspecialchars($blog['created_at']); ?>
    </small>
  </p>
</article>

<div class="note-content">
  <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
</div>

<?php require_once "../includes/footer.php"; ?>