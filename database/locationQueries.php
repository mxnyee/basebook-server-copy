<?php

// Check if a location exists. If not, insert it.
// Calls checkForCity().
function checkForLocation($conn, $locationName, $city, $state) {
  checkForCity($conn, $city, $state);
  if (is_null($locationName)) {
    return;
  }

  $query = '
    SELECT 1
    FROM location
    WHERE locationName = ? and city = ? and state = ?
  ';
  
  $stmt = $conn->prepare($query);
  $numRows = 0;
  if (
    $stmt &&
    $stmt->bind_param('sss', $locationName, $city, $state) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : 'Bad query.';
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for location: ' . $err);
  }
  
  if ($numRows > 0) {
    return;
  }
  
  $query = '
    INSERT INTO location
    VALUES(?,?,?)
  ';
  
  $stmt = $conn->prepare($query);
  $numRows = 0;
  if (
    $stmt &&
    $stmt->bind_param('sss', $locationName, $city, $state) &&
    $stmt->execute()
    ) {
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : 'Bad query.';
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error inserting location: ' . $err);
  }
}

// Check if a city exists. If not, insert it.
function checkForCity($conn, $city, $state) {
  if (is_null($city)) {
    return;
  }
  
  $query = '
    SELECT 1
    FROM city
    WHERE city = ? and state = ?
  ';
  
  $stmt = $conn->prepare($query);
  $numRows = 0;
  if (
    $stmt &&
    $stmt->bind_param('ss', $city, $state) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : 'Bad query.';
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for city: ' . $err);
  }
  
  if ($numRows > 0) {
    return;
  }
  
  $query = '
    INSERT INTO city
    VALUES(?,?)
  ';
  
  $stmt = $conn->prepare($query);
  $numRows = 0;
  if (
    $stmt &&
    $stmt->bind_param('ss', $city, $state) &&
    $stmt->execute()
    ) {
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : 'Bad query.';
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error inserting city: ' . $err);
  }
}
