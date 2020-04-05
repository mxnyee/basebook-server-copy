<?php

require_once 'StatementGroup.php';
require_once __DIR__ . '/../queries/marketQueries.php';
require_once __DIR__ . '/../../constants/reactions.php';

class MarketStatementGroup extends StatementGroup {

  public function __construct(DatabaseConnection $conn) {
    parent::__construct($conn, MARKET_QUERIES);
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