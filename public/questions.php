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
$totalResults = count($items);
?>

<style>
  @media (max-width: 768px) {
    .search-filter-card {
      padding: 1.5rem !important;
    }

    .filter-grid {
      grid-template-columns: 1fr !important;
      gap: 1rem !important;
    }

    .header-section {
      padding: 1.5rem 1rem !important;
    }

    .header-section h1 {
      font-size: 1.75rem !important;
    }

    .question-badges {
      flex-direction: column !important;
      align-items: flex-start !important;
      gap: 0.5rem !important;
    }
  }

  @media (min-width: 769px) and (max-width: 1024px) {
    .search-filter-card {
      padding: 2rem 3rem !important;
    }

    .filter-grid {
      grid-template-columns: 2fr 1fr !important;
    }
  }
</style>

<!-- Header Section -->
<div class="header-section" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); padding: 3rem 2rem; border-radius: 16px; margin-bottom: 2rem; text-align: center; border: 1px solid rgba(148, 163, 184, 0.2);">
  <h1 style="color: #f1f5f9; font-size: 2.5rem; margin-bottom: 1rem; font-weight: 700;">
    📝 Previous Year Question Papers
  </h1>
  <p style="color: #cbd5e1; font-size: 1.1rem;">
    <?php if ($q || $year || $subject): ?>
      Showing <strong style="color: #60a5fa;"><?php echo $totalResults; ?></strong> result<?php echo $totalResults != 1 ? 's' : ''; ?>
    <?php else: ?>
      Access previous year question papers from Google Drive folders shared by the community
    <?php endif; ?>
  </p>
</div>

<!-- Search & Filter Card -->
<div class="card search-filter-card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); margin-bottom: 2rem; padding: 3rem 5rem;">
  <h3 style="color: #60a5fa; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem; font-size: 1.5rem;">
    🔍 Search & Filter
  </h3>

  <form method="GET" style="background: transparent; box-shadow: none; padding: 0; border: none;">
    <div class="filter-grid" style="display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
      <div>
        <label style="color: #60a5fa; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; font-size: 1rem;">
          🔎 Search Keywords
        </label>
        <input
          type="text"
          name="q"
          placeholder="Search by title or description..."
          value="<?php echo htmlspecialchars($q); ?>"
          style="width: 100%; padding: 1rem; border: none; border-radius: 10px; font-size: 1rem; background: rgba(15, 23, 42, 0.8); color: #f1f5f9;">
        <small style="color: #94a3b8; margin-top: 0.5rem; display: block; font-size: 0.875rem;">Search across titles and descriptions</small>
      </div>
      <div>
        <label style="color: #60a5fa; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; font-size: 1rem;">
          📅 Year Filter
        </label>
        <select name="year" style="width: 100%; padding: 1rem; border: none; border-radius: 10px; font-size: 1rem; background: rgba(15, 23, 42, 0.8); color: #f1f5f9; cursor: pointer;">
          <option value="">All Years</option>
          <?php foreach ($years as $y): ?>
            <option value="<?php echo $y; ?>" <?php if ($y == $year) echo 'selected'; ?>><?php echo $y; ?></option>
          <?php endforeach; ?>
        </select>
        <small style="color: #94a3b8; margin-top: 0.5rem; display: block; font-size: 0.875rem;">Filter by year</small>
      </div>
      <div>
        <label style="color: #60a5fa; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.75rem; font-size: 1rem;">
          📚 Subject Filter
        </label>
        <input
          type="text"
          name="subject"
          placeholder="e.g., Math, Physics..."
          value="<?php echo htmlspecialchars($subject); ?>"
          style="width: 100%; padding: 1rem; border: none; border-radius: 10px; font-size: 1rem; background: rgba(15, 23, 42, 0.8); color: #f1f5f9;">
        <small style="color: #94a3b8; margin-top: 0.5rem; display: block; font-size: 0.875rem;">Filter by subject</small>
      </div>
    </div>
    <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: center; justify-content: center;">
      <button type="submit" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 1rem 2.5rem; font-size: 1rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 0.5rem; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
        🔎 Apply Filters
      </button>
      <?php if ($q || $year || $subject): ?>
        <a href="questions.php">
          <button type="button" style="background: rgba(239, 68, 68, 0.15); color: #f87171; border: 1px solid rgba(239, 68, 68, 0.3); padding: 1rem 2rem; font-size: 1rem; border-radius: 10px; cursor: pointer; font-weight: 600;">
            ✖ Clear Filters
          </button>
        </a>
      <?php endif; ?>
    </div>
  </form>
</div>

