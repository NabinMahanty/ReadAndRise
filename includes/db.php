<?php
// includes/db.php

$host = "localhost";
$dbname = "readandrise";
$user = "readandrise";
$pass = "admin";

$use_sqlite = false;
$db_file = __DIR__ . '/../database.sqlite';

if (!extension_loaded('pdo_mysql')) {
    $use_sqlite = true;
}

try {
    if ($use_sqlite) {
        $pdo = new PDO("sqlite:" . $db_file);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->exec("PRAGMA foreign_keys = ON;");
        
        // Auto-initialize SQLite database if tables don't exist
        $tableCheck = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
        if (!$tableCheck->fetch()) {
            // Create tables
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `users` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `name` VARCHAR(191) NOT NULL,
                  `email` VARCHAR(191) NOT NULL UNIQUE,
                  `password` VARCHAR(255) NOT NULL,
                  `role` TEXT NOT NULL DEFAULT 'user',
                  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                );
            ");
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `notes` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `user_id` INTEGER NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `slug` VARCHAR(255) NOT NULL UNIQUE,
                  `category` VARCHAR(100) DEFAULT NULL,
                  `tags` TEXT DEFAULT NULL,
                  `content` TEXT,
                  `attachment_path` VARCHAR(255) DEFAULT NULL,
                  `status` TEXT NOT NULL DEFAULT 'pending',
                  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                );
            ");
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `blogs` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `user_id` INTEGER NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `slug` VARCHAR(255) NOT NULL UNIQUE,
                  `category` VARCHAR(100) DEFAULT NULL,
                  `content` TEXT,
                  `status` TEXT NOT NULL DEFAULT 'pending',
                  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                );
            ");
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `current_affairs` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `user_id` INTEGER NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `summary` TEXT DEFAULT NULL,
                  `content` TEXT,
                  `image_path` VARCHAR(255) DEFAULT NULL,
                  `status` TEXT NOT NULL DEFAULT 'pending',
                  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                );
            ");
            $pdo->exec("
                CREATE TABLE IF NOT EXISTS `questions` (
                  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                  `user_id` INTEGER NOT NULL,
                  `title` VARCHAR(255) NOT NULL,
                  `year` INTEGER DEFAULT NULL,
                  `subject` VARCHAR(191) DEFAULT NULL,
                  `qtype` VARCHAR(100) DEFAULT NULL,
                  `description` TEXT DEFAULT NULL,
                  `drive_folder_link` VARCHAR(255) DEFAULT NULL,
                  `status` TEXT NOT NULL DEFAULT 'pending',
                  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                );
            ");
            
            // Insert admin user (password: admin123)
            $pdo->exec("
                INSERT INTO `users` (`name`, `email`, `password`, `role`)
                VALUES ('Admin', 'admin@readandrise.in', '\$2y\$10\$II5J23yZuVYV7sLAdVLxz.vZKdp/c/0iBAKPP9KaT6s78VjAJ8O4m', 'admin');
            ");
        }
    } else {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    // Ensure upload directories exist
    $dirs = [__DIR__ . '/../uploads/notes', __DIR__ . '/../uploads/current'];
    foreach ($dirs as $d) {
        if (!is_dir($d)) {
            mkdir($d, 0755, true);
        }
    }

} catch (PDOException $e) {
    // If MySQL connection failed, and we haven't tried SQLite yet, we can try SQLite as a secondary fallback
    if (!$use_sqlite) {
        try {
            $pdo = new PDO("sqlite:" . $db_file);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("PRAGMA foreign_keys = ON;");
            
            // Auto-initialize SQLite database if tables don't exist
            $tableCheck = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
            if (!$tableCheck->fetch()) {
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS `users` (
                      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                      `name` VARCHAR(191) NOT NULL,
                      `email` VARCHAR(191) NOT NULL UNIQUE,
                      `password` VARCHAR(255) NOT NULL,
                      `role` TEXT NOT NULL DEFAULT 'user',
                      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                    );
                ");
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS `notes` (
                      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                      `user_id` INTEGER NOT NULL,
                      `title` VARCHAR(255) NOT NULL,
                      `slug` VARCHAR(255) NOT NULL UNIQUE,
                      `category` VARCHAR(100) DEFAULT NULL,
                      `tags` TEXT DEFAULT NULL,
                      `content` TEXT,
                      `attachment_path` VARCHAR(255) DEFAULT NULL,
                      `status` TEXT NOT NULL DEFAULT 'pending',
                      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                    );
                ");
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS `blogs` (
                      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                      `user_id` INTEGER NOT NULL,
                      `title` VARCHAR(255) NOT NULL,
                      `slug` VARCHAR(255) NOT NULL UNIQUE,
                      `category` VARCHAR(100) DEFAULT NULL,
                      `content` TEXT,
                      `status` TEXT NOT NULL DEFAULT 'pending',
                      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                    );
                ");
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS `current_affairs` (
                      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                      `user_id` INTEGER NOT NULL,
                      `title` VARCHAR(255) NOT NULL,
                      `summary` TEXT DEFAULT NULL,
                      `content` TEXT,
                      `image_path` VARCHAR(255) DEFAULT NULL,
                      `status` TEXT NOT NULL DEFAULT 'pending',
                      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                    );
                ");
                $pdo->exec("
                    CREATE TABLE IF NOT EXISTS `questions` (
                      `id` INTEGER PRIMARY KEY AUTOINCREMENT,
                      `user_id` INTEGER NOT NULL,
                      `title` VARCHAR(255) NOT NULL,
                      `year` INTEGER DEFAULT NULL,
                      `subject` VARCHAR(191) DEFAULT NULL,
                      `qtype` VARCHAR(100) DEFAULT NULL,
                      `description` TEXT DEFAULT NULL,
                      `drive_folder_link` VARCHAR(255) DEFAULT NULL,
                      `status` TEXT NOT NULL DEFAULT 'pending',
                      `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
                    );
                ");
                $pdo->exec("
                    INSERT INTO `users` (`name`, `email`, `password`, `role`)
                    VALUES ('Admin', 'admin@readandrise.in', '\$2y\$10\$II5J23yZuVYV7sLAdVLxz.vZKdp/c/0iBAKPP9KaT6s78VjAJ8O4m', 'admin');
                ");
            }
            
            // Ensure upload directories exist
            $dirs = [__DIR__ . '/../uploads/notes', __DIR__ . '/../uploads/current'];
            foreach ($dirs as $d) {
                if (!is_dir($d)) {
                    mkdir($d, 0755, true);
                }
            }
            return; // Success, SQLite database loaded
        } catch (PDOException $ex) {
            // Log original MySQL error and fallback error
            error_log("[ReadAndRise] Database connection failed: " . $e->getMessage() . " and SQLite fallback failed: " . $ex->getMessage());
        }
    } else {
        error_log("[ReadAndRise] Database connection failed: " . $e->getMessage());
    }

    http_response_code(500);
    if (file_exists(__DIR__ . '/../public/500.php')) {
        include __DIR__ . '/../public/500.php';
    } else {
        echo "Internal Server Error. Please try again later.";
    }
    exit;
}
