<?php
require_once "../includes/db.php";

// Set SEO metadata for homepage
$_GET['page_title'] = 'ReadAndRise - Free Educational Platform for Competitive Exam Preparation';
$_GET['page_description'] = 'Access free study materials, exam notes, and success stories from students preparing for CDS, AFCAT, NDA, and other competitive examinations. Join our community-driven learning platform.';
$_GET['page_keywords'] = 'CDS notes, AFCAT preparation, NDA study material, competitive exam resources, defense exam preparation, success stories, free education, study community';

require_once "../includes/header.php";

// Fetch latest approved notes
$stmt = $pdo->query("
    SELECT n.title, n.slug, n.category, n.created_at, u.name AS author
    FROM notes n
    JOIN users u ON n.user_id = u.id
    WHERE n.status = 'approved'
    ORDER BY n.created_at DESC
    LIMIT 8
");
$latestNotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch latest blogs
$blogsStmt = $pdo->query("
    SELECT b.title, b.slug, b.created_at, u.name AS author
    FROM blogs b
    JOIN users u ON b.user_id = u.id
    WHERE b.status = 'approved'
    ORDER BY b.created_at DESC
    LIMIT 4
");
$latestBlogs = $blogsStmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$statsStmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM notes WHERE status = 'approved') as total_notes,
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM blogs WHERE status = 'approved') as total_stories,
        (SELECT COUNT(*) FROM current_affairs WHERE status = 'approved') as total_current,
        (SELECT COUNT(*) FROM questions WHERE status = 'approved') as total_questions
");
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

// Get category-wise note count
$categoryStmt = $pdo->query("
    SELECT category, COUNT(*) as count
    FROM notes
    WHERE status = 'approved'
    GROUP BY category
    ORDER BY count DESC
    LIMIT 8
");
$categories = $categoryStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- Hero Section -->
<div class="hero" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); position: relative; overflow: hidden;">
  <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.05; background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23fff&quot; fill-opacity=&quot;1&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

  <div style="position: relative; z-index: 1;">
    <div class="hero-tagline" style="color: #60a5fa; display: inline-block; background: rgba(59, 130, 246, 0.1); padding: 0.5rem 1.5rem; border-radius: 50px; border: 1px solid rgba(59, 130, 246, 0.3);">
      🎯 Never Forget Your Lakshya
    </div>

    <h1 class="hero-title" style="font-size: 3rem; background: linear-gradient(135deg, #fff 0%, #93c5fd 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
      Empowering Aspirants to Achieve Excellence
    </h1>

    <p class="hero-subtitle" style="color: #cbd5e1; max-width: 800px; margin: 0 auto;">
      Access premium-quality study materials, comprehensive exam notes, and inspiring success stories from
      fellow defense aspirants. Join our community preparing for CDS, AFCAT, NDA, and beyond.
      <strong style="color: #fbbf24;">100% free, community-driven, no hidden costs.</strong>
    </p>

    <div class="hero-buttons" style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; flex-wrap: wrap;">
      <a href="notes.php">
        <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4); padding: 1rem 2rem; font-size: 1.05rem;">
          📚 Explore Study Materials
        </button>
      </a>

      <?php if (!empty($_SESSION['user_id'])): ?>
        <a href="dashboard.php">
          <button type="button" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 2px solid #3b82f6; padding: 1rem 2rem; font-size: 1.05rem;">
            📊 Your Dashboard
          </button>
        </a>
      <?php else: ?>
        <a href="register.php">
          <button type="button" style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 2px solid #3b82f6; padding: 1rem 2rem; font-size: 1.05rem;">
            🚀 Join Community
          </button>
        </a>
      <?php endif; ?>
    </div>

    <!-- Statistics Display -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1.5rem; margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(148, 163, 184, 0.3);">
      <div style="text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #fbbf24; text-shadow: 0 0 20px rgba(251, 191, 36, 0.3);"><?php echo $stats['total_notes'] ?? 0; ?></div>
        <div style="font-size: 0.875rem; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.5rem;">Study Materials</div>
      </div>
      <div style="text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #60a5fa; text-shadow: 0 0 20px rgba(96, 165, 250, 0.3);"><?php echo $stats['total_users'] ?? 0; ?></div>
        <div style="font-size: 0.875rem; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.5rem;">Active Members</div>
      </div>
      <div style="text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #34d399; text-shadow: 0 0 20px rgba(52, 211, 153, 0.3);"><?php echo $stats['total_stories'] ?? 0; ?></div>
        <div style="font-size: 0.875rem; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.5rem;">Success Stories</div>
      </div>
      <div style="text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #a78bfa; text-shadow: 0 0 20px rgba(167, 139, 250, 0.3);"><?php echo $stats['total_current'] ?? 0; ?></div>
        <div style="font-size: 0.875rem; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.5rem;">Current Affairs</div>
      </div>
      <div style="text-align: center;">
        <div style="font-size: 2.5rem; font-weight: 800; color: #fb923c; text-shadow: 0 0 20px rgba(251, 146, 60, 0.3);"><?php echo $stats['total_questions'] ?? 0; ?></div>
        <div style="font-size: 0.875rem; color: #cbd5e1; text-transform: uppercase; letter-spacing: 0.05em; margin-top: 0.5rem;">Question Papers</div>
      </div>
    </div>
  </div>
