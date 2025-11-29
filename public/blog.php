<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

$slug = trim($_GET['slug'] ?? '');
$preview = isset($_GET['preview']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';

if ($slug === '') {
  echo "<div class='card'><p>Story not found.</p></div>";
  require_once "../includes/footer.php";
  exit;
}

// If preview mode (admin only), allow viewing pending blogs
$statusCondition = $preview ? "" : "AND b.status = 'approved'";

$stmt = $pdo->prepare("
    SELECT b.title, b.category, b.content, b.created_at, b.status, u.name AS author
    FROM blogs b
    JOIN users u ON b.user_id = u.id
    WHERE b.slug = ? $statusCondition
    LIMIT 1
");
$stmt->execute([$slug]);
$blog = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$blog) {
  echo "<div class='card'><p>Story not found or not approved yet.</p></div>";
  require_once "../includes/footer.php";
  exit;
}
?>

<?php if ($preview && $blog['status'] === 'pending'): ?>
  <div style="background: #fef3c7; border-left: 4px solid #f59e0b; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
    <strong style="color: #92400e;">⚠️ ADMIN PREVIEW MODE</strong>
    <p style="color: #78350f; margin: 0.5rem 0 0 0;">
      This success story is in <strong>pending</strong> status. Only admins can see this preview.
    </p>
  </div>
<?php endif; ?>

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