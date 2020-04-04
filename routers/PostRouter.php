<?php
use Psr\Container\ContainerInterface;
require_once 'Router.php';

class PostRouter extends Router {
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
      return handleBadRequest($response, $e->getMessage());
    }

    $params = $request->getQueryParams();
    return responseOk($response, $params);
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