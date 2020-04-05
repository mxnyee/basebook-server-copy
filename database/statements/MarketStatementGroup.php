<?php

require_once 'StatementGroup.php';
require_once __DIR__ . '/../queries/marketQueries.php';

class MarketStatementGroup extends StatementGroup {

  public function __construct(DatabaseConnection $conn) {
    parent::__construct($conn, MARKET_QUERIES);
  }


  public function getSortedSuperpowers($params) {
    $ret = [];

    // Build the query
    $query = '
      SELECT itemId, itemName, description, price, duration
      FROM AccountUpgrade
      JOIN Superpower USING(itemId)
      ORDER BY';
    foreach ($params as $param=> $value) {
      $query .= ' ' . $param . ' ASC,';
    }
    $query .= ' itemId ASC';
    echo $query;
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }


  public function getSortedAccessories($params) {
    $ret = [];

    // Build the query
    $query = '
      SELECT itemId, itemName, description, price
      FROM AccountUpgrade
      WHERE itemId NOT IN (
        SELECT itemId
        FROM Superpower
      )
      ORDER BY';
    foreach ($params as $param=> $value) {
      $query .= ' ' . $param . ' ASC,';
    }
    $query .= ' itemId ASC';
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }


  public function checkForItem($itemId) {
    $stmt = $this->statements['checkForItem'];
    $stmt->bind_param('i', $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new NotFoundException('Item not found.');
    }
  }


  public function getItemProperty($itemId, $property) {
    $ret = [];

    $query = '
      SELECT ' . $property . ' 
      FROM AccountUpgrade
      LEFT JOIN Superpower USING(itemId)
      WHERE itemId = ?
    ';

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('i', $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret[$property];
  }


  public function insertPurchase($username, $itemId, $duration) {
    $ret = [];
    $itemId = intval($itemId);

    $stmt = $this->statements['insertPurchase'];
    $stmt->bind_param('sii', $username, $itemId, $duration);
    $stmt->execute();
    
    $ret = [
      'username' => $username,
      'itemId' => $itemId
    ];
    $ret['expiryDate'] = $this->getPurchaseProperty($username, $itemId, 'expiryDate');

    return $ret;
  }


  public function getPurchaseProperty($usrname, $itemId, $property) {
    $ret = [];

    $query = '
      SELECT ' . $property . ' 
      FROM Purchase
      WHERE username = ? AND itemId = ?
    ';

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('si', $username, $itemId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();
    $ret = (!!$ret)? $ret[$property] : null;
    
    return $ret;
  }

  
  public function removeExpiredPurchases() {
    $stmt = $this->statements['removeExpiredPurchases'];
    $stmt->execute();
  }

  
  public function getUserSuperpowers($username) {
    $ret = [];

    $stmt = $this->statements['getUserSuperpowers'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }

  
  public function getUserAccessories($username) {
    $ret = [];

    $stmt = $this->statements['getUserAccessories'];
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }

  
  public function getUserReactionValue($username, $reactionType) {
    $ret = [];

    // Build the query
    $query = '
      SELECT SUM(' . $reactionType . 'Value) AS reactionValue
      FROM Purchase JOIN Superpower USING(itemId)
      WHERE username = ?
    ';
    
    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret['reactionValue'] + REACTION_BASE_VALUE[$reactionType];
  }

}