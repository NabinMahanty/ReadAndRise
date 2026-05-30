# Problems found (grouped)

- **Total matches:** 96

This file groups the grep matches by file and shows the matching lines (validation messages, error handling, and other problem markers). Click a filename to open the source.

---

## [/.gitignore](.gitignore)
- line 22: error_log
- line 23: php_errors.log

## [/admin/add_current.php](admin/add_current.php)
- line 9: $errors = [];
- line 20: if ($title === '') $errors[] = "Title is required.";
- line 21: if ($content === '') $errors[] = "Content is required.";
- line 27: if ($file['error'] === 0) {
- line 32: $errors[] = "Only JPG/PNG/WEBP images are allowed.";
- line 34: $errors[] = "Image must be under 2MB.";
- line 44: $errors[] = "Failed to upload image.";
- line 50: $errors[] = "Error uploading image.";
- line 54: if (empty($errors)) {
- line 73: <?php if (!empty($errors)): ?>
- line 74: <div class="alert-error">
- line 75: <ul><?php foreach ($errors as $e): ?><li><?php echo htmlspecialchars($e); ?></li><?php endforeach; ?></ul>

**Status:** Hardened image upload validation (MIME check via `finfo`, size limit, safe filenames). Stores filename-only in DB.

## [/includes/db.php](includes/db.php)
- line 11: (fixed) replaced `die()` with logging and a user-friendly 500 error page include.

**Status:** Fixed — now logs the error and displays `/public/500.php`.

## [/public/edit_blog.php](public/edit_blog.php)
- line 15: $_SESSION['error'] = "Success story not found or you don't have permission to edit it.";
- line 27: $error = "All fields are required.";
- line 50: $error = "Failed to update success story.";
- line 64-66: error display block

## [/public/add_question.php](public/add_question.php)
- line 8: $errors = [];
- line 19: if ($title === '') $errors[] = "Title required.";
- line 20: if ($year === '' || !preg_match('/^\d{4}$/', $year)) $errors[] = "Valid year required.";
- line 21: if ($drive_link === '' || !filter_var($drive_link, FILTER_VALIDATE_URL)) $errors[] = "Valid Google Drive folder link required.";
- line 23: if (empty($errors)) {
- line 38-39: error display block

## [/public/notes.php](public/notes.php)
- line 176: ✓ Check for spelling errors

## [/public/delete_blog.php](public/delete_blog.php)
- line 15: $_SESSION['error'] = "Success story not found or you don't have permission to delete it.";
- line 26: $_SESSION['error'] = "Failed to delete success story.";

## [/public/add_blog.php](public/add_blog.php)
- line 8: $errors = [];
- line 27: $errors[] = "Title is required.";
- line 30: $errors[] = "Category is required.";
- line 33: $errors[] = "Content cannot be empty.";
- line 36: if (empty($errors)) {
- line 73-76: error display block

## [/public/login.php](public/login.php)
- line 6: $errors = [];
- line 15: $errors[] = "Enter a valid email.";
- line 18: $errors[] = "Password is required.";
- line 35: $errors[] = "Invalid email or password.";
- line 70-72: auth error display

## [/public/add_current.php](public/add_current.php)
- line 13: $error = "Title and content are required.";
- line 18: if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
- line 25: $error = "Only JPG, PNG, and WebP images are allowed.";
- line 27: $error = "Image size must not exceed 5MB.";
- line 40: $error = "Failed to upload image.";
- line 45: if (!isset($error)) {
- line 56: $error = "Failed to submit current affairs post.";
- line 71-73: error display block

**Status:** Hardened image upload validation (MIME check via `finfo`, size limit, safe filenames). Stores filename-only in DB.

## [/public/edit_note.php](public/edit_note.php)
- line 15: $_SESSION['error'] = "Note not found or you don't have permission to edit it.";
- line 28: $error = "Title, category, and content are required.";
- line 42: if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
- line 49: $error = "Only PDF files are allowed.";
- line 51: $error = "File size must not exceed 10MB.";
- line 68: $error = "Failed to upload file.";
- line 85: $error = "Failed to update note.";
- line 100-102: error display block

**Status:** Hardened PDF upload validation (MIME check via `finfo`, size limit, safe filenames). Old files deleted safely; DB stores filename-only.

## [/public/current_affairs.php](public/current_affairs.php)
- line 133: ✓ Check for spelling errors

## [/public/register.php](public/register.php)
- line 6: $errors = [];
- line 17: $errors[] = "Name is required.";
- line 20: $errors[] = "Valid email is required.";
- line 23: $errors[] = "Password must be at least 6 characters.";
- line 26: $errors[] = "Passwords do not match.";
- line 33: $errors[] = "Email already registered.";
- line 69-71: auth error display

## [/public/delete_note.php](public/delete_note.php)
- line 15: $_SESSION['error'] = "Note not found or you don't have permission to delete it.";
- line 31: $_SESSION['error'] = "Failed to delete study material.";

## [/public/add_note.php](public/add_note.php)
- line 8: $errors = [];
- line 29: $errors[] = "Title is required.";
- line 32: $errors[] = "Category is required.";
- line 35: $errors[] = "Content cannot be empty.";
- line 44: if ($file['error'] === 0) {
- line 50: $errors[] = "Only PDF files are allowed.";
- line 52: $errors[] = "PDF file size must not exceed 10MB. Your file is " . round($fileSize / 1024 / 1024, 2) . "MB.";
- line 61: $errors[] = "Failed to upload PDF.";
- line 67: if (empty($errors)) {
- line 119-122: error display block

**Status:** Hardened PDF upload validation (MIME check via `finfo`, size limit, safe filenames). Stores filename-only in DB.

## [/assets/style.css](assets/style.css)
- line 706: .alert-error,
- line 730: .alert-error {
- line 1951: .auth-error {
- line 1961: .auth-error p {

## [/README.md](README.md)
- lines mentioning: Issues and Database Connection Error sections

## [/.htaccess](.htaccess)
- lines mentioning custom error pages (404 and 500)

## [/public/404.php](public/404.php) and [/public/500.php](public/500.php)
- Added user-friendly error pages to match `.htaccess` and `includes/db.php` handling.

---

If you'd like, I can now:
- triage and tag each issue by severity (validation, upload, DB connection, UI display),
- open focused issues or create fixes for high-priority problems (e.g., DB die() call), or
- add line-linked references for every match.
 
Remaining recommended next steps:
- Review and fix template usages to ensure they consistently prefix `uploads/notes/` and `uploads/current/` when rendering stored filenames (I kept DB entries filename-only to match most templates).
- Triage form validation improvements (server-side and client-side) for all forms.
- Add CSRF tokens to POST forms.
- Review permission checks on delete/edit endpoints.

Tell me which next step you prefer and I'll continue (I can implement CSRF protection next, or standardize template rendering). 

Update: I implemented CSRF protection across the main POST forms and delete endpoints.
- Added `includes/csrf.php` with `csrf_token()`, `csrf_field()`, and `csrf_check()`.
- Applied CSRF checks and inserted hidden CSRF fields in: `public/add_note.php`, `public/edit_note.php`, `public/add_current.php`, `admin/add_current.php`, `public/add_blog.php`, `public/edit_blog.php`, `public/add_question.php`, `public/register.php`, `public/login.php`, `public/delete_note.php`, `public/delete_blog.php`.

This helps prevent cross-site request forgery on forms that modify data.

## Database setup attempt

- I added `schema/readandrise.sql` (CREATE DATABASE + CREATE TABLE statements) and `scripts/db_setup.php` (CLI script that attempts to connect to MySQL, create the DB, apply the schema, and ensure uploads directories exist).
- I attempted to run `php scripts/db_setup.php` here. It failed because PHP in this environment lacks the PDO MySQL driver (error: "could not find driver").
- I also checked `mysql` client availability — the client exists, but connecting as local `root` returned "Access denied" (your system likely requires sudo or a password for root).

Manual commands you can run locally to create the database and tables:

1) Using the MySQL client (recommended when you have system access):

```bash
# Run as root (Debian/Ubuntu with sudo):
sudo mysql < schema/readandrise.sql

# Or, if you know the root password:
mysql -u root -p < schema/readandrise.sql
```

2) Using the PHP setup script (requires PHP + pdo_mysql extension):

```bash
# Install PHP PDO MySQL (Debian/Ubuntu):
sudo apt-get update && sudo apt-get install -y php-mysql php-cli
# Then run the script
php scripts/db_setup.php
```

If you run either of the above, the script/file will also create `uploads/notes/` and `uploads/current/` directories (if they don't exist).

If you'd like, I can now (choose one):
- implement CSRF protection across forms, or
- standardize template rendering to consistently prefix `uploads/` paths, or
- attempt to run the SQL here if you provide DB credentials or enable local DB access.

