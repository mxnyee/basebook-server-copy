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
    $data['numCoins'] = $numCoins;
    return $data;
  } else {
    $err = $stmt->error;
    $stmt->close();
    throw new BadRequestException('Error inserting user: ' . $err);
  }
}