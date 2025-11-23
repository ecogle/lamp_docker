<?php
// Load configuration from external file
$config = require_once __DIR__ . '/config.php';

// Database configuration constants
define('DB_HOST', $config['db']['host']);
define('DB_NAME', $config['db']['name']);
define('DB_USER', $config['db']['user']);
define('DB_PASS', $config['db']['pass']);
define('DB_CHARSET', $config['db']['charset']);

/**
 * Get database connection using PDO
 * @return PDO
 */
function getConnection() {
    static $pdo = null;
    
    if ($pdo === null) {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die('Database connection failed: ' . $e->getMessage());
        }
    }
    
    return $pdo;
}

/**
 * Execute a query and return results
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array Results
 */
function query($sql, $params = []) {
    $pdo = getConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

/**
 * Execute a query and return single row
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array|false Single row or false
 */
function queryOne($sql, $params = []) {
    $pdo = getConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch();
}

/**
 * Execute a query (INSERT, UPDATE, DELETE)
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return int Number of affected rows or last insert ID
 */
function execute($sql, $params = []) {
    $pdo = getConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $pdo->lastInsertId() ?: $stmt->rowCount();
}
