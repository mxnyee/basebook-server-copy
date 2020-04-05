<?php

const COMMENT_QUERIES = [

  'insertComment' => '
    INSERT INTO Comment(commentId, postId, username, text)
    VALUES(?, ?, ?, ?)
  ',

  'checkForComment' => '
    SELECT commentId, postId
    FROM Comment
    WHERE commentId = ? AND postId = ?
  ',

  'deleteComment' => '
    DELETE FROM Comment
    WHERE commentId = ? AND postId = ?
  ',
  
  'addUserReactionToComment' => '
    INSERT INTO CommentReaction(username, commentId, postId, value)
    VALUES(?, ?, ?, ?)
  ',

  'checkForUserReactionToComment' => '
    SELECT value
    FROM CommentReaction
    WHERE username = ? AND commentId = ? AND postId = ?
  ',

  'getUserReactionToComment' => '
    SELECT value
    FROM CommentReaction
    WHERE username = ? AND commentId = ? AND postId = ?
  ',

  'removeUserReactionToComment' => '
    DELETE FROM CommentReaction
    WHERE username = ? AND commentId = ? AND postId = ?
  '
  
];