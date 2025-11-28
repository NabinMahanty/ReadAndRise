<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>ReadAndRise.in</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/readandrise/assets/style.css">
</head>

<body>
  <header>
    <h1>ReadAndRise.in</h1>
    <nav>
      <a href="/readandrise/public/index.php">Home</a>
      <a href="/readandrise/public/notes.php">Notes</a>
      <a href="/readandrise/public/blogs.php">Struggle Stories</a>

      <?php if (!empty($_SESSION['user_id'])): ?>

        <?php if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
          <a href="/readandrise/admin/index.php">Admin Panel</a>
        <?php endif; ?>

        <a href="/readandrise/public/dashboard.php">Dashboard</a>
        <a href="/readandrise/public/logout.php">Logout</a>
      <?php else: ?>
        <a href="/readandrise/public/login.php">Login</a>
        <a href="/readandrise/public/register.php">Register</a>
      <?php endif; ?>
    </nav>
  </header>
  <main>