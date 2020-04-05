<?php

require_once 'StatementGroup.php';
require_once __DIR__ . '/../queries/locationQueries.php';

class LocationStatementGroup extends StatementGroup {

  public function __construct(DatabaseConnection $conn) {
    parent::__construct($conn, LOCATION_QUERIES);
  }


  public function checkForCity($city, $state) {
    $exists = 0;
    if (is_null($city) || is_null($state)) return;

    $stmt = $this->statements['checkForCity'];
    $stmt->bind_param("ss", $city, $state);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows;

    if (!$exists) {
      $this->insertCity($city, $state);
    }
  }


  public function insertCity($city, $state) {
    if (is_null($city) || is_null($state)) return;

    $stmt = $this->statements['insertCity'];
    $stmt->bind_param("ss", $city, $state);
    $stmt->execute();
  }


  public function checkForLocation($locationName, $city, $state) {
    $exists = 0;
    if (is_null($locationName) || is_null($city) || is_null($state)) return;
    
    $stmt = $this->statements['checkForLocation'];
    $stmt->bind_param("sss", $locationName, $city, $state);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows;
    
    if (!$exists) {
      $this->insertLocation($locationName, $city, $state);
    }
  }
  

  public function insertLocation($locationName, $city, $state) {
    if (is_null($city) || is_null($state)) return;

    $stmt = $this->statements['insertLocation'];
    $stmt->bind_param("sss", $locationName, $city, $state);
    $stmt->execute();
  }

}