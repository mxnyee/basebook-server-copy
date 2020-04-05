<?php
require_once 'Controller.php';

class PostController extends Controller {
  private $postStatementGroup;
  private $locationStatementGroup;

  public function __construct(DatabaseConnection $conn, Validator $validator, PostStatementGroup $postStatementGroup, LocationStatementGroup $locationStatementGroup) {
    parent::__construct($conn, $validator);
    $this->postStatementGroup = $postStatementGroup;
    $this->locationStatementGroup = $locationStatementGroup;
  }


  public function createPost($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'title', 'text', 'locationName', 'city', 'state'];
    $requiredFields = ['username', 'title', 'text', 'locationName', 'city', 'state'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [
        'username' => $username,
        'title' => $title,
        'text' => $text,
        'locationName' => $locationName,
        'city' => $city,
        'state' => $state
      ] = $body;

      $this->conn->beginTransaction();
      $this->locationStatementGroup->checkForCity($city, $state);
      $this->locationStatementGroup->checkForLocation($locationName, $city, $state);
      $result = $this->postStatementGroup->insertPost($username, $title, $text, $locationName, $city, $state);
      $this->conn->endTransaction();

      return responseCreated($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      switch (get_class($e)) {
        case 'BadRequestException': return handleBadRequest($response, $e->getMessage());
        case 'NotFoundException': return handleNotFound($response, $e->getMessage());
        case 'InternalServerErrorException': return handleInternalServerError($response, $e->getMessage());
        default: throw $e;
      }
    }
  }


  public function getFilteredPosts($request, $response, $args) {
    $validParams = ['username', 'title', 'locationName', 'city', 'state', 'numLikes', 'numDislikes', 'numComments'];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);

      $this->conn->beginTransaction();
      $result = $this->postStatementGroup->getFilteredPosts($params);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      switch (get_class($e)) {
        case 'BadRequestException': return handleBadRequest($response, $e->getMessage());
        case 'NotFoundException': return handleNotFound($response, $e->getMessage());
        case 'InternalServerErrorException': return handleInternalServerError($response, $e->getMessage());
        default: throw $e;
      }
    }
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