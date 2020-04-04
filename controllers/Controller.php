<?php
use Psr\Container\ContainerInterface;

class Controller {
  protected $conn;
  protected $statements;

  public function __construct(ContainerInterface $container, $queries) {
    $this->conn = $container->get('conn');
    $this->statements = [];

    try {
      // Prepare queries for this controller class
      foreach ($queries as $queryName => $queryString) {
        $stmt = $this->conn->prepare($queryString);
        $this->statements[$queryName] = $stmt;
      }
    } catch (mysqli_sql_exception $e) {
      error_log($e->getMessage());
      // TODO: maybe don't throw this
      throw $e;
    }
  }

}