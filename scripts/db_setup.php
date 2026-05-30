<?php
// scripts/db_setup.php
// Usage: php scripts/db_setup.php

// Credentials - keep in sync with includes/db.php
$host = 'localhost';
$dbName = 'readandrise';
$user = 'root';
$pass = '';

echo "Checking MySQL connection to $host...\n";
try {
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    fwrite(STDERR, "ERROR: Could not connect to MySQL at $host. Message: " . $e->getMessage() . "\n");
    exit(1);
}

// check if database exists
$stmt = $pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
$stmt->execute([$dbName]);
$exists = (bool) $stmt->fetchColumn();

if ($exists) {
    echo "Database '$dbName' already exists.\n";
} else {
    echo "Database '$dbName' not found. Creating...\n";
    try {
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
        echo "Database '$dbName' created.\n";
    } catch (PDOException $e) {
        fwrite(STDERR, "ERROR: Failed to create database. " . $e->getMessage() . "\n");
        exit(1);
    }
}

// Run schema SQL
$schemaFile = __DIR__ . '/../schema/readandrise.sql';
if (!file_exists($schemaFile)) {
    fwrite(STDERR, "ERROR: Schema file not found: $schemaFile\n");
    exit(1);
}

echo "Applying schema from $schemaFile ...\n";
$sql = file_get_contents($schemaFile);

// Execute statements separated by semicolon
$pdo->exec("USE `$dbName`;");
$statements = array_filter(array_map('trim', explode(";", $sql)));
$applied = 0;
foreach ($statements as $stmtSql) {
    if ($stmtSql === '') continue;
    try {
        $pdo->exec($stmtSql);
        $applied++;
    } catch (PDOException $e) {
        // Log and continue
        fwrite(STDERR, "WARNING: Could not execute statement: " . substr($stmtSql,0,120) . "... Message: " . $e->getMessage() . "\n");
    }
}

echo "Schema applied (approx statements executed): $applied\n";

// Ensure uploads directories exist
$dirs = [__DIR__ . '/../uploads/notes', __DIR__ . '/../uploads/current'];
foreach ($dirs as $d) {
    if (!is_dir($d)) {
        mkdir($d, 0755, true);
        echo "Created directory: $d\n";
    } else {
        echo "Directory exists: $d\n";
    }
}

echo "Database setup complete. You can now use the application.\n";
