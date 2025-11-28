<?php
require_once "../includes/db.php";

// Set SEO metadata
$_GET['page_title'] = 'Study Materials - Free Notes for CDS, AFCAT, NDA | ReadAndRise';
$_GET['page_description'] = 'Browse comprehensive study materials and exam notes for competitive examinations. Filter by category and search through our extensive repository of free educational content.';
$_GET['page_keywords'] = 'study materials, exam notes, CDS notes, AFCAT preparation, NDA study material, free notes, competitive exams';

require_once "../includes/header.php";

// Search & filter parameters
$q = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');

// Get distinct categories from approved notes
$catStmt = $pdo->query("
    SELECT DISTINCT category 
    FROM notes 
    WHERE status = 'approved'
    ORDER BY category ASC
");
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);

// Build dynamic query
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

// Count total results
$totalResults = count($notes);
?>

<div style="margin-bottom: 2rem;">
  <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem; background: linear-gradient(135deg, #0066ff, #00d4ff); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
    📚 Study Materials Repository
  </h1>
  <p style="color: var(--gray-600); font-size: 1.1rem;">
    <?php if ($q || $category): ?>
      Showing <strong style="color: #0066ff;"><?php echo $totalResults; ?></strong> result<?php echo $totalResults != 1 ? 's' : ''; ?>
    <?php else: ?>
      Explore our comprehensive collection of free study materials
    <?php endif; ?>
  </p>
</div>

<!-- Advanced Search & Filter Card -->
<div class="card" style="background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%); border-left: 4px solid #0066ff; margin-bottom: 2rem; padding: 2rem;">
  <h3 style="color: #0066ff; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
    🔍 <span>Advanced Search & Filter</span>
  </h3>

  <form method="get" style="background: transparent; box-shadow: none; padding: 0;">
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
      <div>
        <label style="color: #1e40af; font-weight: 600; display: block; margin-bottom: 0.5rem;">
          🔎 Search Keywords
        </label>
        <input
          type="text"
          name="q"
          placeholder="Search by title, content, or tags..."
          value="<?php echo htmlspecialchars($q); ?>"
          style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #0066ff; border-radius: 0.5rem; font-size: 1rem;">
        <small style="color: #64748b; margin-top: 0.25rem; display: block;">Search across titles, content, and tags</small>
      </div>

      <div>
        <label style="color: #1e40af; font-weight: 600; display: block; margin-bottom: 0.5rem;">
          📂 Category Filter
        </label>
        <select
          name="category"
          style="width: 100%; padding: 0.75rem 1rem; border: 2px solid #0066ff; border-radius: 0.5rem; font-size: 1rem; background: white;">
          <option value="">📌 All Categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat); ?>"
              <?php if ($cat === $category) echo 'selected'; ?>>
              📁 <?php echo htmlspecialchars($cat); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small style="color: #64748b; margin-top: 0.25rem; display: block;">Filter by exam or subject</small>
      </div>
    </div>

    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
      <button type="submit" style="background: linear-gradient(135deg, #0066ff, #00d4ff); padding: 0.75rem 2rem; font-size: 1rem;">
        🔎 Search & Filter
      </button>
      <?php if ($q || $category): ?>
        <a href="notes.php">
          <button type="button" class="btn-secondary" style="padding: 0.75rem 2rem; font-size: 1rem;">
            ✖ Clear All Filters
          </button>
        </a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Results Section -->
