<?php
use Psr\Container\ContainerInterface;

require_once '../schemas/validator.php';

class UserController {
  protected $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function signup($request, $response, $args) {
    $data = $request->getParsedBody();
    $fields = ['username', 'email', 'password', 'name', 'city', 'state', 'accountType'];
    $required = ['username', 'email', 'password', 'accountType'];

    try {
      validate($this->container->get('validator'), $data, $fields, $required);
    } catch (Exception $e) {
      echo $e->getMessage();
    }
    
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