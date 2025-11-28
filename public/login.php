<?php
require_once "../includes/db.php";
require_once "../includes/header.php";

$errors = [];
$email = "";

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
      // login success
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

<h2>Login</h2>

<?php if (isset($_GET['registered'])): ?>
  <p style="color:green;">Registration successful. Please login.</p>
<?php endif; ?>

<?php if (!empty($errors)): ?>
  <div class="alert-error" style="color:red;">
    <ul>
      <?php foreach ($errors as $e): ?>
        <li><?php echo htmlspecialchars($e); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<form method="post">
  <label>
    Email:<br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>">
  </label><br><br>

  <label>
    Password:<br>
    <input type="password" name="password">
  </label><br><br>

  <button type="submit">Login</button>
</form>

<?php require_once "../includes/footer.php"; ?>