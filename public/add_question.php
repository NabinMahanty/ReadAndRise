<?php
require_once "../includes/db.php";
require_once "../includes/auth.php";
require_once "../includes/header.php";

require_login();

$errors = [];
$title = $year = $subject = $qtype = $description = $drive_link = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $year  = trim($_POST['year'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $qtype = trim($_POST['qtype'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $drive_link = trim($_POST['drive_link'] ?? '');

    if ($title === '') $errors[] = "Title required.";
    if ($year === '' || !preg_match('/^\d{4}$/', $year)) $errors[] = "Valid year required.";
    if ($drive_link === '' || !filter_var($drive_link, FILTER_VALIDATE_URL)) $errors[] = "Valid Google Drive folder link required.";

    if (empty($errors)) {
        $stmt = $pdo->prepare("
            INSERT INTO questions (user_id, title, year, subject, qtype, description, drive_folder_link)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $title, $year, $subject, $qtype, $description, $drive_link]);

        $success_msg = "Submitted. It will be visible after admin approval.";
        $title = $year = $subject = $qtype = $description = $drive_link = "";
    }
}
?>

<h2>Submit Previous Year Question Folder</h2>

<?php if (!empty($errors)): ?>
  <div class="alert-error"><ul><?php foreach($errors as $e):?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach;?></ul></div>
<?php endif; ?>
<?php if (!empty($success_msg)): ?><div class="alert-success"><?php echo htmlspecialchars($success_msg); ?></div><?php endif;?>

<form method="post" class="card">
  <label>Title:
    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
  </label>

  <label>Year:
    <input type="text" name="year" value="<?php echo htmlspecialchars($year); ?>" placeholder="e.g., 2022">
  </label>

  <label>Subject:
    <input type="text" name="subject" value="<?php echo htmlspecialchars($subject); ?>">
  </label>

  <label>Type (MCQ/Descriptive/...):
    <input type="text" name="qtype" value="<?php echo htmlspecialchars($qtype); ?>">
  </label>

  <label>Google Drive Folder Link:
    <input type="text" name="drive_link" value="<?php echo htmlspecialchars($drive_link); ?>" placeholder="https://drive.google.com/drive/folders/xxxxx">
  </label>

  <label>Description / Notes:
    <textarea name="description"><?php echo htmlspecialchars($description); ?></textarea>
  </label>

  <button type="submit">Submit Folder</button>
</form>

<?php require_once "../includes/footer.php"; ?>
