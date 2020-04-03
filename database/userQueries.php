<?php

function insertUser($conn, $data) {
  [
    'username' => $username,
    'email' => $email,
    'password' => $password,
    'name' => $name,
    'accountType' => $accountType
  ] = $data;
  $city = (array_key_exists('city', $data))? $data['city'] : null;
  $state = (array_key_exists('state', $data))? $data['state'] : null;
  $numCoins = 0;

  checkForCity($conn, $city, $state);

  $query = '
    INSERT INTO account(username, email, password, name, city, state, num_coins, account_type)
    VALUES(?,?,?,?,?,?,?,?)
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('ssssssss', $username, $email, $password, $name, $city, $state, $numCoins, $accountType) &&
    $stmt->execute()
   ) {
    $stmt->close();
   } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new BadRequestException('Error inserting user: ' . $err);
  }

  $data['numCoins'] = $numCoins;
  return $data;
}

function checkForUser($conn, $data) {
  [
    'username' => $username,
    'password' => $password
  ] = $data;
  
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
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }
  
  if ($numRows > 0) {
    return;
  }
  
  $query = '
    SELECT 1
    FROM account
    WHERE username = ?
  ';
  
  $stmt = $conn->prepare($query);
  $numRows = 0;
  if (
    $stmt &&
    $stmt->bind_param('s', $username) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }
  
  if ($numRows == 0) {
    throw new NotFoundException('User ' . $username . ' not found.');
  } else {
    throw new BadRequestException('Incorrect login information for ' . $username . '.');
  }
}

function getUser($conn, $username) {
  $query = '
    SELECT email, name, city, a.state, country, num_coins, account_type
    FROM account a LEFT JOIN country c ON a.state = c.state
    WHERE a.username = ?
  ';
  
  $stmt = $conn->prepare($query);
  $numRows = 0;
  $res = [];
  if (
    $stmt &&
    $stmt->bind_param('s', $username) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    $res = $result->fetch_assoc();
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }
  
  if ($numRows == 0) {
    throw new NotFoundException('User ' . $username . ' not found.');
  }

  return $res;
}

function updateUser($conn, $username, $data, $validFields) {
  $toUpdate = [];
  foreach ($validFields as $field) {
    if (array_key_exists($field, $data)) {
      $toUpdate[$field] = $data[$field];
    }
  }
  $numToUpdate = count($toUpdate);

  $city = (array_key_exists('city', $data))? $data['city'] : null;
  $state = (array_key_exists('state', $data))? $data['state'] : null;
  checkForCity($conn, $city, $state);

  getUser($conn, $username);

  $query = 'UPDATE account SET username = \'' . $username . '\'';
  foreach ($toUpdate as $field => $newValue) {
    $query .= ', ' . $field . ' = \'' . $newValue . '\'';
  }
  $query .= ' WHERE username = ?';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('s', $username) &&
    $stmt->execute()
   ) {
    $stmt->close();
   } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new BadRequestException('Error updating user: ' . $err);
  }

  $query = '
    SELECT country
    FROM account a LEFT JOIN country c ON a.state = c.state
    WHERE a.username = ?
  ';
  
  $stmt = $conn->prepare($query);
  $res = [];
  if (
    $stmt &&
    $stmt->bind_param('s', $username) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $res = $result->fetch_assoc();
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }

  if (array_key_exists('city', $toUpdate)) {
    $data['country'] = $res['country'];
  }
  return $data;
}
