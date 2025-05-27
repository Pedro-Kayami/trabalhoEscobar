<?php
require_once 'config.php';

class Database {
    private $connection;
    private static $instance = null;
    
    // Private constructor - singleton pattern
    private function __construct() {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        
        if ($this->connection->connect_error) {
            die('Database Connection Error: ' . $this->connection->connect_error);
        }
        
        $this->connection->set_charset("utf8");
    }
    
    // Get database instance
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    // Get database connection
    public function getConnection() {
        return $this->connection;
    }
    
    // Execute a query
    public function query($sql) {
        return $this->connection->query($sql);
    }
    
    // Execute a prepared statement
    public function prepare($sql) {
        return $this->connection->prepare($sql);
    }
    
    // Get last inserted ID
    public function lastInsertId() {
        return $this->connection->insert_id;
    }
    
    // Get error
    public function error() {
        return $this->connection->error;
    }
    
    // Escape string
    public function escapeString($string) {
        return $this->connection->real_escape_string($string);
    }
}