<?php
// 404 Not Found page
$_GET['page_title'] = '404 — Page Not Found';
$_GET['page_description'] = 'The page you requested could not be found.';
require_once __DIR__ . '/../includes/header.php';
?>
<main style="padding:3rem; text-align:center;">
  <h1 style="font-size:2.4rem;">404 — Page Not Found</h1>
  <p style="max-width:700px; margin:1rem auto; color:#64748b;">We couldn't find the page you're looking for. Check the URL or return to the homepage.</p>
  <p><a href="/ReadAndRise/public/index.php" style="color:#2563eb;">Return to homepage</a></p>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
