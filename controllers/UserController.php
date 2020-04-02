<?php
use Psr\Container\ContainerInterface;

class UserController {
  protected $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function signup($request, $response, $args) {
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