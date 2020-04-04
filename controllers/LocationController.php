<?php
use Psr\Container\ContainerInterface;

require_once '../database/locationPreparedQueries.php';

class LocationController extends Controller {

  public function __construct(ContainerInterface $container) {
    parent::__construct($container, LOCATION_PREPARED_QUERIES);
  }


  function checkForCity($city, $state) {
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
      insertCity($city, $state);
    }
  }


  function insertCity($city, $state) {
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