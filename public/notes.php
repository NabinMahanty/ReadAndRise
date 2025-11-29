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

<!-- Header Section -->
<div class="header-section" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: 1px solid rgba(148, 163, 184, 0.2); border-radius: 12px; padding: 2.5rem; margin-bottom: 2rem;">
  <h1 style="font-size: 2.5rem; margin-bottom: 0.5rem; background: linear-gradient(135deg, #fff 0%, #93c5fd 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
    📚 Study Materials Repository
  </h1>
  <p style="color: #cbd5e1; font-size: 1.1rem;">
    <?php if ($q || $category): ?>
      Showing <strong style="color: #60a5fa;"><?php echo $totalResults; ?></strong> result<?php echo $totalResults != 1 ? 's' : ''; ?>
    <?php else: ?>
      Explore our comprehensive collection of free study materials
    <?php endif; ?>
  </p>
</div>

<style>
  @media (max-width: 768px) {
    .search-filter-card {
      padding: 1.5rem !important;
    }

    .search-grid {
      grid-template-columns: 1fr !important;
      gap: 1rem !important;
    }

    .header-section {
      padding: 1.5rem !important;
    }

    .header-section h1 {
      font-size: 1.75rem !important;
    }

    .note-card {
      flex-direction: column !important;
    }

    .note-meta {
      flex-direction: column !important;
      align-items: flex-start !important;
      gap: 0.5rem !important;
    }
  }

  @media (min-width: 769px) and (max-width: 1024px) {
    .search-filter-card {
      padding: 2rem 3rem !important;
    }
  }
</style>

<!-- Search & Filter Card -->
<div class="card search-filter-card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); margin-bottom: 2rem; padding: 3rem 5rem;">
  <h3 style="color: #60a5fa; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.5rem;">
    🔍 Search & Filter
  </h3>

  <form method="get" style="background: transparent; box-shadow: none; padding: 0; border: none;">
    <div class="search-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
      <div>
        <label style="color: #60a5fa; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; font-size: 1rem;">
          Search Keywords
        </label>
        <input
          type="text"
          name="q"
          placeholder="Search by title, content, or tags..."
          value="<?php echo htmlspecialchars($q); ?>"
          style="width: 100%; padding: 1rem; border: none; border-radius: 10px; font-size: 1rem; background: rgba(15, 23, 42, 0.8); color: #f1f5f9;">
        <small style="color: #94a3b8; margin-top: 0.5rem; display: block; font-size: 0.875rem;">Search across titles, content, and tags</small>
      </div>

      <div>
        <label style="color: #60a5fa; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; font-size: 1rem;">
          📂 Category Filter
        </label>
        <select
          name="category"
          style="width: 100%; padding: 1rem; border: none; border-radius: 10px; font-size: 1rem; background: rgba(15, 23, 42, 0.8); color: #f1f5f9; cursor: pointer;">
          <option value="">📌 All Categories</option>
          <?php foreach ($categories as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat); ?>"
              <?php if ($cat === $category) echo 'selected'; ?>>
              📁 <?php echo htmlspecialchars($cat); ?>
            </option>
          <?php endforeach; ?>
        </select>
        <small style="color: #94a3b8; margin-top: 0.5rem; display: block; font-size: 0.875rem;">Filter by exam or subject</small>
      </div>
    </div>

    <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; justify-content: center;">
      <button type="submit" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 1rem 2.5rem; font-size: 1rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
        🔎 Search & Filter
      </button>
      <?php if ($q || $category): ?>
        <a href="notes.php">
          <button type="button" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 1rem 2rem; font-size: 1rem; border-radius: 10px; cursor: pointer; font-weight: 600;">
            ✖ Clear Filters
          </button>
        </a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Results Section -->
