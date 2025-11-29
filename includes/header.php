<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Dynamic page metadata
$page_title = $_GET['page_title'] ?? 'ReadAndRise - Free Educational Platform for Competitive Exam Preparation';
$page_description = $_GET['page_description'] ?? 'Access free study materials, exam notes, and real success stories from students preparing for CDS, AFCAT, NDA, and other competitive examinations. Join our community-driven learning platform.';
$page_keywords = $_GET['page_keywords'] ?? 'competitive exam notes, CDS preparation, AFCAT study material, NDA notes, free study resources, exam preparation, student community, success stories';
$page_url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Essential Meta Tags -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- Primary SEO Meta Tags -->
  <title><?php echo htmlspecialchars($page_title); ?></title>
  <meta name="title" content="<?php echo htmlspecialchars($page_title); ?>">
  <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
  <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
  <meta name="author" content="ReadAndRise">
  <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
  <link rel="canonical" href="<?php echo htmlspecialchars($page_url); ?>">

  <!-- Open Graph / Facebook Meta Tags -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="<?php echo htmlspecialchars($page_url); ?>">
  <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
  <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
  <meta property="og:image" content="https://<?php echo $_SERVER['HTTP_HOST']; ?>/readandrise/assets/og-image.jpg">
  <meta property="og:site_name" content="ReadAndRise">
  <meta property="og:locale" content="en_US">

  <!-- Twitter Card Meta Tags -->
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:url" content="<?php echo htmlspecialchars($page_url); ?>">
  <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
  <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
  <meta name="twitter:image" content="https://<?php echo $_SERVER['HTTP_HOST']; ?>/readandrise/assets/og-image.jpg">

  <!-- Favicon and Icons -->
  <link rel="icon" type="image/x-icon" href="/readandrise/assets/favicon.ico">
  <link rel="apple-touch-icon" sizes="180x180" href="/readandrise/assets/apple-touch-icon.png">

  <!-- Structured Data / JSON-LD -->
  <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "EducationalOrganization",
      "name": "ReadAndRise",
      "description": "Free educational platform for competitive exam preparation",
      "url": "https://<?php echo $_SERVER['HTTP_HOST']; ?>/readandrise/",
      "logo": "https://<?php echo $_SERVER['HTTP_HOST']; ?>/readandrise/assets/logo.png",
      "sameAs": [
        "https://github.com/NabinMahanty/ReadAndRise"
      ]
    }
  </script>

  <!-- Preconnect for Performance -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

  <!-- Stylesheet -->
  <link rel="stylesheet" href="/readandrise/assets/style.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Lottie Web Component CDN - Load in HEAD for faster init -->
  <script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.8.5/dist/dotlottie-wc.js" type="module"></script>

  <!-- ================= LOADING SCREEN: DOTLOTTIE VERSION =================== -->
  <style>
    /* Hide body content until loaded */
    body {
      opacity: 0;
      transition: opacity 0.3s ease-in;
    }

    body.loaded {
      opacity: 1;
    }

    #page-loader {
      position: fixed;
      inset: 0;
      background: #071022;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      z-index: 99999;
      color: #fff;
      text-align: center;
      padding: 20px;
      opacity: 1;
      transition: opacity 0.5s ease-out;
    }

    #page-loader.hidden {
      opacity: 0;
      pointer-events: none;
    }

    #loader-title {
      margin-top: 10px;
      font-size: 22px;
      font-weight: 700;
      letter-spacing: 1px;
    }

    #loader-sub {
      margin-top: 6px;
      font-size: 13px;
      color: #cbd5e1;
    }
  </style>
</head>

<body>
  <div id="page-loader">
    <!-- YOUR Animation -->
    <dotlottie-wc
      src="https://lottie.host/311da995-fc37-4dba-bc98-28aeeada1b66/yuHTX46CPz.lottie"
      style="width: 220px; height: 220px"
      autoplay
      loop>
    </dotlottie-wc>

    <div id="loader-title">ReadAndRise</div>
    <div id="loader-sub">Preparing your mission…</div>
  </div>

  <header class="main-header">
    <div class="header-container">
      <div class="logo-section">
        <h1 class="site-logo">
          <a href="/readandrise/public/index.php">
            <span class="logo-icon">🎯</span>
            <span class="logo-text">ReadAndRise</span>
          </a>
        </h1>
        <p class="site-tagline">Excellence Through Knowledge</p>
      </div>

      <nav class="main-nav">
        <a href="/readandrise/public/index.php" class="nav-link">
          <span class="nav-icon">🏠</span>
          <span>Home</span>
        </a>
        <a href="/readandrise/public/notes.php" class="nav-link">
          <span class="nav-icon">📚</span>
          <span>Study Materials</span>
        </a>
        <a href="/readandrise/public/blogs.php" class="nav-link">
          <span class="nav-icon">✨</span>
          <span>Success Stories</span>
        </a>
        <a href="/readandrise/public/current_affairs.php" class="nav-link">
          <span class="nav-icon">📰</span>
          <span>Current Affairs</span>
        </a>
        <a href="/readandrise/public/questions.php" class="nav-link">
          <span class="nav-icon">📝</span>
          <span>Question Papers</span>
        </a>

        <?php if (!empty($_SESSION['user_id'])): ?>
          <?php if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
            <a href="/readandrise/admin/index.php" class="nav-link nav-admin">
              <span class="nav-icon">⚙️</span>
              <span>Command Center</span>
            </a>
          <?php endif; ?>

          <a href="/readandrise/public/dashboard.php" class="nav-link nav-dashboard">
            <span class="nav-icon">📊</span>
            <span>Dashboard</span>
          </a>
          <a href="/readandrise/public/logout.php" class="nav-link nav-logout">
            <span class="nav-icon">🚪</span>
            <span>Logout</span>
          </a>
        <?php else: ?>
          <a href="/readandrise/public/login.php" class="nav-link nav-login">
            <span class="nav-icon">🔐</span>
            <span>Login</span>
          </a>
          <a href="/readandrise/public/register.php" class="nav-link nav-register">
            <span class="nav-icon">📝</span>
            <span>Join Now</span>
          </a>
        <?php endif; ?>
      </nav>

      <button class="mobile-menu-toggle" aria-label="Toggle navigation menu">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>
  </header>

  <main class="main-content">