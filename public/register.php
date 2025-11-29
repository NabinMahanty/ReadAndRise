<?php
// public/register.php
require_once "../includes/db.php";
session_start();

$errors = [];
$name = "";
$email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name  = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm  = $_POST['confirm_password'] ?? '';

  if ($name === '') {
    $errors[] = "Name is required.";
  }
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Valid email is required.";
  }
  if (strlen($password) < 6) {
    $errors[] = "Password must be at least 6 characters.";
  }
  if ($password !== $confirm) {
    $errors[] = "Passwords do not match.";
  }

  if (empty($errors)) {
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
      $errors[] = "Email already registered.";
    } else {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
      $stmt->execute([$name, $email, $hash]);

      header("Location: login.php?registered=1");
      exit;
    }
  }
}

require_once "../includes/header.php";
?>

<h2>Create your account</h2>

<?php if (!empty($errors)): ?>
  <div class="alert-error">
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?php echo htmlspecialchars($e); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post">
  <label>
    Name:<br>
    <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
  </label><br><br>

  <label>
    Email:<br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
  </label><br><br>

  <label>
    Password:<br>
    <input type="password" name="password">
  </label><br><br>

  <label>
    Confirm Password:<br>
    <input type="password" name="confirm_password">
  </label><br><br>

  <button type="submit">Register</button>
</form>

<?php require_once "../includes/footer.php"; ?>