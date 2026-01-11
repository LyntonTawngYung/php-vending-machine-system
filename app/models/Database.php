<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        // Use DATABASE_URL from Railway
        $databaseUrl = getenv('DATABASE_URL');
        if (!$databaseUrl) {
            die("Error: DATABASE_URL environment variable not set.");
        }

        $parsedUrl = parse_url($databaseUrl);
        $host = $parsedUrl['host'];
        $port = $parsedUrl['port'] ?? 3306;
        $db   = ltrim($parsedUrl['path'], '/');
        $user = $parsedUrl['user'];
        $pass = $parsedUrl['pass'];
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo; // return PDO directly
    }
}
?>
