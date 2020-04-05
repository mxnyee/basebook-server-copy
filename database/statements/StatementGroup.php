<?php

class StatementGroup {
  protected $conn;
  protected $statements;

  public function __construct(DatabaseConnection $conn, $queries) {
    $this->conn = $conn;
    $this->statements = [];

    try {
      // Prepare queries for this class
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