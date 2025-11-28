<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function require_login()
{
  if (empty($_SESSION['user_id'])) {
    header("Location: /readandrise/public/login.php");
    exit;
  }
}

function is_admin()
{
  return !empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function require_admin()
{
  require_login();
  if (!is_admin()) {
    http_response_code(403);
    echo "Access denied. Admins only.";
    exit;
  }
}
