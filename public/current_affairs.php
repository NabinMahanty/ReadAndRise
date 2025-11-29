<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

// Handle search and category filter
$search = trim($_GET['search'] ?? '');
$where = "WHERE status = 'approved'";
$params = [];

if (!empty($search)) {
  $where .= " AND (title LIKE ? OR summary LIKE ? OR content LIKE ?)";
  $searchParam = "%$search%";
  $params = [$searchParam, $searchParam, $searchParam];
}

$stmt = $pdo->prepare("
    SELECT c.id, c.title, c.summary, c.content, c.image_path, c.created_at, u.name AS author
    FROM current_affairs c
    LEFT JOIN users u ON c.user_id = u.id
    $where
    ORDER BY c.created_at DESC
");
$stmt->execute($params);
$currentItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b;">
  <h2 style="color: #92400e;">📰 Current Affairs</h2>
  <p style="color: #78350f; margin-bottom: 1.5rem;">
    Stay updated with the latest news, events, and developments relevant to your exam preparation.
  </p>

  <form method="GET" style="margin-top: 1rem;">
    <div style="display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap;">
      <input
        type="text"
        name="search"
        placeholder="🔍 Search current affairs..."
        value="<?php echo htmlspecialchars($search); ?>"
        style="flex: 1; min-width: 250px; padding: 0.75rem; border: 2px solid #fbbf24; border-radius: 8px;">
      <button type="submit" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); font-weight: 600;">
        🔍 Search
      </button>
      <?php if ($search): ?>
        <a href="current_affairs.php" style="text-decoration: none;">
          <button type="button" class="btn-secondary" style="background: #fff !important; color: #92400e !important; border: 2px solid #f59e0b !important; font-weight: 600 !important;">
            ❌ Clear Filter
          </button>
        </a>
      <?php endif; ?>
    </div>
  </form>
</div>

<?php if (empty($currentItems)): ?>
  <div class="card" style="text-align: center; padding: 3rem;">
    <p style="font-size: 3rem; margin-bottom: 1rem;">📰</p>
    <h3 style="color: #374151; margin-bottom: 0.5rem;">No Current Affairs Found</h3>
    <p style="color: #6b7280;">
      <?php echo $search ? 'No current affairs match your search. Try different keywords.' : 'Current affairs will be published here soon.'; ?>
    </p>
  </div>
<?php else: ?>
  <div class="notes-list">
    <?php foreach ($currentItems as $item): ?>
      <div class="card" style="display: flex; gap: 1.5rem; align-items: flex-start;">
        <?php if (!empty($item['image_path'])): ?>
          <img
            src="../uploads/current/<?php echo htmlspecialchars($item['image_path']); ?>"
            alt="<?php echo htmlspecialchars($item['title']); ?>"
            style="width: 200px; height: 150px; object-fit: cover; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
        <?php endif; ?>

        <div style="flex: 1;">
          <h3 style="margin-bottom: 0.75rem;">
            <a href="current.php?id=<?php echo $item['id']; ?>" style="color: #1e40af; text-decoration: none;">
              <?php echo htmlspecialchars($item['title']); ?>
            </a>
          </h3>

          <?php if (!empty($item['summary'])): ?>
            <p style="color: #4b5563; margin-bottom: 0.75rem; line-height: 1.6;">
              <?php echo htmlspecialchars($item['summary']); ?>
            </p>
          <?php else: ?>
            <p style="color: #4b5563; margin-bottom: 0.75rem; line-height: 1.6;">
              <?php echo htmlspecialchars(mb_substr(strip_tags($item['content']), 0, 200)) . '...'; ?>
            </p>
          <?php endif; ?>

          <p style="color: #6b7280; font-size: 0.875rem;">
            📅 <?php echo date('d M Y', strtotime($item['created_at'])); ?>
            | ✍️ <?php echo htmlspecialchars($item['author'] ?? 'Admin'); ?>
          </p>

          <a href="current.php?id=<?php echo $item['id']; ?>" style="text-decoration: none;">
            <button type="button" style="margin-top: 0.75rem; font-size: 0.875rem; padding: 0.5rem 1rem;">
              📖 Read Full Article
            </button>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>