<?php

require_once 'Controller.php';

class CommentController extends Controller {
  private $commentStatementGroup;
  private $postStatementGroup;
  private $userStatementGroup;
  private $marketStatementGroup;

  public function __construct(DatabaseConnection $conn, Validator $validator,
      CommentSTatementGroup $commentStatementGroup, PostStatementGroup $postStatementGroup, 
      UserStatementGroup $userStatementGroup, MarketStatementGroup $marketStatementGroup) {
    parent::__construct($conn, $validator);
    $this->commentStatementGroup = $commentStatementGroup;
    $this->postStatementGroup = $postStatementGroup;
    $this->userStatementGroup = $userStatementGroup;
    $this->marketStatementGroup = $marketStatementGroup;
  }


  public function createComment($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'text'];
    $requiredFields = ['username', 'text'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'postId' => $postId ] = $args;
      [ 'username' => $username, 'text' => $text ] = $body;

      $this->conn->beginTransaction();
      $this->userStatementGroup->checkForUser($username);
      $this->postStatementGroup->checkForPost($postId);
      $result = $this->commentStatementGroup->insertComment($postId, $username, $text);
      $this->conn->endTransaction();

      return responseCreated($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }


  public function getFilteredComments($request, $response, $args) {
    $validParams = ['username', 'numLikes', 'numDislikes'];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'postId' => $postId ] = $args;

      $this->conn->beginTransaction();
      $this->postStatementGroup->checkForPost($postId);
      $result = $this->commentStatementGroup->getFilteredComments($postId, $params);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }


  public function deleteComment($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'postId' => $postId, 'commentId' => $commentId ] = $args;

      $this->conn->beginTransaction();
      $this->postStatementGroup->checkForPost($postId);
      $this->commentStatementGroup->checkForComment($commentId, $postId);
      $this->commentStatementGroup->deleteComment($commentId, $postId);
      $this->conn->endTransaction();

      return responseNoContent($response);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }


  public function addCommentReaction($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'reactionType'];
    $requiredFields = ['username', 'reactionType'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'postId' => $postId, 'commentId' => $commentId ] = $args;
      [ 'username' => $username, 'reactionType' => $reactionType ] = $body;

      $this->conn->beginTransaction();
      $this->userStatementGroup->checkForUser($username);
      $this->postStatementGroup->checkForPost($postId);
      $this->commentStatementGroup->checkForComment($commentId, $postId);
      $author = $this->commentStatementGroup->getCommentProperty($commentId, $postId, 'username');
      $this->marketStatementGroup->removeExpiredPurchases();
      $reactionValue = $this->marketStatementGroup->getUserReactionValue($username, $reactionType);
      $this->userStatementGroup->takeCoinsFromSender($username, $reactionValue);
      $this->userStatementGroup->giveCoinsToReceiver($author, $reactionValue);
      $result = $this->commentStatementGroup->AddUserReactionToComment($username, $commentId, $postId, $reactionValue);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }
  

  public function removeCommentReaction($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'postId' => $postId, 'commentId' => $commentId, 'username' => $username ] = $args;

      $this->conn->beginTransaction();
      $this->userStatementGroup->checkForUser($username);
      $this->postStatementGroup->checkForPost($postId);
      $this->commentStatementGroup->checkForComment($commentId, $postId);
      $author = $this->commentStatementGroup->getCommentProperty($commentId, $postId, 'username');
      $this->commentStatementGroup->checkForUserReactionToComment($username, $commentId, $postId);
      $reactionValue = $this->commentStatementGroup->getUserReactionToComment($username, $commentId, $postId);
      $this->userStatementGroup->giveCoinsToReceiver($author, -($reactionValue));
      $this->commentStatementGroup->removeUserReactionToComment($username, $commentId, $postId);
      $this->conn->endTransaction();

      return responseNoContent($response);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }
}