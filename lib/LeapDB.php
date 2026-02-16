<?php

declare(strict_types=1);

namespace FaltLeap;

use PDO;
use PDOException;

class LeapDB
{
    private $host;
    private $username;
    private $password;
    public $database;
    public $schema;

    public $connection;

    // Singleton instance to reuse connections
    private static $instance = null;

    public function __construct()
    {
        // Return existing instance if available (singleton pattern)
        if (self::$instance !== null) {
            $this->host = self::$instance->host;
            $this->username = self::$instance->username;
            $this->password = self::$instance->password;
            $this->database = self::$instance->database;
            $this->schema = self::$instance->schema;
            $this->connection = self::$instance->connection;
            return;
        }

        // Load .env file if not already loaded
        if (!LeapEnv::isLoaded()) {
            if (file_exists('.env')) {
                LeapEnv::load('.env');
            } elseif (file_exists('../.env')) {
                LeapEnv::load('../.env');
            }
        }

        // Get database config from environment variables or fallback to $dbconfig if set
        if (LeapEnv::isLoaded()) {
            $this->host = LeapEnv::get('DB_HOST');
            $this->username = LeapEnv::get('DB_USERNAME');
            $this->password = LeapEnv::get('DB_PASSWORD');
            $this->database = LeapEnv::get('DB_DATABASE');
            $this->schema = LeapEnv::get('DB_SCHEMA');
        } elseif (isset($dbconfig)) {
            // Fallback for backward compatibility
            $this->host = $dbconfig['dbhost'];
            $this->username = $dbconfig['dbusername'];
            $this->password = $dbconfig['dbpassword'];
            $this->database = $dbconfig['dbdatabase'];
            $this->schema = $dbconfig['dbschema'];
        }

        $this->connect();

        // Store this instance as singleton
        self::$instance = $this;
    }


    private function connect()
    {
        //postgres!
        try {
            $connectionstring = 'pgsql:host=' . $this->host . ';dbname=' . $this->database;
            $this->connection = new PDO($connectionstring, $this->username, $this->password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            if ($this->connection) {
                $this->connection->exec('SET search_path TO ' . $this->schema);
                return true;
            }
        } catch (PDOException $e) {
            die($e->getMessage());
        } finally {
        }
        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function query($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function queryObjects($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }

    public function execute($sql, $params = [])
    {
        $stmt = $this->connection->prepare($sql);
        return $stmt->execute($params);
    }


    public function close()
    {
        $this->connection = null;
        self::$instance = null;
    }
}
