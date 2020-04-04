<?php

require_once 'StatementGroup.php';
require_once __DIR__ . '/../queries/userQueries.php';

class UserStatementGroup extends StatementGroup {

  public function __construct(DatabaseConnection $conn) {
    parent::__construct($conn, USER_QUERIES);
  }


  public function getNumUsers() {
    $ret = [];

    $stmt = $this->statements['get_num_users'];
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['num_users'];
  }
  

  public function insertUser($username, $email, $password, $name, $city, $state, $numCoins, $accountType) {
    $res = [];

    $stmt = $this->statements['insert_user'];
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
    $stmt = $this->statements['check_for_user'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new NotFoundException('User ' . $username . ' not found.');
    }
  }

  
  public function checkUserPassword($username, $password) {
    $stmt = $this->statements['check_user_password'];
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();  
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new BadRequestException('Incorrect login information for user ' . $username . '.');
    }
  }


  public function getAllUserInfo($username) {
    $ret = [];

    $stmt = $this->statements['get_all_user_info'];
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

    $stmt = $this->statements['get_user_inventory'];
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

    $stmt = $this->statements['get_user_num_posts'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['num_posts'];
  }

  
  public function getUserNumComments($username) {
    $ret = [];

    $stmt = $this->statements['get_user_num_comments'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['num_comments'];
  }

  
  public function getUserRankByNumPosts($username) {
    $ret = [];

    $stmt = $this->statements['get_user_rank_by_num_posts'];
    $stmt->bind_param('sss', $username, $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['user_rank_by_num_posts'];
  }

  
  public function getRankingByNumPosts($offset, $limit) {
    $ret = [];

    $stmt = $this->statements['get_ranking_by_num_posts'];
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

    $stmt = $this->statements['get_user_rank_by_num_comments'];
    $stmt->bind_param('sss', $username, $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['user_rank_by_num_comments'];
  }

  
  public function getRankingByNumComments($offset, $limit) {
    $ret = [];

    $stmt = $this->statements['get_ranking_by_num_comments'];
    $stmt->bind_param('ii', $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }


}