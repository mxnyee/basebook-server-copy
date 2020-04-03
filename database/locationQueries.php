<?php

// Check if a location exists. If not, insert it.
// Calls checkForCity().
function checkForLocation($conn, $locationName, $city, $state) {
  checkForCity($conn, $city, $state);
  if (is_null($locationName) || empty($locationName)) {
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
    $err = $stmt->error;
    $stmt->close();
    throw new InternalServerErrorException('Error looking for location: ' . $err);
  }
  
  if ($numRows > 0) {
    return;
  }
  
  $query = '
    SELECT 1
    FROM account
    WHERE username = ? and password = ?
  ';
  
  $stmt = $conn->prepare($query);
  $numRows = 0;
  if (
    $stmt &&
    $stmt->bind_param('ss', $username, $password) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    $result->free();
    $stmt->close();
  } else {
    $err = $stmt->error;
    $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }
  
  if ($numRows == 0) {
    throw new BadRequestException("Incorrect login information for $username.");
  } else {
    throw new BadRequestException("User $username not found.");
  }
}

// Check if a city exists. If not, insert it.
function checkForCity($conn, $city, $state) {
  if (is_null($state)) {
    if (!is_null($city)) {
      throw new BadRequestException('Cannot provide a city without a state.');
    }
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
    $err = $stmt->error;
    $stmt->close();
    throw new InternalServerErrorException('Error looking for city: ' . $err);
  }
  
  if ($numRows > 0) {
    return;
  }
  
  $query = '
    INTSERT INTO city
    VALUES(?,?)
  ';
  
  $stmt = $conn->prepare($query);
  $numRows = 0;
  if (
    $stmt &&
    $stmt->bind_param('ss', $username, $password) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    $result->free();
    $stmt->close();
  } else {
    $err = $stmt->error;
    $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }
  
  if ($numRows == 0) {
    throw new BadRequestException("Incorrect login information for $username.");
  } else {
    throw new BadRequestException("User $username not found.");
  }
}
