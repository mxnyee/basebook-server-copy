<?php

class Controller {
  protected $conn;
  protected $statements;

  public function __construct(DatabaseConnection $conn, $queries) {
    $this->conn = $conn;
    $this->statements = [];

    try {
      // Prepare queries for this controller class
      foreach ($queries as $queryName => $queryString) {
        $stmt = $this->conn->prepare($queryString);
        $this->statements[$queryName] = $stmt;
      }
    } catch (mysqli_sql_exception $e) {
      error_log($e->getMessage());
      throw $e;
    }
  }

}