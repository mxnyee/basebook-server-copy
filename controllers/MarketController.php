<?php

require_once 'Controller.php';

class MarketController extends Controller {
  private $marketStatementGroup;
  private $userStatementGroup;

  public function __construct(DatabaseConnection $conn, Validator $validator, 
      MarketStatementGroup $marketStatementGroup, UserStatementGroup $userStatementGroup) {
    parent::__construct($conn, $validator);
    $this->marketStatementGroup = $marketStatementGroup;
    $this->userStatementGroup = $userStatementGroup;
  }

  public function getAllItems($request, $response, $args) {
    $validParams = [];
    $validFields = [];
    $requiredFields = [];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);

      $this->conn->beginTransaction();
      $result = [];
      $result['superpowers'] = $this->marketStatementGroup->getAllSuperpowers();
      $result['accessories'] = $this->marketStatementGroup->getAllAccessories();
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }

  public function purchaseItem($request, $response, $args) {
    $validParams = [];
    $validFields = ['username', 'itemId'];
    $requiredFields = ['username', 'itemId'];
    $params = $request->getQueryParams();
    $body = $request->getParsedBody();

    try {
      $this->validator->validate($params, $body, $validParams, $validFields, $requiredFields, true);
      [ 'username' => $username, 'itemId' => $itemId ] = $body;

      $this->conn->beginTransaction();
      $this->userStatementGroup->checkForUser($username);
      $this->marketStatementGroup->checkForItem($itemId);
      $price = $this->marketStatementGroup->getItemProperty($itemId, 'price');
      $this->userStatementGroup->takeCoinsFromSender($username, $price);
      $duration = $this->marketStatementGroup->getItemProperty($itemId, 'duration');
      $result = $this->marketStatementGroup->insertPurchase($username, $itemId, $duration);
      $this->conn->endTransaction();

      return responseOk($response, $result);

    } catch (Exception $e) {
      $this->conn->rollbackTransaction();
      return handleThrown($response, $e);
    }
  }
}