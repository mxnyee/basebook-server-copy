<?php

const POST_QUERIES = [

  'insertPost' => '
    INSERT INTO Post(username, title, text, locationName, city, state)
    VALUES(?, ?, ?, ?, ?, ?)
  ',

  'checkForPost' => '
    SELECT postId
    FROM Post
    WHERE postId = ?
  ',
  
  'addUserReactionToPost' => '
    INSERT INTO PostReaction(username, postId, value)
    VALUES(?, ?, ?)
  ',

  'checkForUserReactionToPost' => '
    SELECT value
    FROM PostReaction
    WHERE username = ? AND postId = ?
  ',

  'getUserReactionToPost' => '
    SELECT value
    FROM PostReaction
    WHERE username = ? AND postId = ?
  ',

  'removeUserReactionToPost' => '
    DELETE FROM PostReaction
    WHERE username = ? AND postId = ?
  '
  
];