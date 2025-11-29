<?php
require_once "../includes/db.php";

// Set SEO metadata for homepage
$_GET['page_title'] = 'ReadAndRise - Premier Educational Platform for Competitive Exam Excellence';
$_GET['page_description'] = 'Master competitive examinations with free, community-driven study materials. Access comprehensive notes for CDS, AFCAT, NDA, and read inspiring success stories from fellow aspirants.';
$_GET['page_keywords'] = 'CDS notes, AFCAT preparation, NDA study material, competitive exam resources, defense exam preparation, success stories, free education, study community';

require_once "../includes/header.php";

// Fetch latest approved notes (limit 6 for better display)
$stmt = $pdo->query("
    SELECT n.title, n.slug, n.category, n.created_at, u.name AS author
    FROM notes n
    JOIN users u ON n.user_id = u.id
    WHERE n.status = 'approved'
    ORDER BY n.created_at DESC
    LIMIT 6
");
$latestNotes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics for engaging display
$statsStmt = $pdo->query("
    SELECT 
        (SELECT COUNT(*) FROM notes WHERE status = 'approved') as total_notes,
        (SELECT COUNT(*) FROM users) as total_users,
        (SELECT COUNT(*) FROM blogs WHERE status = 'approved') as total_stories
");
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="hero">
  <div class="hero-tagline">🎯 Excellence Through Knowledge</div>
  <h1 class="hero-title">Empowering Aspirants to Achieve Excellence</h1>
  <p class="hero-subtitle">
    Access premium-quality study materials, comprehensive exam notes, and inspiring success stories from
    fellow defense aspirants. Join our elite community of achievers preparing for CDS, AFCAT, NDA, and beyond.
    <strong>100% free, community-driven, no hidden costs.</strong>
  </p>

  <div class="hero-buttons">
    <a href="notes.php">
      <button type="button">📚 Explore Study Materials</button>
    </a>

    <?php if (!empty($_SESSION['user_id'])): ?>
      <a href="dashboard.php">
        <button type="button" class="btn-secondary">📊 Access Your Dashboard</button>
      </a>
    <?php else: ?>
      <a href="register.php">
        <button type="button" class="btn-secondary">🚀 Join Our Community</button>
      </a>
    <?php endif; ?>
  </div>

  <!-- Statistics Display -->
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1.5rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,0.2);">
    <div style="text-align: center;">
      <div style="font-size: 2rem; font-weight: 800; color: #fbbf24;"><?php echo $stats['total_notes'] ?? 0; ?></div>
      <div style="font-size: 0.875rem; color: #d1d5db; text-transform: uppercase; letter-spacing: 0.05em;">Study Materials</div>
    </div>
    <div style="text-align: center;">
      <div style="font-size: 2rem; font-weight: 800; color: #60a5fa;"><?php echo $stats['total_users'] ?? 0; ?></div>
      <div style="font-size: 0.875rem; color: #d1d5db; text-transform: uppercase; letter-spacing: 0.05em;">Active Members</div>
    </div>
    <div style="text-align: center;">
      <div style="font-size: 2rem; font-weight: 800; color: #34d399;"><?php echo $stats['total_stories'] ?? 0; ?></div>
      <div style="font-size: 0.875rem; color: #d1d5db; text-transform: uppercase; letter-spacing: 0.05em;">Success Stories</div>
    </div>
  </div>
</div>

<div class="home-grid">

  <!-- LEFT: Latest Study Materials -->
  <section>
    <div class="section-header">
      <h2>📖 Latest Study Materials</h2>
      <a href="notes.php">View All Resources →</a>
    </div>

    <div class="notes-list">
      <?php if (empty($latestNotes)): ?>
        <div class="card">
          <h3 style="margin-bottom: 0.5rem;">Be a Pioneer! 🌟</h3>
          <p>No materials have been uploaded yet. Take the initiative and be the first to contribute to our knowledge base. Your notes could help hundreds of aspirants achieve their dreams!</p>
          <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="add_note.php">
              <button type="button" style="margin-top: 1rem;">Upload Your First Note</button>
            </a>
          <?php else: ?>
            <a href="register.php">
              <button type="button" style="margin-top: 1rem;">Join to Contribute</button>
            </a>
          <?php endif; ?>
        </div>
      <?php else: ?>
        <ul>
          <?php foreach ($latestNotes as $note): ?>
            <li>
              <a href="note.php?slug=<?php echo urlencode($note['slug']); ?>">
                <strong><?php echo htmlspecialchars($note['title']); ?></strong>
              </a>
              <br>
              <small>
                📂 <strong><?php echo htmlspecialchars($note['category']); ?></strong>
                | ✍️ By <strong><?php echo htmlspecialchars($note['author']); ?></strong>
                | 📅 <?php echo date('d M Y', strtotime($note['created_at'])); ?>
              </small>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div class="card" style="margin-top: 1.5rem; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-left: 4px solid #3b82f6;">
      <h3 style="margin-bottom: 0.75rem; color: #1e40af;">🎯 Why Choose ReadAndRise?</h3>
      <ul style="padding-left: 1.25rem; font-size: 0.95rem; color:#1f2937; line-height: 1.8;">
        <li><strong>Comprehensive Resources:</strong> Access meticulously curated notes for CDS, AFCAT, NDA, MCA, and programming examinations.</li>
        <li><strong>Real Success Stories:</strong> Learn from the journeys, struggles, and triumphs of fellow aspirants.</li>
        <li><strong>Zero Cost Barrier:</strong> No paywalls, no premium tiers—just pure, community-driven knowledge sharing.</li>
        <li><strong>Contribute & Grow:</strong> Share your expertise and help build India's largest free educational repository.</li>
        <li><strong>Mission-Driven:</strong> Dedicated to democratizing education for defense and competitive exam aspirants.</li>
      </ul>
    </div>
  </section>

  <!-- RIGHT: Quick Actions & Information -->
  <aside>
    <div class="quick-card">
      <h3>🚀 Take Action Now</h3>
      <p style="font-size: 0.95rem; line-height: 1.7;">
        Join our elite community of dedicated aspirants. Contribute your knowledge, access premium resources,
        and accelerate your journey to success.
      </p>

      <ul>
        <?php if (!empty($_SESSION['user_id'])): ?>
          <li><a href="add_note.php"><strong>Upload Study Material</strong> - Share your expertise</a></li>
          <li><a href="add_blog.php"><strong>Share Your Journey</strong> - Inspire others</a></li>
          <li><a href="add_current.php"><strong>Submit Current Affairs</strong> - Keep community updated</a></li>
          <li><a href="add_question.php"><strong>Add Question Papers</strong> - Share resources</a></li>
          <li><a href="dashboard.php"><strong>Command Center</strong> - Manage your contributions</a></li>
        <?php else: ?>
          <li><a href="register.php"><strong>Create Free Account</strong> - Join in 2 minutes</a></li>
          <li><a href="login.php"><strong>Member Login</strong> - Access your dashboard</a></li>
          <li><a href="notes.php"><strong>Browse Materials</strong> - No login required</a></li>
          <li><a href="blogs.php"><strong>Read Success Stories</strong> - Get inspired</a></li>
          <li><a href="current_affairs.php"><strong>Current Affairs</strong> - Stay updated</a></li>
          <li><a href="questions.php"><strong>Question Papers</strong> - Practice resources</a></li>
        <?php endif; ?>
      </ul>
    </div>

    <div class="card" style="margin-top: 1.5rem; background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-left: 4px solid #f59e0b;">
      <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: #92400e;">⭐ Featured Initiative</h3>
      <p style="font-size: 0.95rem; color:#78350f; line-height: 1.7;">
        <strong>"Success Stories"</strong> section launching soon! Share your preparation journey—the challenges
        you overcame, strategies that worked, handling family pressure, balancing work and studies. Your story
        could be the motivation someone needs today.
      </p>
    </div>

    <div class="card" style="margin-top: 1.5rem; background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); border-left: 4px solid #10b981;">
      <h3 style="font-size: 1.1rem; margin-bottom: 0.75rem; color: #065f46;">💡 Study Smart, Not Hard</h3>
      <ul style="padding-left: 1.25rem; font-size: 0.875rem; color:#064e3b; line-height: 1.7;">
        <li>Structured preparation materials</li>
        <li>Topic-wise categorization</li>
        <li>Peer-reviewed content quality</li>
        <li>Regular updates and additions</li>
        <li>Mobile-friendly access</li>
      </ul>
    </div>
  </aside>

</div>

<!-- Additional Value Proposition Section -->
<div class="card" style="margin-top: 2rem; background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white; padding: 2rem;">
  <h2 style="color: #fbbf24; text-align: center; margin-bottom: 1.5rem;">🎖️ Our Commitment to Excellence</h2>
  <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
    <div style="text-align: center;">
      <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🎓</div>
      <h4 style="color: white; margin-bottom: 0.5rem;">Quality First</h4>
      <p style="font-size: 0.9rem; color: #d1d5db;">All materials undergo community review to ensure accuracy and relevance.</p>
    </div>
    <div style="text-align: center;">
      <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🤝</div>
      <h4 style="color: white; margin-bottom: 0.5rem;">Community Driven</h4>
      <p style="font-size: 0.9rem; color: #d1d5db;">Built by aspirants, for aspirants. Your success is our mission.</p>
    </div>
    <div style="text-align: center;">
      <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🆓</div>
      <h4 style="color: white; margin-bottom: 0.5rem;">Forever Free</h4>
      <p style="font-size: 0.9rem; color: #d1d5db;">No hidden charges, no premium tiers. Education should be accessible to all.</p>
    </div>
  </div>
</div>

<?php require_once "../includes/footer.php"; ?>