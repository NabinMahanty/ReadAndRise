<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

// filter by category (optional)
$category = trim($_GET['category'] ?? '');

if ($category !== '') {
  $stmt = $pdo->prepare("
        SELECT n.id, n.title, n.slug, n.category, n.tags, n.created_at, u.name AS author
        FROM notes n
        JOIN users u ON n.user_id = u.id
        WHERE n.status = 'approved' AND n.category = ?
        ORDER BY n.created_at DESC
    ");
  $stmt->execute([$category]);
} else {
  $stmt = $pdo->query("
        SELECT n.id, n.title, n.slug, n.category, n.tags, n.created_at, u.name AS author
        FROM notes n
        JOIN users u ON n.user_id = u.id
        WHERE n.status = 'approved'
        ORDER BY n.created_at DESC
    ");
}

$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>All Notes</h2>

<form method="get" style="margin-bottom:15px;">
  <label>
    Filter by category:
    <input type="text" name="category" value="<?php echo htmlspecialchars($category); ?>">
  </label>
  <button type="submit">Filter</button>
  <a href="notes.php">Clear</a>
</form>

<?php
// YAHAN BE AD CODE LAGA SAKTE HO (top banner)
// <!-- Google AdSense block -->
?>
<div class="notes-list">
  <?php if (empty($notes)): ?>
    <p>No notes available yet. Check back soon!</p>
  <?php else: ?>
    <ul>
      <?php foreach ($notes as $note): ?>
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
        <hr>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>
<?php
// YAHAN BE AD CODE LA SAKTE HO (bottom ad)
?>

<?php require_once "../includes/footer.php"; ?>