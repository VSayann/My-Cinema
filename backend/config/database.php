<?php
require_once __DIR__ . '/DotEnv.php';
try {
    $dotenv = new DotEnv(__DIR__ . '/../.env');
    $dotenv->load();
} catch (Exception $e) {
}

define('DB_HOST', DotEnv::required('DB_HOST'));
define('DB_NAME', DotEnv::required('DB_NAME'));
define('DB_USER', DotEnv::required('DB_USER'));
define('DB_PASS', DotEnv::get('DB_PASS', ''));

define('APP_ENV', DotEnv::get('APP_ENV', 'production'));
define('APP_DEBUG', DotEnv::get('APP_DEBUG', false));

class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];
            
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
        } catch (PDOException $e) {
            if (APP_DEBUG) {
                $message .= " : " . $e->getMessage();
            }
            
            die($message);
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    private function __clone() {}
}
