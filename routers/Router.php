<?php

foreach (glob('../controllers/*.php') as $filename) { require_once $filename; }
foreach (glob('../errorHandling/*.php') as $filename) { require_once $filename; }

class Router {
  protected $conn;
  protected $validator;

  public function __construct(DatabaseConnection $conn, Validator $validator) {
    $this->conn = $conn;
    $this->validator = $validator;
  }

}