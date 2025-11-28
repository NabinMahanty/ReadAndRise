<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/header.php";

require_login(); // ensure user logged in

$errors = [];
$title = "";
$category = "";
$tags = "";
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
  $tags     = trim($_POST['tags'] ?? '');
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

    // unique slug banana
    $base_slug = $slug;
    $i = 1;
    while (true) {
      $stmt = $pdo->prepare("SELECT id FROM notes WHERE slug = ?");
      $stmt->execute([$slug]);
      if ($stmt->fetch()) {
        $slug = $base_slug . '-' . $i;
        $i++;
      } else {
        break;
      }
    }

    $stmt = $pdo->prepare("
            INSERT INTO notes (user_id, title, slug, category, tags, content)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
    $stmt->execute([
      $_SESSION['user_id'],
      $title,
      $slug,
      $category,
      $tags,
      $content
    ]);

    // user ko batana ki admin approval ke baad show hoga
    $success_msg = "Note submitted! It will be visible after admin approval.";
    // form reset
    $title = $category = $tags = $content = "";
  }
}
?>

<h2>Add New Note</h2>

<?php if (!empty($errors)): ?>
  <div style="color:red;">
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?php echo htmlspecialchars($e); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php if (!empty($success_msg)): ?>
  <p style="color:green;"><?php echo htmlspecialchars($success_msg); ?></p>
<?php endif; ?>

<form method="post">
  <label>
    Title:<br>
    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" style="width:100%;">
  </label><br><br>

  <label>
    Category (e.g. CDS, AFCAT, MCA, Programming):<br>
    <input type="text" name="category" value="<?php echo htmlspecialchars($category); ?>" style="width:100%;">
  </label><br><br>

  <label>
    Tags (comma separated):<br>
    <input type="text" name="tags" value="<?php echo htmlspecialchars($tags); ?>" style="width:100%;">
  </label><br><br>

  <label>
    Content (your notes):<br>
    <textarea name="content" rows="10" style="width:100%;"><?php echo htmlspecialchars($content); ?></textarea>
  </label><br><br>

  <button type="submit">Submit Note</button>
</form>

<?php require_once "../includes/footer.php"; ?>