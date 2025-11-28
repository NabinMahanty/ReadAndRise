<?php
$host = "localhost";      // hosting pe kuch aur bhi ho sakta hai
$dbname = "readandrise";  // jo upar banaya
$user = "root";           // localhost pe mostly root
$pass = "";               // XAMPP/WAMP me blank hota hai

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
