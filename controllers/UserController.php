<?php

require_once 'Controller.php';

class userController extends Controller {
  private $userStatementGroup;
  private $locationStatementGroup;
  private $marketStatementGroup;

  public function __construct(DatabaseConnection $conn, Validator $validator, 
      UserStatementGroup $userStatementGroup, LocationStatementGroup $locationStatementGroup,
      MarketStatementGroup $marketStatementGroup) {
    parent::__construct($conn, $validator);
    $this->userStatementGroup = $userStatementGroup;
    $this->locationStatementGroup = $locationStatementGroup;
    $this->marketStatementGroup = $marketStatementGroup;
  }


  public function signup($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'email', 'password', 'name', 'city', 'state', 'accountType'];
    $requiredFields = ['username', 'email', 'password', 'accountType'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'name' => $name,
        'city' => $city,
        'state' => $state,
        'accountType' => $accountType
      ] = $body;

      $this->conn->beginTransaction();
      $this->locationStatementGroup->checkForCity($city, $state);
      $result = $this->userStatementGroup->insertUser($username, $email, $password, $name, $city, $state, $accountType);
      $this->conn->endTransaction();

      return responseCreated($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }


  public function login($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'password'];
    $requiredFields = ['username', 'password'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'username' => $username, 'password' => $password ] = $body;

      $this->conn->beginTransaction();
      $this->userStatementGroup->checkForUser($username);
      $this->userStatementGroup->checkUserPassword($username, $password);
      $this->conn->endTransaction();

      return responseNoContent($response);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }


  public function getUser($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'username' => $username ] = $args;

      $this->conn->beginTransaction();
      $this->userStatementGroup->checkForUser($username);
      $result = $this->userStatementGroup->getAllUserInfo($username);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }


  public function updateUser($request, $response, $args) {
    $validParams = [];
    $validFields = ['email', 'password', 'name', 'city', 'state'];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, false);
      [ 'username' => $username ] = $args;
      $city = (array_key_exists('city', $body))? $body['city'] : null;
      $state = (array_key_exists('state', $body))? $body['state'] : null;

      $this->conn->beginTransaction();
      $this->userStatementGroup->checkForUser($username);
      if (!!$city && !$state) $state = $this->userStatementGroup->getUserProperty($username, 'state');
      $this->locationStatementGroup->checkForCity($city, $state);
      $result = $this->userStatementGroup->updateUserInfo($username, $body);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }


  public function getUserInventory($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'username' => $username ] = $args;

      $this->conn->beginTransaction();
      $this->userStatementGroup->checkForUser($username);
      $result['superpowers'] = $this->marketStatementGroup->getUserSuperpowers($username);
      $result['accessories'] = $this->marketStatementGroup->getUserAccessories($username);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }

  
  public function getUserActivity($request, $response, $args) {
    $validParams = ['post', 'comment', 'postReaction', 'commentReaction'];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'username' => $username ] = $args;

      $this->conn->beginTransaction();
      $this->userStatementGroup->checkForUser($username);
      $result = $this->userStatementGroup->getUserActivity($username, $params);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }


  public function getUserInbox($request, $response, $args) {
    $validParams = ['comment', 'postReaction'];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'username' => $username ] = $args;

      $this->conn->beginTransaction();
      $this->userStatementGroup->checkForUser($username);
      $result = $this->userStatementGroup->getUserInbox($username, $params);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }


  public function getUserStats($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'username' => $username ] = $args;

      $this->conn->beginTransaction();
      $result = [];
      $this->userStatementGroup->checkForUser($username);
      $this->userStatementGroup->checkUserPermissions($username, 'canSeeStats');
      $result['numPosts'] = $this->userStatementGroup->getUserProperty($username, 'numPosts');
      $result['numComments'] = $this->userStatementGroup->getUserProperty($username, 'numComments');
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }


  public function getUserRanking($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'username' => $username ] = $args;
      $span = 2;  // Show the ranking of 5 users total (2 below and 2 above the current user)
      $numRowsToGet = 2 * $span + 1;
      
      $this->conn->beginTransaction();
      $result = [];
      $this->userStatementGroup->checkForUser($username);
      $this->userStatementGroup->checkUserPermissions($username, 'canSeeRanking');
      $totalNumRows = $this->userStatementGroup->getNumUsers();

      // By number of posts
      $targetRowNum = $this->userStatementGroup->getUserRankByNumPosts($username) - 1;
      $startingRowNum = max(0, min($targetRowNum - $span, $totalNumRows - $numRowsToGet));
      $result['postRanking'] = $this->userStatementGroup->getRankingByNumPosts($startingRowNum, $numRowsToGet);

      // By number of comments
      $targetRowNum = $this->userStatementGroup->getUserRankByNumComments($username) - 1;
      $startingRowNum = max(0, min($targetRowNum - $span, $totalNumRows - $numRowsToGet));
      $result['commentRanking'] = $this->userStatementGroup->getRankingByNumComments($startingRowNum, $numRowsToGet);

      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }
}