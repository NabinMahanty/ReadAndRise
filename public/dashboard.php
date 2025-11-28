<?php
require_once "../includes/header.php";

if (empty($_SESSION['user_id'])) {
  header("Location: login.php");
  exit;
}
?>

<h2>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>

<p>Yahan se aage tum:
<ul>
  <li>Apne notes upload karoge</li>
  <li>Apne struggle stories likhoge</li>
</ul>
</p>

<?php require_once "../includes/footer.php"; ?>