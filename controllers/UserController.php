<?php

require_once 'Controller.php';
require_once '../database/userPreparedQueries.php';

class UserController extends Controller {

  public function __construct(DatabaseConnection $conn) {
    parent::__construct($conn, USER_PREPARED_QUERIES);
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



}