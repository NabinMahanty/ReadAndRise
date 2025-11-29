<?php
// public/login.php
require_once "../includes/db.php";
session_start();

$errors = [];
$email = "";

// Handle POST before sending any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Enter a valid email.";
  }
  if ($password === '') {
    $errors[] = "Password is required.";
  }

  if (empty($errors)) {
    $stmt = $pdo->prepare("SELECT id, name, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      // login success — set session and redirect
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['user_name'] = $user['name'];
      $_SESSION['user_role'] = $user['role'];

      header("Location: dashboard.php");
      exit;
    } else {
      $errors[] = "Invalid email or password.";
    }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - ReadAndRise</title>
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
          <h2>Welcome back</h2>
          <p>Don't have an account? <a href="register.php">Sign up</a></p>
        </div>

        <?php if (isset($_GET['registered'])): ?>
          <div class="auth-success">
            ✓ Registration successful. Please sign in.
          </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
          <div class="auth-error">
            <?php foreach ($errors as $e): ?>
              <p>✕ <?php echo htmlspecialchars($e); ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <form method="post" class="auth-form">
          <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="name@example.com" required>
          </div>

          <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Enter your password" required>
          </div>

          <button type="submit" class="auth-button">Sign in</button>
        </form>

        <div class="auth-footer">
          <a href="index.php">← Back to home</a>
        </div>
      </div>
    </div>
  </div>
</body>

</html>