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
    $validParams = ['username', 'title', 'locationName', 'city', 'state', 'timestamp', 'numLikes', 'numDislikes', 'numComments'];
    $validFields = [];
    $requiredFields = [];
    $validator = $this->container->get('validator');

    try {
      validate($validator, $request, $validParams, $validFields, $requiredFields);
    } catch (Exception $e) {
      echo $e->getMessage();
    }

    $params = $request->getQueryParams();
    $response->getBody()->write(var_export($params, true));
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