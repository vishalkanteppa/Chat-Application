<?php

// define the Database class which manages the connection to the SQLite database
// ensures that only one instance of the connection exists and provides methods to access that connection
class Database {
    private static $instance = null;
    private $connection;
    private $testConnection;

    private function __construct() {
        $this->connection = new PDO('sqlite:./../data/database.sqlite');
        // make the connection report errors as exceptions
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public static function setInstance($mockInstance) {
        self::$instance = $mockInstance;
    }

    public function getConnection() {
        return $this->connection;
    }
}
