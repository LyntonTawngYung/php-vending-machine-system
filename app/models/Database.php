<?php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        if (getenv('DATABASE_URL')) {
            $databaseUrl = getenv('DATABASE_URL');
            $parsedUrl = parse_url($databaseUrl);
            $host = $parsedUrl['host'];
            $port = $parsedUrl['port'] ?? 3306;
            $db = ltrim($parsedUrl['path'], '/');
            $user = $parsedUrl['user'];
            $pass = $parsedUrl['pass'];
            $charset = 'utf8mb4';
            $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
        } else {
            $config = require __DIR__ . '/../../config/database.php';
            $host = $config['host'];
            $db = $config['db'];
            $user = $config['user'];
            $pass = $config['pass'];
            $charset = $config['charset'];
            $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        }

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>