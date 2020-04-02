<?php
use Psr\Container\ContainerInterface;

require_once '../schemas/validator.php';

class UserController {
  protected $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function signup($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'email', 'password', 'name', 'city', 'state', 'accountType'];
    $requiredFields = ['username', 'email', 'password', 'accountType'];
    $validator = $this->container->get('validator');

    try {
      validate($validator, $request, $validParams, $validFields, $requiredFields);
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    
    $data = $request->getParsedBody();
    $html = var_export($data, true);
    $response->getBody()->write($html);
    return $response;
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