<?php if (empty($notes)): ?>
  <div class="card" style="text-align: center; padding: 4rem 2rem; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2);">
    <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">🔍</div>
    <h3 style="color: #f1f5f9; margin-bottom: 1rem; font-size: 1.5rem;">No Materials Found</h3>
    <p style="font-size: 1.1rem; color: #cbd5e1; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
      We couldn't find any study materials matching your search criteria. Try adjusting your filters or search terms.
    </p>

    <div style="background: rgba(15, 23, 42, 0.5); padding: 1.5rem; border-radius: 12px; margin-top: 2rem; max-width: 500px; margin-left: auto; margin-right: auto; border: 1px solid rgba(148, 163, 184, 0.2);">
      <h4 style="color: #60a5fa; margin-bottom: 1rem; font-size: 1.1rem;">💡 Suggestions:</h4>
      <ul style="text-align: left; list-style: none; padding: 0; color: #cbd5e1;">
        <li style="margin-bottom: 0.5rem;">✓ Try different keywords</li>
        <li style="margin-bottom: 0.5rem;">✓ Check for spelling errors</li>
        <li style="margin-bottom: 0.5rem;">✓ Use broader search terms</li>
        <li style="margin-bottom: 0.5rem;">✓ Clear filters and browse all materials</li>
      </ul>
    </div>

    <div style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
      <a href="notes.php">
        <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
          📚 View All Materials
        </button>
      </a>
      <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="add_note.php">
          <button type="button" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3);">
            📝 Contribute Material
          </button>
        </a>
      <?php endif; ?>
    </div>
  </div>
<?php else: ?>
  <!-- Results Count -->
  <div style="margin-bottom: 1.5rem; padding: 1rem 1.5rem; background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.3);">
    <p style="margin: 0; color: #d1fae5; font-weight: 600;">
      ✅ Found <strong style="color: #6ee7b7;"><?php echo $totalResults; ?></strong>
      study material<?php echo $totalResults != 1 ? 's' : ''; ?>
      <?php if ($category): ?>
        in <strong style="color: #6ee7b7;"><?php echo htmlspecialchars($category); ?></strong>
      <?php endif; ?>
      <?php if ($q): ?>
        matching "<strong style="color: #6ee7b7;"><?php echo htmlspecialchars($q); ?></strong>"
      <?php endif; ?>
    </p>
  </div>

  <!-- Notes List -->
  <div style="display: grid; gap: 1rem;">
    <?php foreach ($notes as $note): ?>
      <a href="note.php?slug=<?php echo urlencode($note['slug']); ?>" style="text-decoration: none;">
        <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1.5rem; transition: all 0.3s ease;">
          <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
            <div style="flex: 1;">
              <h3 style="color: #f1f5f9; margin-bottom: 0.75rem; font-size: 1.25rem; font-weight: 700;">
                <?php echo htmlspecialchars($note['title']); ?>
              </h3>

              <div style="display: flex; flex-wrap: wrap; gap: 1rem; align-items: center; font-size: 0.875rem;">
                <span style="background: rgba(59, 130, 246, 0.3); color: #60a5fa; padding: 0.25rem 0.75rem; border-radius: 50px; font-weight: 600; font-size: 0.85rem;">
                  📂 <?php echo htmlspecialchars($note['category']); ?>
                </span>

                <span style="color: #94a3b8;">
                  ✍️ <strong style="color: #cbd5e1;"><?php echo htmlspecialchars($note['author']); ?></strong>
                </span>

                <span style="color: #94a3b8;">
                  📅 <?php echo date('d M Y', strtotime($note['created_at'])); ?>
                </span>

                <?php if (!empty($note['tags'])): ?>
                  <span style="color: #a78bfa;">
                    🏷️ <?php echo htmlspecialchars($note['tags']); ?>
                  </span>
                <?php endif; ?>
              </div>
            </div>
            <div style="color: #60a5fa; font-size: 1.5rem; opacity: 0.5;">→</div>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<!-- Contribute Section -->
<?php if (!empty($_SESSION['user_id'])): ?>
  <div class="card" style="margin-top: 3rem; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2);">
    <h3 style="color: #60a5fa; margin-bottom: 1rem; font-size: 1.25rem;">🚀 Contribute to the Community</h3>
    <p style="color: #cbd5e1; margin-bottom: 1.5rem;">
      Share your knowledge and help thousands of aspirants. Your contribution makes a difference!
    </p>
    <a href="add_note.php">
      <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
        📝 Upload Your Study Material
      </button>
    </a>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>