</div>

<!-- Quick Access Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-top: 3rem;">

  <div class="card" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%); border: 1px solid rgba(59, 130, 246, 0.3); padding: 2rem; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -20px; right: -20px; font-size: 6rem; opacity: 0.1;">📚</div>
    <div style="position: relative; z-index: 1;">
      <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📚</div>
      <h3 style="color: #dbeafe; margin-bottom: 0.5rem; font-size: 1.25rem;">Study Materials</h3>
      <p style="color: #93c5fd; font-size: 0.875rem; margin-bottom: 1.5rem;">Comprehensive notes and resources</p>
      <a href="notes.php">
        <button style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3); width: 100%; backdrop-filter: blur(10px);">
          Explore Now →
        </button>
      </a>
    </div>
  </div>

  <div class="card" style="background: linear-gradient(135deg, #065f46 0%, #047857 100%); border: 1px solid rgba(16, 185, 129, 0.3); padding: 2rem; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -20px; right: -20px; font-size: 6rem; opacity: 0.1;">✨</div>
    <div style="position: relative; z-index: 1;">
      <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">✨</div>
      <h3 style="color: #d1fae5; margin-bottom: 0.5rem; font-size: 1.25rem;">Success Stories</h3>
      <p style="color: #6ee7b7; font-size: 0.875rem; margin-bottom: 1.5rem;">Inspiring journeys of achievers</p>
      <a href="blogs.php">
        <button style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3); width: 100%; backdrop-filter: blur(10px);">
          Read Stories →
        </button>
      </a>
    </div>
  </div>

  <div class="card" style="background: linear-gradient(135deg, #7c2d12 0%, #92400e 100%); border: 1px solid rgba(245, 158, 11, 0.3); padding: 2rem; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -20px; right: -20px; font-size: 6rem; opacity: 0.1;">📰</div>
    <div style="position: relative; z-index: 1;">
      <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📰</div>
      <h3 style="color: #fef3c7; margin-bottom: 0.5rem; font-size: 1.25rem;">Current Affairs</h3>
      <p style="color: #fde68a; font-size: 0.875rem; margin-bottom: 1.5rem;">Latest news and updates</p>
      <a href="current_affairs.php">
        <button style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3); width: 100%; backdrop-filter: blur(10px);">
          Stay Updated →
        </button>
      </a>
    </div>
  </div>

  <div class="card" style="background: linear-gradient(135deg, #4c1d95 0%, #5b21b6 100%); border: 1px solid rgba(167, 139, 250, 0.3); padding: 2rem; text-align: center; position: relative; overflow: hidden;">
    <div style="position: absolute; top: -20px; right: -20px; font-size: 6rem; opacity: 0.1;">📝</div>
    <div style="position: relative; z-index: 1;">
      <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">📝</div>
      <h3 style="color: #ede9fe; margin-bottom: 0.5rem; font-size: 1.25rem;">Question Papers</h3>
      <p style="color: #c4b5fd; font-size: 0.875rem; margin-bottom: 1.5rem;">Previous year papers</p>
      <a href="questions.php">
        <button style="background: rgba(255, 255, 255, 0.15); color: white; border: 1px solid rgba(255, 255, 255, 0.3); width: 100%; backdrop-filter: blur(10px);">
          Practice Now →
        </button>
      </a>
    </div>
  </div>
