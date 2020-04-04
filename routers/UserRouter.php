<?php

require_once 'Router.php';

class UserRouter extends Router {
  private $userController;
  private $locationController;

  public function __construct(DatabaseConnection $conn, Validator $validator, UserController $userController, LocationController $locationController) {
    parent::__construct($conn, $validator);
    $this->userController = $userController;
    $this->locationController = $locationController;
  }


  public function signup($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'email', 'password', 'name', 'city', 'state', 'accountType'];
    $requiredFields = ['username', 'email', 'password', 'accountType'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'name' => $name,
        'city' => $city,
        'state' => $state,
        'accountType' => $accountType
      ] = $body;
      $numCoins = 0;

      $this->conn->beginTransaction();
      $this->locationController->checkForCity($city, $state);
      $result = $this->userController->insertUser($username, $email, $password, $name, $city, $state, $numCoins, $accountType);
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


  public function login($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'password'];
    $requiredFields = ['username', 'password'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [
        'username' => $username,
        'password' => $password
      ] = $body;

      $this->conn->beginTransaction();
      $this->userController->checkForUser($username);
      $this->userController->checkUserPassword($username, $password);
      $this->conn->endTransaction();

      return responseNoContent($response);

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


  public function getUser($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [
        'username' => $username
      ] = $args;

      $this->conn->beginTransaction();
      $this->userController->checkForUser($username);
      $result = $this->userController->getAllUserInfo($username);
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


  public function updateUser($request, $response, $args) {
    $validParams = [];
    $validFields = ['email', 'password', 'name', 'city', 'state'];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, false);
      [
        'username' => $username
      ] = $args;
      [
        'email' => $email,
        'password' => $password,
        'name' => $name,
        'city' => $city,
        'state' => $state
      ] = $body;

      $this->conn->beginTransaction();
      $this->userController->checkForUser($username);
      if (!!$city && !$state) $state = $this->userController->getUserProperty($username, 'state');
      $this->locationController->checkForCity($city, $state);
      $result = $this->userController->updateUserInfo($username, $email, $password, $name, $city, $state);
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


  public function getUserInventory($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = validate($this->validator, $params, $body, $validParams, $validFields, $requiredFields, true);

      $result = selectUserInventory($this->conn, $args['username']);
      return responseOk($response, $result);

    } catch (BadRequestException $e) {
      return handleBadRequest($response, $e->getMessage());
    } catch (NotFoundException $e) {
      return handleNotFound($response, $e->getMessage());
    } catch (InternalServerErrorException $e) {
      return handleInternalServerError($response, $e->getMessage());
    }
  }


  public function getUserStats($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = validate($this->validator, $params, $body, $validParams, $validFields, $requiredFields, true);

      $result = selectUserStats($this->conn, $args['username']);
      return responseOk($response, $result);

    } catch (BadRequestException $e) {
      return handleBadRequest($response, $e->getMessage());
    } catch (NotFoundException $e) {
      return handleNotFound($response, $e->getMessage());
    } catch (InternalServerErrorException $e) {
      return handleInternalServerError($response, $e->getMessage());
    }
  }


  public function getUserLeaderboard($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = validate($this->validator, $params, $body, $validParams, $validFields, $requiredFields, true);

      $result = selectUserLeaderboard($this->conn, $args['username'], 2);
      return responseOk($response, $result);

    } catch (BadRequestException $e) {
      return handleBadRequest($response, $e->getMessage());
    } catch (NotFoundException $e) {
      return handleNotFound($response, $e->getMessage());
    } catch (InternalServerErrorException $e) {
      return handleInternalServerError($response, $e->getMessage());
    }
  }
}