<?php
use Psr\Container\ContainerInterface;

class PostController {
  protected $container;

  public function __construct(ContainerInterface $container) {
    $this->container = $container;
  }

  public function createPost($request, $response, $args) {
    return $response;
  }

  public function getAllPosts($request, $response, $args) {
    return $response;
  }

  public function searchPosts($request, $response, $args) {
    return $response;
  }

  public function addPostReaction($request, $response, $args) {
    return $response;
  }

  public function removePostReaction($request, $response, $args) {
    return $response;
  }
}