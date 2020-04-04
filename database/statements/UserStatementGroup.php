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
  

  public function insertUser($username, $email, $password, $name, $city, $state, $numCoins, $accountType) {
    $res = [];

    $stmt = $this->statements['insertUser'];
    $stmt->bind_param('ssssssss', $username, $email, $password, $name, $city, $state, $numCoins, $accountType);
    $stmt->execute();

    $res = [ 
      'username' => $username,
      'email' => $email,
      'password' => $password,
      'name' => $name,
      'city' => $city,
      'state' => $state,
      'numCoins' => $numCoins,
      'accountType' => $accountType
    ];

    return $res;
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
      throw new BadRequestException('Incorrect login information for user ' . $username . '.');
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
      FROM account a LEFT JOIN country c USING(state)
      WHERE a.username = ?
    ';

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret[$property];
  }


  public function updateUserInfo($username, $email, $password, $name, $city, $state) {
    $ret = [];

    $fields = [
      'email' => $email,
      'password' => $password,
      'name' => $name,
      'city' => $city,
      'state' => $state
    ];
    $fields = array_filter($fields, function($v) { return !is_null($v); });
    $numFields = count($fields);

    $values = array_values($fields);
    $values[] = $username;

    $prefix = 'UPDATE account SET username = ?';
    $suffix = ' WHERE username = ?';

    $query = $prefix;
    foreach ($fields as $field => $value) {
      $query .= ', ' . $field . ' = ?';
    }
    $query .= $suffix;

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param(str_repeat('s', $numFields + 2), $username, ...$values);
    $stmt->execute();

    $ret = $fields;
    if (!!$state) {
      $ret['country'] = $this->getUserProperty($username, 'country');
    }

    return $ret;
  }

  
  public function getUserInventory($username) {
    $ret = [];

    $stmt = $this->statements['getUserInventory'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }

  
  public function getUserNumPosts($username) {
    $ret = [];

    $stmt = $this->statements['getUserNumPosts'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['numPosts'];
  }

  
  public function getUserNumComments($username) {
    $ret = [];

    $stmt = $this->statements['getUserNumComments'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['numComments'];
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


}