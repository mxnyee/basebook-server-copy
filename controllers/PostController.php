<?php
require_once 'Controller.php';

class PostController extends Controller {
  private $postStatementGroup;
  private $locationStatementGroup;
  private $marketStatementGroup;

  public function __construct(DatabaseConnection $conn, Validator $validator,
    PostStatementGroup $postStatementGroup, LocationStatementGroup $locationStatementGroup, 
    MarketStatementGroup $marketStatementGroup) {
    parent::__construct($conn, $validator);
    $this->postStatementGroup = $postStatementGroup;
    $this->locationStatementGroup = $locationStatementGroup;
    $this->marketStatementGroup = $marketStatementGroup;
  }


  public function createPost($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'title', 'text', 'locationName', 'city', 'state'];
    $requiredFields = ['username', 'title', 'text', 'locationName', 'city', 'state'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
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
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);

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
    $validParams = ['username', 'title', 'keyword', 'locationName', 'city', 'state', 'country'];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);

      $this->conn->beginTransaction();
      $result = $this->postStatementGroup->searchPosts($params);
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

  public function addPostReaction($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'reactionType'];
    $requiredFields = ['username', 'reactionType'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'postId' => $postId ] = $args;
      [ 'username' => $username, 'reactionType' => $reactionType ] = $body;

      $this->conn->beginTransaction();
      $reactionValue = $this->marketStatementGroup->getUserReactionValue($username, $reactionType);
      $result = $this->postStatementGroup->AddUserReactionToPost($username, $postId, $reactionValue);
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

  public function removePostReaction($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'postId' => $postId, 'username' => $username ] = $args;

      $this->conn->beginTransaction();
      $result = $this->postStatementGroup->checkForUserReactionToPost($username, $postId);
      $result = $this->postStatementGroup->removeUserReactionToPost($username, $postId);
      $this->conn->endTransaction();

      return responseNoContent($response, $result);

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
}