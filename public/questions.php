<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

$q = trim($_GET['q'] ?? '');
$year = trim($_GET['year'] ?? '');
$subject = trim($_GET['subject'] ?? '');

// Get distinct years
$yearsStmt = $pdo->query("SELECT DISTINCT year FROM questions WHERE status='approved' ORDER BY year DESC");
$years = $yearsStmt->fetchAll(PDO::FETCH_COLUMN);

$sql = "SELECT id, title, year, subject, qtype, description, drive_folder_link, created_at FROM questions WHERE status='approved'";
$params = [];

if ($year !== '') {
  $sql .= " AND year = ?";
  $params[] = $year;
}
if ($subject !== '') {
  $sql .= " AND subject LIKE ?";
  $params[] = "%$subject%";
}
if ($q !== '') {
  $sql .= " AND (title LIKE ? OR description LIKE ?)";
  $like = "%$q%";
  $params[] = $like;
  $params[] = $like;
}

$sql .= " ORDER BY year DESC, created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="card" style="background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%); border-left: 4px solid #6366f1;">
  <h2 style="color: #3730a3;">📝 Previous Year Question Papers</h2>
  <p style="color: #4338ca; margin-bottom: 1.5rem;">
    Access previous year question papers from Google Drive folders shared by the community.
  </p>

  <form method="GET">
    <div style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 0.75rem; align-items: end;">
      <div>
        <label style="color: #1e1b4b; font-weight: 600;">🔍 Search</label>
        <input
          type="text"
          name="q"
          placeholder="Search by title or description..."
          value="<?php echo htmlspecialchars($q); ?>"
          style="border: 2px solid #818cf8; border-radius: 8px;">
      </div>
      <div>
        <label style="color: #1e1b4b; font-weight: 600;">📅 Year</label>
        <select name="year" style="border: 2px solid #818cf8; border-radius: 8px;">
          <option value="">All Years</option>
          <?php foreach ($years as $y): ?>
            <option value="<?php echo $y; ?>" <?php if ($y == $year) echo 'selected'; ?>><?php echo $y; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div>
        <label style="color: #1e1b4b; font-weight: 600;">📚 Subject</label>
        <input
          type="text"
          name="subject"
          placeholder="e.g., Math"
          value="<?php echo htmlspecialchars($subject); ?>"
          style="border: 2px solid #818cf8; border-radius: 8px;">
      </div>
    </div>
    <div style="display: flex; gap: 0.75rem; margin-top: 1rem;">
      <button type="submit" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); font-weight: 600;">
        🔍 Apply Filters
      </button>
      <?php if ($q || $year || $subject): ?>
        <a href="questions.php" style="text-decoration: none;">
          <button type="button" class="btn-secondary" style="background: #fff !important; color: #3730a3 !important; border: 2px solid #6366f1 !important; font-weight: 600 !important;">
            ❌ Clear All
          </button>
        </a>
      <?php endif; ?>
    </div>
  </form>
</div>

<?php if (empty($items)): ?>
  <div class="card" style="text-align: center; padding: 3rem;">
    <p style="font-size: 3rem; margin-bottom: 1rem;">📝</p>
    <h3 style="color: #374151; margin-bottom: 0.5rem;">No Question Papers Found</h3>
    <p style="color: #6b7280;">
      <?php echo ($q || $year || $subject) ? 'No question papers match your filters. Try adjusting your search.' : 'Question papers will be available here soon.'; ?>
    </p>
  </div>
<?php else: ?>
  <div class="notes-list">
    <?php foreach ($items as $item): ?>
      <div class="card">
        <h3 style="margin-bottom: 0.75rem;">
          <a href="<?php echo htmlspecialchars($item['drive_folder_link']); ?>" target="_blank" rel="noopener" style="color: #1e40af; text-decoration: none;">
            <?php echo htmlspecialchars($item['title'] ?: ($item['subject'] . ' - ' . $item['year'])); ?>
          </a>
        </h3>

        <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.75rem;">
          <span style="background: #dbeafe; color: #1e40af; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
            📅 <?php echo htmlspecialchars($item['year']); ?>
          </span>
          <?php if ($item['subject']): ?>
            <span style="background: #e0e7ff; color: #4338ca; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
              📚 <?php echo htmlspecialchars($item['subject']); ?>
            </span>
          <?php endif; ?>
          <span style="background: #fef3c7; color: #92400e; padding: 0.25rem 0.75rem; border-radius: 6px; font-size: 0.875rem; font-weight: 600;">
            📊 <?php echo htmlspecialchars($item['qtype']); ?>
          </span>
        </div>

        <?php if (!empty($item['description'])): ?>
          <p style="color: #4b5563; margin-bottom: 0.75rem; line-height: 1.6;">
            <?php echo nl2br(htmlspecialchars($item['description'])); ?>
          </p>
        <?php endif; ?>

        <p style="color: #6b7280; font-size: 0.875rem; margin-bottom: 1rem;">
          📅 Added on <?php echo date('d M Y', strtotime($item['created_at'])); ?>
        </p>

        <a href="<?php echo htmlspecialchars($item['drive_folder_link']); ?>" target="_blank" rel="noopener" style="text-decoration: none;">
          <button type="button" style="font-size: 0.875rem;">
            🔗 Open Google Drive Folder
          </button>
        </a>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>