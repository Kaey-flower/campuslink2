<?php

// Database Configuration
define("DB_HOST", "localhost");
define("DB_USERNAME", "faith");
define("DB_PASSWORD", "12345");
define("DB_NAME", "campuslink2");

// PDO Connection
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $pdo = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    // In a production environment, you would log this error and show a generic message
    die("Database connection failed: " . $e->getMessage());
}
