<?php

require_once 'StatementGroup.php';
require_once __DIR__ . '/../queries/postQueries.php';

class PostStatementGroup extends StatementGroup {

  public function __construct(DatabaseConnection $conn) {
    parent::__construct($conn, POST_QUERIES);
  }


  public function insertPost($username, $title, $text, $locationName, $city, $state) {
    $ret = [];

    $stmt = $this->statements['insertPost'];
    $stmt->bind_param('ssssss', $username, $title, $text, $locationName, $city, $state);
    $stmt->execute();
    $postId = $stmt->insert_id;

    $ret = [
      'postId' => $postId,
      'username' => $username,
      'title' => $title,
      'text' => $text,
      'locationName' => $locationName,
      'city' => $city,
      'state' => $state,
      'numLikes' => 0,
      'numDislikes' => 0,
      'numComments' => 0
    ];
    if (!!$state) {
      $ret['country'] = $this->getPostProperty($postId, 'country');
    }
    $ret['timestamp'] = $this->getPostProperty($postId, 'timestamp');
    
    return $ret;
  }


  public function getPostProperty($postId, $property) {
    $ret = [];

    $query = '
      SELECT ' . $property . ' 
      FROM Post LEFT JOIN Country USING(state)
      JOIN NumLikesOnPost USING(postId)
      JOIN NumDislikesOnPost USING(postId)
      JOIN NumCommentsOnPost USING(postId)
      WHERE postId = ?
    ';

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('s', $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret[$property];
  }


  public function getFilteredPosts($filters) {
    $ret = [];

    // Build the query
    $query = 'SELECT postId, title, text, timestamp';
    foreach (array_keys($filters) as $filter) {
      $query .= ', ' . $filter;
    }
    $query .=  ' FROM Post LEFT JOIN Country USING(state)
      JOIN NumLikesOnPost USING(postId)
      JOIN NumDislikesOnPost USING(postId)
      JOIN NumCommentsOnPost USING(postId)
      ORDER BY timestamp DESC
    ';

    $stmt = $this->conn->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }


  public function searchPosts($params) {
    $ret = [];
    $numParams = count($params);
    $values = [];

    // Build the query
    $query = 'SELECT *
      FROM Post LEFT JOIN Country USING(state)
      JOIN NumLikesOnPost USING(postId)
      JOIN NumDislikesOnPost USING(postId)
      JOIN NumCommentsOnPost USING(postId)
      WHERE TRUE';
    foreach ($params as $key => $value) {
      $values[] = '%' . $value . '%';
      $query .= ' AND ' . $key . ' LIKE ?';
    }
    $query .= ' ORDER BY timestamp DESC';

    $stmt = $this->conn->prepare($query);
    if ($numParams > 0) $stmt->bind_param(str_repeat('s', $numParams), ...$values);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }


  public function addUserReactionToPost($username, $postId, $value) {
    $ret = [];

    $stmt = $this->statements['addUserReactionToPost'];
    $stmt->bind_param('ssss', $username, $postId, $value, $value);
    $stmt->execute();

    $ret = [
      'username' => $username, 
      'postId' => $postId,
      'value' => $value
    ];
    
    return $ret;
  }


  public function checkForUserReactionToPost($username, $postId) {
    $stmt = $this->statements['checkForUserReactionToPost'];
    $stmt->bind_param('ss', $username, $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new NotFoundException('User ' . $username . ' did not react to that post.');
    }
  }


  public function removeUserReactionToPost($username, $postId) {
    $stmt = $this->statements['removeUserReactionToPost'];
    $stmt->bind_param('ss', $username, $postId);
    $stmt->execute();
  }

}