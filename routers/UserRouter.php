<?php
use Psr\Container\ContainerInterface;

class UserRouter extends Router {

  public function __construct(ContainerInterface $container, UserController $userController) {
    parent::__construct($container, $userController);
  }


  public function signup($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'email', 'password', 'name', 'city', 'state', 'accountType'];
    $requiredFields = ['username', 'email', 'password', 'accountType'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = validate($this->validator, $params, $body, $validParams, $validFields, $requiredFields, true);
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

      $result = $this->controller->insertUser($username, $email, $password, $name, $city, $state, $numCoins, $accountType);
      return responseCreated($response, $result);

    } catch (BadRequestException $e) {
      return handleBadRequest($response, $e->getMessage());
    } catch (NotFoundException $e) {
      return handleNotFound($response, $e->getMessage());
    } catch (InternalServerErrorException $e) {
      return handleInternalServerError($response, $e->getMessage());
    }
  }


  public function login($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'password'];
    $requiredFields = ['username', 'password'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = validate($this->validator, $params, $body, $validParams, $validFields, $requiredFields, true);
      $body = $request->getParsedBody();
      $this->controller->checkUserPassword($this->conn, $body);
      return responseNoContent($response);

    } catch (BadRequestException $e) {
      return handleBadRequest($response, $e->getMessage());
    } catch (NotFoundException $e) {
      return handleNotFound($response, $e->getMessage());
    } catch (InternalServerErrorException $e) {
      return handleInternalServerError($response, $e->getMessage());
    }
  }


  public function getUser($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = validate($this->validator, $params, $body, $validParams, $validFields, $requiredFields, true);
      $result = $this->controller->getUser($args['username']);
      return responseOk($response, $result);

    } catch (BadRequestException $e) {
      return handleBadRequest($response, $e->getMessage());
    } catch (NotFoundException $e) {
      return handleNotFound($response, $e->getMessage());
    } catch (InternalServerErrorException $e) {
      return handleInternalServerError($response, $e->getMessage());
    }
  }


  public function editUser($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'password', 'name', 'city', 'state'];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $body = validate($this->validator, $params, $body, $validParams, $validFields, $requiredFields, true);

      $body = $request->getParsedBody();
      $result = editUser($this->conn, $args['username'], $body, $validFields);
      return responseOk($response, $result);
    } catch (BadRequestException $e) {
      return handleBadRequest($response, $e->getMessage());
    } catch (NotFoundException $e) {
      return handleNotFound($response, $e->getMessage());
    } catch (InternalServerErrorException $e) {
      return handleInternalServerError($response, $e->getMessage());
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