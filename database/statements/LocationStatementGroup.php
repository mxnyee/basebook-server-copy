<?php

require_once 'StatementGroup.php';
require_once __DIR__ . '/../queries/locationQueries.php';

class LocationStatementGroup extends StatementGroup {

  public function __construct(DatabaseConnection $conn) {
    parent::__construct($conn, LOCATION_QUERIES);
  }


  public function checkForCity($city, $state) {
    $exists = 0;
    if (is_null($city) || is_null($state)) {
      return;
    }
    try {
      $stmt = $this->statements['checkForCity'];
      $stmt->bind_param("ss", $city, $state);
      $stmt->execute();
      $result = $stmt->get_result();
      $exists = $result->num_rows;
    } catch(Exception $e) {
      error_log($e->getMessage());
      throw $e;
    }
    if (!$exists) {
      $this->insertCity($city, $state);
    }
  }


  public function insertCity($city, $state) {
    if (is_null($city) || is_null($state)) return;
    try {
      $stmt = $this->statements['insertCity'];
      $stmt->bind_param("ss", $city, $state);
      $stmt->execute();
    } catch(Exception $e) {
      error_log($e->getMessage());
      throw $e;
    }
  }

}