<?php
use Psr\Container\ContainerInterface;

require_once '../database/userQueries.php';

class UserController {
  protected $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function signup($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'email', 'password', 'name', 'city', 'state', 'accountType'];
    $requiredFields = ['username', 'email', 'password', 'accountType'];

    try {
      $validator = $this->container->get('validator');
      validate($validator, $request, $validParams, $validFields, $requiredFields);
    } catch (BadRequestException $e) {
      return handleBadRequest($response, $e->getMsg());
    }
    
    try {
      $conn = $this->container->get('conn');
      $data = $request->getParsedBody();
      $result = insertUser($conn, $data);
      return responseCreated($response, $result);
    } catch (BadRequestException $e) {
      return handleBadRequest($response, $e->getMsg());
    }
  }

  public function login($request, $response, $args) {
    return $response;
  }

  public function getUser($request, $response, $args) {
    return $response;
  }

  public function updateUser($request, $response, $args) {
    return $response;
  }

  public function getUserInventory($request, $response, $args) {
    return $response;
  }

  public function getUserStats($request, $response, $args) {
    return $response;
  }

  public function getUserLeaderboard($request, $response, $args) {
    return $response;
  }
}