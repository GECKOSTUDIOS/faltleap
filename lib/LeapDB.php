<?php

class LeapDB
{
  private $host;
  private $username;
  private $password;
  public $database;
  public $schema;

  public $connection;

  public function __construct()
  {
    if (file_exists('conf/db.config.php')) {
      include('conf/db.config.php');
    }
    if (file_exists('../conf/db.config.php')) {
      include('../conf/db.config.php');
    }
    $this->host = $dbconfig['dbhost'];
    $this->username = $dbconfig['dbusername'];
    $this->password = $dbconfig['dbpassword'];
    $this->database = $dbconfig['dbdatabase'];
    $this->schema = $dbconfig['dbschema'];
    $this->connect();
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

  public function query($sql)
  {
    $stmt = $this->connection->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_CLASS);
    return $result;
  }

  public function execute($sql)
  {
    $stmt = $this->connection->prepare($sql);
    return $stmt->execute();
  }


  public function close()
  {
    $this->connection->close();
  }
}
