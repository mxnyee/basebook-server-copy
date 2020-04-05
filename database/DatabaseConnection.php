<?php

class DatabaseConnection {
  private $conn;
  
  public function __construct() {
    $dbhost = getenv('DB_HOST');
    $dbuser = getenv('DB_USER');
    $dbpass = getenv('DB_PASS');
    $db_name = getenv('DB_NAME');
    
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $this->conn = new mysqli($dbhost, $dbuser, $dbpass, $db_name);
    if ($this->conn->connect_error) {
        exit('Failed to connect to database.');
    }
  }

  public function prepare($query) {
    return $this->conn->prepare($query);
  }

  public function beginTransaction() {
    $this->conn->autocommit(false);
  }

  public function endTransaction() {
    $this->conn->commit();
    $this->conn->autocommit(true);
  }

  public function rollbackTransaction() {
    $this->conn->rollback();
    $this->conn->autocommit(true);
  }

  public function getError() {
    return $this->conn->error;
  }

  public function close() {
    $this->conn->close();
  }

}