<div class="notes-list">
  <?php if (empty($notes)): ?>
    <div class="card" style="text-align: center; padding: 4rem 2rem; background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border-left: 4px solid #ff4500;">
      <div style="font-size: 4rem; margin-bottom: 1rem;">🔍</div>
      <h3 style="color: #dc2626; margin-bottom: 1rem;">No Materials Found</h3>
      <p style="font-size: 1.1rem; color: #7f1d1d; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
        We couldn't find any study materials matching your search criteria. Try adjusting your filters or search terms.
      </p>

      <div style="background: white; padding: 1.5rem; border-radius: 1rem; margin-top: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
        <h4 style="color: #0066ff; margin-bottom: 1rem;">💡 Suggestions:</h4>
        <ul style="text-align: left; list-style: none; padding: 0; color: #475569;">
          <li style="margin-bottom: 0.5rem;">✓ Try different keywords</li>
          <li style="margin-bottom: 0.5rem;">✓ Check for spelling errors</li>
          <li style="margin-bottom: 0.5rem;">✓ Use broader search terms</li>
          <li style="margin-bottom: 0.5rem;">✓ Clear filters and browse all materials</li>
        </ul>
      </div>

      <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
        <a href="notes.php">
          <button type="button" style="background: linear-gradient(135deg, #0066ff, #00d4ff);">
            📚 View All Materials
          </button>
        </a>
        <?php if (!empty($_SESSION['user_id'])): ?>
          <a href="add_note.php">
            <button type="button" class="btn-secondary">
              📝 Contribute Material
            </button>
          </a>
        <?php endif; ?>
      </div>
    </div>
  <?php else: ?>
    <div style="margin-bottom: 1.5rem; padding: 1rem; background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%); border-radius: 0.75rem; border-left: 4px solid #00ff88;">
      <p style="margin: 0; color: #065f46; font-weight: 600;">
        ✅ Found <strong style="color: #047857; text-shadow: 0 0 10px rgba(4, 120, 87, 0.3);"><?php echo $totalResults; ?></strong>
        study material<?php echo $totalResults != 1 ? 's' : ''; ?>
        <?php if ($category): ?>
          in <strong style="color: #0066ff;"><?php echo htmlspecialchars($category); ?></strong>
        <?php endif; ?>
        <?php if ($q): ?>
          matching "<strong style="color: #0066ff;"><?php echo htmlspecialchars($q); ?></strong>"
        <?php endif; ?>
      </p>
    </div>

    <ul style="list-style: none; padding: 0;">
      <?php foreach ($notes as $note): ?>
        <li style="background: white; border-radius: 1rem; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 4px 15px rgba(0, 102, 255, 0.1); border: 2px solid transparent; transition: all 0.3s ease; position: relative; overflow: hidden;">
          <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: linear-gradient(135deg, #0066ff, #00d4ff); transform: scaleY(0); transition: transform 0.3s ease;"></div>

          <a href="note.php?slug=<?php echo urlencode($note['slug']); ?>"
            style="text-decoration: none; color: inherit; display: block;"
            onmouseover="this.parentElement.style.transform='translateX(8px)'; this.parentElement.style.borderColor='#0066ff'; this.parentElement.querySelector('div').style.transform='scaleY(1)';"
            onmouseout="this.parentElement.style.transform='translateX(0)'; this.parentElement.style.borderColor='transparent'; this.parentElement.querySelector('div').style.transform='scaleY(0)';">

            <h3 style="color: #0a0e27; margin-bottom: 0.75rem; font-size: 1.25rem; font-weight: 700;">
              <?php echo htmlspecialchars($note['title']); ?>
            </h3>

            <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; font-size: 0.9rem; color: #64748b;">
              <span style="background: linear-gradient(135deg, #0066ff, #00d4ff); color: white; padding: 0.25rem 0.75rem; border-radius: 999px; font-weight: 600; font-size: 0.85rem;">
                📂 <?php echo htmlspecialchars($note['category']); ?>
              </span>

              <span style="display: flex; align-items: center; gap: 0.25rem;">
                ✍️ <strong><?php echo htmlspecialchars($note['author']); ?></strong>
              </span>

              <span style="display: flex; align-items: center; gap: 0.25rem;">
                📅 <?php echo date('d M Y', strtotime($note['created_at'])); ?>
              </span>

              <?php if (!empty($note['tags'])): ?>
                <span style="display: flex; align-items: center; gap: 0.25rem; color: #7c3aed;">
                  🏷️ <?php echo htmlspecialchars($note['tags']); ?>
                </span>
              <?php endif; ?>
            </div>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>
</div>

<!-- Quick Actions Card -->
<?php if (!empty($_SESSION['user_id'])): ?>
  <div class="card" style="margin-top: 3rem; background: linear-gradient(135deg, #0a0e27 0%, #1a1f4d 100%); color: white; padding: 2rem; border-left: 4px solid #ff4500;">
    <h3 style="color: #00d4ff; margin-bottom: 1rem;">🚀 Contribute to the Community</h3>
    <p style="color: #e5e7eb; margin-bottom: 1.5rem;">
      Share your knowledge and help thousands of aspirants. Your contribution makes a difference!
    </p>
    <a href="add_note.php">
      <button type="button" style="background: linear-gradient(135deg, #ff4500, #ff6b35);">
        📝 Upload Your Study Material
      </button>
    </a>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>