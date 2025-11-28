<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

// search & filter values
$q = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');

// categories list from DB (sirf approved notes se)
$catStmt = $pdo->query("
    SELECT DISTINCT category 
    FROM notes 
    WHERE status = 'approved'
    ORDER BY category ASC
");
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

// main query build
$sql = "
    SELECT n.id, n.title, n.slug, n.category, n.tags, n.created_at, u.name AS author
    FROM notes n
    JOIN users u ON n.user_id = u.id
    WHERE n.status = 'approved'
";

$params = [];

if ($category !== '') {
  $sql .= " AND n.category = ? ";
  $params[] = $category;
}

if ($q !== '') {
  $sql .= " AND (n.title LIKE ? OR n.content LIKE ? OR n.tags LIKE ?) ";
  $like = '%' . $q . '%';
  $params[] = $like;
  $params[] = $like;
  $params[] = $like;
}

$sql .= " ORDER BY n.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$notes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>📚 Study Materials Repository</h2>

<div class="card" style="background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 4px solid #10b981; margin-bottom: 1.5rem;">
  <form method="get" style="background: transparent; box-shadow: none; padding: 0;">
    <label style="color: #065f46; display:block; margin-bottom:0.5rem;">
      🔍 Search Notes:
      <input
        type="text"
        name="q"
        value="<?php echo htmlspecialchars($q); ?>"
        placeholder="Search by title, content or tags">
    </label>

    <label style="color: #065f46; display:block; margin-top:0.5rem;">
      📂 Filter by Category/Exam:
      <select name="category">
        <option value="">All categories</option>
        <?php foreach ($categories as $cat): ?>
          <option value="<?php echo htmlspecialchars($cat); ?>"
            <?php if ($cat === $category) echo 'selected'; ?>>
            <?php echo htmlspecialchars($cat); ?>
          </option>
        <?php endforeach; ?>
      </select>
    </label>

    <div style="display: flex; gap: 0.75rem; margin-top: 0.75rem;">
      <button type="submit">🔎 Apply Filter</button>
      <a href="notes.php">
        <button type="button" class="btn-secondary">✖ Clear Filter</button>
      </a>
    </div>
  </form>
</div>

<?php
// YAHAN BE AD CODE LAGA SAKTE HO (top banner)
// <!-- Google AdSense block -->
?>

<div class="notes-list">
  <?php if (empty($notes)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
      <h3>💭 No Materials Found</h3>
      <p style="margin-top: 1rem; color: #6b7280;">
        No study materials match your current search/filter.
        Try adjusting your keywords or browse all available resources.
      </p>
      <a href="notes.php">
        <button type="button" style="margin-top: 1.5rem;">View All Materials</button>
      </a>
    </div>
  <?php else: ?>
    <ul>
      <?php foreach ($notes as $note): ?>
        <li>
          <a href="note.php?slug=<?php echo urlencode($note['slug']); ?>">
            <?php echo htmlspecialchars($note['title']); ?>
          </a>
          <br>
          <small>
            📂 Category: <strong><?php echo htmlspecialchars($note['category']); ?></strong>
            | ✍️ Author: <strong><?php echo htmlspecialchars($note['author']); ?></strong>
            | 📅 Published: <?php echo date('d M Y', strtotime($note['created_at'])); ?>
            <?php if (!empty($note['tags'])): ?>
              <br>🏷️ Tags: <?php echo htmlspecialchars($note['tags']); ?>
            <?php endif; ?>
          </small>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php
// YAHAN BE AD CODE LA SAKTE HO (bottom ad)
?>

<style>
  iframe {
    pointer-events: none;
  }
</style>

<script>
  // Disable right click
  document.addEventListener('contextmenu', event => event.preventDefault());

  // Disable keyboard shortcuts (Ctrl+S, Ctrl+P)
  document.onkeydown = function(e) {
    if (e.ctrlKey && (e.key === 's' || e.key === 'p')) {
      e.preventDefault();
    }
  };
</script>

<?php require_once "../includes/footer.php"; ?>