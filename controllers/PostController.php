<?php

require_once 'Controller.php';

class PostController extends Controller {
  private $postStatementGroup;
  private $locationStatementGroup;
  private $userStatementGroup;
  private $marketStatementGroup;
  private $commentStatementGroup;

  public function __construct(DatabaseConnection $conn, Validator $validator,
      PostStatementGroup $postStatementGroup, LocationStatementGroup $locationStatementGroup, 
      UserStatementGroup $userStatementGroup, MarketStatementGroup $marketStatementGroup,
      CommentStatementGroup $commentStatementGroup) {
    parent::__construct($conn, $validator);
    $this->postStatementGroup = $postStatementGroup;
    $this->locationStatementGroup = $locationStatementGroup;
    $this->userStatementGroup = $userStatementGroup;
    $this->marketStatementGroup = $marketStatementGroup;
    $this->commentStatementGroup = $commentStatementGroup;
  }


  public function createPost($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'title', 'text', 'locationName', 'city', 'state'];
    $requiredFields = ['username', 'title', 'text'];
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
      $this->userStatementGroup->checkForUser($username);
      $this->locationStatementGroup->checkForCity($city, $state);
      $this->locationStatementGroup->checkForLocation($locationName, $city, $state);
      $result = $this->postStatementGroup->insertPost($username, $title, $text, $locationName, $city, $state);
      $this->conn->endTransaction();

      return responseCreated($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
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

      foreach ($result as &$post) {
        $post['comments'] = $this->commentStatementGroup->getFilteredComments($post['postId'], ['username' => '', 'numLikes' => '', 'numDislikes' => '']);
      }
      
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
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
      return handleThrown($response, $e);
    }
  }


  public function editPost($request, $response, $args) {
    $validParams = [];
    $validFields = ['title', 'text', 'locationName', 'city', 'state'];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();
    if (is_null($body)) return responseNoContent($response);
    
    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, false);
      [ 'postId' => $postId ] = $args;
      
      $locationName = (isset($body['locationName']))? $body['locationName'] : null;
      $city = (isset($body['city']))? $body['city'] : null;
      $state = (isset($body['state']))? $body['state'] : null;

      $this->conn->beginTransaction();
      $this->postStatementGroup->checkForPost($postId);
      if ((!!$city || !!$state) && !$locationName) $locationName = $this->postStatementGroup->getPostProperty($postId, 'locationName');
      if ((!!$locationName || !!$state) && !$city) $city = $this->postStatementGroup->getPostProperty($postId, 'city');
      if ((!!$locationName || !!$city) && !$state) $state = $this->postStatementGroup->getPostProperty($postId, 'state');
      $this->locationStatementGroup->checkForCity($city, $state);
      $this->locationStatementGroup->checkForLocation($locationName, $city, $state);

      $result = $this->postStatementGroup->editPost($postId, $body);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }
  

  public function deletePost($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'postId' => $postId ] = $args;

      $this->conn->beginTransaction();
      $this->postStatementGroup->checkForPost($postId);
      $this->postStatementGroup->deletePost($postId);
      $this->conn->endTransaction();

      return responseNoContent($response);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
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
      $this->userStatementGroup->checkForUser($username);
      $this->postStatementGroup->checkForPost($postId);
      $author = $this->postStatementGroup->getPostProperty($postId, 'username');
      $this->marketStatementGroup->removeExpiredPurchases();
      $reactionValue = $this->marketStatementGroup->getUserReactionValue($username, $reactionType);
      $this->userStatementGroup->takeCoinsFromSender($username, $reactionValue);
      $this->userStatementGroup->giveCoinsToReceiver($author, $reactionValue);
      $result = $this->postStatementGroup->addUserReactionToPost($username, $postId, $reactionValue);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
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
      $this->userStatementGroup->checkForUser($username);
      $this->postStatementGroup->checkForPost($postId);
      $author = $this->postStatementGroup->getPostProperty($postId, 'username');
      $this->postStatementGroup->checkForUserReactionToPost($username, $postId);
      $reactionValue = $this->postStatementGroup->getUserReactionToPost($username, $postId);
      $this->userStatementGroup->giveCoinsToReceiver($author, -($reactionValue));
      $this->postStatementGroup->removeUserReactionToPost($username, $postId);
      $this->conn->endTransaction();

      return responseNoContent($response);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }
}