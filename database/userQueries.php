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

function checkUserPassword($conn, $data) {
  [
    'username' => $username,
    'password' => $password
  ] = $data;
  $numRows = 0;
  
  $query = '
    SELECT 1
    FROM account
    WHERE username = ? and password = ?
  ';
  
  $stmt = $conn->prepare($query);
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

function selectUser($conn, $username) {
  $numRows = 0;
  $row = [];

  $query = '
    SELECT email, name, city, state, country, num_coins, account_type
    FROM account a LEFT JOIN country c USING(state)
    WHERE a.username = ?
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('s', $username) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $numRows = $result->num_rows;
    $row = $result->fetch_assoc();
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

  return $row;
}

function editUser($conn, $username, $data, $validFields) {
  $toUpdate = [];
  foreach ($validFields as $field) {
    if (array_key_exists($field, $data)) {
      $toUpdate[$field] = $data[$field];
    }
  }
  $numToUpdate = count($toUpdate);
  $row = [];
  $city = (array_key_exists('city', $data))? $data['city'] : null;
  $state = (array_key_exists('state', $data))? $data['state'] : null;
  checkForCity($conn, $city, $state);
  selectUser($conn, $username);

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
    FROM account a LEFT JOIN country c USING(state)
    WHERE a.username = ?
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('s', $username) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }

  if (array_key_exists('city', $toUpdate)) {
    $data['country'] = $row['country'];
  }
  return $data;
}

function selectUserInventory($conn, $username) {
  $arr = [];
  selectUser($conn, $username);

  $query = '
    SELECT item_id, item_name, description, expiry_date, color
    FROM purchase p
    JOIN account_upgrade au USING(item_id)
    LEFT JOIN superpower s USING(item_id)
    LEFT JOIN accessory a USING(item_id)
    WHERE p.username = ?
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('s', $username) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $arr[] = $row;
    }
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }

  return $arr;
}

function selectUserStats($conn, $username) {
  $arr = [];
  selectUser($conn, $username);

  $query = '
    SELECT num_posts
    FROM num_posts np
    WHERE username = ?
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('s', $username) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $arr['numPosts'] = $row['num_posts'];
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }

  $query = '
    SELECT num_comments
    FROM num_comments nc
    WHERE username = ?
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('s', $username) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $arr['numComments'] = $row['num_comments'];
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }

  $arr = (object) $arr;
  return $arr;
}

function selectUserLeaderboard($conn, $username, $offset) {
  $postRanking = [];
  $commentRanking = [];
  $targetRowNum = 0;
  $startingRowNum = 0;
  $numRows = 2 * $offset + 1;
  $totalNumRows = 0;
  selectUser($conn, $username);

  // Total number of users
  $query = '
    SELECT COUNT(1) as num_users
    FROM account a
  ';
  $result = $conn->query($query);
  $totalNumRows = $result->fetch_assoc()['num_users'];
  $result->free();

  $query = '
    SELECT username, num_posts
    FROM num_posts np
    WHERE num_posts > ALL (
        SELECT num_posts FROM num_posts WHERE username = ?
      )
      or (username <= ?
        and num_posts = ALL (
            SELECT num_posts FROM num_posts WHERE username = ?
          )
      )
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('sss', $username, $username, $username) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $targetRowNum = $result->num_rows;
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }
  $startingRowNum = max(0, min($targetRowNum - $offset - 1, $totalNumRows - $numRows));

  $query = '
    SELECT username, num_posts
    FROM num_posts np
    LIMIT ?, ?
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('ii', $startingRowNum, $numRows) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $postRanking[] = $row;
    }
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }

  $query = '
    SELECT username, num_comments
    FROM num_comments nc
    WHERE num_comments > ALL (
        SELECT num_comments FROM num_comments WHERE username = ?
      )
      or (username <= ?
        and num_comments = ALL (
            SELECT num_comments FROM num_comments WHERE username = ?
          )
      )
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('sss', $username, $username, $username) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    $targetRowNum = $result->num_rows;
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }
  $startingRowNum = max(0, min($targetRowNum - $offset - 1, $totalNumRows - $numRows));

  $query = '
    SELECT username, num_comments
    FROM num_comments nc
    LIMIT ?, ?
  ';
  
  $stmt = $conn->prepare($query);
  if (
    $stmt &&
    $stmt->bind_param('ii', $startingRowNum, $numRows) &&
    $stmt->execute()
    ) {
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $commentRanking[] = $row;
    }
    $result->free();
    $stmt->close();
  } else {
    $err = ($stmt)? $stmt->error : $conn->error;
    if ($stmt) $stmt->close();
    throw new InternalServerErrorException('Error looking for user: ' . $err);
  }

  return (object) [ 'postRanking' => $postRanking, 'commentRanking' => $commentRanking];
}
