<?php

require_once '../errorHandling/errorHandler.php';
foreach (glob('../database/statements/*.php') as $filename) { require_once $filename; }

class Controller {
  protected $conn;
  protected $validator;

  public function __construct(DatabaseConnection $conn, Validator $validator) {
    $this->conn = $conn;
    $this->validator = $validator;
  }

}