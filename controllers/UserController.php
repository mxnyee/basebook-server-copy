<?php
use Psr\Container\ContainerInterface;

require_once '../database/userPreparedQueries.php';

class UserController extends Controller {
  private $locationController;

  public function __construct(ContainerInterface $container, LocationController $locationController) {
    parent::__construct($container, USER_PREPARED_QUERIES);
    $this->locationController = $locationController;
  }


  function insertUser($username, $email, $password, $name, $city, $state, $numCoins, $accountType) {
    $res = [];

    try {
      $this->conn->autocommit(FALSE);
      $this->locationController->checkForCity($city, $state);
      
      $stmt = $this->statements['insertUser'];
      $stmt->bind_param('ssssssss', $username, $email, $password, $name, $city, $state, $numCoins, $accountType);
      $stmt->execute();

      $this->conn->autocommit(TRUE);
    } catch(Exception $e) {
      $this->conn->rollback();
      throw $e;
    }

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


  function getUser($username) {
    $ret = [];

    try {
      $this->conn->autocommit(FALSE);
      checkForUser($username);
      
      $stmt = $this->statements['getAllUserInfo'];
      $stmt->bind_param("s", $username);
      $stmt->execute();  
      $ret[] = $stmt->get_result()->fetch_assoc();
    } catch(Exception $e) {
      $this->conn->rollback();
      throw $e;
    } finally {
      $this->conn->autocommit(TRUE);
    }

    return $ret;
    
    // if (
    //   $stmt &&
    //   $stmt->bind_param('s', $username) &&
    //   $stmt->execute()
    //   ) {
    //   $result = $stmt->get_result();
    //   $numRows = $result->num_rows;
    //   $row = $result->fetch_assoc();
    //   $result->free();
    //   $stmt->close();
    // } else {
    //   $err = ($stmt)? $stmt->error : $conn->error;
    //   if ($stmt) $stmt->close();
    //   throw new InternalServerErrorException('Error looking for user: ' . $err);
    // }
  }

}