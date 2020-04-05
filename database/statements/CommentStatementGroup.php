<?php

require_once 'StatementGroup.php';
require_once __DIR__ . '/../queries/commentQueries.php';

class CommentStatementGroup extends StatementGroup {

  public function __construct(DatabaseConnection $conn) {
    parent::__construct($conn, COMMENT_QUERIES);
  }


  public function insertComment($postId, $username, $text) {
    $ret = [];
    $postId = intval($postId);

    $stmt = $this->statements['insertComment'];
    $stmt->bind_param('iss', $postId, $username, $text);
    $stmt->execute();
    $commentId = $stmt->insert_id;

    $ret = [
      'commentId' => $commentId,
      'postId' => $postId,
      'username' => $username,
      'text' => $text,
      'numLikes' => 0,
      'numDislikes' => 0
    ];
    $ret['timestamp'] = $this->getCommentProperty($commentId, $postId, 'timestamp');
    
    return $ret;
  }
  

  public function checkForComment($commentId, $postId) {
    $stmt = $this->statements['checkForComment'];
    $stmt->bind_param('ii', $commentId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new NotFoundException('Comment does not exist.');
    }
  }


  public function getCommentProperty($commentId, $postId, $property) {
    $ret = [];

    $query = '
      SELECT ' . $property . ' 
      FROM Comment
      JOIN NumLikesOnComment USING(commentId, postId)
      JOIN NumDislikesOnComment USING(commentId, postId)
      WHERE commentId = ? AND postId = ?
    ';

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('ii', $commentId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();

    return $ret[$property];
  }


  public function getFilteredComments($postId, $filters) {
    $ret = [];

    // Build the query
    $query = 'SELECT commentId, text, timestamp';
    foreach (array_keys($filters) as $filter) {
      $query .= ', ' . $filter;
    }
    $query .=  ' FROM Comment
      JOIN NumLikesOnComment USING(commentId, postId)
      JOIN NumDislikesOnComment USING(commentId, postId)
      WHERE postId = ?
      ORDER BY timestamp ASC
    ';

    $stmt = $this->conn->prepare($query);
    $stmt->bind_param('i', $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
      $ret[] = $row;
    }

    return $ret;
  }


  public function deleteComment($commentId, $postId) {
    $stmt = $this->statements['deleteComment'];
    $stmt->bind_param('ii', $commentId, $postId);
    $stmt->execute();
  }


  public function addUserReactionToComment($username, $commentId, $postId, $value) {
    $ret = [];
    $commentId = intval($commentId);
    $postId = intval($postId);

    $stmt = $this->statements['addUserReactionToComment'];
    $stmt->bind_param('siis', $username, $commentId, $postId, $value);
    $stmt->execute();

    $ret = [
      'username' => $username, 
      'commentId' => $commentId,
      'postId' => $postId,
      'value' => $value
    ];
    
    return $ret;
  }


  public function checkForUserReactionToComment($username, $commentId, $postId) {
    $stmt = $this->statements['checkForUserReactionToComment'];
    $stmt->bind_param('sii', $username, $commentId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
      throw new NotFoundException('User ' . $username . ' did not react to that comment.');
    }
  }


  public function getUserReactionToComment($username, $commentId, $postId) {
    $stmt = $this->statements['getUserReactionToComment'];
    $stmt->bind_param('sii', $username, $commentId, $postId);
    $stmt->execute();
    $result = $stmt->get_result();
    $ret = $result->fetch_assoc();
    return $ret['value'];
  }


  public function removeUserReactionToComment($username, $commentId, $postId) {
    $stmt = $this->statements['removeUserReactionToComment'];
    $stmt->bind_param('sii', $username, $commentId, $postId);
    $stmt->execute();
  }

}