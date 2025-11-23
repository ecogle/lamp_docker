<?php
// Template for creating database tables using PDO
// Usage: Copy this file, edit the table definition, and run in your container

$host = 'db';
$db   = 'exampledb';
$user = 'root';
$pass = 'exampleRootPass';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Example table creation SQL
    $sql = "CREATE TABLE IF NOT EXISTS example_table (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

    $pdo->exec($sql);
    echo "Table created successfully." . PHP_EOL;
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . PHP_EOL;
    exit(1);
}
