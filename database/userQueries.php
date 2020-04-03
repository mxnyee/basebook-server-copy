<?php

function insertUser($conn, $data) {
  [
    'username' => $username,
    'email' => $email,
    'password' => $password,
    'name' => $name,
    'city' => $city,
    'state' => $state,
    'accountType' => $accountType
  ] = $data;
  $numCoins = 0;

  $query = '
    INSERT INTO account(username, email, password, name, city, state, num_coins, account_type)
    VALUES(?,?,?,?,?,?,?,?);
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('ssssssss', $username, $email, $password, $name, $city, $state, $numCoins, $accountType) &&
    $stmt->execute()
   ) {
    $stmt->close();
   } else {
    $err = $stmt->error;
    $stmt->close();
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
    $err = $stmt->error;
    $stmt->close();
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
    $err = $stmt->error;
    $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }
  
  if ($numRows == 0) {
    throw new BadRequestException("User $username not found.");
  } else {
    throw new BadRequestException("Incorrect login information for $username.");
  }
}