</div>

<!-- Main Content Grid: Latest Content & Categories -->
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem; margin-top: 3rem;">

  <!-- Left: Latest Study Materials & Success Stories -->
  <div>
    <!-- Latest Study Materials -->
    <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2);">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h2 style="color: #f1f5f9; font-size: 1.5rem; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
          📖 Latest Study Materials
        </h2>
        <a href="notes.php" style="color: #60a5fa; font-size: 0.875rem; text-decoration: none; display: flex; align-items: center; gap: 0.25rem; padding: 0.5rem 1rem; background: rgba(59, 130, 246, 0.1); border-radius: 8px; border: 1px solid rgba(59, 130, 246, 0.2);">
          View All →
        </a>
      </div>

      <?php if (empty($latestNotes)): ?>
        <div style="text-align: center; padding: 3rem 1rem;">
          <div style="font-size: 4rem; margin-bottom: 1rem; opacity: 0.5;">📚</div>
          <h3 style="color: #f1f5f9; margin-bottom: 0.5rem;">Be a Pioneer! 🌟</h3>
          <p style="color: #cbd5e1; margin-bottom: 1.5rem;">No materials uploaded yet. Be the first to contribute!</p>
          <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="add_note.php">
              <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">Upload Your First Note</button>
            </a>
          <?php else: ?>
            <a href="register.php">
              <button type="button" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">Join to Contribute</button>
            </a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <div style="display: grid; gap: 1rem;">
          <?php foreach ($latestNotes as $note): ?>
            <a href="note.php?slug=<?php echo urlencode($note['slug']); ?>" style="text-decoration: none;">
              <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); padding: 1.25rem; border-radius: 12px; border: 1px solid rgba(148, 163, 184, 0.2); transition: all 0.3s ease;">
                <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
                  <div style="flex: 1;">
                    <h3 style="color: #f1f5f9; font-size: 1.1rem; margin-bottom: 0.5rem; font-weight: 600;">
                      <?php echo htmlspecialchars($note['title']); ?>
                    </h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem; font-size: 0.875rem; color: #94a3b8;">
                      <span>📂 <strong style="color: #60a5fa;"><?php echo htmlspecialchars($note['category']); ?></strong></span>
                      <span>✍️ <?php echo htmlspecialchars($note['author']); ?></span>
                      <span>📅 <?php echo date('d M Y', strtotime($note['created_at'])); ?></span>
                    </div>
                  </div>
                  <div style="color: #60a5fa; font-size: 1.5rem; opacity: 0.5;">→</div>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Latest Success Stories -->
    <?php if (!empty($latestBlogs)): ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); margin-top: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
          <h2 style="color: #f1f5f9; font-size: 1.5rem; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
            ✨ Recent Success Stories
          </h2>
          <a href="blogs.php" style="color: #60a5fa; font-size: 0.875rem; text-decoration: none; display: flex; align-items: center; gap: 0.25rem; padding: 0.5rem 1rem; background: rgba(59, 130, 246, 0.1); border-radius: 8px; border: 1px solid rgba(59, 130, 246, 0.2);">
            View All →
          </a>
        </div>

        <div style="display: grid; gap: 1rem;">
          <?php foreach ($latestBlogs as $blog): ?>
            <a href="blog.php?slug=<?php echo urlencode($blog['slug']); ?>" style="text-decoration: none;">
              <div style="background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); padding: 1.25rem; border-radius: 12px; border: 1px solid rgba(16, 185, 129, 0.2); transition: all 0.3s ease;">
                <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
                  <div style="flex: 1;">
                    <h3 style="color: #d1fae5; font-size: 1.1rem; margin-bottom: 0.5rem; font-weight: 600;">
                      <?php echo htmlspecialchars($blog['title']); ?>
                    </h3>
                    <div style="display: flex; flex-wrap: wrap; gap: 1rem; font-size: 0.875rem; color: #6ee7b7;">
                      <span>✍️ <?php echo htmlspecialchars($blog['author']); ?></span>
                      <span>📅 <?php echo date('d M Y', strtotime($blog['created_at'])); ?></span>
                    </div>
                  </div>
                  <div style="color: #34d399; font-size: 1.5rem; opacity: 0.5;">→</div>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <!-- Right: Categories & User Actions -->
  <div>
    <!-- Popular Categories -->
    <?php if (!empty($categories)): ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2);">
        <h3 style="color: #f1f5f9; font-size: 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
          🎯 Popular Categories
        </h3>
        <div style="display: grid; gap: 0.75rem;">
          <?php foreach ($categories as $category): ?>
            <a href="notes.php?category=<?php echo urlencode($category['category']); ?>" style="text-decoration: none;">
              <div style="background: rgba(59, 130, 246, 0.1); padding: 1rem; border-radius: 8px; border: 1px solid rgba(59, 130, 246, 0.2); transition: all 0.3s ease;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                  <span style="color: #f1f5f9; font-weight: 600; font-size: 0.95rem;"><?php echo htmlspecialchars($category['category']); ?></span>
                  <span style="background: rgba(59, 130, 246, 0.3); color: #60a5fa; padding: 0.25rem 0.75rem; border-radius: 50px; font-size: 0.75rem; font-weight: 700;">
                    <?php echo $category['count']; ?>
                  </span>
                </div>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- User Actions -->
    <?php if (!empty($_SESSION['user_id'])): ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); margin-top: 1.5rem;">
        <h3 style="color: #f1f5f9; font-size: 1.25rem; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
          🚀 Contribute
        </h3>
        <div style="display: grid; gap: 0.75rem;">
          <a href="add_note.php" style="text-decoration: none;">
            <div style="background: rgba(59, 130, 246, 0.1); padding: 1rem; border-radius: 8px; border: 1px solid rgba(59, 130, 246, 0.2); color: #93c5fd; transition: all 0.3s ease;">
              <strong>📚 Upload Study Material</strong>
            </div>
          </a>
          <a href="add_blog.php" style="text-decoration: none;">
            <div style="background: rgba(16, 185, 129, 0.1); padding: 1rem; border-radius: 8px; border: 1px solid rgba(16, 185, 129, 0.2); color: #6ee7b7; transition: all 0.3s ease;">
              <strong>✨ Share Your Story</strong>
            </div>
          </a>
          <a href="add_current.php" style="text-decoration: none;">
            <div style="background: rgba(245, 158, 11, 0.1); padding: 1rem; border-radius: 8px; border: 1px solid rgba(245, 158, 11, 0.2); color: #fde68a; transition: all 0.3s ease;">
              <strong>📰 Add Current Affairs</strong>
            </div>
          </a>
          <a href="add_question.php" style="text-decoration: none;">
            <div style="background: rgba(167, 139, 250, 0.1); padding: 1rem; border-radius: 8px; border: 1px solid rgba(167, 139, 250, 0.2); color: #c4b5fd; transition: all 0.3s ease;">
              <strong>📝 Add Question Paper</strong>
            </div>
          </a>
        </div>
      </div>
    <?php else: ?>
      <div class="card" style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); border: 1px solid rgba(148, 163, 184, 0.2); margin-top: 1.5rem;">
        <h3 style="color: #f1f5f9; font-size: 1.25rem; margin-bottom: 1rem;">👋 Join Our Community</h3>
        <p style="color: #cbd5e1; font-size: 0.95rem; margin-bottom: 1.5rem;">Create a free account to contribute and access all features.</p>
        <a href="register.php">
          <button style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); width: 100%; margin-bottom: 0.75rem;">
            🚀 Create Account
          </button>
        </a>
        <a href="login.php">
          <button style="background: rgba(59, 130, 246, 0.15); color: #60a5fa; border: 1px solid rgba(59, 130, 246, 0.3); width: 100%;">
            🔐 Login
          </button>
        </a>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php require_once "../includes/footer.php"; ?>