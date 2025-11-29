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
$totalResults = count($blogs);
?>

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
      padding: 1.5rem 1rem !important;
    }

    .header-section h1 {
      font-size: 1.75rem !important;
    }

    .blog-meta {
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

<!-- Header Section -->
<div class="header-section" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); padding: 3rem 2rem; border-radius: 16px; margin-bottom: 2rem; text-align: center; border: 1px solid rgba(148, 163, 184, 0.2);">
  <h1 style="color: #f1f5f9; font-size: 2.5rem; margin-bottom: 1rem; font-weight: 700;">
    ✨ Success Stories & Blogs
  </h1>
  <p style="color: #cbd5e1; font-size: 1.1rem;">
    <?php if ($q || $category): ?>
      Showing <strong style="color: #60a5fa;"><?php echo $totalResults; ?></strong> result<?php echo $totalResults != 1 ? 's' : ''; ?>
    <?php else: ?>
      Get inspired by success stories and insightful articles from our community
    <?php endif; ?>
  </p>
</div>

<!-- Search & Filter Card -->
<div class="card search-filter-card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); margin-bottom: 2rem; padding: 3rem 5rem;">
  <h3 style="color: #60a5fa; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.5rem;">
    🔍 Search & Filter
  </h3>

  <form method="get" style="background: transparent; box-shadow: none; padding: 0; border: none;">
    <div class="search-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
      <div>
        <label style="color: #60a5fa; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; font-size: 1rem;">
          🔎 Search Keywords
        </label>
        <input
          type="text"
          name="q"
          placeholder="Search by title or content..."
          value="<?php echo htmlspecialchars($q); ?>"
          style="width: 100%; padding: 1rem; border: none; border-radius: 10px; font-size: 1rem; background: rgba(15, 23, 42, 0.8); color: #f1f5f9;">
        <small style="color: #94a3b8; margin-top: 0.5rem; display: block; font-size: 0.875rem;">Search across titles and content</small>
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
        <small style="color: #94a3b8; margin-top: 0.5rem; display: block; font-size: 0.875rem;">Filter by category</small>
      </div>
    </div>

    <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; justify-content: center;">
      <button type="submit" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 1rem 2.5rem; font-size: 1rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
        🔎 Search & Filter
      </button>
      <?php if ($q || $category): ?>
        <a href="blogs.php">
          <button type="button" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 1rem 2rem; font-size: 1rem; border-radius: 10px; cursor: pointer; font-weight: 600;">
            ✖ Clear Filters
          </button>
        </a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Results Section -->
<?php if (empty($blogs)): ?>
  <div class="card" style="text-align: center; padding: 4rem 2rem; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2);">
    <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">✨</div>
    <h3 style="color: #f1f5f9; margin-bottom: 1rem; font-size: 1.5rem;">No Stories Found</h3>
    <p style="font-size: 1.1rem; color: #cbd5e1; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
      <?php echo ($q || $category) ? 'We couldn\'t find any success stories matching your search criteria. Try adjusting your filters.' : 'Success stories will be published here soon.'; ?>
    </p>

    <div style="background: rgba(15, 23, 42, 0.5); padding: 1.5rem; border-radius: 12px; margin-top: 2rem; max-width: 500px; margin-left: auto; margin-right: auto; border: 1px solid rgba(148, 163, 184, 0.2);">
      <h4 style="color: #60a5fa; margin-bottom: 1rem; font-size: 1.1rem;">💡 Suggestions:</h4>
      <ul style="text-align: left; list-style: none; padding: 0; color: #cbd5e1;">
        <li style="margin-bottom: 0.5rem;">✓ Try different keywords</li>
        <li style="margin-bottom: 0.5rem;">✓ Select a different category</li>
        <li style="margin-bottom: 0.5rem;">✓ Use broader search terms</li>
        <li style="margin-bottom: 0.5rem;">✓ Clear filters and browse all stories</li>
      </ul>
    </div>

    <div style="margin-top: 2rem;">
      <a href="blogs.php">
        <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 1rem 2rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600;">
          ✨ View All Stories
        </button>
      </a>
    </div>
  </div>
<?php else: ?>
  <!-- Results Count -->
  <div style="margin-bottom: 1.5rem; padding: 1rem 1.5rem; background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.3);">
    <p style="margin: 0; color: #d1fae5; font-weight: 600;">
      ✅ Found <strong style="color: #6ee7b7;"><?php echo $totalResults; ?></strong>
      success stor<?php echo $totalResults != 1 ? 'ies' : 'y'; ?>
      <?php if ($category): ?>
        in <strong style="color: #6ee7b7;"><?php echo htmlspecialchars($category); ?></strong>
      <?php endif; ?>
      <?php if ($q): ?>
        matching "<strong style="color: #6ee7b7;"><?php echo htmlspecialchars($q); ?></strong>"
      <?php endif; ?>
    </p>
  </div>

  <!-- Success Stories List -->
  <div style="display: grid; gap: 1rem;">
    <?php foreach ($blogs as $blog): ?>
      <a href="blog.php?slug=<?php echo urlencode($blog['slug']); ?>" style="text-decoration: none;">
        <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1.5rem; transition: all 0.3s ease;">
          <h3 style="color: #f1f5f9; margin-bottom: 1rem; font-size: 1.25rem; font-weight: 600; line-height: 1.4;">
            <?php echo htmlspecialchars($blog['title']); ?>
          </h3>

          <div style="display: flex; align-items: center; gap: 1.5rem; color: #94a3b8; font-size: 0.875rem; flex-wrap: wrap;">
            <span style="display: flex; align-items: center; gap: 0.5rem; background: rgba(168, 85, 247, 0.2); color: #a78bfa; padding: 0.5rem 1rem; border-radius: 8px; font-weight: 600; border: 1px solid rgba(168, 85, 247, 0.3);">
              📂 <?php echo htmlspecialchars($blog['category']); ?>
            </span>
            <span style="display: flex; align-items: center; gap: 0.5rem;">
              ✍️ <?php echo htmlspecialchars($blog['author']); ?>
            </span>
            <span style="display: flex; align-items: center; gap: 0.5rem;">
              📅 <?php echo date('d M Y', strtotime($blog['created_at'])); ?>
            </span>
            <span style="color: #60a5fa; font-weight: 600; margin-left: auto;">
              📖 Read Story →
            </span>
          </div>
        </div>
      </a>
    <?php endforeach; ?>
  </div>
  </div>
<?php endif; ?>

<!-- Contribute Section -->
<?php if (!empty($_SESSION['user_id'])): ?>
  <div style="margin-top: 3rem; padding: 2.5rem; background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%); border-radius: 16px; text-align: center; border: 1px solid rgba(59, 130, 246, 0.3);">
    <h3 style="color: #f1f5f9; margin-bottom: 1rem; font-size: 1.5rem; font-weight: 600;">✨ Share Your Success Story</h3>
    <p style="color: #cbd5e1; margin-bottom: 1.5rem; font-size: 1.1rem;">
      Inspire others by sharing your journey, achievements, and valuable insights with the community.
    </p>
    <a href="add_blog.php">
      <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 1rem 2.5rem; font-size: 1rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
        ➕ Share Your Story
      </button>
    </a>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>