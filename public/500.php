<?php
// 500 Internal Server Error page
$_GET['page_title'] = '500 — Internal Server Error';
$_GET['page_description'] = 'Something went wrong on our end. We are working to fix it.';
require_once __DIR__ . '/../includes/header.php';
?>
<main style="padding:3rem; text-align:center;">
  <h1 style="font-size:2.4rem;">500 — Internal Server Error</h1>
  <p style="max-width:700px; margin:1rem auto; color:#64748b;">An unexpected error occurred while processing your request. Please try again later. If the problem persists, contact the site administrator.</p>
  <p><a href="/ReadAndRise/public/index.php" style="color:#2563eb;">Return to homepage</a></p>
</main>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>