<!-- Results Section -->
<?php if (empty($items)): ?>
  <div class="card" style="text-align: center; padding: 4rem 2rem; background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2);">
    <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">📝</div>
    <h3 style="color: #f1f5f9; margin-bottom: 1rem; font-size: 1.5rem;">No Question Papers Found</h3>
    <p style="font-size: 1.1rem; color: #cbd5e1; margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
      <?php echo ($q || $year || $subject) ? 'We couldn\'t find any question papers matching your filters. Try adjusting your search criteria.' : 'Question papers will be available here soon.'; ?>
    </p>

    <div style="background: rgba(15, 23, 42, 0.5); padding: 1.5rem; border-radius: 12px; margin-top: 2rem; max-width: 500px; margin-left: auto; margin-right: auto; border: 1px solid rgba(148, 163, 184, 0.2);">
      <h4 style="color: #60a5fa; margin-bottom: 1rem; font-size: 1.1rem;">💡 Suggestions:</h4>
      <ul style="text-align: left; list-style: none; padding: 0; color: #cbd5e1;">
        <li style="margin-bottom: 0.5rem;">✓ Try different keywords</li>
        <li style="margin-bottom: 0.5rem;">✓ Select a different year</li>
        <li style="margin-bottom: 0.5rem;">✓ Adjust subject filter</li>
        <li style="margin-bottom: 0.5rem;">✓ Clear all filters and browse</li>
      </ul>
    </div>

    <div style="margin-top: 2rem;">
      <a href="questions.php">
        <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 1rem 2rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600;">
          📝 View All Question Papers
        </button>
      </a>
    </div>
  </div>
<?php else: ?>
  <!-- Results Count -->
  <div style="margin-bottom: 1.5rem; padding: 1rem 1.5rem; background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.3);">
    <p style="margin: 0; color: #d1fae5; font-weight: 600;">
      ✅ Found <strong style="color: #6ee7b7;"><?php echo $totalResults; ?></strong>
      question paper<?php echo $totalResults != 1 ? 's' : ''; ?>
      <?php if ($year): ?>
        for <strong style="color: #6ee7b7;"><?php echo htmlspecialchars($year); ?></strong>
      <?php endif; ?>
      <?php if ($subject): ?>
        in <strong style="color: #6ee7b7;"><?php echo htmlspecialchars($subject); ?></strong>
      <?php endif; ?>
      <?php if ($q): ?>
        matching "<strong style="color: #6ee7b7;"><?php echo htmlspecialchars($q); ?></strong>"
      <?php endif; ?>
    </p>
  </div>

  <!-- Question Papers List -->
  <div style="display: grid; gap: 1rem;">
    <?php foreach ($items as $item): ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); padding: 1.5rem; transition: all 0.3s ease;">
        <h3 style="color: #f1f5f9; margin-bottom: 1rem; font-size: 1.25rem; font-weight: 600; line-height: 1.4;">
          <?php echo htmlspecialchars($item['title'] ?: ($item['subject'] . ' - ' . $item['year'])); ?>
        </h3>

        <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; margin-bottom: 1rem;">
          <span style="background: rgba(59, 130, 246, 0.2); color: #60a5fa; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(59, 130, 246, 0.3);">
            📅 <?php echo htmlspecialchars($item['year']); ?>
          </span>
          <?php if ($item['subject']): ?>
            <span style="background: rgba(168, 85, 247, 0.2); color: #a78bfa; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(168, 85, 247, 0.3);">
              📚 <?php echo htmlspecialchars($item['subject']); ?>
            </span>
          <?php endif; ?>
          <span style="background: rgba(251, 191, 36, 0.2); color: #fbbf24; padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.875rem; font-weight: 600; border: 1px solid rgba(251, 191, 36, 0.3);">
            📊 <?php echo htmlspecialchars($item['qtype']); ?>
          </span>
        </div>

        <?php if (!empty($item['description'])): ?>
          <p style="color: #cbd5e1; margin-bottom: 1rem; line-height: 1.6;">
            <?php echo nl2br(htmlspecialchars($item['description'])); ?>
          </p>
        <?php endif; ?>

        <div style="display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap;">
          <span style="color: #94a3b8; font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
            📅 Added on <?php echo date('d M Y', strtotime($item['created_at'])); ?>
          </span>
          <a href="<?php echo htmlspecialchars($item['drive_folder_link']); ?>" target="_blank" rel="noopener" style="text-decoration: none;">
            <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 0.75rem 1.5rem; font-size: 0.875rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3); display: flex; align-items: center; gap: 0.5rem;">
              🔗 Open Google Drive Folder
            </button>
          </a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<!-- Contribute Section -->
<?php if (!empty($_SESSION['user_id'])): ?>
  <div style="margin-top: 3rem; padding: 2.5rem; background: linear-gradient(135deg, rgba(59, 130, 246, 0.1) 0%, rgba(37, 99, 235, 0.1) 100%); border-radius: 16px; text-align: center; border: 1px solid rgba(59, 130, 246, 0.3);">
    <h3 style="color: #f1f5f9; margin-bottom: 1rem; font-size: 1.5rem; font-weight: 600;">📝 Share Question Papers</h3>
    <p style="color: #cbd5e1; margin-bottom: 1.5rem; font-size: 1.1rem;">
      Have previous year question papers? Share them with the community and help others prepare better.
    </p>
    <a href="add_question.php">
      <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); padding: 1rem 2.5rem; font-size: 1rem; color: white; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
        ➕ Add Question Papers
      </button>
    </a>
  </div>
<?php endif; ?>

<?php require_once "../includes/footer.php"; ?>