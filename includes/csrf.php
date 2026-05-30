<?php
// Simple CSRF helper
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

function csrf_token() {
  if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
  }
  return $_SESSION['csrf_token'];
}

function csrf_field() {
  $token = htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8');
  return "<input type=\"hidden\" name=\"_csrf\" value=\"$token\">";
}

function csrf_check() {
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['_csrf'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', (string)$token)) {
      http_response_code(400);
      echo "Invalid CSRF token.";
      exit;
    }
  }
}
