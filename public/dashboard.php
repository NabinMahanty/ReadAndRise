<?php
require_once "../includes/auth.php";
require_once "../includes/header.php";

require_login();
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>

<p>Yahan se aap:</p>
<ul>
  <li><a href="add_note.php">Apne notes upload kar sakte hain</a></li>
  <li><a href="add_blog.php">Apni struggle /journey story share karein</a></li>
</ul>

<?php require_once "../includes/footer.php"; ?>