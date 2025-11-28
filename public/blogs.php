<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

$q = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');

// categories from blogs
$catStmt = $pdo->query("
    SELECT DISTINCT category
    FROM blogs
    WHERE status = 'approved'
    ORDER BY category ASC
");
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

$sql = "
    SELECT b.title, b.slug, b.category, b.created_at, u.name AS author
    FROM blogs b
    JOIN users u ON b.user_id = u.id
    WHERE b.status = 'approved'
";

$params = [];

if ($category !== '') {
  $sql .= " AND b.category = ? ";
  $params[] = $category;
}

if ($q !== '') {
  $sql .= " AND (b.title LIKE ? OR b.content LIKE ?) ";
  $like = '%' . $q . '%';
  $params[] = $like;
  $params[] = $like;
}

$sql .= " ORDER BY b.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h2>✨ Success Stories</h2>

<div class="card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #ff4500; margin-bottom: 1.5rem;">
  <form method="get" style="background: transparent; box-shadow: none; padding: 0;">
    <label style="color: #92400e; display:block; margin-bottom:0.5rem;">
      🔍 Search Stories:
      <input
        type="text"
        name="q"
        value="<?php echo htmlspecialchars($q); ?>"
        placeholder="Search by title or story content">
    </label>

    <label style="color: #92400e; display:block; margin-top:0.5rem;">
      📂 Filter by Category:
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
      <a href="blogs.php">
        <button type="button" class="btn-secondary">✖ Clear Filter</button>
      </a>
    </div>
  </form>
</div>

<div class="blogs-list">
  <?php if (empty($blogs)): ?>
    <div class="card" style="text-align: center; padding: 3rem;">
      <h3>💭 No Stories Found</h3>
      <p style="margin-top: 1rem; color: #6b7280;">
        No success stories match your current search/filter.
        Try adjusting your keywords or browse all inspiring journeys.
      </p>
      <a href="blogs.php">
        <button type="button" style="margin-top: 1.5rem;">View All Stories</button>
      </a>
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
            📂 Category: <strong><?php echo htmlspecialchars($blog['category']); ?></strong>
            | ✍️ Author: <strong><?php echo htmlspecialchars($blog['author']); ?></strong>
            | 📅 Published: <?php echo date('d M Y', strtotime($blog['created_at'])); ?>
          </small>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<?php require_once "../includes/footer.php"; ?>