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
      $stmt = $pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
      $stmt->execute([$name, $email, $hash]);

      header("Location: login.php?registered=1");
      exit;
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - ReadAndRise</title>
  <link rel="stylesheet" href="/readandrise/assets/style.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body class="auth-body">
  <div class="auth-container">
    <div class="auth-right">
      <div class="auth-form-container">
        <div class="auth-branding">
          <img src="/readandrise/assets/logo.png" alt="ReadAndRise Logo" class="auth-logo">
        </div>

        <div class="auth-header">
          <h2>Create your account</h2>
          <p>Already have an account? <a href="login.php">Sign in</a></p>
        </div>

        <?php if (!empty($errors)): ?>
          <div class="auth-error">
            <?php foreach ($errors as $e): ?>
              <p>✕ <?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form method="post" class="auth-form">
          <div class="form-group">
            <label>Full name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" placeholder="John Doe" required>
          </div>

          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="name@example.com" required>
          </div>

          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Create a password (min. 6 characters)" required>
          </div>

          <div class="form-group">
            <label>Confirm password</label>
            <input type="password" name="confirm_password" placeholder="Confirm your password" required>
          </div>

          <button type="submit" class="auth-button">Create account</button>
        </form>

        <div class="auth-footer">
          <a href="index.php">← Back to home</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>