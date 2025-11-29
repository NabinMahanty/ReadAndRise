<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/header.php";

require_login();

$errors = [];
$title = "";
$category = "";
$content = "";

function make_slug($string)
{
  $slug = strtolower(trim($string));
  $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
  $slug = trim($slug, '-');
  return $slug;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title    = trim($_POST['title'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $content  = trim($_POST['content'] ?? '');

  if ($title === '') {
    $errors[] = "Title is required.";
  }
  if ($category === '') {
    $errors[] = "Category is required.";
  }
  if ($content === '') {
    $errors[] = "Content cannot be empty.";
  }

  if (empty($errors)) {
    $slug = make_slug($title);

    // unique slug
    $base_slug = $slug;
    $i = 1;
    while (true) {
      $stmt = $pdo->prepare("SELECT id FROM blogs WHERE slug = ?");
      $stmt->execute([$slug]);
      if ($stmt->fetch()) {
        $slug = $base_slug . '-' . $i;
        $i++;
      } else {
        break;
      }
    }

    $stmt = $pdo->prepare("
            INSERT INTO blogs (user_id, title, slug, category, content, status)
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
    $stmt->execute([
      $_SESSION['user_id'],
      $title,
      $slug,
      $category,
      $content
    ]);

    $success_msg = "Your story is submitted! It will be visible after admin approval.";
    $title = $category = $content = "";
  }
}
?>

<h2>Share Your Struggle / Journey</h2>

<?php if (!empty($errors)): ?>
  <div class="alert-error">
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?php echo htmlspecialchars($e); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if (!empty($success_msg)): ?>
  <div class="alert-success">
    <?php echo htmlspecialchars($success_msg); ?>
  </div>
<?php endif; ?>

<form method="post">
  <label>
    Title:<br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
  </label><br>

  <label>
    Category (e.g. Struggle, Journey, Motivation, Failure to Success):<br>
    <input type="text" name="category" value="<?php echo htmlspecialchars($category); ?>">
  </label><br>

  <label>
    Your Story:<br>
    <textarea name="content" rows="10"><?php echo htmlspecialchars($content); ?></textarea>
  </label><br>

  <button type="submit">Submit Story</button>
</form>

<?php require_once "../includes/footer.php"; ?>