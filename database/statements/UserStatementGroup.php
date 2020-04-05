<?php

require_once 'StatementGroup.php';
require_once __DIR__ . '/../queries/userQueries.php';

class UserStatementGroup extends StatementGroup {

  public function __construct(DatabaseConnection $conn) {
    parent::__construct($conn, USER_QUERIES);
  }


  public function getNumUsers() {
    $ret = [];

    $stmt = $this->statements['getNumUsers'];
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['numUsers'];
  }
  

  public function insertUser($username, $email, $password, $name, $city, $state, $accountType) {
    $ret = [];

    $stmt = $this->statements['insertUser'];
    $stmt->bind_param('sssssss', $username, $email, $password, $name, $city, $state, $accountType);
    $stmt->execute();

    $ret = [
      'username' => $username,
      'email' => $email,
      'password' => $password,
      'name' => $name,
      'city' => $city,
      'state' => $state,
      'accountType' => $accountType,
      'numCoins' => 0
    ];
    if (!!$state) {
      $ret['country'] = $this->getUserProperty($username, 'country');
    }
    
    return $ret;
  }

  
  public function checkForUser($username) {
    $stmt = $this->statements['checkForUser'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new NotFoundException('User ' . $username . ' not found.');
    }
  }

  
  public function checkUserPassword($username, $password) {
    $stmt = $this->statements['checkUserPassword'];
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();  
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new NotFoundException('Incorrect login information for user ' . $username . '.');
    }
  }


  public function getAllUserInfo($username) {
    $ret = [];

    $stmt = $this->statements['getAllUserInfo'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret;
  }


  public function getUserProperty($username, $property) {
    $ret = [];

    $query = '
      SELECT ' . $property . ' 
      FROM Account LEFT JOIN Country USING(state)
      JOIN NumPostsByUser USING(username)
      JOIN NumCommentsByUser USING(username)
      WHERE username = ?
    ';

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret[$property];
  }


  public function updateUserInfo($username, $fields) {
    $ret = [];

    $numFields = count($fields);
    $values = array_values($fields);
    $values[] = $username;

    // Build the query
    $query = 'UPDATE Account SET username = ?';
    foreach ($fields as $field => $value) {
      $query .= ', ' . $field . ' = ?';
    }
    $query .= ' WHERE username = ?';

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param(str_repeat('s', $numFields + 2), $username, ...$values);
    $stmt->execute();

    $ret = $fields;
    if (array_key_exists('state', $fields)) {
      $ret['country'] = $this->getUserProperty($username, 'country');
    }

    return $ret;
  }

  
  public function getUserRankByNumPosts($username) {
    $ret = [];

    $stmt = $this->statements['getUserRankByNumPosts'];
    $stmt->bind_param('sss', $username, $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['userRankByNumPosts'];
  }

  
  public function getRankingByNumPosts($offset, $limit) {
    $ret = [];

    $stmt = $this->statements['getRankingByNumPosts'];
    $stmt->bind_param('ii', $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }

  
  public function getUserRankByNumComments($username) {
    $ret = [];

    $stmt = $this->statements['getUserRankByNumComments'];
    $stmt->bind_param('sss', $username, $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['userRankByNumComments'];
  }

  
  public function getRankingByNumComments($offset, $limit) {
    $ret = [];

    $stmt = $this->statements['getRankingByNumComments'];
    $stmt->bind_param('ii', $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }


  public function checkUserPermissions($username, $permissions) {
      $stmt = $this->statements['checkUserPermissions'];
      $stmt->bind_param('s', $username);
      $stmt->execute();
      $result = $stmt->get_result();
      $ret = $result->fetch_assoc();
      if ($ret[$permissions] == false) {
        throw new ForbiddenException('Upgrade your account to see this info.');
      }
    }
}